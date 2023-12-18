<?php

namespace App\Http\Livewire\RecoveryClaim;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Polis;
use App\Models\Klaim;
use App\Models\Kepesertaan;
use App\Models\RecoveryClaim;

class Insert extends Component
{
    use WithFileUploads;
    public $polis,$polis_id,$file,$peserta=[],$is_insert=false,$kepesertaan_id,$tanggal_efektif,$tanggal_pengajuan,
            $perihal_internal_memo,$tujuan_pembayaran,$nama_bank,$no_rekening,$tgl_jatuh_tempo,$no_peserta_awal,$no_peserta_akhir;
    public function render()
    {
        return view('livewire.recovery-claim.insert');
    }

    public function mount()
    {
        $this->tanggal_pengajuan = date('Y-m-d');
        $this->tanggal_efektif = date('Y-m-d');
        $this->polis = Polis::select('polis.*')
                        ->join('klaim','klaim.polis_id','=','polis.id')
                        ->groupBy('polis.id')
                        ->get();
    }

    public function updated($propertyName)
    {
        if($propertyName=='polis_id'){
            $this->peserta = [];
        }
    }

    public function add_peserta()
    {
        $index = count($this->peserta);
        $peserta = Klaim::with('kepesertaan')->where('kepesertaan_id',$this->kepesertaan_id)->first();
        if($peserta){
            $this->peserta[$index]['id'] = $peserta->kepesertaan_id;
            $this->peserta[$index]['klaim_id'] = $peserta->id;
            $this->peserta[$index]['no_klaim'] = $peserta->no_pengajuan;
            $this->peserta[$index]['no_peserta'] = $peserta->kepesertaan->no_peserta;
            $this->peserta[$index]['nama'] = $peserta->kepesertaan->nama;
            $this->peserta[$index]['tanggal_mulai'] = $peserta->kepesertaan->tanggal_mulai;
            $this->peserta[$index]['tanggal_akhir'] = $peserta->kepesertaan->tanggal_akhir;
            $this->peserta[$index]['basic'] = $peserta->kepesertaan->basic;
            $this->peserta[$index]['masa_bulan'] = $peserta->kepesertaan->masa_bulan;
            $this->peserta[$index]['reas'] = isset($peserta->kepesertaan->reas->no_pengajuan) ? $peserta->kepesertaan->reas->no_pengajuan : '-';
            $this->peserta[$index]['reasuradur'] = isset($peserta->kepesertaan->reas->reasuradur->name) ? $peserta->kepesertaan->reas->reasuradur->name : '-';
            $this->peserta[$index]['nilai_klaim'] = $peserta->nilai_klaim_reas;
            $this->peserta[$index]['status_klaim'] = $peserta->status;

            $ids = [];
            foreach($this->peserta as $item){
                $ids[] = $item['id'];
            }
            $this->emit('on-change-peserta',implode("-",$ids));
        }else{
            $this->emit('message-error','Data Kepesertaan tidak ditemukan');
        }
    }

    public function delete_peserta($k)
    {
        unset($this->peserta[$k]);
    }

    public function submit()
    {
        try{
            $this->validate([
                'polis_id' => 'required',
                'tanggal_pengajuan' => 'required'
            ]);

            foreach($this->peserta as $k => $item){
                $running_number = get_setting('running_number_recovery_claim')+1;
                $running_number_dn = get_setting('running_number_dn_recovery_claim')+1;
    
                update_setting('running_number_recovery_claim',$running_number);
                update_setting('running_number_dn_recovery_claim',$running_number_dn);
    
                $no_pengajuan = "RC".date('dmy') .str_pad($running_number,6, '0', STR_PAD_LEFT);
                // 0062/AJRIUS-DN-KLRS/XI/2023
                $nomor_dn = str_pad($running_number_dn,4, '0', STR_PAD_LEFT) ."/AJRIUS-DN-KLRS/".numberToRomawi(date('m'))."/".date('Y');
                
                $data = new RecoveryClaim();
                $data->nomor_dn = $nomor_dn;
                $data->polis_id = $this->polis_id;
                $data->no_pengajuan = $no_pengajuan;
                $data->tanggal_pengajuan = $this->tanggal_pengajuan;
                // $data->tgl_jatuh_tempo = $this->tgl_jatuh_tempo;
                $data->user_created_id = \Auth::user()->id;
                $data->kepesertaan_id = $item['id'];
                $data->nilai_klaim = $item['nilai_klaim'];
                $data->klaim_id = $item['klaim_id'];
                $data->save();

                Klaim::find($item['klaim_id'])->update(['recovery_claim_id'=>$data->id]);

                $peserta = Kepesertaan::find($item['id']);
                if($peserta){
                    $peserta->recovery_claim_id = $data->id;
                    $peserta->save();
                }
            }

            session()->flash('message-success',__('Klaim Reasuransi berhasil disubmit'));

            return redirect()->route('recovery-claim.index');

        } catch (\Throwable $e) {
            $this->emit('message-error', json_encode($e));
        }
    }
}
