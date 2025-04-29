<?php

namespace App\Livewire\Component;

use App\Models\Farmalkes;
use Livewire\Component;

class FarmalkesItem extends Component
{
    public Farmalkes $farmalkes; 
    public $loop = 0;

    
    public $edit = false;
    public $nama = '';
    public $pbf  = '';
    public $harga_beli  = '';
    public $hna  = '';
    public $satuan  = '';
    public $diskon  = '';
    public $kemasan  = '';
    public $kode  = '';
    public $ppn  = '';


    public function mount(Farmalkes $farmalkes) {
        $farmalkes->loadMissing('pbf');
        $this->farmalkes = $farmalkes;
        $this->nama = $farmalkes->nama;
        $this->pbf = $farmalkes->pbf->id;
        $this->harga_beli = $farmalkes->harga_beli;
        $this->hna = $farmalkes->hna;
        $this->satuan = $farmalkes->satuan;
        $this->diskon = $farmalkes->diskon;
        $this->kemasan = $farmalkes->kemasan;
        $this->kode = $farmalkes->kode;
        $this->ppn = $farmalkes->ppn;
        // dd($this->nama);

    }

    public function render()
    {
        return view('livewire.component.farmalkes-item');
    }

    public function save()
    {
        $validated = $this->validate([
            'nama' => ['required', 'max:255', 'string'],
            'kode' => ['required',  'max:255', 'string'],
            'satuan' => ['required',  'max:20', 'string'],
            'kemasan' => ['required',  'max:20', 'string'],
            'harga_beli' => ['required',  'numeric'],
            'hna' => ['required',  'numeric'],
            'diskon' => ['required',  'numeric'],
            'ppn' => ['required',  'numeric'],
        ]);

        $this->farmalkes->fill($validated);
        $this->farmalkes->save();

        // $this->farmalkes->update([
        //     'nama' => $this->nama,
        //     'kode' => $this->kode,
        //     'satuan' => $this->satuan,
        //     'kemasan' => $this->kemasan,
        //     'harga_beli' => $this->hargaBeli,
        //     'hna' => $this->hna,
        //     'diskon' => $this->diskon,
        //     'ppn' => $this->ppn,
        // ]);

        $this->dispatch('success', "{$this->farmalkes->nama} berhasil di update!");
    }
}
