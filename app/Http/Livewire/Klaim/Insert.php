<?php

namespace App\Http\Livewire\Klaim;

use Livewire\Component;
use App\Models\Polis;
use App\Models\Klaim;
use App\Models\Kepesertaan;

class Insert extends Component
{
    public $kepesertaan=[],$peserta,$no_pengajuan,$polis,$polis_id,$transaction_id,$kepesertaan_id,$tanggal_meninggal,$nilai_klaim,$jenis_klaim,$tempat_dan_sebab;
    public $kadaluarsa_klaim_hari,$kadaluarsa_klaim_tanggal;
    public function render()
    {
        return view('livewire.klaim.insert');
    }

    public function mount()
    {
        $this->transaction_id = date('ymdhis');
        $this->polis = Polis::where('status_approval',1)->get();
    }

    public function updated($propertyName)
    {
        $this->kepesertaan = [];$this->peserta = [];

        if($this->polis_id) {
            $this->kepesertaan = Kepesertaan::where(['polis_id'=>$this->polis_id,'status_akseptasi'=>1])->whereNull('klaim_id')->get();
            $this->emit('reload-kepesertaan');
        }
        if($this->kepesertaan_id) {
            $this->peserta = Kepesertaan::with(['polis','reas','polis.produk','pengajuan'])->find($this->kepesertaan_id);
            $this->kadaluarsa_klaim_hari = $this->peserta->polis->kadaluarsa_klaim;
            $this->kadaluarsa_klaim_tanggal = $this->peserta->polis->kadaluarsa_klaim ? date('Y-m-d',strtotime("+{$this->peserta->polis->kadaluarsa_klaim} days")) : '';
        }
    }

    public function save()
    {
        $this->validate([
            'polis_id'=>'required',
            'kepesertaan_id'=>'required',
            'tanggal_meninggal'=>'required',
            'nilai_klaim'=>'required',
            'jenis_klaim'=>'required',
            'tempat_dan_sebab'=>'required'
        ]);

        $data = new Klaim();
        $data->no_pengajuan = 'K'.date('dmy').str_pad((Klaim::count()+1),6, '0', STR_PAD_LEFT);;
        $data->polis_id = $this->polis_id;
        $data->kepesertaan_id = $this->kepesertaan_id;
        $data->tanggal_meninggal = $this->tanggal_meninggal;
        $data->nilai_klaim = $this->nilai_klaim;
        $data->jenis_klaim = $this->jenis_klaim;
        $data->tempat_dan_sebab = $this->tempat_dan_sebab;
        $data->kadaluarsa_klaim_hari = $this->kadaluarsa_klaim_hari;
        $data->kadaluarsa_klaim_tanggal = $this->kadaluarsa_klaim_tanggal;
        $data->save();

        $this->peserta->klaim_id = $data->id;
        $this->peserta->save();

        session()->flash('message-success',__('Pengajuan berhasil disubmit, silahkan menunggu persetujuan'));

        return redirect()->route('klaim.edit',$data->id);
    }
}
