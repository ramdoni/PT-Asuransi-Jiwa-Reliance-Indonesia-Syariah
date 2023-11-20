<?php

namespace App\Http\Livewire\RecoveryClaim;

use Livewire\Component;
use App\Models\RecoveryClaim;
use App\Models\RecoveryClaimPayment;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;
    public $data,$reas_tanggal_kirim,$reas_tanggal_jawaban,$reas_note_jawaban,$reas_status,$reas_tanggal_penerimaan,$reas_note_penerimaan;
    public $form_payment=false,$payment_amount,$payment_date,$payment_file,$payments,$reas_file_penerimaan,$reas_file_jawaban;
    public $tanggal_pengajuan,$tgl_jatuh_tempo,$tujuan_pembayaran,$nama_bank,$no_rekening;
    public function render()
    {
        return view('livewire.recovery-claim.edit');
    }

    public function mount(RecoveryClaim $id)
    {
        $this->data = $id;
        $this->reas_tanggal_kirim = $id->reas_tanggal_kirim;
        $this->reas_tanggal_jawaban = $id->reas_tanggal_jawaban;
        $this->reas_note_jawaban = $id->reas_note_jawaban;
        $this->reas_tanggal_penerimaan = $id->reas_tanggal_penerimaan;
        $this->reas_note_penerimaan = $id->reas_note_penerimaan;
        $this->reas_status = $id->reas_status;
        $this->tanggal_pengajuan = $id->tanggal_pengajuan;
        $this->tgl_jatuh_tempo = $id->tgl_jatuh_tempo;
        $this->tujuan_pembayaran = $id->tujuan_pembayaran;
        $this->nama_bank = $id->nama_bank;
        $this->no_rekening = $id->no_rekening;
        $this->payment_date = date('Y-m-d');
        $this->payments = RecoveryClaimPayment::where('recovery_claim_id',$id->id)->get();
    }

    public function update_data()
    {
        $this->data->tanggal_pengajuan = $this->tanggal_pengajuan;
        $this->data->tgl_jatuh_tempo = $this->tgl_jatuh_tempo;
        $this->data->tujuan_pembayaran = $this->tujuan_pembayaran;
        $this->data->nama_bank = $this->nama_bank;
        $this->data->no_rekening = $this->no_rekening;
        $this->data->save();

        $this->emit('message-success','Updated');
    }

    public function update()
    {
        $this->data->reas_tanggal_kirim = $this->reas_tanggal_kirim;
        $this->data->reas_tanggal_jawaban = $this->reas_tanggal_jawaban;
        $this->data->reas_note_jawaban = $this->reas_note_jawaban;
        $this->data->reas_tanggal_penerimaan = $this->reas_tanggal_penerimaan;
        $this->data->reas_note_penerimaan = $this->reas_note_penerimaan;
        $this->data->reas_status = $this->reas_status;
        $this->data->save();

        if($this->reas_file_penerimaan){
            $this->validate([
                'reas_file_penerimaan' => 'required|mimes:xlsx,pdf,xlx,jpeg,jpg,png|max:10240'
            ]);
    
            $name = 'penerimaan.'.$this->reas_file_penerimaan->extension();
            $this->reas_file_penerimaan->storePubliclyAs("public/recovery-claim/{$this->data->id}",$name);
            $this->data->reas_file_penerimaan = "/storage/recovery-claim/{$this->data->id}/".$name;;
            $this->data->save();
        }
        
        if($this->reas_file_jawaban){
            $this->validate([
                'reas_file_jawaban' => 'required|mimes:xlsx,pdf,xlx,jpeg,jpg,png|max:10240'
            ]);
    
            $name = 'jawaban.'.$this->reas_file_jawaban->extension();
            $this->reas_file_jawaban->storePubliclyAs("public/recovery-claim/{$this->data->id}",$name);
            $this->data->reas_file_jawaban = "/storage/recovery-claim/{$this->data->id}/".$name;;
            $this->data->save();
        }
        

        $this->emit('message-success','Recovery Claim berhasil diupdate');
    }

    public function update_payment()
    {
        $this->validate([
            'payment_amount' => 'required',
            'payment_date' => 'required',
            'payment_file' => 'required|mimes:xlsx,pdf,xlx,jpeg,jpg,png|max:10240'
        ]);

        $name = date('dmYHis').'.'.$this->payment_file->extension();
        $this->payment_file->storePubliclyAs("public/recovery-claim/{$this->data->id}",$name);
        
        $data['payment_amount'] = $this->payment_amount;
        $data['payment_date'] = $this->payment_amount;
        $data['recovery_claim_id'] = $this->data->id;
        $data['payment_file'] = "/storage/recovery-claim/{$this->data->id}/".$name;

        RecoveryClaimPayment::create($data);

        $this->emit('message-success','Payment updated');

        $this->form_payment = false;
    }
}
