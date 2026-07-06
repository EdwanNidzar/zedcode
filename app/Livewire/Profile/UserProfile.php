<?php

namespace App\Livewire\Profile;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserProfile extends Component
{
    public $nik, $divisi, $jabatan, $unit_kerja, $phone, $atasan_id;
    
    public function mount()
    {
        $user = Auth::user();
        $this->nik = $user->nik;
        $this->divisi = $user->divisi;
        $this->jabatan = $user->jabatan;
        $this->unit_kerja = $user->unit_kerja;
        $this->phone = $user->phone;
        $this->atasan_id = $user->atasan_id;
    }

    public function save()
    {
        $this->validate([
            'nik' => 'nullable|string|max:50',
            'divisi' => 'nullable|string|max:255',
            'jabatan' => 'nullable|string|max:255',
            'unit_kerja' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:30',
            'atasan_id' => 'nullable|exists:users,id',
        ]);

        $user = Auth::user();
        $user->update([
            'nik' => $this->nik,
            'divisi' => $this->divisi,
            'jabatan' => $this->jabatan,
            'unit_kerja' => $this->unit_kerja,
            'phone' => $this->phone,
            'atasan_id' => $this->atasan_id,
        ]);

        session()->flash('success', 'Profil berhasil diperbarui!');
    }

    public function render()
    {
        // Don't list self as possible atasan
        $atasans = User::where('id', '!=', Auth::id())->orderBy('name')->get();

        return view('livewire.profile.user-profile', compact('atasans'))
            ->layout('components.app-layout');
    }
}
