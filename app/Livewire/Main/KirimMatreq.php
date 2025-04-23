<?php

namespace App\Livewire\Main;

use App\Models\Matreq;
use App\Models\Unit;
use Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Kirim Permintaan dari Unit')]
class KirimMatreq extends Component
{

    use WithPagination;

    #[Url('unit')]
    public $unitQ = '';

    #[Url('search')]
    public $searchQ = '';

    #[Url]
    public $startDate = '';

    #[Url]
    public $endDate = '';

    public function render()
    {
        $matreq = Matreq::with('fromUnit', 'toUnit', 'items.farmalkes.pbf')->where('to_unit_id', Auth::user()->unit_id)
        ->orderByRaw("FIELD(status, 'request', 'kirim', 'selesai')")
        ->when($this->unitQ, function($query) {
            return $query->where('from_unit_id', $this->unitQ);
        })
        ->when($this->searchQ, function($query) {
            $query->search($this->searchQ);
        })
        ->when($this->startDate && $this->endDate, function($query) {
            $query->dateRange($this->startDate, $this->endDate);
        })
        ->paginate(20);
        return view('livewire.main.kirim-matreq', compact('matreq'));
    }

    #[Computed(persist: true, )]
    public function units() {
        return Unit::whereNot('id', Auth::user()->unit_id)->pluck('nama', 'id')->toArray();
    }
}
