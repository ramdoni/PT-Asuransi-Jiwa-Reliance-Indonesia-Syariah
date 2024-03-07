<?php

namespace App\Http\Livewire\Tagihansoa;

use Livewire\Component;
use App\Models\Tagihansoa;

class Detail extends Component
{
    public $data,$bank_name,$bank_no_rekening,$bank_owner;
    public $type_pengajuan_arr= [1=>'Kontribusi Reas',2=>'Recovery Claim',3=> 'Refund',4=>'Endorse',5=>'Claim'];
    public function render()
    {
        return view('livewire.tagihansoa.detail');
    }

    public function mount(Tagihansoa $data)
    {
        $this->data = $data;
        $this->bank_name = $data->bank_name;
        $this->bank_no_rekening = $data->bank_no_rekening;
        $this->bank_owner = $data->bank_owner;
    }

    public function update_rekening()
    {
        $this->data->update([
            'bank_name'=> $this->bank_name,
            'bank_no_rekening' => $this->bank_no_rekening,
            'bank_owner' => $this->bank_owner
        ]);

        $this->emit('message-success','Data rekning berhasil disimpan');
    }

    
    public function submit_head_teknik()
    {
        \LogActivity::add("Tagihan SOA Head Teknik #{$this->data->id}");

        $this->data->head_teknik_submitted = date('Y-m-d H:i:s');
        // $this->data->head_teknik_note = $this->note;
        $this->data->status = 1;
        $this->data->save();

        // find reas cancel
        // ReasCancel::where('memo_cancel_id',$this->data->id)->update(['status'=>1]);

        $this->emit('message-success','Tagihan SOA Submitted');
    }

    public function submit_head_syariah()
    {
        \LogActivity::add("Tagihan SOA Head Syariah #{$this->data->id}");

        $this->data->head_syariah_submitted = date('Y-m-d H:i:s');
        // $this->data->head_syariah_note = $this->note;
        $this->data->status = 2;
        $this->data->save();

        // find reas cancel
        // ReasCancel::where('memo_cancel_id',$this->data->id)->update(['status'=>2]);

        // $polis = Polis::where('no_polis',$this->data->polis->no_polis)->first();
        // if(!$polis){
        //     $polis = Polis::create([
        //         'no_polis'=>$this->data->polis->no_polis,
        //         'pemegang_polis'=>$this->data->polis->nama
        //     ]);
        // }

        // Expense::updateOrCreate(['reference_no'=>$this->data->nomor],[
        //     'policy_id'=>$polis->id,
        //     'recipient'=> $this->data->polis->no_polis ." / ". $this->data->polis->nama,
        //     'reference_no'=>$this->data->nomor,
        //     'reference_type'=>'Cancelation',
        //     'reference_date'=>$this->data->tanggal_pengajuan,
        //     // 'description'=>$description,
        //     'payment_amount'=>$this->data->total_kontribusi,
        //     'nominal'=>$this->data->total_kontribusi,
        //     'transaction_id'=>$this->data->id,
        //     'transaction_table'=>'memo_cancel'
        // ]);

        $this->emit('message-success','Tagihan SOA submitted');
    }
}
