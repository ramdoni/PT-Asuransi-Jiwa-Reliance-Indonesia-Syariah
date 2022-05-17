<?php

namespace App\Http\Livewire\BankBook;

use Livewire\Component;
use App\Models\BankBook;
use App\Models\Income;
use Livewire\WithPagination;

class Payable extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $check_id=[],$type,$transaction_id,$filter_status,$filter_from_bank,$filter_to_bank;

    public function render()
    {
        $data = BankBook::where('type','P')->orderBy('id','desc');
        
        if($this->filter_status!="") $data->where('status',$this->filter_status);
        if($this->filter_from_bank) $data->where('from_bank_id',$this->filter_from_bank);
        if($this->filter_to_bank) $data->where('to_bank_id',$this->filter_to_bank);

        return view('livewire.bank-book.payable')->with(['data'=>$data->paginate(100)]);
    }

    public function clear_filter()
    {
        $this->reset(['filter_status','filter_from_bank','filter_to_bank']);
        $this->emit('clear-filter');
    }

    public function mount()
    {
        $this->premium_receivable = Income::where('reference_type','Premium Receivable')->get();
    }

    public function updated($propertyName)
    {
        if($propertyName=='type'){
            $this->emit('select-premium-receivable');
        }

        if($propertyName=='check_id') $this->emit('set_bank_book',$this->check_id);
    }
}
