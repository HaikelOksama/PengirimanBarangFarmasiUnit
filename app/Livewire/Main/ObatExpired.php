<?php

namespace App\Livewire\Main;

use App\Models\ObatExpired as ModelsObatExpired;
use App\Services\BarangExpireService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ObatExpired extends Component
{

    use WithPagination;

    private BarangExpireService $service;

    #[Url()]
    public $startDateQ;

    #[Url()]
    public $endDateQ;

    #[Url()]
    public $searchQ;

    public function boot() {
        $this->service = new BarangExpireService();
    }

    public function render()
    {
        return view('livewire.main.obat-expired');
    }


    #[Computed()]
    public function expiredList() {
        ModelsObatExpired::syncAll();
        $data = ModelsObatExpired::with('items.farmalkes')
        ->where('unit_id', Auth::user()->unit_id)
        ->when($this->startDateQ && $this->endDateQ, function($q) {
            $q->whereBetween('tanggal', [$this->startDateQ, $this->endDateQ]);
        })->when($this->searchQ, function ($q) {
            $trimmed = trim($this->searchQ);
            $q->where('nomor', 'like', "%{$trimmed}%")
            ->orWhere('keterangan', 'like', "%{$trimmed}%")
            ->orWhere('status', 'like', "%{$trimmed}%")
            ;
        })->paginate(20);
        return $data;
    }

    public function reload() {
        unset($this->expiredList);
    }
}
