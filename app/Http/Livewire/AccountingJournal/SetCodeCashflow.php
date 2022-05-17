<?php

namespace App\Http\Livewire\AccountingJournal;

use Livewire\Component;
use App\Models\Journal;

class SetCodeCashflow extends Component
{
    public $code_cashflow_id,$active_id;
    protected $listeners = ['modalEdit'];
    public function render()
    {
        return view('livewire.accounting-journal.set-code-cashflow');
    }
    public function mount()
    {
    }
    public function modalEdit($id)
    {
        $this->active_id = Journal::find($id);
        $this->code_cashflow_id = $this->active_id->code_cashflow_id;

        \LogActivity::add("Accounting - Journal Set Code Cahs Flow {$this->active_id->id}");
    }
    public function save()
    {
        $this->validate([
            'code_cashflow_id'=>'required'
        ]);

        $this->active_id->code_cashflow_id = $this->code_cashflow_id;
        $this->active_id->save();

        $this->emit('modalEditHide');
        $this->code_cashflow_id = '';
        session()->flash('message-success',__('Data saved successfully'));
    }
}
