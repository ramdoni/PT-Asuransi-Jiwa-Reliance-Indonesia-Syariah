<?php

namespace App\Http\Livewire\IncomePremiumReceivable;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Expenses;

class AddClaimPayable extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $data,$keyword,$check_id=[],$total=0,$peserta;
    public function render()
    {
        $data = Expenses::select('expenses.*')
        ->with(['pesertas'])
        ->orderBy('expenses.id','desc')->where('expenses.reference_type','Claim')->groupBy('expenses.id')
        ->leftJoin('expense_pesertas','expense_pesertas.expense_id','=','expenses.id')
        ->where('expenses.policy_id',$this->data->policy_id)
        ->where('status',4); // hanya statusnya draft saja
        
        if($this->keyword) {
            $data->where(function($table){
                $max = (int)(0.1*$this->keyword)+$this->keyword;
                $min = $this->keyword - (int)(0.1*$this->keyword);
                $table->where('payment_amount','<=',$max)->where('payment_amount','>=',$min);
            });
        }
        if($this->peserta) $data->where(function($table){
                                $table->where('expense_pesertas.no_peserta','LIKE',"%{$this->peserta}%")
                                    ->orWhere('expense_pesertas.nama_peserta','LIKE',"%{$this->peserta}%");
                            });
        return view('livewire.income-premium-receivable.add-claim-payable')->with(['claim'=>$data->paginate(20)]);
    }

    public function mount($data)
    {
        $this->data = $data;
    }

    public function updated()
    {
        $this->total = Expenses::whereIn('id',$this->check_id)->sum('payment_amount');; 
    }

    public function submit()
    {
        $this->emit('set-claim',$this->check_id);
    }
}
