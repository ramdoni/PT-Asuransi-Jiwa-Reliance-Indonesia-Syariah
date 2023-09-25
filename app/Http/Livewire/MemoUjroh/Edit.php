<?php

namespace App\Http\Livewire\MemoUjroh;

use Livewire\Component;
use App\Models\MemoUjroh;
use App\Models\Pengajuan;
use App\Models\Polis;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

class Edit extends Component
{
    public $data,$total_kontribusi,$total_net_kontribusi;
    public $pengajuan,$polis,$pengajuan_arr=[];
    public function render()
    {
        return view('livewire.memo-ujroh.edit');
    }

    public function mount($id)
    {
        $this->data = MemoUjroh::find($id);
        $this->pengajuan = Pengajuan::where('memo_ujroh_id',$this->data->id)->get();
        $this->polis = Polis::find($this->data->polis_id);
        //$this->reload();
    }

    public function reload()
    {
        $polis = Polis::find($this->data->polis_id);
        
        $this->data->perkalian_biaya_penutupan = $polis->perkalian_biaya_penutupan;
        $this->data->maintenance = $polis->maintenance;
        $this->data->maintenance_penerima = $polis->maintenance_penerima;
        $this->data->maintenance_nama_bank = $polis->maintenance_nama_bank;
        $this->data->maintenance_no_rekening = $polis->maintenance_no_rekening;

        $this->data->admin_agency = $polis->admin_agency;
        $this->data->admin_agency_penerima = $polis->admin_agency_penerima;
        $this->data->admin_agency_nama_bank = $polis->admin_agency_nama_bank;
        $this->data->admin_agency_no_rekening = $polis->admin_agency_no_rekening;

        $this->data->agen_penutup = $polis->agen_penutup;
        $this->data->agen_penutup_penerima = $polis->agen_penutup_penerima;
        $this->data->agen_penutup_nama_bank = $polis->agen_penutup_nama_bank;
        $this->data->agen_penutup_no_rekening = $polis->agen_penutup_no_rekening;

        $this->data->ujroh_handling_fee_broker = $polis->ujroh_handling_fee_broker;
        $this->data->ujroh_handling_fee_broker_penerima = $polis->ujroh_handling_fee_broker_penerima;
        $this->data->ujroh_handling_fee_broker_nama_bank = $polis->ujroh_handling_fee_broker_nama_bank;
        $this->data->ujroh_handling_fee_broker_no_rekening = $polis->ujroh_handling_fee_broker_no_rekening;
        
        $this->data->referal_fee = $polis->referal_fee;
        $this->data->referal_fee_penerima = $polis->referal_fee_penerima;
        $this->data->referal_fee_nama_bank = $polis->referal_fee_nama_bank;
        $this->data->referal_fee_no_rekening = $polis->referal_fee_no_rekening;
        
        $total_maintenance = 0;$total_agen_penutup=0;$total_admin_agency=0;$total_ujroh_handling_fee_broker=0;$total_referal_fee=0;
        
        $this->data->maintenance = str_replace(',','.',$this->data->maintenance);
        $this->data->agen_penutup = str_replace(',','.',$this->data->agen_penutup);
        $this->data->admin_agency = str_replace(',','.',$this->data->admin_agency);
        $this->data->ujroh_handling_fee_broker = str_replace(',','.',$this->data->ujroh_handling_fee_broker);
        $this->data->referal_fee = str_replace(',','.',$this->data->referal_fee);

        $kontribusi_nett = 0;
        $this->pengajuan_arr = [];
        foreach($this->pengajuan as $k => $item){

            $this->pengajuan_arr[$k]['id'] = $item->id;
            $kontribusi = Pengajuan::join('kepesertaan','kepesertaan.pengajuan_id','=','pengajuan.id')
                                        ->where('pengajuan.id',$item->id)
                                        ->where('kepesertaan.status_akseptasi',1)
                                        ->sum('kepesertaan.kontribusi');
            
            $item->kontribusi = $kontribusi;
            
            $this->pengajuan_arr[$k]['kontribusi_gross'] = $kontribusi;
            $this->pengajuan_arr[$k]['kontribusi_net'] = ($kontribusi - $item->potong_langsung - $item->brokerage_ujrah);

            $kontribusi_nett += ($kontribusi - $item->potong_langsung - $item->brokerage_ujrah);

            if($this->data->perkalian_biaya_penutupan !='Kontribusi Gross')
                $kontribusi = $kontribusi - $item->potong_langsung - $item->brokerage_ujrah;
            
            $maintenance = ($this->data->maintenance>0 and $kontribusi>0)?($kontribusi *($this->data->maintenance/100)):0;
            $agen_penutup = ($this->data->agen_penutup>0 and $kontribusi>0)?($kontribusi *($this->data->agen_penutup/100)):0;
            $admin_agency = ($this->data->admin_agency>0 and $item->kontribusi>0)?($kontribusi *($this->data->admin_agency/100)):0;
            $ujroh_handling_fee_broker = ($this->data->ujroh_handling_fee_broker>0 and $kontribusi>0)?($kontribusi *($this->data->ujroh_handling_fee_broker/100)):0;
            $referal_fee = ($this->data->referal_fee>0 and $kontribusi>0)?($kontribusi *($this->data->referal_fee/100)):0;

            $item->maintenance  = $maintenance;
            $item->agen_penutup = $agen_penutup;
            $item->admin_agency = $admin_agency;
            $item->ujroh_handling_fee_broker = $ujroh_handling_fee_broker;
            $item->referal_fee = $referal_fee;
            $item->save();

            $this->pengajuan_arr[$k]['potong_langsung'] = $item->potong_langsung;
            $this->pengajuan_arr[$k]['payment_date'] = $item->payment_date;
            $this->pengajuan_arr[$k]['kontribusi'] = $kontribusi;
            $this->pengajuan_arr[$k]['dn_number'] = $item->dn_number;
            $this->pengajuan_arr[$k]['maintenance'] = $maintenance;
            $this->pengajuan_arr[$k]['agen_penutup'] = $agen_penutup;
            $this->pengajuan_arr[$k]['admin_agency'] = $admin_agency;
            $this->pengajuan_arr[$k]['ujroh_handling_fee_broker'] = $ujroh_handling_fee_broker;
            $this->pengajuan_arr[$k]['referal_fee'] = $referal_fee;

            $total_maintenance += $maintenance;    
            $total_agen_penutup += $agen_penutup;    
            $total_admin_agency += $admin_agency;    
            $total_ujroh_handling_fee_broker += $ujroh_handling_fee_broker;    
            $total_referal_fee += $referal_fee; 
        }

        $this->data->total_maintenance = $total_maintenance;
        $this->data->total_agen_penutup = $total_agen_penutup;
        $this->data->total_admin_agency = $total_admin_agency;
        $this->data->total_ujroh_handling_fee_broker = $total_ujroh_handling_fee_broker;
        $this->data->total_referal_fee = $total_referal_fee;
        $this->data->total_kontribusi_gross = Pengajuan::join('kepesertaan','kepesertaan.pengajuan_id','=','pengajuan.id')
                                                ->where('memo_ujroh_id',$this->data->id)
                                                ->where('kepesertaan.status_akseptasi',1)
                                                ->sum('kepesertaan.kontribusi');
        $this->data->total_kontribusi_nett = $kontribusi_nett;
        $this->data->save();
    }

    public function submit_underwriting()
    {
        \LogActivity::add("[web][Memo Ujroh][{$this->data->nomor}] Submit Underwriting");

        $this->emit('message-success','Memo Ujroh berhasil disubmit');

        $this->data->status = 1;
        $this->data->save();
    }

    public function submit_head_teknik()
    {
        \LogActivity::add("[web][Memo Ujroh][{$this->data->nomor}] Submit Head Teknik");

        $this->emit('message-success','Memo Ujroh berhasil disubmit');

        $this->data->status = 2; 
        $this->data->save();
    }

    public function submit_head_syariah()
    {
        \LogActivity::add("[web][Memo Ujroh][{$this->data->nomor}] Submit Head Syariah");

        $this->emit('message-success','Memo Ujroh berhasil disubmit');

        $this->data->status = 3; 
        $this->data->save();
    }

    public function downloadExcel()
    {
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("PMT System")
                                    ->setLastModifiedBy("Entigi System")
                                    ->setTitle("Office 2007 XLSX Product Database")
                                    //->setSubject("Daftar Peserta")
                                    ->setKeywords("office 2007 openxml php");

        $activeSheet = $objPHPExcel->setActiveSheetIndex(0);
        $activeSheet->setCellValue('A1', 'INTERNAL MEMO');
        $activeSheet->mergeCells("A1:K2");
        $activeSheet->getRowDimension('1')->setRowHeight(34);
        $activeSheet->getStyle('A1')->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'font' => [
                'size' => 24,
                'bold' => true,
            ]
        ]);;

        $activeSheet->setCellValue('A5', 'Kepada')
                    ->setCellValue('B5', " : Dept. Finance Syariah")
                    ->setCellValue('A6', 'Dari')
                    ->setCellValue('B6', ' : Admin Marketing')
                    ->setCellValue('A7', 'Tanggal')
                    ->setCellValue('B7', ' : '.date('d-M-Y',strtotime($this->data->tanggal_pengajuan)))
                    ->setCellValue('A8', 'No')
                    ->setCellValue('B8', " : {$this->data->nomor}")
                    ->setCellValue('A9', 'Perihal')
                    ->setCellValue('B9', " : Permohonan Pembayaran Biaya Penutupan")
                    ->setCellValue('A10', 'Pemegang Polis')
                    ->setCellValue('B10', " : {$this->polis->nama}");

        $activeSheet->setCellValue('A14', 'Assalamu`alaikum Wr. Wb')
                    ->setCellValue('A15', 'Dengan Hormat,')
                    ->setCellValue('A17', 'Sehubungan dengan Pembayaran Kontribusi yang telah diterima, mohon dapat dilakukan pembayaran Biaya Penutupan dengan data sebagai berikut : ');

        $activeSheet->setCellValue('A20', 'Ket.')
                    ->setCellValue('B20', 'Perkalian Biaya Penutupan')
                    ->setCellValue('C20', 'Penerima Pembayaran')
                    ->setCellValue('D20', 'Nama Bank')
                    ->setCellValue('E20', 'No. Rekening');
        $activeSheet->getStyle("A20:I20")->applyFromArray([
                        'font' => [
                            'bold' => true,
                        ],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                        ],
                    ]);
        
        $activeSheet->getStyle('A20:E24')
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN)
                    ->setColor(new Color('000000'));

        $activeSheet->getRowDimension('20')->setRowHeight(26);

        $activeSheet->setCellValue('A21', 'MAINTENANCE')
                    ->setCellValue('B21', 'Kontribusi Gross')
                    ->setCellValue('C21', $this->data->maintenance_penerima)
                    ->setCellValue('D21', $this->data->maintenance_nama_bank)
                    ->setCellValue('E21', $this->data->maintenance_no_rekening);
        
        $activeSheet->setCellValue('A22', 'AGEN PENUTUP')
                    ->setCellValue('B22', 'Kontribusi Gross')
                    ->setCellValue('C22', $this->data->agen_penutup_penerima)
                    ->setCellValue('D22', $this->data->agen_penutup_nama_bank)
                    ->setCellValue('E22', $this->data->agen_penutup_no_rekening);

        $activeSheet->setCellValue('A23', 'AGEN AGENCY')
                    ->setCellValue('B23', 'Kontribusi Gross')
                    ->setCellValue('C23', $this->data->admin_agency_penerima)
                    ->setCellValue('D23', $this->data->admin_agency_nama_bank)
                    ->setCellValue('E23', $this->data->admin_agency_no_rekening);

        $activeSheet->setCellValue('A24', 'UJROH (Handling Fee ) BROKER')
                    ->setCellValue('B24', 'Kontribusi Gross')
                    ->setCellValue('C24', $this->data->ujroh_handling_fee_broker_penerima)
                    ->setCellValue('D24', $this->data->ujroh_handling_fee_broker_nama_bank)
                    ->setCellValue('E24', $this->data->ujroh_handling_fee_broker_no_rekening);

        $activeSheet->setCellValue('A24', 'UJROH (Handling Fee ) BROKER')
                    ->setCellValue('B24', 'Kontribusi Gross')
                    ->setCellValue('C24', $this->data->referal_fee_penerima)
                    ->setCellValue('D24', $this->data->referal_fee_nama_bank)
                    ->setCellValue('E24', $this->data->referal_fee_no_rekening);
    
        $activeSheet->getColumnDimension('A')->setWidth(50);
        $activeSheet->getColumnDimension('B')->setWidth(35);
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
        
        $activeSheet->getStyle("A28:K28")->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                ],
            ]);
        $activeSheet->getStyle("A29:K29")->applyFromArray([
                'font' => [
                    'bold' => true,
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
                ],
            ]);

        $activeSheet->getStyle('A28:K28')
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
                    ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('000000'));
        $activeSheet->getStyle('A29:K29')
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
                    ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('000000'));

        $activeSheet->setCellValue('A28', 'No Polis')
                    ->setCellValue('B28', 'Pemegang Polis')
                    ->setCellValue('C28', 'No Debit Note')
                    ->setCellValue('D28', 'Kontribusi Gross')
                    ->setCellValue('E28', 'Kontribusi Nett')
                    ->setCellValue('F28', 'Tanggal Bayar')
                    ->setCellValue('G28', 'Maintenance')
                    ->setCellValue('G29', ($this->data->maintenance?$this->data->maintenance:0) ."%")
                    ->setCellValue('H28', 'Agen Penutup')
                    ->setCellValue('H29', ($this->data->agen_penutup?$this->data->agen_penutup:0) ."%")
                    ->setCellValue('I28', 'Admin Agency')
                    ->setCellValue('I29', ($this->data->admin_agency?$this->data->admin_agency:0) ."%")
                    ->setCellValue('J28', 'Ujroh (Handling Fee ) Broker')
                    ->setCellValue('J29', ($this->data->ujroh_handling_fee_broker?$this->data->ujroh_handling_fee_broker:0) ."%")
                    ->setCellValue('K28', 'Referal Fee')
                    ->setCellValue('K29', ($this->data->referal_fee?$this->data->referal_fee:0) ."%");

        $activeSheet->mergeCells("A28:A29");
        $activeSheet->mergeCells("B28:B29");
        $activeSheet->mergeCells("C28:C29");
        $activeSheet->mergeCells("D28:D29");
        $activeSheet->mergeCells("E28:E29");
        $activeSheet->mergeCells("F28:F29");

        $num=30;
        foreach($this->pengajuan as $item){
            $activeSheet->setCellValue('A'.$num, $item->polis->no_polis)
                ->setCellValue('B'.$num, $item->polis->nama)
                ->setCellValue('C'.$num, $item->dn_number)
                ->setCellValue('D'.$num, $item->kontribusi)
                ->setCellValue('E'.$num, $item->net_kontribusi)
                ->setCellValue('F'.$num, ($item->tanggal_bayar ? date('d-M-Y',strtotime($item->tanggal_bayar)) : '-'))
                ->setCellValue('G'.$num, $item->maintenance)
                ->setCellValue('H'.$num, $item->agen_penutup)
                ->setCellValue('I'.$num, $item->admin_agency)
                ->setCellValue('J'.$num, $item->ujroh_handling_fee_broker)
                ->setCellValue('K'.$num, $item->referal_fee);
            $activeSheet->getStyle("A{$num}:K{$num}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
                    ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('000000'));
            $activeSheet->getStyle("D{$num}:E{$num}")->getNumberFormat()->setFormatCode('#,##0.00');
            $activeSheet->getStyle("G{$num}:K{$num}")->getNumberFormat()->setFormatCode('#,##0.00');

            $num++;
        }

        $num++;
        $activeSheet->getStyle("A{$num}:K{$num}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
                    ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('000000'));
        $activeSheet->setCellValue('A'.$num, "TOTAL")
                    ->setCellValue('D'.$num, $this->data->total_kontribusi_gross)
                    ->setCellValue('E'.$num, $this->data->total_kontribusi_nett)
                    ->setCellValue('G'.$num, $this->data->total_maintenance)
                    ->setCellValue('H'.$num, $this->data->total_agen_penutup)
                    ->setCellValue('I'.$num, $this->data->total_admin_agency)
                    ->setCellValue('J'.$num, $this->data->total_ujroh_handling_fee_broker)
                    ->setCellValue('K'.$num, $this->data->total_referal_fee);
        $activeSheet->getStyle("D{$num}:E{$num}")->getNumberFormat()->setFormatCode('#,##0.00');
        $activeSheet->getStyle("G{$num}:K{$num}")->getNumberFormat()->setFormatCode('#,##0.00');
        
        
        $activeSheet->getStyle("A{$num}:K{$num}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
                    ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('000000'));
        $activeSheet->getStyle("A{$num}:K{$num}")->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);
        $num++;
        
        $activeSheet->setCellValue('A'.$num, "SUBTOTAL");
        $activeSheet->getStyle("A{$num}:K{$num}")
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN)
                    ->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('000000'));
        $activeSheet->getStyle("A{$num}:K{$num}")->applyFromArray([
            'font' => [
                'bold' => true,
            ],
        ]);
        $activeSheet->mergeCells("G{$num}:K{$num}");
        $activeSheet->setCellValue('G'.$num, 
            $this->data->total_maintenance + 
            $this->data->total_agen_penutup + 
            $this->data->total_admin_agency + 
            $this->data->total_ujroh_handling_fee_broker + 
            $this->data->total_referal_fee
        );
        $activeSheet->getStyle("G{$num}")->getNumberFormat()->setFormatCode('#,##0.00');


        $num = $num + 3;
        $activeSheet->setCellValue('A'.$num,"Data tersebut sudah sesuai dengan data di Underwriting Syariah");
        $num = $num + 2;
        $activeSheet->setCellValue('A'.$num,"Demikian disampaikan, atas perhatian dan kerjasamanya diucapkan terima kasih.");$num++;
        $activeSheet->setCellValue('A'.$num,"Wassalamu`alaikum Wr. Wb.");$num = $num+4;
        $activeSheet->setCellValue("A{$num}","Mengajukan")
        ->setCellValue("C{$num}","Mengetahui")
        ->setCellValue("E{$num}","Mengetahui")
        ->setCellValue("H{$num}","Mengetahui")
        ->setCellValue("K{$num}","Diterima Oleh,"); $num = $num + 6;

        $activeSheet->setCellValue("A{$num}","Estikomah")
        ->setCellValue("C{$num}","Sutarto")
        ->setCellValue("E{$num}","Ahmad Syafei")
        ->setCellValue("H{$num}","Budi Dharma Sadewa");
        $activeSheet->getStyle("A{$num}")->applyFromArray([
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],        
            ],
        ]);
        $activeSheet->getStyle("C{$num}")->applyFromArray([
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],        
            ],
        ]);
        $activeSheet->getStyle("E{$num}")->applyFromArray([
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],        
            ],
        ]);
        $activeSheet->getStyle("H{$num}")->applyFromArray([
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],        
            ],
        ]);
        $num++;
        $activeSheet->setCellValue("A{$num}","Admin")
        ->setCellValue("C{$num}","Marketing")
        ->setCellValue("E{$num}","Teknik Syariah")
        ->setCellValue("H{$num}","General Manager")
        ->setCellValue("K{$num}","Dept. Finance");

        // Rename worksheet
        // $activeSheet->setTitle('Pengajuan');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, "Xlsx");

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="internal-memo'.$this->data->id.'.xlsx"');
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
        },'internal-memo'.$this->data->id.'.xlsx');

    }
}
