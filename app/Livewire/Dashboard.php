<?php

namespace App\Livewire;

use App\Models\ChangeLog;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $changelog = ChangeLog::orderBy('created_at', 'desc')->limit(5)->get();
        return view('livewire.dashboard', compact('changelog'));
    }
}
