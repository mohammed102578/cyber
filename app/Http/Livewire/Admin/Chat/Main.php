<?php

namespace App\Http\Livewire\Admin\Chat;

use Livewire\Component;

class Main extends Component
{
    public function render()
    {
        return view('livewire.admin.chat.main')->layout('layouts.admin.app');
    }
} 
