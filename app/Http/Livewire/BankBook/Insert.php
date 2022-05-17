<?php

namespace App\Http\Livewire\BankBook;

use Livewire\Component;
use App\Models\BankBook;

class Insert extends Component
{
    public $generate_no_voucher,$type,$data,$amount,$note,$payment_date,$propose,$bank_code;

    public function render()
    {
        return view('livewire.bank-book.insert');
    }

    public function mount($data)
    {
        $this->data = $data;
        $this->generate_no_voucher = $this->type.$this->propose.str_pad((BankBook::count()+1),8, '0', STR_PAD_LEFT).$this->data->code;
        $this->payment_date = date('Y-m-d');
        $this->bank_code = $this->data->code;
    }

    public function save()
    {
        $this->validate([
            'type'=>'required',
            'amount'=>'required',     
            'payment_date'=>'required',
            'propose' => 'required',
            'bank_code' => 'required'
        ]);
        
        $this->generate_no_voucher = $this->type.$this->propose.str_pad((BankBook::count()+1),8, '0', STR_PAD_LEFT).$this->data->code;

        $data = new BankBook();
        $data->from_bank_id = $this->data->id;
        $data->type = $this->type;
        $data->propose = $this->propose;
        $data->amount = str_replace(',','',$this->amount);
        $data->note = $this->note;
        $data->no_voucher = $this->generate_no_voucher;
        $data->payment_date = $this->payment_date;
        $data->save();
        
        $this->generate_no_voucher = $this->type.$this->propose.str_pad((BankBook::count()+1),8, '0', STR_PAD_LEFT).$this->data->code;
        $this->emit('refresh');
        $this->reset(['type','amount','note']);

        \LogActivity::add("Bank Book - Insert #{$data->id}");
    }
}
