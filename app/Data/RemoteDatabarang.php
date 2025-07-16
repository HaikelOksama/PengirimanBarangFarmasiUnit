<?php

namespace App\Data;

class RemoteDatabarang
{
    public string $kode;
    public string $nama;
    public int $isi;
    public string $satuan;
    public string $kemasan;
    public string $pbf_kode;
    public float $harga_beli;
    public float $hna;
    public float $diskon;
    public float $ppn;

    public function __construct(array $data)
    {
        $this->kode = $data['kode'];
        $this->nama = $data['nama'];
        $this->isi = (int) $data['isi'];
        $this->satuan = $data['satuan'];
        $this->kemasan = $data['kemasan'];
        $this->pbf_kode = $data['pbf_kode'];
        $this->harga_beli = (float) $data['harga_beli'];
        $this->hna = (float) $data['hna'];
        $this->diskon = (float) $data['diskon'];
        $this->ppn = (float) $data['ppn'];
    }
}