<?php

namespace App\Http\Livewire\CustomReport;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Model\CustomReport;

class Upload extends Component
{
    use WithFileUploads;
    
    public $file,$name;

    public function render()
    {
        return view('livewire.custom-report.upload');
    }

    public function save()
    {
        \LogActivity::add('[web] Custom Report');
        
        $this->validate([
            'file'=>'required|mimes:xlsx|max:51200' // 50MB maksimal
        ]);
        
        // $new = new CustomReport();
        // $new->user_id = \Auth::user()->id;
        // $new->title = $this->name;
        // $new->save();

        $path = $this->file->getRealPath();
        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        $reader->setReadDataOnly(true);
        $data_ = $reader->load($path);
        $sheetData = $data_->getActiveSheet()->toArray(null, true, true, true);

        if(count($sheetData) > 0){
            $insert = [];
            $header = [];
            $filter = [];
            $filter_value = [];
            $header_key = 0;

            /**
             * Parsing 
             * 
             * @param header
             * @param filter
             */
            foreach($sheetData as $key => $item){
                foreach(range('A','Z') as $alphabet){
                    if(isset($item[$alphabet]) and $item[$alphabet]) {
                        if(strtolower($item[$alphabet])=='filter') 
                            $filter[] = $alphabet;
                        else{
                            $explode = explode('.',$item[$alphabet]);
                            if(count($explode)==2 and \Schema::hasTable($explode[0])){
                                $header_key = $key;
                                $header[$alphabet] = $item[$alphabet];
                            }
                        }
                    }
                }
            }
            
            /**
             * get Value Filter
             * 
             * @param string 
             **/
            $query_where = [];
            foreach($sheetData as $key => $item){
                
                if($header_key>=$key) continue;

                foreach($filter as $alphabet){
                    $explode = explode('.',$item[$alphabet]);
                    if(isset($item[$alphabet]) and $item[$alphabet] and strtolower($item[$alphabet])!='filter' and \Schema::hasTable($explode[0])==false) {
                        $query_where[$header[$alphabet]][]  = $item[$alphabet];
                    }
                }
            }

            $or_where = "";
            foreach($query_where as $field => $val){
                $or_where .= "{$field} ". implode('OR ', array_map(
                    function ($v, $k) {
                        if(is_array($v)){
                            return $k.'[]='.implode('&'.$k.'[]=', $v);
                        }else{
                            $v = ltrim($v);
                            $v = rtrim($v);
                            $v = trim(preg_replace('/\t/', '', $v));

                            return $k."='{$v}'";
                        }
                    }, 
                    $val, 
                    array_keys($val)
                ));
            }

            dd($or_where);

            $objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $objPHPExcel->getProperties()->setCreator("ENTIGI System")
                                        ->setLastModifiedBy("ENTIGI System")
                                        ->setTitle("Office 2007 XLSX Product Database")
                                        ->setSubject("Custom Report")
                                        ->setKeywords("office 2007 openxml php");
            $activeSheet = $objPHPExcel->setActiveSheetIndex(0);
            
            $num=1;        
             
            $query_field = [];
            $query_relation = "";
            $query_table = [];
            $first=true;
            foreach($header as $k => $val){
                
                $explode = explode(".",$val);
                if(isset($explode[0]) and \Schema::hasTable($explode[0])){
                    
                    $query_field[] = $val;

                    // if(in_array($explode[0],$query_table)==false){
                    //     $query_table[] = $explode[0];
                    // }

                    if($first) {
                        $query_table[] = $explode[0];
                        $query_relation = " {$explode[0]} ";
                        $first=false;
                    }else{
                        if(!in_array($explode[0],$query_table)){

                            // foreach(\Illuminate\Support\Facades\Schema::getColumnListing($explode[0]) as $column){
                                
                            // }

                            $query_relation .= " JOIN {$explode[0]} ON {$explode[0]}.{$query_table[0]}_id";   
                            $query_table[] = $explode[0];
                        }
                    }

                    $title = str_replace('_',' ',$explode[1]);
                    $title = ucwords($title);

                    $activeSheet->setCellValue("{$k}{$num}", $title);                    
                }
            }
            
            /**
             * Merge All Query
             **/
            $query = "SELECT ".implode(',',$header)." FROM {$query_relation}";
            if(count($query_where)>0) {
                /*
                $query_where = implode('AND ', array_map(
                    function ($v, $k) {
                        if(is_array($v)){
                            return $k.'[]='.implode('&'.$k.'[]=', $v);
                        }else{
                            $v = ltrim($v);
                            $v = rtrim($v);
                            $v = trim(preg_replace('/\t/', '', $v));

                            return $k."='{$v}'";
                        }
                    }, 
                    $query_where, 
                    array_keys($query_where)
                ));
                */

                $query .=" WHERE {$query_where}";   
            }

            dd($query);

            $query .= " LIMIT 1000";

            $num++;

            $get_data = \DB::select(\DB::raw($query)); 
            
            foreach($get_data  as $item){
                foreach($header as $k => $val){
                    $explode = explode(".",$val);
                    if(isset($explode[0]) and \Schema::hasTable($explode[0])){
                        $field = $explode[1];
                        $activeSheet->setCellValue("{$k}{$num}", $item->$field);
                    }
                }
                $num++;
            }

            $objPHPExcel->setActiveSheetIndex(0);
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPHPExcel, "Xlsx");

            // Redirect output to a client’s web browser (Excel5)
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment;filename="'.date('Ymd').'.xlsx"');
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
            },date('Ymd').'.xlsx');
        }
    }
}
