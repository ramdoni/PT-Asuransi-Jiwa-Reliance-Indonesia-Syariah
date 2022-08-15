<?php

namespace App\Http\Livewire\Pengajuan;

use App\Models\Pengajuan;
use Livewire\Component;
use App\Models\Polis;
use App\Models\Kepesertaan;
use App\Models\Rate;
use App\Models\UnderwritingLimit;
use Livewire\WithFileUploads;
use App\Jobs\PengajuanCalculate;

class Insert extends Component
{
    use WithFileUploads;
    public $polis=[],$file,$polis_id,$no_pengajuan,$kepesertaan=[],$check_all=0,$check_id=[],$check_arr;
    public $total_pengajuan=0,$perhitungan_usia,$masa_asuransi,$message_error = '',$is_calculate=false;
    protected $listeners = ['set_calculate'=>'set_calculate'];
    public function render()
    {
        return view('livewire.pengajuan.insert');
    }

    public function mount()
    {
        $this->no_pengajuan =  date('dmy').str_pad((Pengajuan::count()+1),6, '0', STR_PAD_LEFT);
        $this->polis = Polis::get();
    }

    public function set_calculate($condition=false)
    {
        $this->is_calculate = $condition;
        $this->emit('reload-row');
    }
    public function calculate()
    {
        $this->is_calculate = true;
        PengajuanCalculate::dispatch($this->polis_id,$this->perhitungan_usia,$this->masa_asuransi);
    }

    public function clear_file()
    {
        $this->kepesertaan = [];
        $this->reset('file');
    }

    public function keepAll()
    {
        foreach($this->kepesertaan as $k => $item){
            $item->is_double = 0;
            $item->parent_id = 0;
            $item->save();
        }    
        $this->check_id = [];
        $this->emit('reload-page');
        $this->emit('message-success','Data berhasil diproses');
    }

    public function deleteAll()
    {
        foreach($this->kepesertaan as $k => $item){
            Kepesertaan::find($item->id)->delete();
        }
        $this->check_id = [];

        $this->emit('reload-page');        
        $this->emit('message-success','Data berhasil diproses');
    }

    public function delete(Kepesertaan $data)
    {
        $data->delete();
        $this->kepesertaan = Kepesertaan::where(['polis_id'=>$this->polis_id,'is_temp'=>1])->get();

        $this->emit('reload-page');        
    }

    public function keep(Kepesertaan $data)
    {
        $data->is_double = 0;;
        $data->save();

        $this->emit('reload-page');        
    }

    public function updated($propertyName)
    {
        if($propertyName=='polis_id'){
            $this->emit('set_polis_id',$this->polis_id);
            $find_polis = Polis::find($this->polis_id);
            if($find_polis){
                if($find_polis->rate_->count()==0 || $find_polis->uw_limit_->count()==0)
                    $this->message_error = 'Rate / UW limit belum tersedia';
                else
                    $this->message_error = '';
            }
        }

        if($propertyName=='check_all' and $this->check_all==1){
            foreach($this->kepesertaan as $k => $item){
                $this->check_id[$k] = $item->id;
            }
        }elseif($propertyName=='check_all' and $this->check_all==0){
            $this->check_id = [];
        }

        if($propertyName=='polis_id') Kepesertaan::where(['polis_id'=>$this->polis_id,'is_temp'=>1])->delete();

        if($propertyName=='file') $this->temp_upload();

        // $this->total_pengajuan = Kepesertaan::where(['polis_id'=>$this->polis_id,'is_temp'=>1,'is_double'=>0])->get()->count();
    }

    public function temp_upload()
    {
        $this->validate([
            'file'=>'required|mimes:xlsx|max:51200', // 50MB maksimal
        ]);
        
        $path = $this->file->getRealPath();
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $xlsx = $reader->load($path);
        $sheetData = $xlsx->getActiveSheet()->toArray();
        $total_data = 0;
        $total_double = 0;
        $total_success = 0;
        Kepesertaan::where(['polis_id'=>$this->polis_id,'is_temp'=>1,'is_double'=>1])->delete();
        $insert = [];
        foreach($sheetData as $key => $item){
            if($key<=1) continue;
            /**
             * Skip
             * Nama, Tanggal lahir
             */
            if($item[1]=="" || $item[10]=="") continue;
            $insert[$total_data]['polis_id'] = $this->polis_id;
            $insert[$total_data]['nama'] = $item[1];
            $insert[$total_data]['no_ktp'] = $item[2];
            $insert[$total_data]['alamat'] = $item[3];
            $insert[$total_data]['no_telepon'] = $item[4];
            $insert[$total_data]['pekerjaan'] = $item[5];
            $insert[$total_data]['bank'] = $item[6];
            $insert[$total_data]['cab'] = $item[7];
            $insert[$total_data]['no_closing'] = $item[8];
            $insert[$total_data]['no_akad_kredit'] = $item[9];
            $insert[$total_data]['tanggal_lahir'] = @\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[10])->format('Y-m-d');
            $insert[$total_data]['jenis_kelamin'] = $item[11];
            if($item[12]) $insert[$total_data]['tanggal_mulai'] = @\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[12])->format('Y-m-d');
            if($item[13]) $insert[$total_data]['tanggal_akhir'] = @\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[13])->format('Y-m-d');
            $insert[$total_data]['basic'] = $item[14];
            $insert[$total_data]['tinggi_badan'] = $item[15];
            $insert[$total_data]['berat_badan'] = $item[16];
            $insert[$total_data]['kontribusi'] = 0;
            $insert[$total_data]['is_temp'] = 1;
            $insert[$total_data]['is_double'] = 2;
            $total_data++;
        }

        if(count($insert)>0)  {
            Kepesertaan::insert($insert);
        }
        
        $this->emit('reload-row');
        $this->emit('attach-file');
    }

    public function save()
    {
        $this->validate([
            'file'=>'required|mimes:xlsx|max:51200', // 50MB maksimal
            'polis_id'=>'required',
            'masa_asuransi' => 'required'
        ]);

        \LogActivity::add('[web] Upload Kepesertaan');
        
        $pengajuan = new Pengajuan();
        $pengajuan->masa_asuransi = $this->masa_asuransi;
        $pengajuan->perhitungan_usia = $this->perhitungan_usia;
        $pengajuan->polis_id = $this->polis_id;
        $pengajuan->status = 0;
        $pengajuan->total_akseptasi = $this->total_pengajuan;
        $pengajuan->total_approve = 0;
        $pengajuan->total_reject = 0;
        $pengajuan->no_pengajuan =  date('dmy').str_pad((Pengajuan::count()+1),6, '0', STR_PAD_LEFT);
        $pengajuan->account_manager_id = \Auth::user()->id;
        $pengajuan->save();

        foreach($this->kepesertaan as $item){
            $item->pengajuan_id = $pengajuan->id;
            $item->is_temp = 0;
            $item->status_polis = 'Akseptasi';
            $item->save();
        }

        $this->hitung();

        session()->flash('message-success',__('Pengajuan berhasil diupload, silahkan menunggu persetujuan'));

        return redirect()->route('pengajuan.index');
    }

    public function hitung()
    {
        $polis = Polis::find($this->polis_id);
        $iuran_tabbaru = $polis->iuran_tabbaru;
        $ujrah = $polis->ujrah_atas_pengelolaan;
        foreach(Kepesertaan::where(['polis_id'=>$this->polis_id,'is_temp'=>1])->get() as $data){

            $check =  Kepesertaan::where(['polis_id'=>$this->polis_id,'nama'=>$data->nama,'tanggal_lahir'=>$data->tanggal_lahir])->where(function($table){
                $table->where('status_polis','Inforce')->orWhere('status_polis','Akseptasi');
            })->sum('basic');

            if($check>0){
                $data->is_double = 1;
                $data->parent_id = $check->id;
                $data->akumulasi_ganda = $check+$data->basic;;
            }else $data->is_double=0;

            $data->usia = $data->tanggal_lahir ? hitung_umur($data->tanggal_lahir,$this->perhitungan_usia) : '0';
            $data->masa = hitung_masa($data->tanggal_mulai,$data->tanggal_akhir);
            $data->masa_bulan = hitung_masa_bulan($data->tanggal_mulai,$data->tanggal_akhir,$this->masa_asuransi);
            $nilai_manfaat_asuransi = $data->basic;

            
            // find rate
            $rate = Rate::where(['tahun'=>$data->usia,'bulan'=>$data->masa_bulan,'polis_id'=>$this->polis_id])->first();
            $data->rate = $rate ? $rate->rate : 0;
            $data->kontribusi = $nilai_manfaat_asuransi * $data->rate/1000;
        

            $rate = Rate::where(['tahun'=>$data->usia,'bulan'=>$data->masa_bulan,'polis_id'=>$this->polis_id])->first();
            $data->rate = $rate ? $rate->rate : 0;
            $data->kontribusi = $nilai_manfaat_asuransi * $data->rate/1000;
            
            $rate = Rate::where(['tahun'=>$data->usia,'bulan'=>$data->masa_bulan,'polis_id'=>$this->polis_id])->first();
            if(!$rate || $rate->rate ==0 || $rate->rate ==""){
                $data->rate = 0;
                $data->kontribusi = 0;
            }else{
                $data->rate = $rate ? $rate->rate : 0;
                $data->kontribusi = $nilai_manfaat_asuransi * $data->rate/1000;
            }
            
            $data->dana_tabarru = ($data->kontribusi*$iuran_tabbaru)/100; // persen ngambil dari daftarin polis
            $data->dana_ujrah = ($data->kontribusi*$ujrah)/100; 
            $data->extra_mortalita = $data->rate_em*$nilai_manfaat_asuransi/1000;
            
            if($data->akumulasi_ganda)
                $uw = UnderwritingLimit::whereRaw("{$data->akumulasi_ganda} BETWEEN min_amount and max_amount")->where(['usia'=>$data->usia,'polis_id'=>$this->polis_id])->first();
            else
                $uw = UnderwritingLimit::whereRaw("{$nilai_manfaat_asuransi} BETWEEN min_amount and max_amount")->where(['usia'=>$data->usia,'polis_id'=>$this->polis_id])->first();
            
            if(!$uw) $uw = UnderwritingLimit::where(['usia'=>$data->usia,'polis_id'=>$this->polis_id])->orderBy('max_amount','ASC')->first();
            if($uw){
                $data->uw = $uw->keterangan;
                $data->ul = $uw->keterangan;
            }
            $data->is_hitung = 1;
            $data->save();
        }

        $this->emit('message-success','Data berhasil dikalkukasi');
        $this->emit('reload-row');
    }
}
