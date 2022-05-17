<?php

namespace App\Http\Livewire\ExpenseCommisionPayable;

use App\Models\Expenses;
use Livewire\Component;

class DetailVoucher extends Component
{
    public $data=[];
    protected $listeners = ['set-voucher'=>'setVoucher'];
    public function render()
    {
        return view('livewire.expense-commision-payable.detail-voucher');
    }
    public function setVoucher(Expenses $id)
    {
        $this->data = $id;
    }
}
