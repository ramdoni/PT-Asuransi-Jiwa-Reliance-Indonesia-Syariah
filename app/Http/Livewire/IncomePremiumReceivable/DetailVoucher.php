<?php

namespace App\Http\Livewire\IncomePremiumReceivable;

use Livewire\Component;
use App\Models\Income;

class DetailVoucher extends Component
{
    public $data=[];
    protected $listeners = ['set-voucher'=>'setVoucher'];
    public function render()
    {
        return view('livewire.income-premium-receivable.detail-voucher');
    }

    public function setVoucher(Income $id)
    {
        $this->data = $id;
    }
}
