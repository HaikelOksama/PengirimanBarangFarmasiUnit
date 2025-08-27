<?php

namespace App\Services;

use App\Data\RemoteBarangExpired;
use App\Models\Farmalkes;
use App\Models\ObatExpired;
use App\Models\ObatExpiredItem;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BarangExpireService
{

    // protected const BASE_URL = 'http://192.168.1.226:8080/api/gudangfarmasi/barangexpired'; //for dev only

    public function store($data): Response
    {
        $ket = $data['keterangan'];
        $tanggal = $data['tanggal'];
        $items = $data['items'];

        $expireNum = $this->generateExpireNum();
        $unit = Auth::user()->unit;
        $itemsLocal = collect($items)->map(fn($item) => [
            'farmalkes_id' => $item['data']->id,
            'qty' => (int) $item['qty'],
            'expire_date' => $item['expire_date']
        ]);

        $res = DB::transaction(function () use (&$itemsLocal, $tanggal, $ket, $expireNum, $unit, $items) {
            $obatExpired = new ObatExpired();
            $obatExpired->tanggal = $tanggal;
            $obatExpired->nomor = $expireNum;
            $obatExpired->status = 'pending';
            $obatExpired->unit_id = $unit->id;
            $obatExpired->keterangan = $ket;
            $obatExpired->save();
            foreach ($itemsLocal as $item) {
                $farmalkes = Farmalkes::find($item['farmalkes_id']);

                $obatExpiredItem = new ObatExpiredItem();
                $obatExpiredItem->obatExpired()->associate($obatExpired);
                $obatExpiredItem->farmalkes()->associate($farmalkes);
                $obatExpiredItem->qty = $item['qty'];
                $obatExpiredItem->expire_date = $item['expire_date'];
                $obatExpiredItem->save();
            }

            $apt = $unit->apoteker;
            $kepala = $unit->kepala_unit;
            $itemsRemote = collect($items)->map(fn($item) => [
                'kode_brng' => $item['data']->kode,
                'jumlah' => (int) $item['qty'],
                'expire_date' => $item['expire_date']
            ]);
            $remote = new RemoteBarangExpired(
                $tanggal,
                $unit->nama,
                $expireNum,
                $apt,
                $kepala,
                $ket,
                $itemsRemote->toArray()
            );
            $response = $this->sendToRemote($remote);
            $obatExpired->remote_id = $response->collect()->get('id');
            $obatExpired->save();
            return $response;
        });

        return $res;
    }

    private function sendToRemote(RemoteBarangExpired $barangExpired): Response
    {
        $url = env('NLM_API_URL', ''). '/store';
        $res = Http::withHeaders([
            'X-API-KEY' => env('NLM_API_KEY', ''),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])
            ->post($url, $barangExpired->toArray());
        return $res;
    }

    public function syncWithRemote(ObatExpired $obatExpired)
    {
        $nomor = base64_encode($obatExpired->nomor);
        $url = env('NLM_API_URL', '') . '/' . $nomor;
        $res = Http::withHeaders([
            'X-API-KEY' => env('NLM_API_KEY', ''),
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ])->get($url);
        $status = $res->collect()->get('data')['status'];
        if($status != $obatExpired->status) {
            $obatExpired->status = $status;
            $obatExpired->save();
            Log::log('info', 'update status obat expired ' . $obatExpired->nomor . ' to ' . $obatExpired->status);
        }
    }

    public function generateExpireNum()
    {
        $kodeUnit = Auth::user()->unit->code;
        $prefix = "NLM/{$kodeUnit}/ED/";
        $latest = ObatExpired::where('unit_id', Auth::user()->unit_id)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->count() + 1;
        $padded = str_pad($latest, 3, "0", STR_PAD_LEFT);
        $month = now()->format('m');
        $year = now()->format('Y');
        $suffix = "{$year}/{$month}/{$padded}";

        return $prefix . $suffix;
    }
}
