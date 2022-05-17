<?php

namespace App\Http\Livewire\IncomeReinsurance;

use App\Models\Income;
use Livewire\Component;

class DetailVoucher extends Component
{
    public $data=[];
    protected $listeners = ['set-voucher'=>'setVoucher'];
    public function render()
    {
        return view('livewire.income-reinsurance.detail-voucher');
    }

    public function setVoucher(Income $id)
    {
        $this->data = $id;
    }
}