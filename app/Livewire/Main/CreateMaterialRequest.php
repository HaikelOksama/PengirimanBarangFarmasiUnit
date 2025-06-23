<?php

namespace App\Livewire\Main;

use App\Models\Farmalkes;
use App\Models\Matreq;
use App\Models\Unit;
use App\Services\MatreqService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Buat Permintaan Alkes dan Obat')]
class CreateMaterialRequest extends Component
{
    public $searchFarmalkes = '';
    public $selected = null;

    #[Validate('date|required')]
    public $tglBuat;

    #[Validate(
        rule: 'required|exists:units,id',
    )]
    public $toUnit;

    #[Validate(
        'required|array|min:1',
    )]
    public $requestList = [];

        public function mount() {
            $this->tglBuat = now();
        }

    public function addFarmalkes() {
        $farmalkes = Farmalkes::find($this->selected);
        $this->selected = null;
        $this->searchFarmalkes = '';
        if(!empty($this->requestList)) {
            foreach ($this->requestList as &$item) {
                if($item['data']->id == $farmalkes->id) {
                    $item['qty'] = $item['qty'] + 1;
                    return;
                }
            }
        } 
        $this->requestList[] = ['data' => $farmalkes, 'qty' => 1];
        

    }

    public function unsetFarmalkes($index) {
        unset($this->requestList[$index]);
        $this->requestList = array_values($this->requestList);
    }

    public function render()
    {
        $options = [];
        if (strlen($this->searchFarmalkes) > 2) {
            $options = Farmalkes::with('pbf')->where('nama', 'like', '%' . $this->searchFarmalkes . '%')
            ->select('id', 'nama', 'pbf_kode')
                ->limit(20)->get();
        }

        return view('livewire.main.create-material-request', compact('options'));
    }

    #[Computed(persist: true)]
    public function units() {
        return Unit::where('id', '!=', Auth::user()->unit_id)->select('id', 'nama')->get();
    }

    public function submit() {
        $this->validate();
        $matreq = new Matreq();
        $matreq->toUnit()->associate($this->toUnit);
        $matreq->fromUnit()->associate(Auth::user()->unit_id);
        $serv = new MatreqService($matreq);
        $serv->requestMatreq($this->tglBuat);
        $serv->syncItems($this->requestList);
        $this->reset('requestList', 'toUnit');
        $this->dispatch('success', 'Permintaan untuk unit ' . $matreq->toUnit->nama . ' berhasil dikirim');
    }
}
