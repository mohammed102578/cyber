<?php

namespace App\Http\Livewire\Reporter\Chat;

use Livewire\Component;

class Main extends Component
{
    public function render()
    {
        return view('livewire.reporter.chat.main')->layout('layouts.reporter.app');
    }
} 
