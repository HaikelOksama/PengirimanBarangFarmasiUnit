<?php

namespace App\Data;

class RemoteBarangExpired {

    
    /**
     * Construct a new RemoteBarangExpired.
     *
     * @param string $tanggal the date of this expired material request
     * @param string $unit the unit of this expired material request
     * @param array $items the items of this expired material request.
     *                     Must be an array of RemoteBarangExpiredItem objects.
     * @param string $keterangan the description of this expired material request
     * @param string $apoteker the apoteker of this expired material request
     * @param string $kepala_unit the head of unit of this expired material request
     */
    public function __construct(
        protected string $tanggal,
        protected string $unit,
        protected string $nomor,
        protected string $apoteker,
        protected string $kepala_unit,
        protected string $keterangan,
        protected array $items,
    )
    {}

    public function toArray() {
        return [
            'tanggal' => $this->tanggal,
            'unit' => $this->unit,
            'nomor' => $this->nomor,
            'apoteker' => $this->apoteker,
            'kepala_unit' => $this->kepala_unit,
            'keterangan' => $this->keterangan,
            'items' => $this->items,
        ];
    }
    
}

class RemoteBarangExpiredItem {
    public function __construct(
        protected string $kode_brng,
        protected int $jumlah,
        protected string $expire_date
    )
    {}
}