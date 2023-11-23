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
            $field_selected,$value_selected,$key_selected,$metode_endorse;
    
    public function render()
    {
        return view('livewire.endorsement.insert');
    }

    public function mount()
    {
        $this->polis = Polis::where('status_approval',1)->get();
        $this->tanggal_pengajuan = date('Y-m-d');
    }

    public function updated($propertyName)
    {
        if($propertyName=='polis_id'){
            $this->peserta = [];$this->metode_endorse="";$this->jenis_pengajuan='';
        }
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

            if($this->metode_endorse==1){
                $this->peserta[$index]['refund_tanggal_efektif'] = date('Y-m-d');
                $this->peserta[$index]['refund_sisa_masa_asuransi'] = hitung_masa_bulan(date('Y-m-d'),$peserta->tanggal_akhir,3);
                $this->peserta[$index]['refund_kontribusi'] = ($this->peserta[$index]['refund_sisa_masa_asuransi'] / $peserta->masa_bulan) * (($peserta->polis->refund / 100) * $this->peserta[$index]['total_kontribusi_dibayar']);
            }

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
        $validate = [
            'polis_id' => 'required',
            "peserta"    => "required|array",
            "peserta.*"  => "required",
            'tanggal_pengajuan' => 'required',
            'jenis_pengajuan' => 'required'
        ];

        if($this->jenis_pengajuan==1){
            $validate['metode_endorse'] = 'required';
        }
        
        $this->validate($validate);
        
        try {
            \DB::transaction(function () {
                $no_pengajuan = 
                $data = Endorsement::create([
                    'polis_id' => $this->polis_id,
                    'tanggal_pengajuan'=>$this->tanggal_pengajuan,
                    'jenis_pengajuan'=>$this->jenis_pengajuan,
                    'metode_endorse' => $this->metode_endorse
                ]);

                $no_pengajuan = str_pad($data->id,4, '0', STR_PAD_LEFT) ."/UWS-M-END/AJRIUS/".numberToRomawi(date('m')).'/'.date('Y');

                Endorsement::find($data->id)->update(['no_pengajuan'=>$no_pengajuan]);
                
                // Refund
                if($this->metode_endorse==1){
                    $this->refund($data->id);
                }
                // Cancel
                if($this->metode_endorse==2){
                    $this->cancel($data);
                }
                session()->flash('message-success',__('Endorse submitted'));

                return redirect()->route('endorsement.index');
            });
        }catch (\Throwable $e) {
            $this->emit('message-error', json_encode($e));
        }
    }

    public function refund($id)
    {
        try {
            \DB::transaction(function () {
                $total = 0;
                foreach($this->peserta as $k => $item){
                    $peserta = Kepesertaan::find($item['id']);
                    if($peserta){
                        $total++;

                        EndorsementPeserta::create([
                            'endorsement_id'=>$id,
                            'before_data'=>json_encode($peserta),
                            'after_data'=>json_encode($item)
                        ]);

                        $peserta->nama = $item['nama'];
                        $peserta->no_ktp = $item['no_ktp'];
                        $peserta->no_telepon = $item['no_telepon'];
                        $peserta->jenis_kelamin = $item['jenis_kelamin'];
                        $peserta->endorsement_id = $id;
                        $peserta->refund_tanggal_efektif = $item['refund_tanggal_efektif'];
                        $peserta->refund_sisa_masa_asuransi = hitung_masa_bulan($item['refund_tanggal_efektif'],$peserta->tanggal_akhir,3);
                        $peserta->total_kontribusi_dibayar = $item['total_kontribusi_dibayar'];
                        $peserta->refund_kontribusi = ($peserta->refund_sisa_masa_asuransi / $peserta->masa_bulan) * (($peserta->polis->refund / 100) * $peserta->total_kontribusi_dibayar) ;
                        $peserta->save();
                    }
                }

                Endorsement::find($id)->update(['total_peserta'=>$total]);
            });
        } catch (\Throwable $e) {
            $this->emit('message-error', json_encode($e));
        }
    }
    
    public function cancel($data)
    {
        try {
            \DB::transaction(function () {
                $polis = Polis::find($this->polis_id);
                
                $total = 0;$total_kontribusi=0;$total_manfaat_asuransi = 0;$total_kontribusi_gross=0;$total_kontribusi_tambahan=0;
                $total_potongan_langsung = 0;$total_ujroh_brokerage=0;$total_ppn=0;$total_pph=0;
                foreach($this->peserta as $k => $item){
                    $peserta = Kepesertaan::find($item['id']);
                    if($peserta){
                        $peserta->memo_cancel_id = $data->id;
                        $peserta->total_kontribusi_dibayar = $peserta->kontribusi + $peserta->extra_kontribusi + $peserta->extra_mortalita;
                        $peserta->save();
                        $total++;

                        $total_kontribusi_gross += $peserta->kontribusi;
                        // $total_potongan_langsung += $peserta->jumlah_potongan_langsung;
                        $total_kontribusi_tambahan += $peserta->extra_kontribusi;
                        $total_kontribusi += $peserta->kontribusi + $peserta->extra_kontribusi + $peserta->extra_mortalita;;
                        $total_manfaat_asuransi += $peserta->basic;
                    }
                }

                if($polis->potong_langsung){
                    $total_potongan_langsung = $total_kontribusi_gross*($polis->potong_langsung/100);
                }

                if($polis->fee_base_brokerage){
                    $polis->fee_base_brokerage = str_replace(",",".",$polis->fee_base_brokerage);
                    $data->brokerage_ujrah_persen = $polis->fee_base_brokerage;
                    $data->brokerage_ujrah = @$total_kontribusi*($polis->fee_base_brokerage/100);
                }

                /**
                 * Hitung PPH
                 */
                if($polis->pph){
                    $data->pph =  $polis->pph;

                    if($polis->ket_diskon=='Potong Langsung + Brokerage Ujroh')
                        $data->pph_amount = $data->brokerage_ujrah*($polis->pph/100);
                    else
                        $data->pph_amount = $data->potong_langsung*($polis->pph/100);
                }

                /**
                 * Hitung PPN
                 */
                if($polis->ppn){
                    $data->ppn =  $polis->ppn;
                    if($data->potong_langsung)
                        $data->ppn = (($polis->ppn/100) * $data->potong_langsung);
                    else
                        $data->ppn = $kontribusi*($polis->ppn/100);
                }

                $data->total_kontribusi_gross = $total_kontribusi_gross;
                $data->total_potongan_langsung = $total_potongan_langsung;
                $data->total_kontribusi_tambahan = $total_kontribusi_tambahan;
                $data->total_manfaat_asuransi = $total_manfaat_asuransi;
                $data->total_kontribusi = $total_kontribusi;
                $data->total_peserta = $total;  
                $data->save();
            });
        } catch (\Throwable $e) {
            $this->emit('message-error', json_encode($e));
        }
    }
}
