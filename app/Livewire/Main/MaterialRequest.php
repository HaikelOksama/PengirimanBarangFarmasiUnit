<?php

namespace App\Livewire\Main;

use App\Models\Matreq;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class MaterialRequest extends Component
{

    use WithPagination;

    public function render()
    {  
        $matreq = Matreq::where('from_unit_id', Auth::user()->unit_id)->paginate(20);
        return view('livewire.main.material-request', compact('matreq'));
    }
}
