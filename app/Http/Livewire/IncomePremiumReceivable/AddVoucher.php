<?php

namespace App\Http\Livewire\IncomePremiumReceivable;

use Livewire\Component;
use App\Models\BankBook;
use Livewire\WithPagination;

class AddVoucher extends Component
{
    use WithPagination;
    public $data,$total=0,$check_id=[],$is_disabled=false;
    protected $paginationTheme = 'bootstrap';

    public function render()
    {
        $data = BankBook::where('type','R')->where(function($table){
            $table->where('status',0)->orWhere('status',2);
        })->orderBy('id','ASC');

        return view('livewire.income-premium-receivable.add-voucher')->with(['lists'=>$data->paginate(20)]);
    }

    public function mount($data)
    {
        $this->data = $data;
    }

    public function updated()
    {
        $this->resetPage();
        $this->total = BankBook::whereIn('id',$this->check_id)->sum('amount');
        if($this->total >=$this->data->nominal)
            $this->is_disabled = true;
        else
            $this->is_disabled = false;
    }

    public function submit()
    {
        $this->emit('set-voucher',$this->check_id);
    }
}
