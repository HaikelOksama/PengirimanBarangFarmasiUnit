<?php

namespace App\Livewire\Main;

use App\Enums\MatreqStatus;
use App\Models\Matreq;
use App\Models\Unit;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;


class MaterialRequest extends Component
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
        $matreq = Matreq::with('fromUnit', 'toUnit', 'items.farmalkes.pbf')
        ->when(!Auth::user()->hasRole('admin'), function($query) {
            $query->where('from_unit_id', Auth::user()->unit_id);
        })
        ->orderByRaw("FIELD(status,'kirim','request', 'selesai')")
        ->orderByDesc('created_at')
        ->when($this->unitQ, function($query) {
            return $query->where('to_unit_id', $this->unitQ);
        })
        ->when($this->searchQ, function($query) {
            $query->search($this->searchQ);
        })
        ->when($this->startDate && $this->endDate, function($query) {
            $query->dateRange($this->startDate, $this->endDate);
        })
        ->paginate(10);
        return view('livewire.main.material-request', compact('matreq'))->title('Permintaan '.Auth::user()->unit?->nama ?? 'Unit');
    }

    public function receive($id) {
        $matreq = Matreq::find($id);
        $matreq->status = MatreqStatus::SELESAI->value;
        $matreq->tgl_terima = now();
        $matreq->save();
        $this->dispatch('success', 'Permintaan untuk unit ' . $matreq->toUnit->nama . ' selesai');
    }

    public function destroy($id) {
        $matreq = Matreq::find($id);
        $matreq->delete();
        $this->dispatch('success', 'Permintaan untuk unit ' . $matreq->toUnit->nama . ' berhasil dihapus');
    }

    #[Computed(persist: true, )]
    public function units() {
        return Unit::whereNot('id', Auth::user()->unit_id)->pluck('nama', 'id')->toArray();
    }
}
