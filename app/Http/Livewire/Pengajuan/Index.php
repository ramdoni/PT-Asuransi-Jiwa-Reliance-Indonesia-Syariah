<?php

namespace App\Http\Livewire\Pengajuan;

use Livewire\Component;
use App\Models\Pengajuan;
use App\Models\Kepesertaan;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use Livewire\WithPagination;
use App\Models\MemoUjroh;
use App\Models\Polis;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $selected,$check_id=[],$is_pengajuan_reas=false,$is_pengajuan_memo_ujroh=false;
    public $filter_keyword,$filter_status_invoice,$filter_status,$start_tanggal_pengajuan,$end_tanggal_pengajuan,$start_tanggal_pembayaran,$end_tanggal_pembayaran;
    public $start_tanggal_akseptasi,$end_tanggal_akseptasi,$polis_pengajuan,$polis_id,$memo_selected=[];
    public function render()
    {
        $data = $this->data();

        $total_all = clone $data;
        $total_paid = clone $data;
        $total_unpaid = clone $data;

        if($this->is_pengajuan_memo_ujroh) {
            $data->where('pengajuan.status',3)
                ->whereNotNull('payment_date')
                ->whereNull('memo_ujroh_id');
        }
        
        return view('livewire.pengajuan.index')->with([
                'data'=>$data->paginate(100),
                'total_all'=>$total_all->count(),
                'total_dn'=>$total_all->sum('net_kontribusi'),
                'total_dn_count'=> $total_all->whereNotNull('dn_number')->count(),
                'total_dn_paid'=>$total_paid->where('status_invoice',1)->sum('net_kontribusi'),
                'total_dn_unpaid'=>$total_unpaid->whereNotNull('dn_number')->where('status_invoice','0')->sum('net_kontribusi'),
            ]);
    }

    public function data()
    {
        $data = Pengajuan::select('pengajuan.*')->with(['polis','account_manager','reas'])
                // ->orderBy('created_at','DESC')
                ->orderByRaw('IF(pengajuan.status = 6, 0,1)')
                ->orderBy('pengajuan.created_at','DESC')
                ->join('polis','polis.id','=','pengajuan.polis_id')
                ;
        if($this->filter_keyword) $data->where(function($table){
            // foreach(\Illuminate\Support\Facades\Schema::getColumnListing('pengajuan') as $column){
            foreach(['pengajuan.no_pengajuan','pengajuan.dn_number','pengajuan.no_surat','polis.no_polis','polis.nama'] as $column){
                $table->orWhere($column,'LIKE',"%{$this->filter_keyword}%");
            }
        });
        if($this->filter_status_invoice) $data->where('pengajuan.status_invoice',$this->filter_status_invoice);
        if($this->filter_status) $data->where('pengajuan.status',$this->filter_status);
        if($this->start_tanggal_pengajuan and $this->end_tanggal_pengajuan){
            if($this->start_tanggal_pengajuan == $this->end_tanggal_pengajuan)
                $data->whereDate('pengajuan.created_at',$this->start_tanggal_pengajuan);
            else
                $data->whereBetween('pengajuan.created_at',[$this->start_tanggal_pengajuan." 00:00:00",$this->end_tanggal_pengajuan." 23:59:59"]);
        }

        if($this->start_tanggal_pembayaran and $this->end_tanggal_pembayaran){
            if($this->start_tanggal_pembayaran == $this->end_tanggal_pembayaran)
                $data->whereDate('pengajuan.payment_date',$this->start_tanggal_pembayaran);
            else
                $data->whereBetween('pengajuan.payment_date',[$this->start_tanggal_pembayaran,$this->end_tanggal_pembayaran]);
        }

        if($this->start_tanggal_akseptasi and $this->end_tanggal_akseptasi){
            if($this->start_tanggal_akseptasi == $this->end_tanggal_akseptasi)
                $data->whereDate('pengajuan.head_syariah_submit',$this->start_tanggal_akseptasi);
            else
                $data->whereBetween('pengajuan.head_syariah_submit',[$this->start_tanggal_akseptasi,$this->end_tanggal_akseptasi]);
        }

        if($this->polis_id) $data->where('pengajuan.polis_id',$this->polis_id);

        return $data;
    }

    public function mount()
    {
        $this->polis_pengajuan = Pengajuan::with('polis')->where('pengajuan.status',3)->groupBy('polis_id')->get();
    }

    public function updated($propertyName)
    {
        if($propertyName =='is_pengajuan_memo_ujroh' and $this->is_pengajuan_memo_ujroh==true){
            $this->emit('select-memo-ujroh');
        }

        if($propertyName =='is_pengajuan_memo_ujroh' and $this->is_pengajuan_memo_ujroh==false){
            $this->check_id = [];
        }

        if($propertyName=='polis_id'){
            $this->check_id = [];
        }
    }

    public function submit_memo_ujroh()
    {
        if(count(array_filter($this->check_id))==0){
            $this->emit('message-error','Pengajuan harus di pilih.');
        }elseif($this->polis_id==""){
            $this->emit('message-error','Polis harus di pilih.');
        }else{
            $data = new MemoUjroh();
            $data->polis_id = $this->polis_id;
            $data->tanggal_pengajuan = date('Y-m-d');
            $data->user_created_id = \Auth::user()->id;
            // $data->save();
            $increment = get_setting('running_number_memo_ujroh') + 1;
            # 000646/UWS-M/AJRI-US/VIII/2023
            $data->nomor = str_pad($increment,6, '0', STR_PAD_LEFT) ."/UWS-M/AJRI-US/".numberToRomawi(date('m')).'/'.date('Y');
            $data->save();
            
            update_setting('running_number_memo_ujroh',$increment);
            
            foreach($this->memo_selected as $item){
                Pengajuan::find($item->id)->update(['memo_ujroh_id'=>$data->id]);
                // $item->memo_ujroh_id = $data->id;
                // $item->save();                
            }

            $polis = Polis::find($data->polis_id);
            $pengajuan = Pengajuan::where('memo_ujroh_id',$data->id)->get();
            
            $data->perkalian_biaya_penutupan = $polis->perkalian_biaya_penutupan;
            $data->maintenance = $polis->maintenance;
            $data->maintenance_penerima = $polis->maintenance_penerima;
            $data->maintenance_nama_bank = $polis->maintenance_nama_bank;
            $data->maintenance_no_rekening = $polis->maintenance_no_rekening;

            $data->admin_agency = $polis->admin_agency;
            $data->admin_agency_penerima = $polis->admin_agency_penerima;
            $data->admin_agency_nama_bank = $polis->admin_agency_nama_bank;
            $data->admin_agency_no_rekening = $polis->admin_agency_no_rekening;

            $data->agen_penutup = $polis->agen_penutup;
            $data->agen_penutup_penerima = $polis->agen_penutup_penerima;
            $data->agen_penutup_nama_bank = $polis->agen_penutup_nama_bank;
            $data->agen_penutup_no_rekening = $polis->agen_penutup_no_rekening;

            $data->ujroh_handling_fee_broker = $polis->ujroh_handling_fee_broker;
            $data->ujroh_handling_fee_broker_penerima = $polis->ujroh_handling_fee_broker_penerima;
            $data->ujroh_handling_fee_broker_nama_bank = $polis->ujroh_handling_fee_broker_nama_bank;
            $data->ujroh_handling_fee_broker_no_rekening = $polis->ujroh_handling_fee_broker_no_rekening;
            
            $data->referal_fee = $polis->referal_fee;
            $data->referal_fee_penerima = $polis->referal_fee_penerima;
            $data->referal_fee_nama_bank = $polis->referal_fee_nama_bank;
            $data->referal_fee_no_rekening = $polis->referal_fee_no_rekening;
            
            $total_maintenance = 0;$total_agen_penutup=0;$total_admin_agency=0;$total_ujroh_handling_fee_broker=0;$total_referal_fee=0;
            
            $data->maintenance = str_replace(',','.',$data->maintenance);
            $data->agen_penutup = str_replace(',','.',$data->agen_penutup);
            $data->admin_agency = str_replace(',','.',$data->admin_agency);
            $data->ujroh_handling_fee_broker = str_replace(',','.',$data->ujroh_handling_fee_broker);
            $data->referal_fee = str_replace(',','.',$data->referal_fee);
            $kontribusi_nett = 0;
            foreach($pengajuan as $item){
                $kontribusi = Pengajuan::join('kepesertaan','kepesertaan.pengajuan_id','=','pengajuan.id')
                                        ->where('pengajuan.id',$item->id)
                                        ->where('kepesertaan.status_akseptasi',1)
                                        ->sum('kepesertaan.kontribusi');
            
                $item->kontribusi = $kontribusi;
                
                $kontribusi_nett += ($kontribusi - $item->potong_langsung - $item->brokerage_ujrah);

                if($data->perkalian_biaya_penutupan !='Kontribusi Gross')
                    $kontribusi = $kontribusi - $item->potong_langsung - $item->brokerage_ujrah;
                
                $maintenance = ($data->maintenance>0 and $kontribusi>0)?($kontribusi *($data->maintenance/100)):0;
                $agen_penutup = ($data->agen_penutup>0 and $kontribusi>0)?($kontribusi *($data->agen_penutup/100)):0;
                $admin_agency = ($data->admin_agency>0 and $item->kontribusi>0)?($kontribusi *($data->admin_agency/100)):0;
                $ujroh_handling_fee_broker = ($data->ujroh_handling_fee_broker>0 and $kontribusi>0)?($kontribusi *($data->ujroh_handling_fee_broker/100)):0;
                $referal_fee = ($data->referal_fee>0 and $kontribusi>0)?($kontribusi *($data->referal_fee/100)):0;

                $item->maintenance  = $maintenance;
                $item->agen_penutup = $agen_penutup;
                $item->admin_agency = $admin_agency;
                $item->ujroh_handling_fee_broker = $ujroh_handling_fee_broker;
                $item->referal_fee = $referal_fee;
                $item->save();
                
                $total_maintenance += $maintenance;    
                $total_agen_penutup += $agen_penutup;    
                $total_admin_agency += $admin_agency;    
                $total_ujroh_handling_fee_broker += $ujroh_handling_fee_broker;    
                $total_referal_fee += $referal_fee; 
            }

            $data->total_maintenance = $total_maintenance;
            $data->total_agen_penutup = $total_agen_penutup;
            $data->total_admin_agency = $total_admin_agency;
            $data->total_ujroh_handling_fee_broker = $total_ujroh_handling_fee_broker;
            $data->total_referal_fee = $total_referal_fee;
            $data->total_kontribusi_gross = Pengajuan::join('kepesertaan','kepesertaan.pengajuan_id','=','pengajuan.id')
                                                ->where('memo_ujroh_id',$data->id)
                                                ->where('kepesertaan.status_akseptasi',1)
                                                ->sum('kepesertaan.kontribusi');
            $data->total_kontribusi_nett = $kontribusi_nett;
            $data->save();

            session()->flash('message-success',__('Memo Ujroh berhasil disubmit'));

            return redirect()->route('memo-ujroh.index');
        }
    }

    public function confirm_memo_ujroh()
    {
        if(count(array_filter($this->check_id))==0){
            $this->emit('message-error','Pengajuan harus di pilih.');
        }else{
            $this->memo_selected = Pengajuan::whereIn('id',$this->check_id)->get();
            $this->emit('show-modal-submit-memo-ujroh');
        }
    }

    public function clear_filter()
    {
        $this->reset(['filter_status_invoice','filter_keyword']);
    }

    public function set_id($id)
    {
        $this->selected = Pengajuan::find($id);
    } 

    public function set_memo_ujroh()
    {
        $this->is_pengajuan_memo_ujroh = true;
    }

    public function submit_reas()
    {
        $this->emit('set_pengajuan',$this->check_id);
        $this->emit('modal_submit_reas');
    }

    public function delete()
    {
        Kepesertaan::where('pengajuan_id',$this->selected->id)->delete();

        $this->selected->delete();

        session()->flash('message-success',__('Pengajuan berhasil dihapus'));

        return redirect()->route('pengajuan.index');
    }
    
    public function downloadAll()
    {
        $data = $this->data();
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("PMT System")
                                    ->setLastModifiedBy("Stalavista System")
                                    ->setTitle("Office 2007 XLSX Product Database")
                                    ->setSubject("Daftar Peserta")
                                    ->setKeywords("office 2007 openxml php");

        $title = 'DAFTAR KEPESERTAAN ASURANSI JIWA KUMPULAN SYARIAH';

        $activeSheet = $objPHPExcel->setActiveSheetIndex(0);
        $activeSheet->setCellValue('A1', $title);
        $activeSheet->mergeCells("A1:O1");
        $activeSheet->getRowDimension('1')->setRowHeight(34);
        $activeSheet->getStyle('A1')->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'font' => [
                'size' => 16,
                'bold' => true,
            ]
        ]);;

        $activeSheet
                ->setCellValue('A2', 'NO')
                ->setCellValue('B2', 'BULAN')
                ->setCellValue('C2', 'USER MEMO')
                ->setCellValue('D2', 'USER AKSEPTASI')
                ->setCellValue('E2', 'TRANSAKSI ID')
                ->setCellValue('F2', 'BERKAS AKSEPTASI')
                ->setCellValue('G2', 'TGL PENGAJUAN EMAIL')
                ->setCellValue('H2', 'TGL PRODUKSI')
                ->setCellValue('I2', 'PRODUKSI AKRUAL')
                ->setCellValue('J2', 'BORDERO')
                ->setCellValue('K2', 'NO MEMO')
                ->setCellValue('L2', 'NO DEBIT NOTE')
                ->setCellValue('M2', 'NO POLIS')
                ->setCellValue('N2', 'PEMEGANG POLIS')
                ->setCellValue('O2', 'ALAMAT')
                ->setCellValue('P2', 'CABANG')
                ->setCellValue('Q2', 'JENIS PRODUK')
                ->setCellValue('R2', 'JML KEPESERTAAN TERTUNDA')
                ->setCellValue('S2', 'MANFAAT KEPESERTAAN TERTUNDA')
                ->setCellValue('T2', 'KONTRIBUSI KEPESERTAAN TERTUNDA')
                ->setCellValue('U2', 'JML KEPESERTAAN')
                ->setCellValue('V2', 'NO KEPESERTAAN AWAL')
                ->setCellValue('W2', 's/d')
                ->setCellValue('X2', 'NO KEPESERTAAN AKHIR')
                ->setCellValue('Y2', 'TGL MULAI ASURANSI')
                ->setCellValue('Z2', 'TGL AKHIR ASURANSI')
                ->setCellValue('AA2', 'UANG ASURANSI')
                ->setCellValue('AB2', 'DANA TABBARU')
                ->setCellValue('AC2', 'DANA UJRAH')
                ->setCellValue('AD2', 'KONTRIBUSI')
                ->setCellValue('AE2', 'EXTRA KONTRIBUSI')
                ->setCellValue('AF2', 'EXTRA MORTALITY')
                ->setCellValue('AG2', 'TOTAL KONTRIBUSI')
                ->setCellValue('AH2', 'POT LANGSUNG(%)')
                ->setCellValue('AI2', 'JML DISCOUNT')
                ->setCellValue('AJ2', 'STATUS POTONGAN')
                ->setCellValue('AK2', 'HANDLING FEE')
                ->setCellValue('AL2', 'JML FEE')
                ->setCellValue('AM2', 'PPH')
                ->setCellValue('AN2', 'JML PPH')
                ->setCellValue('AO2', 'PPN')
                ->setCellValue('AP2', 'JML PPN')
                ->setCellValue('AQ2', 'BIAYA POLIS')
                ->setCellValue('AR2', 'extPst')
                ->setCellValue('AS2', 'NETT KONTRIBUSI')
                ->setCellValue('AT2', 'TERBILANG')
                ->setCellValue('AU2', 'TGL UPDATE DATABASE')
                ->setCellValue('AP2', 'TGL UPDATE SISTEM')
                ->setCellValue('AW2', 'NO BERKAS SISTEM')
                ->setCellValue('AX2', 'TGL POSTING SISTEM')
                ->setCellValue('AY2', 'KET POSTING')
                ->setCellValue('AZ2', 'GRACE PERIOD')
                ->setCellValue('AA2', 'GRACE PERIOD')
                ->setCellValue('BB2', 'TGL JATUH TEMPO')
                ->setCellValue('BD2', 'TGL LUNAS');

        $activeSheet->getStyle("A2:BC2")->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                    ],
                ]);
        

        $activeSheet->getColumnDimension('A')->setAutoSize(true);
        $activeSheet->getColumnDimension('B')->setAutoSize(true);
        $activeSheet->getColumnDimension('C')->setAutoSize(true);
        $activeSheet->getColumnDimension('D')->setAutoSize(true);
        $activeSheet->getColumnDimension('E')->setAutoSize(true);
        $activeSheet->getColumnDimension('F')->setAutoSize(true);
        $activeSheet->getColumnDimension('G')->setAutoSize(true);
        $activeSheet->getColumnDimension('H')->setAutoSize(true);
        $activeSheet->getColumnDimension('I')->setAutoSize(true);
        $activeSheet->getColumnDimension('J')->setAutoSize(true);
        $activeSheet->getColumnDimension('K')->setAutoSize(true);
        $activeSheet->getColumnDimension('L')->setAutoSize(true);
        $activeSheet->getColumnDimension('M')->setAutoSize(true);
        $activeSheet->getColumnDimension('N')->setAutoSize(true);
        $activeSheet->getColumnDimension('O')->setAutoSize(true);
        $activeSheet->getColumnDimension('P')->setAutoSize(true);
        $activeSheet->getColumnDimension('Q')->setAutoSize(true);
        $activeSheet->getColumnDimension('R')->setAutoSize(true);
        $activeSheet->getColumnDimension('S')->setAutoSize(true);
        $activeSheet->getColumnDimension('T')->setAutoSize(true);
        $activeSheet->getColumnDimension('U')->setAutoSize(true);
        $activeSheet->getColumnDimension('V')->setAutoSize(true);
        $activeSheet->getColumnDimension('W')->setAutoSize(true);
        $activeSheet->getColumnDimension('X')->setAutoSize(true);
        $activeSheet->getColumnDimension('Y')->setAutoSize(true);
        $activeSheet->getColumnDimension('Z')->setAutoSize(true);
        $activeSheet->getColumnDimension('AA')->setAutoSize(true);
        $activeSheet->getColumnDimension('AB')->setAutoSize(true);
        $activeSheet->getColumnDimension('AC')->setAutoSize(true);
        $activeSheet->getColumnDimension('AD')->setAutoSize(true);
        $activeSheet->getColumnDimension('AE')->setAutoSize(true);
        $activeSheet->getColumnDimension('AF')->setAutoSize(true);
        $activeSheet->getColumnDimension('AG')->setAutoSize(true);
        $activeSheet->getColumnDimension('AH')->setAutoSize(true);
        $activeSheet->getColumnDimension('AI')->setAutoSize(true);
        $activeSheet->getColumnDimension('AJ')->setAutoSize(true);
        $activeSheet->getColumnDimension('AK')->setAutoSize(true);
        $activeSheet->getColumnDimension('AL')->setAutoSize(true);
        $activeSheet->getColumnDimension('AM')->setAutoSize(true);
        $activeSheet->getColumnDimension('AN')->setAutoSize(true);
        $activeSheet->getColumnDimension('AO')->setAutoSize(true);
        $activeSheet->getColumnDimension('AP')->setAutoSize(true);
        $activeSheet->getColumnDimension('AQ')->setAutoSize(true);
        $activeSheet->getColumnDimension('AR')->setAutoSize(true);
        $activeSheet->getColumnDimension('AS')->setAutoSize(true);
        $activeSheet->getColumnDimension('AT')->setAutoSize(true);
        $activeSheet->getColumnDimension('AU')->setAutoSize(true);
        $activeSheet->getColumnDimension('AV')->setAutoSize(true);
        $activeSheet->getColumnDimension('AW')->setAutoSize(true);
        $activeSheet->getColumnDimension('AX')->setAutoSize(true);
        $activeSheet->getColumnDimension('AY')->setAutoSize(true);
        $activeSheet->getColumnDimension('AZ')->setAutoSize(true);
        $activeSheet->getColumnDimension('BA')->setAutoSize(true);
        $activeSheet->getColumnDimension('BC')->setAutoSize(true);
        $activeSheet->getColumnDimension('BD')->setAutoSize(true);
        $num=3;
        $k=0;
        // $data = $data->withCount(['ditolak','diterima']);
        foreach($data->get() as $k => $i){
            $k++;
            
            $sum_tertunda = Kepesertaan::selectRaw('count(*) as total_qty,sum(kontribusi) as total_kontribusi,sum(basic) as total_basic')
                            ->where(['pengajuan_id'=>$i->id,'status_akseptasi'=>2])->first();

            $activeSheet
                ->setCellValue('A'.$num,$k)
                ->setCellValue('B'.$num,date('F',strtotime($i->created_at)))
                ->setCellValue('C'.$num,isset($i->account_manager->name) ? $i->account_manager->name : '-')
                ->setCellValue('D'.$num,'-')
                ->setCellValue('E'.$num,$i->id.'/'.date("m/Y",strtotime($i->created_at)))
                ->setCellValue('F'.$num,'-')
                ->setCellValue('G'.$num,date("d-m-Y",strtotime($i->created_at)))
                ->setCellValue('H'.$num,date("Y_m",strtotime($i->created_at)))
                ->setCellValue('I'.$num,"-")
                ->setCellValue('J'.$num,"-")
                ->setCellValue('K'.$num,$i->no_surat)
                ->setCellValue('L'.$num,$i->dn_number)
                ->setCellValue('M'.$num,isset($i->polis->no_polis) ? $i->polis->no_polis : '-')
                ->setCellValue('N'.$num,isset($i->polis->nama) ? $i->polis->nama : '-')
                ->setCellValue('O'.$num,isset($i->polis->alamat) ? $i->polis->alamat : '-')
                ->setCellValue('P'.$num,"-") // CABANG
                ->setCellValue('Q'.$num,isset($i->polis->produk->nama) ? $i->polis->produk->nama : '-')
                ->setCellValue('R'.$num,$sum_tertunda->total_qty) // JML KEPESERTAAN TERTUNDA
                ->setCellValue('S'.$num,$sum_tertunda->total_basic) // KONTRIBUSI KEPESERTAAN TERTUNDA
                ->setCellValue('T'.$num,$sum_tertunda->total_kontribusi)
                ->setCellValue('U'.$num,$i->total_approve) 
                ->setCellValue('V'.$num,$i->no_peserta_awal) 
                ->setCellValue('W'.$num,'s/d') 
                ->setCellValue('X'.$num,$i->no_peserta_akhir) 
                ->setCellValue('Y'.$num,"-") 
                ->setCellValue('Z'.$num,"-") 
                ->setCellValue('AA'.$num,$i->basic) 
                ->setCellValue('AB'.$num,$i->dana_tabbaru) 
                ->setCellValue('AC'.$num,$i->dana_ujrah) 
                ->setCellValue('AD'.$num,$i->kontribusi) 
                ->setCellValue('AE'.$num,$i->extra_kontribusi) 
                ->setCellValue('AF'.$num,$i->extra_mortalita) 
                ->setCellValue('AG'.$num,$i->net_kontribusi) 
                ->setCellValue('AH'.$num,$i->potong_langsung_persen) 
                ->setCellValue('AI'.$num,$i->potong_langsung) 
                ->setCellValue('AJ'.$num,"-") 
                ->setCellValue('AK'.$num,$i->polis->fee_base_brokerage) 
                ->setCellValue('AL'.$num,$i->brokerage_ujrah) 
                ->setCellValue('AM'.$num,$i->pph_persen) 
                ->setCellValue('AN'.$num,$i->pph) 
                ->setCellValue('AO'.$num,$i->ppn_persen) 
                ->setCellValue('AP'.$num,$i->ppn) 
                ->setCellValue('AQ'.$num,$i->biaya_polis_materai) 
                ->setCellValue('AR'.$num,$i->biaya_sertifikat) 
                ->setCellValue('AS'.$num,$i->net_kontribusi) 
                ->setCellValue('AT'.$num,terbilang($i->net_kontribusi)) 
                ->setCellValue('AU'.$num,'-') 
                ->setCellValue('AV'.$num,date('d-M-Y',strtotime($i->updated_at))) 
                ->setCellValue('AW'.$num,date('d-M-Y',strtotime($i->updated_at))) 
                ->setCellValue('AX'.$num,1) 
                ->setCellValue('AY'.$num,$i->head_syariah_submit ? date('d-M-Y',strtotime($i->head_syariah_submit)) : '-') 
                ->setCellValue('AZ'.$num,"-") 
                ->setCellValue('AA'.$num,"-") 
                ->setCellValue('BB'.$num,$i->tanggal_jatuh_tempo ? date('d-M-Y',strtotime($i->tanggal_jatuh_tempo)):'-') 
                ->setCellValue('BD'.$num,$i->payment_date ? date('d-M-Y',strtotime($i->payment_date)):'-');

                $activeSheet->getStyle("S{$num}:T{$num}")->getNumberFormat()->setFormatCode('#,##0');
                $activeSheet->getStyle("AA{$num}:AG{$num}")->getNumberFormat()->setFormatCode('#,##0');
                $activeSheet->getStyle("AL{$num}")->getNumberFormat()->setFormatCode('#,##0');
                $activeSheet->getStyle("AI{$num}:AI{$num}")->getNumberFormat()->setFormatCode('#,##0');
                $activeSheet->getStyle("AN{$num}")->getNumberFormat()->setFormatCode('#,##0');
                $activeSheet->getStyle("AS{$num}")->getNumberFormat()->setFormatCode('#,##0');
                $activeSheet->getStyle("AP{$num}:AR{$num}")->getNumberFormat()->setFormatCode('#,##0');
                
                $activeSheet->getStyle("K{$num}")->getNumberFormat()->setFormatCode('#');
                $activeSheet->getStyle("K{$num}")
                ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

                // if($i->extra_mortalita) $activeSheet->getStyle("L{$num}")->getNumberFormat()->setFormatCode('#,##0.00');
                // if($i->extra_kontribusi) $activeSheet->getStyle("M{$num}")->getNumberFormat()->setFormatCode('#,##0.00');

                // $activeSheet->getStyle("N{$num}")->getNumberFormat()->setFormatCode('#,##0.00');

            $activeSheet->getStyle("A{$num}:BC{$num}")->applyFromArray([
                'borders' => [
                    'top' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ]
                ],
            ]);
            $num++;
        }

        // Rename worksheet
        $activeSheet->setTitle('Pengajuan');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, "Xlsx");
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="pengajuan.xlsx"');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        return response()->streamDownload(function() use($writer){
            $writer->save('php://output');
        },'pengajuan-'.date('d-m-Y').'.xlsx');
    }


    public function downloadExcel($data,$status=1)
    {
        $data = Pengajuan::find($data);
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("PMT System")
                                    ->setLastModifiedBy("Stalavista System")
                                    ->setTitle("Office 2007 XLSX Product Database")
                                    ->setSubject("Daftar Peserta")
                                    ->setKeywords("office 2007 openxml php");

        $title = 'DAFTAR KEPESERTAAN ASURANSI JIWA KUMPULAN SYARIAH';
        if($status==1) $title = 'DAFTAR KEPESERTAAN ASURANSI JIWA KUMPULAN SYARIAH';
        if($status==2) $title = 'DAFTAR KEPESERTAAN TERTUNDA ASURANSI JIWA KUMPULAN SYARIAH';

        $activeSheet = $objPHPExcel->setActiveSheetIndex(0);
        $activeSheet->setCellValue('A1', $title);
        $activeSheet->mergeCells("A1:O1");
        $activeSheet->getRowDimension('1')->setRowHeight(34);
        $activeSheet->getStyle('A1')->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'font' => [
                'size' => 16,
                'bold' => true,
            ]
        ]);;

        $activeSheet->setCellValue('B4', 'DEBIT NOTE NUMBER')
                    ->setCellValue('C4', " : ".$data->dn_number)
                    ->setCellValue('B5', 'NOMOR POLIS')
                    ->setCellValue('C5', " : ".$data->polis->no_polis)
                    ->setCellValue('B6', 'PEMEGANG POLIS')
                    ->setCellValue('C6', ' : '.(isset($data->polis->nama) ? $data->polis->nama : '-'))
                    ->setCellValue('B7', 'PRODUK ASURANSI')
                    ->setCellValue('C7', ' : '.(isset($data->polis->produk->nama) ? $data->polis->produk->nama : '-'));

        $activeSheet->getStyle("B4:B7")->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
            ]);
        $activeSheet->getStyle("C4:C7")->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
            ]);

        if($status==1){
            $activeSheet
                    ->setCellValue('A8', 'NO')
                    ->setCellValue('B8', 'NO PESERTA')
                    ->setCellValue('C8', 'NAMA PESERTA')
                    ->setCellValue('D8', 'TGL. LAHIR')
                    ->setCellValue('E8', 'USIA')
                    ->setCellValue('F8', 'MULAI ASURANSI')
                    ->setCellValue('G8', 'AKHIR ASURANSI')
                    ->setCellValue('H8', 'NILAI MANFAAT ASURANSI')
                    ->setCellValue('I8', 'DANA TABBARU')
                    ->setCellValue('J8', 'DANA UJRAH')
                    ->setCellValue('K8', 'KONTRIBUSI')
                    ->setCellValue('L8', 'EXTRA MORTALITA')
                    ->setCellValue('M8', 'EXTRA KONTRIBUSI')
                    ->setCellValue('N8', 'TOTAL KONTRIBUSI')
                    ->setCellValue('O8', 'TGL STNC')
                    ->setCellValue('P8', 'UL')
                    ->setCellValue('Q8', 'KET');

            $activeSheet->getStyle("A8:Q8")->applyFromArray([
                        'font' => [
                            'bold' => true,
                        ],
                        'borders' => [
                            'top' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],
                            'bottom' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],
                        ],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                        ],
                    ]);
        }

        if($status==2){
            $activeSheet
                    ->setCellValue('A8', 'NO')
                    ->setCellValue('B8', 'NAMA PESERTA')
                    ->setCellValue('C8', 'TGL. LAHIR')
                    ->setCellValue('D8', 'USIA')
                    ->setCellValue('E8', 'MULAI ASURANSI')
                    ->setCellValue('F8', 'AKHIR ASURANSI')
                    ->setCellValue('G8', 'NILAI MANFAAT ASURANSI')
                    ->setCellValue('H8', 'DANA TABBARU')
                    ->setCellValue('I8', 'DANA UJRAH')
                    ->setCellValue('J8', 'KONTRIBUSI')
                    ->setCellValue('K8', 'EXTRA MORTALITA')
                    ->setCellValue('L8', 'EXTRA KONTRIBUSI')
                    ->setCellValue('M8', 'TOTAL KONTRIBUSI')
                    ->setCellValue('N8', 'TGL STNC')
                    ->setCellValue('O8', 'UL')
                    ->setCellValue('P8', 'KET')
                    ;
            $activeSheet->getStyle("A8:P8")->applyFromArray([
                        'font' => [
                            'bold' => true,
                        ],
                        'borders' => [
                            'top' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],
                            'bottom' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],
                        ],
                    ]);
        }

        if($status==3){
            $activeSheet
                    ->setCellValue('A8', 'NO')
                    ->setCellValue('B8', 'NO PESERTA')
                    ->setCellValue('C8', 'NAMA PESERTA')
                    ->setCellValue('D8', 'NO KTP')
                    ->setCellValue('E8', 'TGL. LAHIR')
                    ->setCellValue('F8', 'USIA')
                    ->setCellValue('G8', 'MULAI ASURANSI')
                    ->setCellValue('H8', 'AKHIR ASURANSI')
                    ->setCellValue('I8', 'NILAI MANFAAT ASURANSI')
                    ->setCellValue('J8', 'DANA TABBARU')
                    ->setCellValue('K8', 'DANA UJRAH')
                    ->setCellValue('L8', 'KONTRIBUSI')
                    ->setCellValue('M8', 'EXTRA MORTALITA')
                    ->setCellValue('N8', 'EXTRA KONTRIBUSI')
                    ->setCellValue('O8', 'TOTAL KONTRIBUSI')
                    ->setCellValue('P8', 'TGL STNC')
                    ->setCellValue('Q8', 'UL')
                    ->setCellValue('R8', 'KET');

            $activeSheet->getStyle("A8:R8")->applyFromArray([
                        'font' => [
                            'bold' => true,
                        ],
                        'borders' => [
                            'top' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],
                            'bottom' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                'color' => ['argb' => '000000'],
                            ],
                        ],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                        ],
                    ]);
        }

        $activeSheet->getColumnDimension('A')->setWidth(5);
        $activeSheet->getColumnDimension('B')->setAutoSize(true);
        $activeSheet->getColumnDimension('C')->setAutoSize(true);
        $activeSheet->getColumnDimension('D')->setAutoSize(true);
        $activeSheet->getColumnDimension('E')->setAutoSize(true);
        $activeSheet->getColumnDimension('F')->setAutoSize(true);
        $activeSheet->getColumnDimension('G')->setAutoSize(true);
        $activeSheet->getColumnDimension('H')->setAutoSize(true);
        $activeSheet->getColumnDimension('I')->setAutoSize(true);
        $activeSheet->getColumnDimension('J')->setAutoSize(true);
        $activeSheet->getColumnDimension('K')->setAutoSize(true);
        $activeSheet->getColumnDimension('L')->setAutoSize(true);
        $activeSheet->getColumnDimension('M')->setAutoSize(true);
        $activeSheet->getColumnDimension('N')->setAutoSize(true);
        $activeSheet->getColumnDimension('O')->setAutoSize(true);
        $activeSheet->getColumnDimension('P')->setAutoSize(true);
        $activeSheet->getColumnDimension('Q')->setAutoSize(true);
        $activeSheet->getColumnDimension('R')->setAutoSize(true);
        $num=9;

        if($status==1){
            $k=0;
            foreach($data->kepesertaan->where('status_akseptasi',$status) as $k => $i){
                $k++;
                $activeSheet
                    ->setCellValue('A'.$num,$k)
                    ->setCellValue('B'.$num,$i->no_peserta)
                    ->setCellValue('C'.$num,$i->nama)
                    ->setCellValue('D'.$num,$i->tanggal_lahir?date('d-M-Y',strtotime($i->tanggal_lahir)) : '-')
                    ->setCellValue('E'.$num,$i->usia)
                    ->setCellValue('F'.$num,$i->tanggal_mulai?date('d-M-Y',strtotime($i->tanggal_mulai)) : '-')
                    ->setCellValue('G'.$num,$i->tanggal_akhir?date('d-M-Y',strtotime($i->tanggal_akhir)) : '-')
                    ->setCellValue('H'.$num,$i->basic)
                    ->setCellValue('I'.$num,$i->dana_tabarru)
                    ->setCellValue('J'.$num,$i->dana_ujrah)
                    ->setCellValue('K'.$num,$i->kontribusi)
                    ->setCellValue('L'.$num,$i->extra_mortalita?$i->extra_mortalita:'-')
                    ->setCellValue('M'.$num,$i->extra_kontribusi?$i->extra_kontribusi:'-')
                    ->setCellValue('N'.$num,$i->extra_mortalita+$i->kontribusi+$i->extra_kontribusi)
                    ->setCellValue('O'.$num,$i->tanggal_stnc?date('d-M-Y',strtotime($i->tanggal_stnc)) : '-')
                    ->setCellValue('P'.$num,$i->ul)
                    ->setCellValue('Q'.$num,$i->reason_reject);

                    $activeSheet->getStyle("H{$num}:K{$num}")->getNumberFormat()->setFormatCode('#,##0.00');

                    if($i->extra_mortalita) $activeSheet->getStyle("L{$num}")->getNumberFormat()->setFormatCode('#,##0.00');
                    if($i->extra_kontribusi) $activeSheet->getStyle("M{$num}")->getNumberFormat()->setFormatCode('#,##0.00');

                    $activeSheet->getStyle("N{$num}")->getNumberFormat()->setFormatCode('#,##0.00');

                $activeSheet->getStyle("A{$num}:Q{$num}")->applyFromArray([
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ]
                    ],
                ]);
                $num++;
            }

            $total_basic = $data->kepesertaan->where('status_akseptasi',$status)->sum('basic');
            $total_dana_tabarru = $data->kepesertaan->where('status_akseptasi',$status)->sum('dana_tabarru');
            $total_dana_ujrah = $data->kepesertaan->where('status_akseptasi',$status)->sum('dana_ujrah');
            $total_kontribusi = $data->kepesertaan->where('status_akseptasi',$status)->sum('kontribusi');
            $total_em = $data->kepesertaan->where('status_akseptasi',$status)->sum('extra_mortalita');
            $total_ek = $data->kepesertaan->where('status_akseptasi',$status)->sum('extra_kontribusi');

            $activeSheet
                        ->setCellValue("B{$num}",'TOTAL')
                        ->setCellValue("H{$num}",$total_basic)
                        ->setCellValue("I{$num}",$total_dana_tabarru)
                        ->setCellValue("J{$num}",$total_dana_ujrah)
                        ->setCellValue("K{$num}",$total_kontribusi)
                        ->setCellValue("L{$num}",$total_em)
                        ->setCellValue("M{$num}",$total_ek)
                        ->setCellValue("N{$num}",$total_kontribusi+$total_em+$total_ek);
            $activeSheet->getStyle("H{$num}:N{$num}")->getNumberFormat()->setFormatCode('#,##0.00');
            $activeSheet->getStyle("A{$num}:Q{$num}")->applyFromArray([
                'borders' => [
                    'top' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
                'font' => [
                    'bold' => true,
                ],
            ]);
        }

        if($status==2){
            $k=0;
            foreach($data->kepesertaan->where('status_akseptasi',$status) as $i){
                $k++;
                $activeSheet
                    ->setCellValue('A'.$num,$k)
                    ->setCellValue('B'.$num,$i->nama)
                    ->setCellValue('C'.$num,$i->tanggal_lahir?date('d-M-Y',strtotime($i->tanggal_lahir)) : '-')
                    ->setCellValue('D'.$num,$i->usia)
                    ->setCellValue('E'.$num,$i->tanggal_mulai?date('d-M-Y',strtotime($i->tanggal_mulai)) : '-')
                    ->setCellValue('F'.$num,$i->tanggal_akhir?date('d-M-Y',strtotime($i->tanggal_akhir)) : '-')
                    ->setCellValue('G'.$num,$i->basic)
                    ->setCellValue('H'.$num,$i->dana_tabarru)
                    ->setCellValue('I'.$num,$i->dana_ujrah)
                    ->setCellValue('J'.$num,$i->kontribusi)
                    ->setCellValue('K'.$num,$i->extra_mortalita?$i->extra_mortalita:'-')
                    ->setCellValue('L'.$num,$i->extra_kontribusi?$i->extra_kontribusi:'-')
                    ->setCellValue('M'.$num,$i->extra_mortalita+$i->kontribusi+$i->extra_kontribusi)
                    ->setCellValue('N'.$num,$i->tanggal_stnc?date('d-M-Y',strtotime($i->tanggal_stnc)) : '-')
                    ->setCellValue('O'.$num,$i->ul)
                    ->setCellValue('P'.$num,$i->reason_reject);

                if($i->extra_mortalita) $activeSheet->getStyle("K{$num}")->getNumberFormat()->setFormatCode('#,##0.00');
                if($i->extra_kontribusi) $activeSheet->getStyle("L{$num}")->getNumberFormat()->setFormatCode('#,##0.00');

                $activeSheet->getStyle("G{$num}:J{$num}")->getNumberFormat()->setFormatCode('#,##0.00');
                $activeSheet->getStyle("M{$num}")->getNumberFormat()->setFormatCode('#,##0.00');

                $activeSheet->getStyle("A{$num}:P{$num}")->applyFromArray([
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                ]);

                $num++;
            }

            $total_basic = $data->kepesertaan->where('status_akseptasi',$status)->sum('basic');
            $total_dana_tabarru = $data->kepesertaan->where('status_akseptasi',$status)->sum('dana_tabarru');
            $total_dana_ujrah = $data->kepesertaan->where('status_akseptasi',$status)->sum('dana_ujrah');
            $total_kontribusi = $data->kepesertaan->where('status_akseptasi',$status)->sum('kontribusi');
            $total_em = $data->kepesertaan->where('status_akseptasi',$status)->sum('extra_mortalita');
            $total_ek = $data->kepesertaan->where('status_akseptasi',$status)->sum('extra_kontribusi');

            $activeSheet
                        ->setCellValue("B{$num}",'TOTAL')
                        ->setCellValue("G{$num}",$total_basic)
                        ->setCellValue("H{$num}",$total_dana_tabarru)
                        ->setCellValue("I{$num}",$total_dana_ujrah)
                        ->setCellValue("J{$num}",$total_kontribusi)
                        ->setCellValue("K{$num}",$total_em)
                        ->setCellValue("L{$num}",$total_ek)
                        ->setCellValue("M{$num}",$total_kontribusi+$total_em+$total_ek);
            $activeSheet->getStyle("G{$num}:M{$num}")->getNumberFormat()->setFormatCode('#,##0.00');
            $activeSheet->getStyle("A{$num}:P{$num}")->applyFromArray([
                'borders' => [
                    'top' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
                'font' => [
                    'bold' => true,
                ],
            ]);
        }

        if($status==3){
            $k=0;
            foreach($data->kepesertaan->where('status_akseptasi',1) as $k => $i){
                $k++;
                $activeSheet
                    ->setCellValue('A'.$num,$k)
                    ->setCellValue('B'.$num,$i->no_peserta)
                    ->setCellValue('C'.$num,$i->nama)
                    ->setCellValue('D'.$num,$i->no_ktp)
                    ->setCellValue('E'.$num,$i->tanggal_lahir?date('d-M-Y',strtotime($i->tanggal_lahir)) : '-')
                    ->setCellValue('F'.$num,$i->usia)
                    ->setCellValue('G'.$num,$i->tanggal_mulai?date('d-M-Y',strtotime($i->tanggal_mulai)) : '-')
                    ->setCellValue('H'.$num,$i->tanggal_akhir?date('d-M-Y',strtotime($i->tanggal_akhir)) : '-')
                    ->setCellValue('I'.$num,$i->basic)
                    ->setCellValue('J'.$num,$i->dana_tabarru)
                    ->setCellValue('K'.$num,$i->dana_ujrah)
                    ->setCellValue('L'.$num,$i->kontribusi)
                    ->setCellValue('M'.$num,$i->extra_mortalita?$i->extra_mortalita:'-')
                    ->setCellValue('N'.$num,$i->extra_kontribusi?$i->extra_kontribusi:'-')
                    ->setCellValue('O'.$num,$i->extra_mortalita+$i->kontribusi+$i->extra_kontribusi)
                    ->setCellValue('P'.$num,$i->tanggal_stnc?date('d-M-Y',strtotime($i->tanggal_stnc)) : '-')
                    ->setCellValue('Q'.$num,$i->ul)
                    ->setCellValue('R'.$num,$i->reason_reject);

                    $activeSheet->getStyle("I{$num}:L{$num}")->getNumberFormat()->setFormatCode('#,##0.00');

                    if($i->extra_mortalita) $activeSheet->getStyle("M{$num}")->getNumberFormat()->setFormatCode('#,##0.00');
                    if($i->extra_kontribusi) $activeSheet->getStyle("N{$num}")->getNumberFormat()->setFormatCode('#,##0.00');

                    $activeSheet->getStyle("N{$num}")->getNumberFormat()->setFormatCode('#,##0.00');

                $activeSheet->getStyle("A{$num}:R{$num}")->applyFromArray([
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ]
                    ],
                ]);
                $num++;
            }

            $total_basic = $data->kepesertaan->where('status_akseptasi',$status)->sum('basic');
            $total_dana_tabarru = $data->kepesertaan->where('status_akseptasi',$status)->sum('dana_tabarru');
            $total_dana_ujrah = $data->kepesertaan->where('status_akseptasi',$status)->sum('dana_ujrah');
            $total_kontribusi = $data->kepesertaan->where('status_akseptasi',$status)->sum('kontribusi');
            $total_em = $data->kepesertaan->where('status_akseptasi',$status)->sum('extra_mortalita');
            $total_ek = $data->kepesertaan->where('status_akseptasi',$status)->sum('extra_kontribusi');

            $activeSheet
                        ->setCellValue("C{$num}",'TOTAL')
                        ->setCellValue("I{$num}",$total_basic)
                        ->setCellValue("J{$num}",$total_dana_tabarru)
                        ->setCellValue("K{$num}",$total_dana_ujrah)
                        ->setCellValue("L{$num}",$total_kontribusi)
                        ->setCellValue("M{$num}",$total_em)
                        ->setCellValue("N{$num}",$total_ek)
                        ->setCellValue("O{$num}",$total_kontribusi+$total_em+$total_ek);
            $activeSheet->getStyle("I{$num}:O{$num}")->getNumberFormat()->setFormatCode('#,##0.00');
            $activeSheet->getStyle("A{$num}:R{$num}")->applyFromArray([
                'borders' => [
                    'top' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                    'bottom' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['argb' => '000000'],
                    ],
                ],
                'font' => [
                    'bold' => true,
                ],
            ]);
        }

        $num++;
        $num++;
        $num++;
        if($status==1){
            $activeSheet
                ->setCellValue('N'.$num,"Jakarta, ".date('d F Y'))
                ->setCellValue('N'.($num+4),"Underwriting Syariah");

            $activeSheet->getStyle("N".$num)->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
            $activeSheet->getStyle("N".($num+4))->applyFromArray([
                    'borders' => [
                        'bottom' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'font'=>[
                        'bold'=>true
                    ]
                ]);
            }else{
                $activeSheet
                        ->setCellValue('K'.$num,"Jakarta, ".date('d F Y'))
                        ->setCellValue('K'.($num+4),"Underwriting Syariah");

                    $activeSheet->getStyle("K".$num)->applyFromArray([
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            ],
                        ]);
                    $activeSheet->getStyle("K".($num+4))->applyFromArray([
                            'borders' => [
                                'bottom' => [
                                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                                    'color' => ['argb' => '000000'],
                                ],
                            ],
                            'alignment' => [
                                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                            ],
                            'font'=>[
                                'bold'=>true
                            ]
                        ]);
            }

        // Rename worksheet
        $activeSheet->setTitle('Pengajuan');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, "Xlsx");

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$data->no_pengajuan.'.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        //header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        return response()->streamDownload(function() use($writer){
            $writer->save('php://output');
        },$data->no_pengajuan.'.xlsx');
    }
}
