<?php

namespace App\Livewire\Main;

use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Farmalkes extends Component
{
    use WithPagination;

    #[Url]
    public $search = '';

    public function updating($property, $value) {
        if($property === 'search') {
            $this->resetPage();
        }
    }

    public function search()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.main.farmalkes');
    }

    #[Computed]
    public function farmalkes() {
        return \App\Models\Farmalkes::with('pbf')->when($this->search, function ($q) {
            $q->where('nama', 'like', "%{$this->search}%")->orWhereHas('pbf', function($q) {
                $q->where('nama', 'like', "%{$this->search}%");
            });
        })->orderBy('nama')->paginate(20);
    }

    public function bust() {
        // unset($this->farmalkes());
    }
}
