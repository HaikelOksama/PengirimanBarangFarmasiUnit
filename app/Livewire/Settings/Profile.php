<?php

namespace App\Livewire\Settings;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class Profile extends Component
{
    public string $name = '';

    public ?string $email = '';

    public string $apoteker = '';
    public string $kepala_unit = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email ?? '';
        $this->apoteker = Auth::user()->unit?->apoteker ?? '';
        $this->kepala_unit = Auth::user()->unit?->kepala_unit ?? '';
    }

    /**
     * Update the profile information for the currently authenticated user.
     */
    public function updateProfileInformation(): void
    {
        $user = Auth::user();

        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
        
        $user->fill($validated);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        if($user->unit != null) {
            $unitValidate = $this->validate([
                'kepala_unit' => ['required', 'string', 'max:255'],
                'apoteker' => ['required', 'string', 'max:255']
            ]);
            $unit = $user->unit;
            $unit->fill($unitValidate);
    
            $unit->save();
        }
        $user->save();
        
        $this->dispatch('profile-updated', name: $user->name);
    }

    /**
     * Send an email verification notification to the current user.
     */
    public function resendVerificationNotification(): void
    {
        $user = Auth::user();

        if ($user->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('dashboard', absolute: false));

            return;
        }

        $user->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }
}
