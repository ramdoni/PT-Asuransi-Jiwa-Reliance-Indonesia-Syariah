<?php

namespace App\Http\Livewire\BankBook;

use Livewire\Component;
use App\Models\BankBook;

class TeknikInsert extends Component
{
    public $bank_book_id,$bank_books=[];
    public function render()
    {
        return view('livewire.bank-book.teknik-insert');
    }

    public function mount()
    {
        $this->bank_books = BankBook::where('status',0)->get();
    }
}
