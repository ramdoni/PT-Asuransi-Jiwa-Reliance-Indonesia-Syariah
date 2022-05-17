<?php

namespace App\Http\Livewire\Accounting\Others\Payable;

use Livewire\Component;

class Edit extends Component
{
    public $is_readonly = false,$payment_amount,$outstanding_balance;
    public function render()
    {
        return view('livewire.accounting.others.payable.edit');
    }
}
