<?php

namespace App\Http\Livewire\Endorsement;

use Livewire\Component;
use App\Models\Polis;
use App\Models\Kepesertaan;
use App\Models\Endorsement;
use App\Models\EndorsementPeserta;

class Insert extends Component
{
    public $polis,$polis_id,$file,$peserta=[],$is_insert=false,$kepesertaan_id,$tanggal_efektif,$tanggal_pengajuan,
            $perihal_internal_memo,$memo_cancel,$tujuan_pembayaran,$nama_bank,$no_rekening,$tgl_jatuh_tempo,$jenis_pengajuan,
            $field_selected,$value_selected,$key_selected;
    
    public function render()
    {
        return view('livewire.endorsement.insert');
    }

    public function mount()
    {
        $this->polis = Polis::where('status_approval',1)->get();
        $this->tanggal_pengajuan = date('Y-m-d');
    }

    public function set_edit($k,$field,$value)
    {
        $this->field_selected = $field;
        $this->value_selected = $value;
        $this->key_selected = $k;
    }

    public function update_peserta()
    {
        $this->peserta[$this->key_selected][$this->field_selected] = $this->value_selected;
        $this->emit('modal','hide');
    }

    public function add_peserta()
    {
        $index = count($this->peserta);
        $peserta = Kepesertaan::find($this->kepesertaan_id);
        if($peserta){
            $this->peserta[$index]['id'] = $peserta->id;
            $this->peserta[$index]['status_polis'] = $peserta->status_polis;
            $this->peserta[$index]['no_peserta'] = $peserta->no_peserta;
            $this->peserta[$index]['nama'] = $peserta->nama;
            $this->peserta[$index]['no_ktp'] = $peserta->no_ktp;
            $this->peserta[$index]['jenis_kelamin'] = $peserta->jenis_kelamin;
            $this->peserta[$index]['no_telepon'] = $peserta->no_telepon;
            $this->peserta[$index]['tanggal_mulai'] = $peserta->tanggal_mulai;
            $this->peserta[$index]['tanggal_akhir'] = $peserta->tanggal_akhir;
            $this->peserta[$index]['basic'] = $peserta->basic;
            $this->peserta[$index]['masa_bulan'] = $peserta->masa_bulan;
            $this->peserta[$index]['total_kontribusi_dibayar'] = $peserta->kontribusi + $peserta->extra_kontribusi + $peserta->extra_mortalita ;
            $this->peserta[$index]['reas'] = isset($peserta->reas->no_pengajuan) ? $peserta->reas->no_pengajuan : '-';
            $this->peserta[$index]['reasuradur'] = isset($peserta->reas->reasuradur->name) ? $peserta->reas->reasuradur->name : '-';

            $ids = [];
            foreach($this->peserta as $item){
                $ids[] = $item['id'];
            }
            $this->emit('on-change-peserta',implode("-",$ids));
        }else{
            $this->emit('message-error','Data Kepesertaan tidak ditemukan');
        }
    }
    
    public function submit()
    {
        $this->validate([
            'polis_id' => 'required',
            "peserta"    => "required|array",
            "peserta.*"  => "required",
            'tanggal_pengajuan' => 'required',
            'jenis_pengajuan' => 'required'
        ]);
        try {
            \DB::transaction(function () {
                $no_pengajuan = 
                $data = Endorsement::create([
                    'polis_id' => $this->polis_id,
                    'tanggal_pengajuan'=>$this->tanggal_pengajuan,
                    'jenis_pengajuan'=>$this->jenis_pengajuan
                ]);

                $no_pengajuan = str_pad($data->id,4, '0', STR_PAD_LEFT) ."/UWS-M-END/AJRIUS/".numberToRomawi(date('m')).'/'.date('Y');

                Endorsement::find($data->id)->update(['no_pengajuan'=>$no_pengajuan]);
                $total = 0;
                foreach($this->peserta as $k => $item){
                    $peserta = Kepesertaan::find($item['id']);
                    if($peserta){
                        $total++;

                        EndorsementPeserta::create([
                            'endorsement_id'=>$data->id,
                            'before_data'=>json_encode($peserta),
                            'after_data'=>json_encode($item)
                        ]);

                        $peserta->nama = $item['nama'];
                        $peserta->no_ktp = $item['no_ktp'];
                        $peserta->no_telepon = $item['no_telepon'];
                        $peserta->jenis_kelamin = $item['jenis_kelamin'];
                        $peserta->endorsement_id = $data->id;
                        $peserta->save();
                    }
                }

                Endorsement::find($data->id)->update(['total_peserta'=>$total]);

                session()->flash('message-success',__('Endorse submitted'));

                return redirect()->route('endorsement.index');
            });
        }catch (\Throwable $e) {
            $this->emit('message-error', json_encode($e));
        }
    }
}
