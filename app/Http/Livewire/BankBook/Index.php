<?php

namespace App\Http\Livewire\BankBook;

use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        return view('livewire.bank-book.index');
    }

    public function mount()
    {
        \LogActivity::add("Bank Book");
    }
}
