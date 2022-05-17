<?php

namespace App\Http\Livewire\IncomeRecoveryClaim;

use Livewire\Component;
use App\Models\Income;
use App\Models\Expenses;
use App\Models\IncomeRecoveryClaim;
use App\Models\IncomeTitipanPremi;

class Insert extends Component
{
    public $is_submit=true,$data,$premium_receivable,$expense_id,$reference_no,$nominal;
    public $is_readonly=false,$description,$reference_date,$amount;
    public $add_pesertas=[],$no_peserta=[],$nama_peserta=[];
    public $add_claim_payables=[],$add_expense_id=[];

    protected $listeners = ['emit-add-bank'=>'emitAddBank','set-titipan-premi'=>'setTitipanPremi'];

    public function render()
    {
        return view('livewire.income-recovery-claim.insert');
    }

    public function mount()
    {
        $this->reference_date = date('Y-m-d');
    }
    
    public function clearTitipanPremi()
    {
        $this->reset('temp_titipan_premi','temp_arr_titipan_id','total_titipan_premi','from_bank_account_id','to_bank_account_id');
        $this->emit('init-form');
    }

    public function setTitipanPremi($id)
    {
        $this->temp_arr_titipan_id[] = $id;
        $this->temp_titipan_premi = Income::whereIn('id',$this->temp_arr_titipan_id)->get();
        $this->total_titipan_premi = 0;
        foreach($this->temp_titipan_premi as $titipan){
            $this->total_titipan_premi += $titipan->outstanding_balance;
        }
        if($this->total_titipan_premi < $this->payment_amount){
            $this->payment_amount = $this->total_titipan_premi;
            $this->outstanding_balance = abs(replace_idr($this->payment_amount) - $this->nominal);
        }elseif($this->total_titipan_premi>$this->payment_amount){
            $this->payment_amount = $this->payment_amount;
            $this->outstanding_balance = 0;
        }
        $this->emit('init-form');
    }

    public function add_claim_payable()
    {
        $this->add_claim_payables[] = count($this->add_claim_payables);
        $this->add_expense_id[] = '';
        $this->emit('init-form');
    }

    public function emitAddBank($id)
    {
        $this->from_bank_account_id = $id;
        $this->emit('init-form');
    }

    public function updated($propertyName)
    {
        if($propertyName=='expense_id'){
            $this->data = Expenses::find($this->expense_id);
        }
        $this->emit('init-form');
    }

    public function save()
    {
        $this->validate([
            'expense_id' => 'required',
            'amount' => 'required',
            'reference_no' => 'required'
        ]);

        $data = new Income();
        $data->reference_type = 'Recovery Claim';
        $data->description = $this->description;
        $data->reference_no = $this->reference_no;
        $data->client = isset($this->data->policy->no_polis) ? $this->data->policy->no_polis .' / '. $this->data->policy->pemegang_polis : '';
        $data->reference_date = $this->reference_date;
        $data->status = 1;
        $data->user_id = \Auth::user()->id;
        $data->nominal = replace_idr($this->amount);
        $data->transaction_id = $this->expense_id;
        $data->transaction_table = 'expenses';
        $data->save();

        if($this->add_claim_payables){
            foreach($this->add_claim_payables as $k => $v){
                if($this->add_expense_id[$k]) 
                IncomeRecoveryClaim::create([
                    'income_id' => $data->id,
                    'expense_id' => $this->add_expense_id[$k]
                ]);
            }
        }
        
        \LogActivity::add("Income - Recovery Claim Submit {$this->data->id}");
        
        session()->flash('message-success',__('Data saved successfully'));

        return redirect()->route('income.recovery-claim');
    }
}
