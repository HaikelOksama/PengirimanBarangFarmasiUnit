<?php

namespace App\Livewire\Main;

use App\Models\Pbf;
use App\Services\BarangExpireService;
use App\Services\DatabarangService;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CreateObatExpired extends Component
{
    public $searchFarmalkes = '';
    public $selected = null;

    #[Validate(
        'required|date'
    )]
    public $tanggal;

    #[Validate(
        'required|string|max:255',
    )]
    public $keterangan;

    private $pbfMap;

    #[Validate([
        'requestList' => 'required|min:1',
        'requestList.*.qty' => ['required', 'numeric', 'min:1'],
        'requestList.*.expire_date' => ['required', 'date'],
    ], message: [
        'requestList.*.qty.required' => 'Kuantiti tidak boleh kosong',
        'requestList.*.qty.min' => 'Kuantiti minimal 1',
        'requestList.*.expire_date.required' => 'Tanggal kadaluarsa tidak boleh kosong',
    ])]
    public $requestList = [];


    private DatabarangService $databarangService;
    private BarangExpireService $barangExpireService;

    public function boot()
    {
        $this->databarangService = new DatabarangService();
        $this->barangExpireService = new BarangExpireService();
    }

    public function mount()
    {
        $this->tanggal = now();
        $pbfMap = Pbf::select('kode', 'nama')->get()->keyBy('kode');
        $this->pbfMap = $pbfMap;
    }

    public function addFarmalkes()
    {
        $farmalkes = $this->databarangService->syncWithLocalSingle($this->selected);

        // dd($farmalkes);
        // $farmalkes = Farmalkes::find($this->selected); local
        $this->selected = null;
        $this->searchFarmalkes = '';
        if (!empty($this->requestList)) {
            foreach ($this->requestList as &$item) {
                if ($item['data']->id == $farmalkes['data']['id']) {
                    $item['qty'] = $item['qty'] + 1;
                    return;
                }
            }
        }
        $this->requestList[] = ['data' => $farmalkes['data'], 'qty' => 1];
    }

    public function unsetFarmalkes($index)
    {
        unset($this->requestList[$index]);
        $this->requestList = array_values($this->requestList);
    }

    public function render()
    {
        $options = [];
        $pbfMap = $this->pbfMap;
        if (strlen($this->searchFarmalkes) > 2) {
            try {
                $options = $this->databarangService->getFromRemote($this->searchFarmalkes)->map(function ($item) use ($pbfMap) {
                    $item->pbf_kode = $pbfMap[$item->pbf_kode]->nama ?? '-';
                    return $item;
                });
            } catch (\Throwable $th) {
                $options = Farmalkes::with('pbf')->where('nama', 'like', '%' . $this->searchFarmalkes . '%')
                    ->select('id', 'kode', 'nama', 'pbf_kode')
                    ->limit(20)->get();
            }
        }

        return view('livewire.main.create-obat-expired', compact('options'));
    }

    public function submit(): void
    {
        $this->validate();
        $data = collect([
            'keterangan' => $this->keterangan,
            'tanggal' => $this->tanggal->format('Y-m-d'),
            'items' => $this->requestList
        ]);

        $result = $this->barangExpireService->store($data);
        if ($result->status() == 201) {
            $this->dispatch('success', 'Data berhasil dikirim');
            $this->reset('requestList', 'keterangan', 'tanggal');
        } else {
            $this->dispatch('error', "Data gagal dikirim, silahkan coba lagi, error: {$result->collect()->get('message')}");
        }
    }
}
