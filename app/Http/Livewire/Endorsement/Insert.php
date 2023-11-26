<?php

namespace App\Http\Livewire\Endorsement;

use Livewire\Component;
use App\Models\Polis;
use App\Models\Kepesertaan;
use App\Models\Endorsement;
use App\Models\EndorsementPeserta;
use App\Models\JenisPerubahan;
use App\Models\Rate;
use App\Models\Pengajuan;
use App\Models\UnderwritingLimit;

class Insert extends Component
{
    public $polis,$polis_id,$file,$peserta=[],$is_insert=false,$kepesertaan_id,$tanggal_efektif,$tanggal_pengajuan,
            $perihal_internal_memo,$memo_cancel,$tujuan_pembayaran,$nama_bank,$no_rekening,$tgl_jatuh_tempo,$jenis_pengajuan=1,
            $field_selected,$value_selected,$key_selected,$metode_endorse,$jenis_perubahan_id;
    public $label_update_peserta = [
        'extra_mortalita' => 'Extra Mortalita',
        'extra_kontribusi' => 'Extra Kontribusi',
        'tanggal_mulai' => 'Tanggal Mulai',
        'tanggal_akhir' => 'Tanggal Akhir',
        'basic' => 'Nilai Manfaat Asuransi'
    ];
    protected $listeners = ['reload-page'=>'$refresh'];
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
            $this->peserta = [];$this->metode_endorse="";
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
        if(in_array($this->field_selected,['extra_mortalita','extra_kontribusi','basic','tanggal_mulai','tanggal_akhir'])){
            $polis = Polis::find($this->polis_id);

            $iuran_tabbaru = $polis->iuran_tabbaru;
            $ujrah = $polis->ujrah_atas_pengelolaan;

            foreach($this->peserta as $k => $i){
                
                $masa_asuransi = Pengajuan::find($i['pengajuan_id']) ? Pengajuan::find($i['pengajuan_id'])->masa_asuransi : 1; 
                $perhitungan_usia = Pengajuan::find($i['pengajuan_id']) ? Pengajuan::find($i['pengajuan_id'])->perhitungan_usia : 1; 

                $this->peserta[$k]['usia'] =  $this->peserta[$k]['tanggal_lahir'] ? hitung_umur($this->peserta[$k]['tanggal_lahir'],$perhitungan_usia,$this->peserta[$k]['tanggal_mulai']) : '0';
                $this->peserta[$k]['masa'] = hitung_masa($this->peserta[$k]['tanggal_mulai'],$this->peserta[$k]['tanggal_akhir']);
                $this->peserta[$k]['masa_bulan'] = hitung_masa_bulan($this->peserta[$k]['tanggal_mulai'],$this->peserta[$k]['tanggal_akhir'],$masa_asuransi);

                $rate = Rate::where(['tahun'=>$this->peserta[$k]['usia'],'bulan'=>$this->peserta[$k]['masa_bulan'],'polis_id'=>$this->polis_id])->first();

                if(!$rate || $rate->rate ==0 || $rate->rate ==""){
                    $this->peserta[$k]['rate'] = 0;
                    $this->peserta[$k]['kontribusi'] = 0;
                }else{
                    $this->peserta[$k]['rate'] = $rate ? $rate->rate : 0;
                    $this->peserta[$k]['kontribusi'] = $this->peserta[$k]['rate']==0 ? 0 : ($this->peserta[$k]['basic'] * $this->peserta[$k]['rate']/1000);
                }

                $this->peserta[$k]['dana_tabarru'] = ($this->peserta[$k]['kontribusi']*$iuran_tabbaru)/100; // persen ngambil dari daftarin polis
                $this->peserta[$k]['dana_ujrah'] = ($this->peserta[$k]['kontribusi']*$ujrah)/100;
                $this->peserta[$k]['extra_mortalita'] = $this->peserta[$k]['extra_mortalita'];

                if($this->peserta[$k]['akumulasi_ganda'])
                    $uw = UnderwritingLimit::whereRaw("{$this->peserta[$k]['akumulasi_ganda']} BETWEEN min_amount and max_amount")->where(['usia'=>$this->peserta[$k]['usia'],'polis_id'=>$this->polis_id])->first();
                else
                    $uw = UnderwritingLimit::whereRaw("{$this->peserta[$k]['basic']} BETWEEN min_amount and max_amount")->where(['usia'=>$this->peserta[$k]['usia'],'polis_id'=>$this->polis_id])->first();

                if($uw){
                    $this->peserta[$k]['uw'] = $uw->keterangan;
                    $this->peserta[$k]['ul'] = $uw->keterangan;
                }else{
                    $this->peserta[$k]['uw'] = '>Max UA';
                    $this->peserta[$k]['ul'] = '>Max UA';
                }

                $this->peserta[$k]['total_kontribusi'] = $this->peserta[$k]['kontribusi'] + $this->peserta[$k]['extra_kontribusi'] + $this->peserta[$k]['extra_mortalita'];
            }
        }   

        $this->emit('modal','hide');
    }

    public function delete_peserta($k)
    {
        unset($this->peserta[$k]);
    }

    public function add_peserta()
    {
        $index = count($this->peserta);
        $peserta = Kepesertaan::find($this->kepesertaan_id)->toArray();
        $polis = Polis::find($this->polis_id);
        if($peserta){
            foreach($peserta as $field => $val){
                $this->peserta[$index][$field] = $val;
            }

            $this->peserta[$index]['total_kontribusi'] = $peserta['kontribusi'] + $peserta['extra_kontribusi'] + $peserta['extra_mortalita'];
            $this->peserta[$index]['total_kontribusi_dibayar'] = $peserta['kontribusi'] + $peserta['extra_kontribusi'] + $peserta['extra_mortalita'];

            if($this->metode_endorse==1){
                $this->peserta[$index]['refund_tanggal_efektif'] = date('Y-m-d');
                $this->peserta[$index]['refund_sisa_masa_asuransi'] = hitung_masa_bulan(date('Y-m-d'),$peserta['tanggal_akhir'],3);
                $this->peserta[$index]['refund_kontribusi'] = ($this->peserta[$index]['refund_sisa_masa_asuransi'] / $peserta['masa_bulan']) * (($polis->refund / 100) * $this->peserta[$index]['total_kontribusi_dibayar']);
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
            'jenis_pengajuan' => 'required',
            'jenis_perubahan_id' => 'required'
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
                    'metode_endorse' => $this->metode_endorse,
                    'requester_id' => \Auth::user()->id,
                    'jenis_perubahan_id' => $this->jenis_perubahan_id,
                    'status'=>1
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
    }
    
    public function cancel($data)
    {
        $polis = Polis::find($this->polis_id);
        $total = 0;$total_kontribusi=0;$total_manfaat_asuransi = 0;$total_kontribusi_gross=0;$total_kontribusi_tambahan=0;
        $total_potongan_langsung = 0;$total_ujroh_brokerage=0;$total_ppn=0;$total_pph=0;

        $basic_perubahan=0;$kontribusi_netto_perubahan=0;$em_perubahan=0;$ek_perubahan=0;
        $field_perubahan=[];$value_perubahan_before=[];$value_perubahan_after=[];
        foreach($this->peserta as $k => $item){
            $peserta = Kepesertaan::find($item['id']);
            if($peserta){
                EndorsementPeserta::create([
                    'endorsement_id'=>$data->id,
                    'before_data'=>json_encode($peserta),
                    'after_data'=>json_encode($item)
                ]);

                foreach($this->label_update_peserta as $f=>$v){
                    if($peserta->$f!=$item[$f]){
                        $field_perubahan[] = $f;
                        if(in_array($f,['basic','extra_mortalita','extra_kontribusi'])){
                            $value_perubahan_before[] = "{$v}:".format_idr($peserta->$f);
                            $value_perubahan_after[] = "{$v}:".format_idr($item[$f]);
                        }elseif (in_array($f,['tanggal_akhir','tanggal_mulai'])) {
                            $value_perubahan_before[] = "{$v}:".date('d-M-Y',strtotime($peserta->$f));
                            $value_perubahan_after[] = "{$v}:".date('d-M-Y',strtotime($item[$f]));
                        }else{
                            $value_perubahan_before[] = "{$v}:".$peserta->$f;
                            $value_perubahan_after[] = "{$v}:".$item[$f];
                        }
                    }
                }

                $peserta->endorsement_id = $data->id;
                $peserta->total_kontribusi_dibayar = $peserta->kontribusi + $peserta->extra_kontribusi + $peserta->extra_mortalita;
                $peserta->save();

                $em_perubahan += $item['extra_mortalita'];
                $ek_perubahan += $item['extra_kontribusi'];

                $total++;

                $kontribusi_netto_perubahan += $item['kontribusi']+$item['extra_mortalita']+$item['extra_kontribusi'];
                $basic_perubahan += $item['basic'];

                $total_kontribusi_gross += $peserta->kontribusi;
                $total_kontribusi_tambahan += $peserta->extra_kontribusi;
                $total_kontribusi += $peserta->kontribusi + $peserta->extra_kontribusi + $peserta->extra_mortalita;;
                $total_manfaat_asuransi += $peserta->basic;
            }
        }

        $data->field_perubahan = json_encode($field_perubahan);
        $data->value_perubahan_before = json_encode($value_perubahan_before);
        $data->value_perubahan_after = json_encode($value_perubahan_after);

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
                $data->pph = $data->brokerage_ujrah*($polis->pph/100);
            else
                $data->pph = $data->potong_langsung*($polis->pph/100);
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

        $data->kontribusi_netto_perubahan = $kontribusi_netto_perubahan;
        $data->basic_perubahan = $basic_perubahan;

        $data->jenis_dokumen = 1; // 1 = CN, 2 = DN
        
        if($data->total_kontribusi_gross > $data->kontribusi_netto_perubahan)
            $selisih = ($data->total_kontribusi_gross - $data->kontribusi_netto_perubahan);
        else
            $selisih = ($data->kontribusi_netto_perubahan - $data->total_kontribusi_gross);
        
        $data->selisih = $selisih;
        $data->save();
    }
}
