<?php

namespace App\Services;

use App\Data\RemoteBarangExpired;
use App\Models\Farmalkes;
use App\Models\ObatExpired;
use App\Models\ObatExpiredItem;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BarangExpireService
{
    // base URL for remote API
    protected const BASE_URL = 'gudangfarmasi/barangexpired';

    /**
     * Store obat expired data locally and push to remote API
     */
    public function store($data): Response
    {
        $ket     = $data['keterangan'] ?? null;
        $tanggal = $data['tanggal'] ?? now()->toDateString();
        $items   = $data['items'] ?? [];

        $expireNum = $this->generateExpireNum();
        $unit      = Auth::user()->unit;

        $itemsLocal = collect($items)->map(fn($item) => [
            'farmalkes_id' => $item['data']->id ?? null,
            'qty'          => (int) ($item['qty'] ?? 0),
            'expire_date'  => $item['expire_date'] ?? null,
        ]);

        return DB::transaction(function () use ($itemsLocal, $tanggal, $ket, $expireNum, $unit, $items) {
            // create local master
            $obatExpired = new ObatExpired();
            $obatExpired->tanggal    = $tanggal;
            $obatExpired->nomor      = $expireNum;
            $obatExpired->status     = 'pending';
            $obatExpired->unit_id    = $unit->id;
            $obatExpired->keterangan = $ket;
            $obatExpired->save();

            // create local items
            foreach ($itemsLocal as $item) {
                if (!$item['farmalkes_id']) {
                    continue; // skip invalid
                }

                $farmalkes = Farmalkes::find($item['farmalkes_id']);
                if (!$farmalkes) {
                    continue;
                }

                $obatExpiredItem = new ObatExpiredItem();
                $obatExpiredItem->obatExpired()->associate($obatExpired);
                $obatExpiredItem->farmalkes()->associate($farmalkes);
                $obatExpiredItem->qty         = $item['qty'];
                $obatExpiredItem->expire_date = $item['expire_date'];
                $obatExpiredItem->save();
            }

            // build payload for remote
            $apt    = $unit->apoteker;
            $kepala = $unit->kepala_unit;

            $itemsRemote = collect($items)->map(fn($item) => [
                'kode_brng'   => $item['data']->kode ?? null,
                'jumlah'      => (int) ($item['qty'] ?? 0),
                'expire_date' => $item['expire_date'] ?? null,
            ]);

            $remotePayload = new RemoteBarangExpired(
                $tanggal,
                $unit->nama,
                $expireNum,
                $apt,
                $kepala,
                $ket,
                $itemsRemote->toArray()
            );

            $response = $this->sendToRemote($remotePayload);

            // handle remote failure
            if (!$response->successful() || $response->status() !== 201) {
                Log::error('Remote store obat expired failed', [
                    'status'   => $response->status(),
                    'response' => $response->body(),
                ]);
                throw new Exception('Gagal kirim ke remote. Status: ' . $response->status());
            }

            // update local with remote ID
            $remoteId = $response->json('id');
            if ($remoteId) {
                $obatExpired->remote_id = $remoteId;
                $obatExpired->save();
            }

            return $response;
        });
    }

    /**
     * Send data to remote API
     */
    private function sendToRemote(RemoteBarangExpired $barangExpired): Response
    {
        $url = rtrim(env('NLM_API_URL', ''), '/') . '/' . self::BASE_URL . '/store';

        return Http::withHeaders([
            'X-API-KEY'     => env('NLM_API_KEY', ''),
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ])->post($url, $barangExpired->toArray());
    }

    /**
     * Sync status from remote API
     */
    public function syncWithRemote(ObatExpired $obatExpired): void
    {
        $nomor = base64_encode($obatExpired->nomor);
        $url   = rtrim(env('NLM_API_URL', ''), '/') . '/' . self::BASE_URL . '/' . $nomor;

        $res = Http::withHeaders([
            'X-API-KEY'     => env('NLM_API_KEY', ''),
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ])->get($url);

        if (!$res->successful() || !isset($res['data']['status'])) {
            Log::error('Sync obat expired failed', [
                'url'      => $url,
                'response' => $res->body(),
            ]);
            return;
        }

        $status = $res['data']['status'];
        if ($status !== $obatExpired->status) {
            $old = $obatExpired->status;
            $obatExpired->status = $status;
            $obatExpired->save();

            Log::info("Update status obat expired {$obatExpired->nomor} from {$old} to {$status}");
        }
    }

    /**
     * Generate nomor expire with monthly counter
     */
    public function generateExpireNum(): string
    {
        $user     = Auth::user();
        $kodeUnit = $user->unit->code;

        $prefix = "NLM/{$kodeUnit}/ED/";

        $latest = ObatExpired::where('unit_id', $user->unit_id)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count() + 1;

        $padded = str_pad($latest, 3, "0", STR_PAD_LEFT);
        $month  = now()->format('m');
        $year   = now()->format('Y');
        $suffix = "{$year}/{$month}/{$padded}";

        return $prefix . $suffix;
    }
}
