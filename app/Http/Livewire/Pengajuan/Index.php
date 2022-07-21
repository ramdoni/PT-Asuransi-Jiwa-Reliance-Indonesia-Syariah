<?php

namespace App\Http\Livewire\Pengajuan;

use Livewire\Component;
use App\Models\Pengajuan;

class Index extends Component
{
    public function render()
    {
        $data = Pengajuan::with('polis')->orderBy('id','DESC');
        
        return view('livewire.pengajuan.index')->with(['data'=>$data->paginate(100)]);
    }

    public function downloadExcel(Pengajuan $data,$status=1)
    {
        $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("PMT System")
                                    ->setLastModifiedBy("Stalavista System")
                                    ->setTitle("Office 2007 XLSX Product Database")
                                    ->setSubject("Daftar Peserta")
                                    // ->setDescription("Health Check")
                                    ->setKeywords("office 2007 openxml php")
                                    // ->setCategory("Health Check")
                                    ;

        $title = 'DAFTAR KEPESERTAAN ASURANSI JIWA KUMPULAN SYARIAH';
        if($status==1) $title = 'DAFTAR KEPESERTAAN ASURANSI JIWA KUMPULAN SYARIAH';
        if($status==2) $title = 'DAFTAR KEPESERTAAN TERTUNDA ASURANSI JIWA KUMPULAN SYARIAH';
        
        $activeSheet = $objPHPExcel->setActiveSheetIndex(0);
        // $activeSheet->getStyle('A1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('689a3b');
        $activeSheet->setCellValue('A1', $title);
        $activeSheet->mergeCells("A1:O1");
        $activeSheet->getRowDimension('1')->setRowHeight(34);
        $activeSheet->getStyle('B1')->getFont()->setSize(16);
        $activeSheet->getStyle('B1')->getAlignment()->setWrapText(false);

        $activeSheet->setCellValue('B4', 'DEBIT NOTE NUMBER')
                    ->setCellValue('C4', "'".$data->dn_number)

                    ->setCellValue('B5', 'NOMOR POLIS')
                    ->setCellValue('C5', "'".$data->polis->no_polis)

                    ->setCellValue('B6', 'PEMEGANG POLIS')
                    ->setCellValue('C6', isset($data->polis->nama) ? $data->polis->nama : '-')

                    ->setCellValue('B7', 'PRODUK ASURANSI')
                    ->setCellValue('C7', isset($data->polis->produk->nama) ? $data->polis->produk->nama : '-');

        
        if($status==1){
            $activeSheet->getStyle('A8:O8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('c2d7f3');
            $activeSheet
                    ->setCellValue('A8', 'NO')
                    ->setCellValue('B8', 'KET')
                    ->setCellValue('C8', 'NO PESERTA')
                    ->setCellValue('D8', 'NAMA PESERTA')
                    ->setCellValue('E8', 'TGL. LAHIR')
                    ->setCellValue('F8', 'USIA')
                    ->setCellValue('G8', 'MULAI ASURANSI')
                    ->setCellValue('H8', 'AKHIR ASURANSI')
                    ->setCellValue('I8', 'NILAI MANFAAT ASURANSI')
                    ->setCellValue('J8', 'DANA TABBARU')
                    ->setCellValue('K8', 'DANA UJRAH')
                    ->setCellValue('L8', 'KONTRIBUSI')
                    ->setCellValue('M8', 'TOTAL KONTRIBUSI')
                    ->setCellValue('N8', 'TGL STNC')
                    ->setCellValue('O8', 'UL');
        }

        if($status==2){
            $activeSheet->getStyle('A8:N8')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setRGB('c2d7f3');
            $activeSheet
                    ->setCellValue('A8', 'NO')
                    ->setCellValue('B8', 'KET')
                    ->setCellValue('C8', 'NAMA PESERTA')
                    ->setCellValue('D8', 'TGL. LAHIR')
                    ->setCellValue('E8', 'USIA')
                    ->setCellValue('F8', 'MULAI ASURANSI')
                    ->setCellValue('G8', 'AKHIR ASURANSI')
                    ->setCellValue('H8', 'NILAI MANFAAT ASURANSI')
                    ->setCellValue('I8', 'DANA TABBARU')
                    ->setCellValue('J8', 'DANA UJRAH')
                    ->setCellValue('K8', 'KONTRIBUSI')
                    ->setCellValue('L8', 'TOTAL KONTRIBUSI')
                    ->setCellValue('M8', 'TGL STNC')
                    ->setCellValue('N8', 'UL');
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
        $num=9;

        if($status==1){
            foreach($data->kepesertaan->where('status_akseptasi',$status) as $k => $i){
                $activeSheet
                    ->setCellValue('A'.$num,($k+1))
                    ->setCellValue('B'.$num,$i->reason_reject)
                    ->setCellValue('C'.$num,$i->no_peserta)
                    ->setCellValue('D'.$num,$i->nama)
                    ->setCellValue('E'.$num,$i->tanggal_lahir)
                    ->setCellValue('F'.$num,$i->usia)
                    ->setCellValue('G'.$num,$i->tanggal_mulai?date('d-m-Y',strtotime($i->tanggal_mulai)) : '-')
                    ->setCellValue('H'.$num,$i->tanggal_akhir?date('d-m-Y',strtotime($i->tanggal_mulai)) : '-')
                    ->setCellValue('I'.$num,$i->basic)
                    ->setCellValue('J'.$num,$i->dana_tabarru)
                    ->setCellValue('K'.$num,$i->dana_ujrah)
                    ->setCellValue('L'.$num,$i->kontribusi)
                    ->setCellValue('M'.$num,$i->extra_mortalita+$i->kontribusi+$i->extra_kontribusi)
                    ->setCellValue('N'.$num,$i->tanggal_stnc)
                    ->setCellValue('O'.$num,$i->ul);
                
                $num++;
            }
        }

        if($status==2){
            foreach($data->kepesertaan->where('status_akseptasi',$status) as $k => $i){
                $activeSheet
                    ->setCellValue('A'.$num,($k+1))
                    ->setCellValue('B'.$num,$i->reason_reject)
                    ->setCellValue('C'.$num,$i->nama)
                    ->setCellValue('D'.$num,$i->tanggal_lahir)
                    ->setCellValue('E'.$num,$i->usia)
                    ->setCellValue('F'.$num,$i->tanggal_mulai?date('d-m-Y',strtotime($i->tanggal_mulai)) : '-')
                    ->setCellValue('G'.$num,$i->tanggal_akhir?date('d-m-Y',strtotime($i->tanggal_mulai)) : '-')
                    ->setCellValue('H'.$num,$i->basic)
                    ->setCellValue('I'.$num,$i->dana_tabarru)
                    ->setCellValue('J'.$num,$i->dana_ujrah)
                    ->setCellValue('K'.$num,$i->kontribusi)
                    ->setCellValue('L'.$num,$i->extra_mortalita+$i->kontribusi+$i->extra_kontribusi)
                    ->setCellValue('M'.$num,$i->tanggal_stnc)
                    ->setCellValue('N'.$num,$i->ul);
                
                $num++;
            }
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
