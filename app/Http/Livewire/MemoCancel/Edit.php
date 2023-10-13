<?php

namespace App\Http\Livewire\MemoCancel;

use Livewire\Component;
use App\Models\MemoCancel;

class Edit extends Component
{
    public $note;
    
    public function render()
    {
        return view('livewire.memo-cancel.edit');
    }

    public function mount(MemoCancel $id)
    {
        $this->data = $id;
    }

    public function submit_head_teknik()
    {
        \LogActivity::add("Memo Cancel Head Teknik #{$this->data->id}");

        $this->data->head_teknik_submitted = date('Y-m-d H:i:s');
        $this->data->head_teknik_note = $this->note;
        $this->data->status = 1;
        $this->data->save();

        $this->emit('message-success','Memo Cancel berhasil disubmit');
    }

    public function submit_head_syariah()
    {
        \LogActivity::add("Memo Cancel Head Syariah #{$this->data->id}");

        $this->data->head_syariah_submitted = date('Y-m-d H:i:s');
        $this->data->head_syariah_note = $this->note;
        $this->data->status = 2;
        $this->data->save();

        $this->emit('message-success','Memo Cancel berhasil disubmit');
    }
}
