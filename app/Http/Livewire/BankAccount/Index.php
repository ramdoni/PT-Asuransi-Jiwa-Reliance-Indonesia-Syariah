<?php

namespace App\Http\Livewire\BankAccount;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\BankAccount;

class Index extends Component
{
    use WithPagination;
    public $keyword,$coa_group_id,$insert=false;
    protected $paginationTheme = 'bootstrap';
    public $owner,$bank,$no_rekening,$cabang,$open_balance,$code,$coa_id,$active_date;
    public function render()
    {
        $data = \App\Models\BankAccount::orderBy('id','DESC')
        //->where('is_client',1)
        ;
        if($this->keyword) $data = $data->where(function($table){
            $table->where('no_rekening','LIKE', '%'.$this->keyword.'%')
            ->orWhere('owner','LIKE', '%'.$this->keyword.'%')
            ->orWhere('bank','LIKE', '%'.$this->keyword.'%')
            ->orWhere('cabang','LIKE', '%'.$this->keyword.'%');
        });

        return view('livewire.bank-account.index')->with(['data'=>$data->paginate(50)]);
    }
    public function mount()
    {
        \LogActivity::add("Bank Account");
    }
    public function delete($id)
    {
        \LogActivity::add("Bank Account Delete {$id}");

        \App\Models\BankAccount::find($id)->delete();
    }

    public function save()
    {
        $this->validate([
            // 'code'=>'required',
            'owner'=>'required',
            'bank'=>'required',
            'no_rekening'=>'required',
            'cabang'=>'required',
            // 'open_balance'=>'required',
            // 'coa_id'=>'required'
        ]);
        $data = new BankAccount();
        $data->owner = $this->owner;
        $data->bank = $this->bank;
        $data->no_rekening = $this->no_rekening;
        $data->cabang = $this->cabang;
        $data->open_balance = $this->open_balance;
        $data->code = $this->code;
        $data->coa_id = $this->coa_id;
        $data->active_date = $this->active_date;
        $data->status = 1;
        $data->save();
        $this->emit('message-success',__('Data saved successfully'));
        \LogActivity::add("Bank Account Submit {$data->id}");
        $this->insert = false;
    }
}
