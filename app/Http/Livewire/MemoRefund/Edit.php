<?php

namespace App\Http\Livewire\MemoRefund;

use Livewire\Component;
use App\Models\Refund;
use App\Models\ReasRefund;
use App\Models\Kepesertaan;

class Edit extends Component
{
    public $note,$tujuan_pembayaran,$nama_bank,$tgl_jatuh_tempo;
    
    public function render()
    {
        return view('livewire.memo-refund.edit');
    }

    public function mount(Refund $id)
    {
        $this->data = $id;
        $this->tujuan_pembayaran = $id->tujuan_pembayaran;
        $this->nama_bank = $id->nama_bank;
        $this->no_rekening = $id->no_rekening;
        $this->tgl_jatuh_tempo = $id->tgl_jatuh_tempo;
    }

    public function update_data()
    {
        $this->validate([
            'tujuan_pembayaran' => 'required'
        ]);

        $this->data->tujuan_pembayaran = $this->tujuan_pembayaran;
        $this->data->nama_bank = $this->nama_bank;
        $this->data->no_rekening = $this->no_rekening;
        $this->data->tgl_jatuh_tempo = $this->tgl_jatuh_tempo;
        $this->data->save();

        $this->emit('message-success','Data Updated.');
    }

    public function submit_head_teknik($status)
    {
        \LogActivity::add("Memo Refund Head Teknik #{$this->data->id}");

        $this->data->head_teknik_submitted = date('Y-m-d H:i:s');
        $this->data->head_teknik_note = $this->note;

        if($status==1)
            $this->data->status = 1;
        else
            $this->data->status = 4;

        $this->data->save();

        // find reas refund
        ReasRefund::where('memo_refund_id',$this->data->id)->update(['status'=>1]);

        $this->emit('message-success','Memo Refund berhasil disubmit');
    }

    public function submit_head_syariah()
    {
        \LogActivity::add("Memo Refund Head Syariah #{$this->data->id}");

        $this->data->head_syariah_submitted = date('Y-m-d H:i:s');
        $this->data->head_syariah_note = $this->note;
        $this->data->status = 2;
        $this->data->save();

        Kepesertaan::where('memo_refund_id',$this->data->id)->update(['status_polis'=>'Surrender']);

        // find reas refund
        ReasRefund::where('memo_refund_id',$this->data->id)->update(['status'=>2]);

        $this->emit('message-success','Memo Refund berhasil disubmit');
    }
}
