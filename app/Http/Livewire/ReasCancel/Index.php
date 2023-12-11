<?php

namespace App\Http\Livewire\ReasCancel;

use Livewire\Component;
use App\Models\ReasCancel;
use App\Models\Kepesertaan;
use App\Models\Polis;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $selected_id,$check_id=[],$filter_polis_id,$is_download=false,$check_all,$polis;
    public function render()
    {
        $data = $this->data();

        return view('livewire.reas-cancel.index')->with(['data'=>$data->paginate(100)]);
    }
    
    public function mount()
    {
        $this->polis = Polis::select('polis.*')
                            ->join('reas_cancel','reas_cancel.polis_id','=','polis.id')
                            ->groupBy('polis.id')
                            ->get();
    }

    public function delete()
    {
        ReasCancel::find($this->selected_id)->delete();
        Kepesertaan::where('reas_cancel_id',$this->selected_id)->update(['reas_cancel_id'=>null]);

        $this->emit('message-success','Reas berhasil dihapus');$this->emit('modal','hide');
    }
    
    public function data()
    {
        $data = ReasCancel::orderBy('id','DESC');
        
        if($this->filter_polis_id) $data->where('polis_id',$this->filter_polis_id);

        return $data;
    }

    public function checked_all()
    {
        if($this->check_all==1){
            foreach($this->data()->get() as $item){
                $this->check_id[$item->id] = $item->id;
            }
        }
        if($this->check_all==0){
            $this->check_id = [];
        }
    }

    public function downloadExcel()
    {
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Entigi System")
                                    ->setLastModifiedBy("Stalavista System")
                                    ->setTitle("Office 2007 XLSX Product Database")
                                    ->setSubject("Daftar Peserta")
                                    ->setKeywords("office 2007 openxml php");

        $activeSheet = $objPHPExcel->setActiveSheetIndex(0);
        $activeSheet->setCellValue('A2', 'DAFTAR PEMBATALAN KEPESERTAAN ASURANSI JIWA KUMPULAN SYARIAH');
        $activeSheet->mergeCells("A2:L2");

        $activeSheet->getRowDimension('2')->setRowHeight(34);
        $activeSheet->getStyle('A2')->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'font' => [
                'size' => 16,
                'bold' => true,
            ]
        ]);

        $activeSheet->getStyle('A3')->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'font' => [
                'size' => 16,
                'bold' => true,
            ]
        ]);;
        $polis = Polis::find($this->filter_polis_id);
        $activeSheet->setCellValue('A4', 'NOMOR POLIS')
                    ->setCellValue('C4', " : {$polis->no_polis}")
                    ->setCellValue('A5', 'PEMEGANG POLIS')
                    ->setCellValue('C5', " : {$polis->no_polis}")
                    ->setCellValue('A6', 'PRODUK ASURANSI')
                    ->setCellValue('C6', " : {$polis->produk->NAMA}")
                    ->setCellValue('A7', 'CARA PEMBAYARAN KONSTRIBUSI')
                    ->setCellValue('C7', " : SEKALIGUS");
        
        $activeSheet
                ->setCellValue('A9', 'NO')
                ->setCellValue('B9', 'NOMOR')
                ->setCellValue('C9', 'NAMA PESERTA')
                ->setCellValue('D9', 'TGL LAHIR')
                ->setCellValue('E9', 'USIA')
                ->setCellValue('F9', 'MULAI ASURANSI')
                ->setCellValue('G9', 'AKHIR ASURANSI')
                ->setCellValue('H9', 'NILAI MANFAAT ASURANSI')
                ->setCellValue('I9', 'TOTAL KONTRIBUSI')
                ->setCellValue('J9', 'PENGEMBALIAN KONTRIBUSI')
                ->setCellValue('K9', 'PENGEMBALIAN NETTO KONTRIBUSI')
                ->setCellValue('L9', 'UW LIMIT')
                ->setCellValue('M9', 'KETERANGAN');
        
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

        // $activeSheet->getColumnDimension('A')->setWidth(5.43);
            
        $activeSheet->getStyle("A9:O9")->applyFromArray([
                    'borders' => [
                        'top' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                        'left' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '000000'],
                        ],
                        'right' => [
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

        $data = Kepesertaan::where('polis_id',$this->filter_polis_id)->whereIn('reas_cancel_id',$this->check_id)->get();
        $num=10;
        $total_manfaat=0;$total_kontribusi=0;$total_net_kontribusi=0;
        $tanggal_pengajuan = date('Y-m-d');
        foreach($data as $k=>$i){
            $activeSheet
                ->setCellValue("A{$num}", $k+1)
                ->setCellValue("B{$num}", $i->no_peserta)
                ->setCellValue("C{$num}", $i->nama)
                ->setCellValue("D{$num}", date('d-M-Y',strtotime($i->tanggal_lahir)))
                ->setCellValue("E{$num}", $i->usia)
                ->setCellValue("F{$num}", date('d-M-Y',strtotime($i->tanggal_mulai)))
                ->setCellValue("G{$num}", date('d-M-Y',strtotime($i->tanggal_akhir)))
                ->setCellValue("H{$num}", $i->basic)
                ->setCellValue("I{$num}", $i->kontribusi)
                ->setCellValue("J{$num}", $i->kontribusi)
                ->setCellValue("K{$num}", $i->total_kontribusi_dibayar)
                ->setCellValue("L{$num}", $i->ul)
                ->setCellValue("M{$num}", $i->keterangan);

            $activeSheet->getStyle("H{$num}:K{$num}")->getNumberFormat()->setFormatCode('#,##0');

            $total_manfaat += $i->basic;
            $total_kontribusi += $i->kontribusi;
            $total_net_kontribusi += $i->total_kontribusi_dibayar;
                
            $num++;
        }

        $num++;
        $activeSheet->getStyle("A{$num}:M{$num}")->applyFromArray([
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
            ]
        ]);

        $activeSheet
        ->setCellValue("H{$num}", $total_manfaat)
        ->setCellValue("I{$num}", $total_kontribusi)
        ->setCellValue("J{$num}", $total_kontribusi)
        ->setCellValue("K{$num}", $total_net_kontribusi);
        $activeSheet->getStyle("H{$num}:K{$num}")->getNumberFormat()->setFormatCode('#,##0');
        $num += 4;
        $activeSheet->mergeCells("H{$num}:M{$num}");
        $activeSheet->mergeCells("H".($num+6).":M".($num+6)."");
        $activeSheet
            ->setCellValue("H{$num}", 'Jakarta, '.date('d F Y',strtotime($tanggal_pengajuan)))
            ->setCellValue("H".($num+6), "Underwriting");
        
        $activeSheet->getStyle("H".($num+1).":H".($num+2))->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
            'font' => [
                'bold' => true,
            ]
        ]);
       
        $activeSheet->getStyle("H{$num}:H".($num+6))->applyFromArray([
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER
            ],
        ]);
        // Rename worksheet
        // $activeSheet->setTitle('Pengajuan');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, "Xlsx");

        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.date('d-F-Y').'.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        //header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $this->filter_polis_id='';$this->is_download=false;$this->check_id = [];$this->check_all=0;
        return response()->streamDownload(function() use($writer){
            $writer->save('php://output');
        },date('d-F-Y').'.xlsx');
    }
}
