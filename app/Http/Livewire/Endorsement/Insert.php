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
use App\Models\ReasEndorse;
use App\Models\UnderwritingLimit;
use App\Models\ReasuradurRateRates;
use App\Models\ReasuradurRateUw;

class Insert extends Component
{
    public $polis,$polis_id,$file,$peserta=[],$is_insert=false,$kepesertaan_id,$tanggal_efektif,$tanggal_pengajuan,
            $perihal_internal_memo,$memo_cancel,$tujuan_pembayaran,$nama_bank,$no_rekening,$tgl_jatuh_tempo,$jenis_pengajuan=1,
            $field_selected,$value_selected,$key_selected,$metode_endorse,$jenis_perubahan_id,$polis_selected;
    public $label_update_peserta = [
        'extra_mortalita' => 'Extra Mortalita',
        'extra_kontribusi' => 'Extra Kontribusi',
        'tanggal_mulai' => 'Tanggal Mulai',
        'tanggal_akhir' => 'Tanggal Akhir',
        'tanggal_lahir' => 'Tanggal Lahir',
        'basic' => 'Nilai Manfaat Asuransi',
        'nama' => 'Nama',
        'jenis_kelamin' => 'Jenis Kelamin',
        'no_ktp' => 'No KTP',
        'no_telepon' => 'No Telepon'
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
            $this->polis_selected = Polis::find($this->polis_id);
            $this->peserta = [];$this->metode_endorse="";
        }
        if($propertyName=='jenis_pengajuan'){
            $this->reset('metode_endorse');
        }
        $polis = Polis::find($this->polis_id);

        foreach($this->peserta as $k => $i){
            $this->peserta[$k]['refund_sisa_masa_asuransi'] = $this->peserta[$k]['masa_bulan'] - hitung_masa_bulan($this->peserta[$k]['tanggal_mulai'], $this->peserta[$k]['refund_tanggal_efektif'],3);
            $this->peserta[$k]['refund_kontribusi'] = ($this->peserta[$k]['refund_sisa_masa_asuransi'] / $this->peserta[$k]['masa_bulan']) * (($polis->refund / 100) * $this->peserta[$k]['total_kontribusi_dibayar']);
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
        // simpan tanggal mulai sebelum perubahan
        if($this->field_selected=='tanggal_mulai') $value_tanggal_mulai = $this->peserta[$this->key_selected][$this->field_selected];

        $this->peserta[$this->key_selected][$this->field_selected] = $this->value_selected;
        if(in_array($this->field_selected,['refund_tanggal_efektif','extra_mortalita','extra_kontribusi','basic','tanggal_mulai','tanggal_akhir','tanggal_lahir'])){
            $polis = Polis::find($this->polis_id);

            $iuran_tabbaru = $polis->iuran_tabbaru;
            $ujrah = $polis->ujrah_atas_pengelolaan;

            foreach($this->peserta as $k => $i){
                if($this->field_selected=='tanggal_mulai' and $this->jenis_pengajuan == 2){
                    $earlier = new \DateTime($value_tanggal_mulai);
                    $later = new \DateTime($i['tanggal_akhir']);
        
                    $total_hari = $later->diff($earlier)->format("%a");
        
                    $this->peserta[$k]['tanggal_akhir'] = date('Y-m-d',strtotime("{$i['tanggal_mulai']} + {$total_hari} days"));
                }

                $masa_asuransi = Pengajuan::find($i['pengajuan_id']) ? Pengajuan::find($i['pengajuan_id'])->masa_asuransi : 1; 
                $perhitungan_usia = Pengajuan::find($i['pengajuan_id']) ? Pengajuan::find($i['pengajuan_id'])->perhitungan_usia : 1; 
                
                $this->peserta[$k]['usia'] =  $this->peserta[$k]['tanggal_lahir'] ? hitung_umur($this->peserta[$k]['tanggal_lahir'],$perhitungan_usia,$this->peserta[$k]['tanggal_mulai']) : '0';
                $this->peserta[$k]['masa'] = hitung_masa($this->peserta[$k]['tanggal_mulai'],$this->peserta[$k]['tanggal_akhir']);
                $this->peserta[$k]['masa_bulan'] = hitung_masa_bulan($this->peserta[$k]['tanggal_mulai'],$this->peserta[$k]['tanggal_akhir'],$masa_asuransi);

                // if($polis->rate_single_usia=='Usia' || $polis->rate_single_usia==""){
                    $rate = Rate::where(['tahun'=>$this->peserta[$k]['usia'],'bulan'=>$this->peserta[$k]['masa_bulan'],'polis_id'=>$this->polis_id])->first();

                    if(!$rate || $rate->rate ==0 || $rate->rate ==""){
                        $this->peserta[$k]['rate'] = 0;
                        $this->peserta[$k]['kontribusi'] = 0;
                    }else{
                        $this->peserta[$k]['rate'] = $rate ? $rate->rate : 0;
                        $this->peserta[$k]['kontribusi'] = $this->peserta[$k]['rate']==0 ? 0 : ($this->peserta[$k]['basic'] * $this->peserta[$k]['rate']/1000);
                    }
                // }

                $this->peserta[$k]['dana_tabarru'] = ($this->peserta[$k]['kontribusi']*$iuran_tabbaru)/100; // persen ngambil dari daftarin polis
                $this->peserta[$k]['dana_ujrah'] = ($this->peserta[$k]['kontribusi']*$ujrah)/100;

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

                if($this->metode_endorse==1){
                    $this->peserta[$k]['refund_sisa_masa_asuransi'] = $this->peserta[$k]['masa_bulan'] - hitung_masa_bulan($this->peserta[$k]['tanggal_mulai'], $this->peserta[$k]['refund_tanggal_efektif'],3);
                    $this->peserta[$k]['refund_kontribusi'] = ($this->peserta[$k]['refund_sisa_masa_asuransi'] / $this->peserta[$k]['masa_bulan']) * (($polis->refund / 100) * $this->peserta[$k]['total_kontribusi_dibayar']);
                }

                $this->peserta[$k]['total_kontribusi'] = $this->peserta[$k]['kontribusi'];
                
                if((int)$this->peserta[$k]['extra_kontribusi']>0) $this->peserta[$k]['total_kontribusi'] += (int)$this->peserta[$k]['extra_kontribusi'];
                if((int)$this->peserta[$k]['extra_mortalita']>0) $this->peserta[$k]['total_kontribusi'] += (int)$this->peserta[$k]['extra_mortalita'];
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
            
            $this->peserta[$index]['before_kontribusi'] = $peserta['kontribusi'];
            $this->peserta[$index]['total_kontribusi'] = $peserta['kontribusi'] + $peserta['extra_kontribusi'] + $peserta['extra_mortalita'];
            $this->peserta[$index]['total_kontribusi_dibayar'] = $peserta['kontribusi'] + $peserta['extra_kontribusi'] + $peserta['extra_mortalita'];

            $this->peserta[$index]['refund_tanggal_efektif'] = date('Y-m-d');
            $this->peserta[$index]['refund_sisa_masa_asuransi'] = $peserta['masa_bulan'] - hitung_masa_bulan($peserta['tanggal_mulai'],date('Y-m-d'),3);
            $this->peserta[$index]['refund_kontribusi'] = ($this->peserta[$index]['refund_sisa_masa_asuransi'] / $peserta['masa_bulan']) * (($polis->refund / 100) * $this->peserta[$index]['total_kontribusi_dibayar']);
            
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

                if($this->jenis_pengajuan==2){
                    $this->tidakMempengaruhiPremi($data);
                }

                if($this->jenis_pengajuan==1){
                    $this->mempengaruhiPremi($data);
                }

                session()->flash('message-success',__('Endorse submitted'));

                return redirect()->route('endorsement.index');
            });
        }catch (\Throwable $e) {
            $this->emit('message-error', json_encode($e));
        }
    }

    public function tidakMempengaruhiPremi($data)
    {
        $polis = Polis::find($this->polis_id);
        $total = 0;$total_kontribusi=0;$total_manfaat_asuransi = 0;$total_kontribusi_gross=0;$total_kontribusi_tambahan=0;
        $total = 0; $field_perubahan=[];$value_perubahan_before=[];$value_perubahan_after=[];
        foreach($this->peserta as $k => $item){
            $peserta = Kepesertaan::find($item['id']);
            if($peserta){
                $total++;

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
                        }elseif (in_array($f,['tanggal_akhir','tanggal_mulai','tanggal_lahir'])) {
                            $value_perubahan_before[] = "{$v}:".date('d-M-Y',strtotime($peserta->$f));
                            $value_perubahan_after[] = "{$v}:".date('d-M-Y',strtotime($item[$f]));
                        }else{
                            $value_perubahan_before[] = "{$v}:".$peserta->$f;
                            $value_perubahan_after[] = "{$v}:".$item[$f];
                        }
                    }
                }

                $peserta->endorsement_id = $data->id;
                $peserta->status_polis = 'Surrender';
                $peserta->save();
            }
        }

        $data->field_perubahan = json_encode($field_perubahan);
        $data->value_perubahan_before = json_encode($value_perubahan_before);
        $data->value_perubahan_after = json_encode($value_perubahan_after);
        $data->total_peserta = $total;
        $data->save();

        $reasuradur = Kepesertaan::select('kepesertaan.*')->where('kepesertaan.endorsement_id',$data->id)
                                ->join('reas','reas.id','=','kepesertaan.reas_id')
                                ->join('reasuradur','reasuradur.id','=','reas.reasuradur_id')
                                ->where(function($table){
                                    $table->where('reasuradur.name','<>','OR')
                                            ->orWhere('reasuradur.name','<>','');
                                })
                                ->groupBy('reasuradur.id')
                                ->get();
                
        foreach($reasuradur as $item){
            $reas_refund = new ReasEndorse();
            $reas_refund->endorsement_id = $data->id;
            $reas_refund->status = 0;
            $reas_refund->polis_id = $data->polis_id;
            $reas_refund->tanggal_pengajuan = $data->tanggal_pengajuan;
            $reas_refund->reas_id = $item->reas_id;
            $reas_refund->save();
            
            $reas_refund->nomor = str_pad($reas_refund->id,6, '0', STR_PAD_LEFT) ."/END-C/AJRI/".numberToRomawi(date('m')).'/'.date('Y');
            
            Kepesertaan::where(['endorsement_id'=>$data->id,'reas_id'=>$item->reas_id])->update(['reas_endorse_id'=>$reas_refund->id]);
 
            $reas_refund->total_kontribusi_refund = Kepesertaan::where(['endorsement_id'=>$data->id,'reas_id'=>$item->reas_id])->sum('refund_kontribusi_reas');
            $reas_refund->total_peserta = Kepesertaan::where(['endorsement_id'=>$data->id,'reas_id'=>$item->reas_id])->get()->count();
            $reas_refund->total_manfaat_asuransi = Kepesertaan::where(['endorsement_id'=>$data->id,'reas_id'=>$item->reas_id])->sum('nilai_manfaat_asuransi_reas');
            $reas_refund->total_kontribusi = Kepesertaan::where(['endorsement_id'=>$data->id,'reas_id'=>$item->reas_id])->sum('net_kontribusi_reas');
            $reas_refund->save();   
        }
    }

    public function mempengaruhiPremi($data)
    {
        $polis = Polis::find($this->polis_id);
        $total = 0;$total_kontribusi=0;$total_manfaat_asuransi = 0;$total_kontribusi_gross=0;$total_kontribusi_tambahan=0;
        $total_potongan_langsung = 0;$total_ujroh_brokerage=0;$total_ppn=0;$total_pph=0;

        $basic_perubahan=0;$kontribusi_netto_perubahan=0;$em_perubahan=0;$ek_perubahan=0;
        $field_perubahan=[];$value_perubahan_before=[];$value_perubahan_after=[];
        foreach($this->peserta as $k => $item){
            $peserta = Kepesertaan::find($item['id']);
            if($peserta){
                foreach($this->label_update_peserta as $f=>$v){
                    if($peserta->$f!=$item[$f]){
                        $field_perubahan[] = $f;
                        if(in_array($f,['basic','extra_mortalita','extra_kontribusi'])){
                            $value_perubahan_before[] = "{$v}:".format_idr($peserta->$f);
                            $value_perubahan_after[] = "{$v}:".format_idr($item[$f]);
                        }elseif (in_array($f,['tanggal_akhir','tanggal_mulai','tanggal_lahir'])) {
                            $value_perubahan_before[] = "{$v}:".date('d-M-Y',strtotime($peserta->$f));
                            $value_perubahan_after[] = "{$v}:".date('d-M-Y',strtotime($item[$f]));
                        }else{
                            $value_perubahan_before[] = "{$v}:".$peserta->$f;
                            $value_perubahan_after[] = "{$v}:".$item[$f];
                        }
                    }
                }

                $peserta->status_polis = 'Surrender';
                $peserta->endorsement_id = $data->id;
                $peserta->total_kontribusi_dibayar = $peserta->kontribusi + $peserta->extra_kontribusi + $peserta->extra_mortalita;
                $peserta->refund_sisa_masa_asuransi = $peserta->masa_bulan - hitung_masa_bulan($peserta->tanggal_mulai, $item['refund_tanggal_efektif'],3);
                $peserta->save();

                /**
                 *
                    Nilai Pengembalian Kontribusi = t/n x % x kontribusi gross
                    t            = sisa masa asuransi (dalam bulan)
                    n            = masa asuransi (dalam bulan)
                    %            = persentase pengembalian asuransi (sesuai yang tercantum di Polis)
                */
                $peserta->refund_kontribusi = ($peserta->refund_sisa_masa_asuransi / $peserta->masa_bulan) * (($peserta->polis->refund / 100) * $peserta->total_kontribusi_dibayar);
                
                // Refund
                if($this->metode_endorse==1){
                    if($peserta->net_kontribusi_reas>0 and $peserta->reas_id>0) {
                        /**
                            1.Nilai Pengembalian Kontribusi = t/n x % x kontribusi gross reas atau
                            2.Nilai Pengembalian Kontribusi = t/n x dana tabarru’reas
                            3.Nilai Pengembalian Kontribusi = t/n x % x dana tabarru’reas
                            */
                        if(isset($peserta->reas->rate_uw->type_pengembalian_kontribusi)){
                            $refund_reas_persen = isset($peserta->reas->rate_uw->persentase_refund) ? str_replace(",",".",$peserta->reas->rate_uw->persentase_refund) : 0; 
                            $type_pengembalian = $peserta->reas->rate_uw->type_pengembalian_kontribusi;
                            $data_tabbaru_reas =  isset($peserta->reas->rate_uw->persentase_refund) ? str_replace(",",".",$peserta->reas->rate_uw->tabbaru) : 0; 
                            $reas_tabarru = str_replace(",",".",$peserta->reas->rate_uw->tabbaru);

                            if($peserta->reas->rate_uw->tabbaru)
                                $dana_tabbaru_reas = ($reas_tabarru/100)*$peserta->net_kontribusi_reas;
                            else
                                $dana_tabbaru_reas = $peserta->net_kontribusi_reas;

                            $refund_reas =  ($peserta->refund_sisa_masa_asuransi / $peserta->masa_bulan) * (($refund_reas_persen / 100) * $dana_tabbaru_reas);
                            $refund_reas_net =  ($peserta->refund_sisa_masa_asuransi / $peserta->masa_bulan) * (($refund_reas_persen / 100) * $peserta->net_kontribusi_reas);

                            // Nilai Pengembalian Kontribusi = t/n x % x kontribusi gross reas atau
                            if($type_pengembalian==1){
                                $peserta->refund_kontribusi_reas = ($peserta->refund_sisa_masa_asuransi / $peserta->masa_bulan) * (($refund_reas_persen / 100) * $peserta->net_kontribusi_reas);
                            }
                            // Nilai Pengembalian Kontribusi = t/n x dana tabarru’reas
                            if($type_pengembalian==2){
                                $peserta->refund_kontribusi_reas = ($peserta->refund_sisa_masa_asuransi / $peserta->masa_bulan) * $dana_tabbaru_reas;
                            }
                            //Nilai Pengembalian Kontribusi = t/n x % x dana tabarru’reas
                            if($type_pengembalian==3){
                                $peserta->refund_kontribusi_reas = ($peserta->refund_sisa_masa_asuransi / $peserta->masa_bulan) * (($refund_reas_persen / 100) * $dana_tabbaru_reas);
                            }
                        }
                    }
                }
                
                // Cancel
                if($this->metode_endorse==2){
                    $peserta->refund_kontribusi_reas = $peserta->net_kontribusi_reas;
                }

                if($peserta->net_kontribusi_reas>0 and $peserta->reas_id>0) {
                    $or = $peserta->reas->reas;
                    $ajri = $peserta->reas->or;
                    $ri_com = $peserta->reas->ri_com;
                    $perhitungan_usia = $peserta->reas->perhitungan_usia;
                    $manfaat_asuransi = $item['basic'];
                    $item['usia_reas'] = $item['tanggal_lahir'] ? hitung_umur($item['tanggal_lahir'],$perhitungan_usia,$item['tanggal_mulai']) : '0';

                    // Calculate Reas
                    $reas_manfaat_asuransi_ajri = ($item['basic']*$ajri)/100;

                    if($item['is_double_reas']==1){
                        if($item['akumulasi_ganda_reas']>=100000000){
                            $item['nilai_manfaat_asuransi_reas'] = $manfaat_asuransi;
                            $item['reas_manfaat_asuransi_ajri'] = 0;
                        }else{
                            $akumulasi_reas = $item['akumulasi_ganda_reas'] + $reas_manfaat_asuransi_ajri;

                            if($akumulasi_reas > 100000000){
                                $sisa_akumulasi_ajri = 100000000 - $item['akumulasi_ganda_reas'];
                                $sisa_akumulasi_reas = $manfaat_asuransi - $sisa_akumulasi_ajri;
                            }else{
                                $sisa_akumulasi_reas = ($manfaat_asuransi*$or)/100;
                                $sisa_akumulasi_ajri = ($manfaat_asuransi*$ajri)/100;
                            }
                            $item['nilai_manfaat_asuransi_reas'] = $sisa_akumulasi_reas;
                            $item['reas_manfaat_asuransi_ajri'] = $sisa_akumulasi_ajri;
                        }
                    }else{
                        if($reas_manfaat_asuransi_ajri>=100000000){
                            $item['nilai_manfaat_asuransi_reas'] = $manfaat_asuransi - 100000000;
                            $item['reas_manfaat_asuransi_ajri'] = 100000000;
                        }else{
                            $item['nilai_manfaat_asuransi_reas'] = ($manfaat_asuransi*$or)/100;
                            $item['reas_manfaat_asuransi_ajri'] = ($manfaat_asuransi*$ajri)/100;
                        }
                    }
                    // kontribusi reas
                    $rate = ReasuradurRateRates::where(['tahun'=>$item['usia_reas'],'bulan'=>$item['masa_bulan'],'reasuradur_rate_id'=>$peserta->reas->reasuradur_rate_id])->first();
                    if($rate){
                        $item['rate_reas'] = $rate->rate;
                        $item['total_kontribusi_reas'] = ($rate->rate*$item['nilai_manfaat_asuransi_reas'])/1000;
                    }else {
                        $item['rate_reas'] = 0;
                        $item['total_kontribusi_reas'] = 0;
                    }
                    if($ri_com)
                        $item['ujroh_reas'] = ($item['total_kontribusi_reas'] * $ri_com) / 100;
                    else
                        $item['ujroh_reas'] = 0;

                    // ul
                    $uw = ReasuradurRateUw::whereRaw("{$manfaat_asuransi} BETWEEN min_amount and max_amount")->where(['usia'=>$item['usia_reas'],'reasuradur_rate_id'=>$peserta->reas->reasuradur_rate_id])->first();
                    if(!$uw) $uw = ReasuradurRateUw::where(['usia'=>$item['usia_reas'],'reasuradur_rate_id'=>$peserta->reas->reasuradur_rate_id])->orderBy('max_amount','ASC')->first();
                    if($uw) $item['ul_reas'] = $uw->keterangan;

                    $item['net_kontribusi_reas'] = $item['total_kontribusi_reas'] + $item['reas_extra_kontribusi'] - $item['ujroh_reas'];

                    if($item['total_kontribusi_reas']<=0){
                        $item['nilai_manfaat_asuransi_reas'] = 0;
                        $item['reas_manfaat_asuransi_ajri'] = $item['basic'];
                        $item['status_reas'] = 2; // tidak direaskan karna distribusinya 0
                    }else $item['status_reas'] = 1;

                    if(isset($peserta->reas->rate_uw->or)){
                        if($peserta->reas->rate_uw->or==100.00){
                            $item['status_reas'] = 2;
                            $item['nilai_manfaat_asuransi_reas'] = 0;
                            $item['total_kontribusi_reas'] = 0;
                            $item['net_kontribusi_reas'] = 0;
                        }
                    }
                    if(strtoupper($peserta->reas->reasuradur->name) =='OR'){
                        $item['status_reas'] = 2;
                        $item['nilai_manfaat_asuransi_reas'] = 0;
                        $item['total_kontribusi_reas'] = 0;
                        $item['net_kontribusi_reas'] = 0;
                    }
                    // end calculate reas
                }
                
                $peserta->save();

                $em_perubahan += $item['extra_mortalita'];
                $ek_perubahan += $item['extra_kontribusi'];
                
                $item['potongan_langsung'] = 0;
                
                if($polis->potong_langsung){
                    $peserta->jumlah_potongan_langsung = $peserta->kontribusi*($polis->potong_langsung/100);
                    $item['jumlah_potongan_langsung'] = $item['kontribusi']*($polis->potong_langsung/100);
                    $total_potongan_langsung += $item['jumlah_potongan_langsung'];
                }

                if($polis->fee_base_brokerage){
                    $polis->fee_base_brokerage = str_replace(",",".",$polis->fee_base_brokerage);
                    
                    $peserta->brokerage_ujrah_persen = $polis->fee_base_brokerage;
                    $peserta->brokerage_ujrah = @$peserta->kontribusi*($polis->fee_base_brokerage/100);

                    $item['brokerage_ujrah_persen'] = $polis->fee_base_brokerage;
                    $item['brokerage_ujrah'] = @$item['kontribusi']*($polis->fee_base_brokerage/100);
                    $total_ujroh_brokerage += $item['brokerage_ujrah'];
                }
        
                if($polis->pph){
                    $peserta->pph_amount = $polis->pph;
                    $item['pph'] =  $polis->pph;
        
                    if($polis->ket_diskon=='Potong Langsung + Brokerage Ujroh'){
                        $peserta->pph_amount = $peserta->brokerage_ujrah*($polis->pph/100);
                        $item['pph_amount'] = $item['brokerage_ujrah']*($polis->pph/100);
                    }else{
                        $peserta->pph_amount = $peserta->jumlah_potongan_langsung*($polis->pph/100);
                        $item['pph_amount'] = $item['jumlah_potongan_langsung']*($polis->pph/100);
                    }
                    $total_pph += $item['pph_amount'];
                }
        
                if($polis->ppn){
                    $peserta->ppn = $polis->ppn; 
                    $item['ppn'] =  $polis->ppn;

                    if(isset($peserta->jumlah_potongan_langsung))
                        $peserta->ppn = (($polis->ppn/100) * $peserta->jumlah_potongan_langsung);
                    else
                        $peserta->ppn = $peserta->kontribusi*($polis->ppn/100);

                    if(isset($item['jumlah_potongan_langsung']))
                        $item['ppn'] = (($polis->ppn/100) * $item['jumlah_potongan_langsung']);
                    else
                        $item['ppn'] = $item['kontribusi']*($polis->ppn/100);
                    
                    $total_ppn += $item['ppn'];
                }

                $kontribusi_netto_perubahan += $item['kontribusi']+$item['extra_mortalita']+$item['extra_kontribusi'];
                $basic_perubahan += $item['basic'];

                $total_kontribusi_gross += $peserta->kontribusi;
                $total_kontribusi_tambahan += $peserta->extra_kontribusi;
                $total_kontribusi += $peserta->kontribusi + $peserta->extra_kontribusi + $peserta->extra_mortalita;;
                $total_manfaat_asuransi += $peserta->basic;

                $item['nett_kontribusi'] = $item['kontribusi']+
                                            $item['extra_kontribusi']+
                                            $item['extra_mortalita']+
                                            $item['pph_amount']-($item['ppn_amount']+$item['jumlah_potongan_langsung']+$item['brokerage_ujrah']);
                
                $peserta->nett_kontribusi = $peserta->kontribusi+
                    $peserta->extra_kontribusi+
                    $peserta->extra_mortalita+
                    $peserta->pph_amount-($peserta->ppn_amount+$peserta->jumlah_potongan_langsung+$peserta->brokerage_ujrah);
                
                // $peserta->refund_sisa_masa_asuransi = hitung_masa_bulan($item['refund_tanggal_efektif'],$peserta->tanggal_akhir,3);

                $peserta->refund_kontribusi = ($peserta->refund_sisa_masa_asuransi / $peserta->masa_bulan) * (($peserta->polis->refund / 100) * $peserta->total_kontribusi_dibayar);
                    
                // if($peserta->net_kontribusi_reas>0 and $peserta->reas_id>0){
                //     $refund_reas_persen = isset($peserta->reas->rate_uw->persentase_refund) ? $peserta->reas->rate_uw->persentase_refund : 0; 
                //     $peserta->refund_kontribusi_reas = ($peserta->refund_sisa_masa_asuransi / $peserta->masa_bulan) * (($refund_reas_persen / 100) * $peserta->net_kontribusi_reas);
                // }

                EndorsementPeserta::create([
                    'endorsement_id'=>$data->id,
                    'before_data'=>json_encode($peserta),
                    'after_data'=>json_encode($item)
                ]);
                $total++;
                $peserta->save();
            }
        }
        
        $data->pph_persen = $polis->pph;
        $data->ppn_persen = $polis->ppn;
        $data->pph = $total_pph;
        $data->ppn = $total_ppn;
        $data->field_perubahan = json_encode($field_perubahan);
        $data->value_perubahan_before = json_encode($value_perubahan_before);
        $data->value_perubahan_after = json_encode($value_perubahan_after);
        $data->total_kontribusi_gross = $total_kontribusi_gross;
        $data->total_potongan_langsung = $total_potongan_langsung;
        $data->total_kontribusi_tambahan = $total_kontribusi_tambahan;
        $data->total_manfaat_asuransi = $total_manfaat_asuransi;
        $data->total_kontribusi = $total_kontribusi;
        $data->total_peserta = $total;
        $data->kontribusi_netto_perubahan = $kontribusi_netto_perubahan;
        $data->basic_perubahan = $basic_perubahan;
        $data->jenis_dokumen = 1; // 1 = CN, 2 = DN
        
        if($data->total_kontribusi_gross > $data->kontribusi_netto_perubahan){
            $selisih = ($data->total_kontribusi_gross - $data->kontribusi_netto_perubahan);
            $data->jenis_dokumen = 2; // 1 = CN, 2 = DN
        }else{
            $selisih = ($data->kontribusi_netto_perubahan - $data->total_kontribusi_gross);
        }
        
        $data->selisih = $selisih;
        $data->save();

        $reasuradur = Kepesertaan::select('kepesertaan.*')->where('kepesertaan.endorsement_id',$data->id)
                                ->join('reas','reas.id','=','kepesertaan.reas_id')
                                ->join('reasuradur','reasuradur.id','=','reas.reasuradur_id')
                                ->where(function($table){
                                    $table->where('reasuradur.name','<>','OR')
                                            ->orWhere('reasuradur.name','<>','');
                                })
                                ->groupBy('reasuradur.id')
                                ->get();
                
        foreach($reasuradur as $item){
            $reas_refund = new ReasEndorse();
            $reas_refund->endorsement_id = $data->id;
            $reas_refund->status = 0;
            $reas_refund->polis_id = $data->polis_id;
            $reas_refund->tanggal_pengajuan = $data->tanggal_pengajuan;
            $reas_refund->reas_id = $item->reas_id;
            $reas_refund->save();
            
            $reas_refund->nomor = str_pad($reas_refund->id,6, '0', STR_PAD_LEFT) ."/END-C/AJRI/".numberToRomawi(date('m')).'/'.date('Y');
            
            Kepesertaan::where(['endorsement_id'=>$data->id,'reas_id'=>$item->reas_id])->update(['reas_endorse_id'=>$reas_refund->id]);
 
            $reas_refund->total_kontribusi_refund = Kepesertaan::where(['endorsement_id'=>$data->id,'reas_id'=>$item->reas_id])->sum('refund_kontribusi_reas');
            $reas_refund->total_peserta = Kepesertaan::where(['endorsement_id'=>$data->id,'reas_id'=>$item->reas_id])->get()->count();
            $reas_refund->total_manfaat_asuransi = Kepesertaan::where(['endorsement_id'=>$data->id,'reas_id'=>$item->reas_id])->sum('nilai_manfaat_asuransi_reas');
            $reas_refund->total_kontribusi = Kepesertaan::where(['endorsement_id'=>$data->id,'reas_id'=>$item->reas_id])->sum('net_kontribusi_reas');
            $reas_refund->save();   
        }
    }
}
