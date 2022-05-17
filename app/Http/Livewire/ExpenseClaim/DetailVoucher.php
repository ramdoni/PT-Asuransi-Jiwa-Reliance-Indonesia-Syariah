<?php

namespace App\Http\Livewire\ExpenseClaim;

use Livewire\Component;
use App\Models\Expenses;

class DetailVoucher extends Component
{
    public $data=[];
    protected $listeners = ['set-voucher'=>'setVoucher'];
    public function render()
    {
        return view('livewire.expense-claim.detail-voucher');
    }
    public function setVoucher(Expenses $id)
    {
        $this->data = $id;
    }
}
