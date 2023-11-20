<?php

namespace App\Http\Livewire\MemoCancel;

use Livewire\Component;
use App\Models\MemoCancel;
use App\Models\ReasCancel;

class Edit extends Component
{
    public $note,$tanggal_pengajuan,$tanggal_efektif,$tujuan_pembayaran,$nama_bank,$no_rekening,$tgl_jatuh_tempo;
    
    public function render()
    {
        return view('livewire.memo-cancel.edit');
    }

    public function mount(MemoCancel $id)
    {
        $this->data = $id;
        $this->tanggal_pengajuan = $id->tanggal_pengajuan;
        $this->tanggal_efektif = $id->tanggal_efektif;
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

    public function submit_head_teknik()
    {
        \LogActivity::add("Memo Cancel Head Teknik #{$this->data->id}");

        $this->data->head_teknik_submitted = date('Y-m-d H:i:s');
        $this->data->head_teknik_note = $this->note;
        $this->data->status = 1;
        $this->data->save();

        // find reas cancel
        ReasCancel::where('memo_cancel_id',$this->data->id)->update(['status'=>1]);

        $this->emit('message-success','Memo Cancel berhasil disubmit');
    }

    public function submit_head_syariah()
    {
        \LogActivity::add("Memo Cancel Head Syariah #{$this->data->id}");

        $this->data->head_syariah_submitted = date('Y-m-d H:i:s');
        $this->data->head_syariah_note = $this->note;
        $this->data->status = 2;
        $this->data->save();

        // find reas cancel
        ReasCancel::where('memo_cancel_id',$this->data->id)->update(['status'=>2]);

        $this->emit('message-success','Memo Cancel berhasil disubmit');
    }
}
