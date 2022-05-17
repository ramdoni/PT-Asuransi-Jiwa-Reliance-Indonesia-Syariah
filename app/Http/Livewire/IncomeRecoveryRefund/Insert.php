<?php

namespace App\Http\Livewire\IncomeRecoveryRefund;

use Livewire\Component;
use App\Models\Income;
use App\Models\IncomePeserta;
use App\Models\IncomeTitipanPremi;

class Insert extends Component
{
    public $is_submit=true,$data,$premium_receivable,$no_polis,$reference_no,$amount;
    public $is_readonly=false,$description,$reference_date;
    public $add_pesertas=[],$no_peserta=[],$nama_peserta=[];
    protected $listeners = ['emit-add-bank'=>'emitAddBank','set-titipan-premi'=>'setTitipanPremi'];
    public function render()
    {
        return view('livewire.income-recovery-refund.insert');
    }
    
    public function mount()
    {
        $this->reference_date = date('Y-m-d');
    }

    public function updated($propertyName)
    {
        if($propertyName=='no_polis'){
            $this->data = \App\Models\Policy::find($this->no_polis);
        }
        $this->emit('init-form');
    }

    public function delete_peserta($key)
    {
        unset($this->add_pesertas[$key],$this->no_peserta[$key],$this->nama_peserta[$key]);
    }

    public function add_peserta()
    {
        $this->add_pesertas[] = count($this->add_pesertas);
        $this->no_peserta[] = '';
        $this->nama_peserta[] = '';
    }

    public function save()
    {
        $this->validate([
            'reference_no' => 'required',
            'amount' => 'required'
        ]);

        $data = new Income();
        $data->reference_type = 'Recovery Refund';
        $data->description = $this->description;
        $data->reference_no = $this->reference_no;
        $data->client = isset($this->data->no_polis) ? $this->data->no_polis .' / '. $this->data->pemegang_polis : '';
        $data->reference_date = $this->reference_date;
        $data->status = 1;
        $data->user_id = \Auth::user()->id;
        $data->nominal = replace_idr($this->amount);
        $data->policy_id = $this->no_polis;
        $data->save();

        if($this->add_pesertas){
            foreach($this->add_pesertas as $k=>$v){
                if(!empty($this->no_peserta[$k]) and !empty($this->nama_peserta[$k])){
                    $peserta = new IncomePeserta();
                    $peserta->income_id = $data->id;
                    $peserta->no_peserta = $this->no_peserta[$k];
                    $peserta->nama_peserta = $this->nama_peserta[$k];
                    $peserta->type = 1; // Recovery Refund
                    $peserta->policy_id = $this->data->id;
                    $peserta->save();
                }
            }
        }

        \LogActivity::add("Income - Recovery Refund Submit {$this->data->id}");

        session()->flash('message-success',__('Data saved successfully'));
        
        return redirect()->route('income.recovery-refund');
    }
}
