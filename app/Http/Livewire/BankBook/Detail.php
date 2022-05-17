<?php

namespace App\Http\Livewire\BankBook;

use Livewire\Component;
use App\Models\BankBook;
use Livewire\WithPagination;

class Detail extends Component
{
    use WithPagination;

    public $active,$data,$generate_no_voucher;
    public $type="P",$to_bank_account_id,$amount,$note,$opening_balance=0,$status;
    public $filter_type,$filter_amount,$payment_date,$date_from,$date_to;
    protected $listeners = ['refresh'=>'$refresh'];
    public function render()
    {
        $data = BankBook::where('from_bank_id',$this->data->id)->orderBy('id','DESC');

        if($this->filter_type) $data->where('type',$this->filter_type);
        if($this->status) $data->where('status',$this->status);
        if($this->filter_amount) $data->where(function($table){
                $max = (int)(0.1*$this->filter_amount)+$this->filter_amount;
                $min = $this->filter_amount - (int)(0.1*$this->filter_amount);
                $table->where('amount','<=',$max)->where('amount','>=',$min);
            });
        if($this->date_from and $this->date_to) {
            if($this->date_from == $this->date_to)
                $data->whereDate('payment_date',$this->date_from);
            else
                $data->whereBetween('payment_date',[$this->date_from,$this->date_to]);
        }

        $p = clone $data;
        $r = clone $data;
        $a = clone $data;
        $u = clone $data;
        $settle = clone $data;
        $total = clone $data;

        return view('livewire.bank-book.detail')->with(['lists'=>$data->paginate(100), 
                                                        'total_unidentity'=>$u->where('status',0)->count(), 
                                                        'total_settle'=>$settle->where('status',1)->count(), 
                                                        'unidentity'=>$u->where('status',0)->sum('amount'),
                                                        'total'=>$total->sum('amount'),
                                                        'total_payable'=>$p->where('type','p')->sum('amount'),
                                                        'total_receivable'=>$r->where('type','r')->sum('amount'),
                                                        'total_a'=>$a->where('type','a')->sum('amount')]);
    }

    public function reset_filter()
    {
        $this->reset(['date_from','date_to','filter_type','status','filter_amount']);
    }

    public function mount($data,$active)
    {
        $this->data = $data;
        $this->active = $active;
        $this->generate_no_voucher = $this->type.str_pad((BankBook::count()+1),8, '0', STR_PAD_LEFT);
        $this->opening_balance = $this->data->open_balance;
    }

    public function updated($propertyName)
    {
        $this->generate_no_voucher = $this->type.str_pad((BankBook::count()+1),8, '0', STR_PAD_LEFT);
        $this->emit('init-form');
    }

    public function delete(BankBook $id)
    {
        \LogActivity::add("Bank Book - Delete #{$id->id}");

        $id->delete();
        $this->emit('message-success','Data deleted successfully');
    }
}