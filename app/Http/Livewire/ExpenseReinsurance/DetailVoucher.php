<?php

namespace App\Http\Livewire\ExpenseReinsurance;

use App\Models\Expenses;
use Livewire\Component;

class DetailVoucher extends Component
{
    public $data=[];
    protected $listeners = ['set-voucher'=>'setVoucher'];
    public function render()
    {
        return view('livewire.expense-reinsurance.detail-voucher');
    }

    public function setVoucher(Expenses $id)
    {
        $this->data = $id;
    }
}
