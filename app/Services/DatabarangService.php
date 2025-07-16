<?php

namespace App\Services;

use App\Data\RemoteDatabarang;
use App\Models\Farmalkes;
use Illuminate\Support\Facades\Http;

class DatabarangService {

    // protected const BASE_URL = 'http://192.168.1.220:8012/api/'; //for dev only
    

    public static function getFromRemote($query) {
        $res = Http::withHeaders(['X-API-KEY' => env('NLM_API_KEY', '')])->get(env('NLM_API_URL', '') . 'gudangfarmasi/databarang', [
            'search' => $query
        ]);
        $data = $res->json();
        // dd($data['data']);
        return collect($data['data'])->map(fn($item) => new RemoteDatabarang($item));
    }

    public static function getSingleFromRemote($id) {
        $res = Http::withHeaders(['X-API-KEY' => env('NLM_API_KEY', '')])->get(env('NLM_API_URL', '') . 'gudangfarmasi/databarang/' . $id);
        $data = $res->json();
        return new RemoteDatabarang($data['data']);
    }

    public static function syncWithLocalSingle($id) {
        try {
            $res = self::getSingleFromRemote($id);
            $farmalkes = Farmalkes::where('kode', $res->kode)->first();
            if(empty($farmalkes)) {
                $farmalkes = new Farmalkes();
            }
            $farmalkes->kode = $res->kode;
            $farmalkes->nama = $res->nama;
            $farmalkes->isi = $res->isi;
            $farmalkes->satuan = $res->satuan;
            $farmalkes->kemasan = $res->kemasan;
            $farmalkes->pbf_kode = $res->pbf_kode;
            $farmalkes->harga_beli = round($res->harga_beli * $res->isi, 2);
            $farmalkes->hna = round($res->hna, 2);
            $farmalkes->diskon = $res->diskon;
            $farmalkes->ppn = $res->ppn;
            $farmalkes->save();
            return ['data' => $farmalkes, 'status' => 'success'];
        } catch (\Throwable $th) {
            return ['data' => Farmalkes::where('kode', $id)->first(), 'status' => 'failed', 'err' => $th->getMessage()];
        }
    }
    

}