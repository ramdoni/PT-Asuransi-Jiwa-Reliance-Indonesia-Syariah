<?php

namespace App\Http\Livewire\BankBook;

use Livewire\Component;
use App\Models\BankBook;
use App\Models\BankBookTransactionItem;

class PayableDetail extends Component
{
    protected $listeners = ['setid'];
    public $bank_book,$vouchers=[],$total_voucher=0,$items=[],$error_suspend=[];

    public function render()
    {
        return view('livewire.bank-book.payable-detail');
    }

    public function setid(BankBook $id)
    {
        $this->bank_book = $id;
        $this->vouchers = BankBook::where('bank_book_transaction_id',$this->bank_book->bank_book_transaction_id)->get();
        $this->items = BankBookTransactionItem::where('bank_book_transaction_id',$this->bank_book->bank_book_transaction_id)->get();
    }
}