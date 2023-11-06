<?php

namespace App\Http\Livewire\MemoRefund;

use Livewire\Component;
use App\Models\Refund;
use App\Models\ReasRefund;

class Edit extends Component
{
    public $note;
    
    public function render()
    {
        return view('livewire.memo-refund.edit');
    }

    public function mount(Refund $id)
    {
        $this->data = $id;
    }

    public function submit_head_teknik()
    {
        \LogActivity::add("Memo Refund Head Teknik #{$this->data->id}");

        $this->data->head_teknik_submitted = date('Y-m-d H:i:s');
        $this->data->head_teknik_note = $this->note;
        $this->data->status = 1;
        $this->data->save();

        // find reas refund
        ReasCancel::where('memo_refund_id',$this->data->id)->update(['status'=>1]);

        $this->emit('message-success','Memo Refund berhasil disubmit');
    }

    public function submit_head_syariah()
    {
        \LogActivity::add("Memo Refund Head Syariah #{$this->data->id}");

        $this->data->head_syariah_submitted = date('Y-m-d H:i:s');
        $this->data->head_syariah_note = $this->note;
        $this->data->status = 2;
        $this->data->save();

        // find reas refund
        ReasRefund::where('memo_refund_id',$this->data->id)->update(['status'=>2]);

        $this->emit('message-success','Memo Refund berhasil disubmit');
    }
}
