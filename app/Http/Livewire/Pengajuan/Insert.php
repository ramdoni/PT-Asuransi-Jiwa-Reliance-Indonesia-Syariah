<?php

namespace App\Http\Livewire\Pengajuan;

use App\Models\Pengajuan;
use Livewire\Component;
use App\Models\Polis;
use App\Models\Kepesertaan;
use App\Models\Rate;
use App\Models\UnderwritingLimit;
use Livewire\WithFileUploads;

class Insert extends Component
{
    use WithFileUploads;
    public $polis=[],$file,$polis_id,$no_pengajuan,$kepesertaan=[],$check_all=0,$check_id=[],$check_arr;
    public $total_double=0,$total_pengajuan=0,$perhitungan_usia;
    protected $listeners = ['reload-page'=>'$refresh'];
    public function render()
    {
        if($this->polis_id){
            $this->total_pengajuan = Kepesertaan::where(['polis_id'=>$this->polis_id,'is_temp'=>1,'is_double'=>0])->get()->count();
            $this->kepesertaan = Kepesertaan::where(['polis_id'=>$this->polis_id,'is_temp'=>1])->get();
            $this->total_double = Kepesertaan::where(['polis_id'=>$this->polis_id,'is_temp'=>1,'is_double'=>1])->get()->count();
        }

        return view('livewire.pengajuan.insert');
    }

    public function mount()
    {
        $this->no_pengajuan =  date('dmy').str_pad((Pengajuan::count()+1),6, '0', STR_PAD_LEFT);
        $this->polis = Polis::get();
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
        if($propertyName=='check_all' and $this->check_all==1){
            foreach($this->kepesertaan as $k => $item){
                $this->check_id[$k] = $item->id;
            }
        }elseif($propertyName=='check_all' and $this->check_all==0){
            $this->check_id = [];
        }

        if($propertyName=='polis_id') Kepesertaan::where(['polis_id'=>$this->polis_id,'is_temp'=>1])->delete();

        if($propertyName=='file'){
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
            foreach($sheetData as $key => $item){
                if($key<=1) continue;
                /**
                 * Skip
                 * Nama, Tanggal lahir
                 */
                if($item[1]=="" || $item[10]=="") continue;
                
                $tanggal_lahir = @\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[10])->format('Y-m-d');

                $check =  Kepesertaan::where(['polis_id'=>$this->polis_id,'nama'=>$item[1],'tanggal_lahir'=>$tanggal_lahir])->first();
                
                $data = new Kepesertaan();
                
                if($check){
                    $data->is_double = 1;
                    $data->parent_id = $check->id;
                    $total_double++;
                }

                $data->polis_id = $this->polis_id;
                $data->nama = $item[1];
                $data->no_ktp = $item[2];
                $data->alamat = $item[3];
                $data->no_telepon = $item[4];
                $data->pekerjaan = $item[5];
                $data->bank = $item[6];
                $data->cab = $item[7];
                $data->no_closing = $item[8];
                $data->no_akad_kredit = $item[9];
                if($item[10]) $data->tanggal_lahir = $tanggal_lahir;
                $data->jenis_kelamin = $item[11];
                if($item[12]) $data->tanggal_mulai = @\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[12])->format('Y-m-d');
                if($item[13]) $data->tanggal_akhir = @\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[13])->format('Y-m-d');
                $data->basic = $item[14];
                $data->sub_basic1 = $item[15];
                $data->sub_basic2 = $item[16];
                $data->sub_basic3 = $item[17];
                $data->rider1 = $item[18];
                $data->rider2 = $item[19];
                $data->rider3 = $item[20];
                $data->usia = $data->tanggal_lahir ? hitung_umur($data->tanggal_lahir,$this->perhitungan_usia) : '0';
                $data->masa = hitung_masa($data->tanggal_mulai,$data->tanggal_akhir);
                $data->masa_bulan = hitung_masa_bulan($data->tanggal_mulai,$data->tanggal_akhir);
                $data->kontribusi = 0;
                $data->is_temp = 1;
                $data->save();

                $total_data++;
            }

            $this->kepesertaan = Kepesertaan::where(['polis_id'=>$this->polis_id,'is_temp'=>1])->get();
            $this->total_double = Kepesertaan::where(['polis_id'=>$this->polis_id,'is_temp'=>1,'is_double'=>1])->get()->count();

            $this->emit('attach-file');
        }

        $this->total_pengajuan = Kepesertaan::where(['polis_id'=>$this->polis_id,'is_temp'=>1,'is_double'=>0])->get()->count();
    }

    public function save()
    {
        $this->validate([
            'file'=>'required|mimes:xlsx|max:51200', // 50MB maksimal
            'polis_id'=>'required'
        ]);

        \LogActivity::add('[web] Upload Kepesertaan');
        
        $pengajuan = new Pengajuan();
        $pengajuan->perhitungan_usia = $this->perhitungan_usia;
        $pengajuan->polis_id = $this->polis_id;
        $pengajuan->no_pengajuan = $this->no_pengajuan;
        $pengajuan->status = 0;
        $pengajuan->total_akseptasi = $this->total_pengajuan;
        $pengajuan->total_approve = 0;
        $pengajuan->total_reject = 0;
        $pengajuan->save();

        foreach($this->kepesertaan as $item){
            $item->pengajuan_id = $pengajuan->id;
            $item->is_temp = 0;
            $item->save();
        }

        $this->hitung();

        session()->flash('message-success',__('Pengajuan berhasil diupload, silahkan menunggu persetujuan'));

        return redirect()->route('pengajuan.index');
    }

    public function hitung()
    {
        //$datas = Kepesertaan::where('pengajuan_id',$this->data->id)->orderBy('id','ASC')->get();
        foreach($this->kepesertaan as $data){
            // generate no peserta
            //$no_peserta = $data->polis->produk->id ."-". date('ym').str_pad($data->id,4, '0', STR_PAD_LEFT).'-'.str_pad($data->polis_id,6, '0', STR_PAD_LEFT);
            //$data->no_peserta = $no_peserta;
            $nilai_manfaat_asuransi = $data->basic;

            if($data->masa_bulan /12 >15)
                $data->kontribusi_keterangan = 'max. 15 th';
            else{
                // find rate
                $rate = Rate::where(['tahun'=>$data->usia,'bulan'=>$data->masa_bulan,'polis_id'=>$data->polis_id])->first();
                $data->rate = $rate ? $rate->rate : 0;
                $data->kontribusi = $nilai_manfaat_asuransi * $data->rate/1000;
            }
            
            $data->dana_tabarru = ($data->kontribusi*$data->polis->iuran_tabbaru)/100; // persen ngambil dari daftarin polis
            $data->dana_ujrah = ($data->kontribusi*$data->polis->ujrah_atas_pengelolaan)/100; 
            $data->extra_mortalita = $data->rate_em*$nilai_manfaat_asuransi/1000;
            
            /**
             * 
             * @var : kontribusi
             * @param : = IF((AF9/12)>15;"max. 15 th";ROUNDDOWN(R9*AJ9/1000;0))
             * 
             * @var : Masa Bulan
             * @param : AF9 = IF(L9="";"";AO9*12+(AP9+IF(AQ9>0;1;0)))
             *  
             * @param : R9 = Nilai manfaat asuransi
             * @param : N9 = Usia
             * @param : AJ9 = VLOOKUP(N9;rate;AF9+1;0)
             */

            /**  
             * @param : UW
             * @param : =IF(N9+(AF9/12)>75;"X+N=75";VLOOKUP(R9;uw_limit;VLOOKUP(N9;lookup;4;TRUE);TRUE)) 
             * 
             */
            if($data->usia + ($data->masa_bulan/12) > 75){
                $data->ul = "X+N=75";
                $data->uw = "X+N=75";
            }else{
                $uw = UnderwritingLimit::where('max_amount','<=',$nilai_manfaat_asuransi)->where('min_amount','>=',$nilai_manfaat_asuransi)->where(['usia'=>$data->usia,'polis_id'=>$data->polis_id])->first();

                if(!$uw) $uw = UnderwritingLimit::where(['usia'=>$data->usia,'polis_id'=>$data->polis_id])->orderBy('max_amount','ASC')->first();
                if($uw){
                    $data->uw = $uw->keterangan;
                    $data->ul = $uw->keterangan;
                }
            }

            if(isset($data->polis->waiting_period) and $data->polis->waiting_period !="")
                $data->tanggal_stnc = date('Y-m-d',strtotime(" +{$data->polis->waiting_period} month", strtotime($data->polis->tanggal_akseptasi)));
            else{
                if(countDay($data->polis->tanggal_akseptasi,$data->tanggal_mulai) > $data->polis->retroaktif){
                    $data->tanggal_stnc = date('Y-m-d');
                }elseif(countDay($data->polis->tanggal_akseptasi,$data->tanggal_mulai) < $data->polis->retroaktif){
                    $data->tanggal_stnc = null;
                }
            }

            $data->is_hitung = 1;
            $data->save();
        }

        $this->emit('message-success','Data berhasil dikalkukasi');
        $this->emit('reload-page');
    }
}
