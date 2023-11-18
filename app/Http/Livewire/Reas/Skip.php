<?php

namespace App\Http\Livewire\Reas;

use Livewire\Component;
use App\Models\Reas;
use App\Models\Kepesertaan;
use Livewire\WithPagination;

class Skip extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap',$listeners = ['reassign'=>'set_reassign','filter-ul'=>'filter_ul','filter-peserta'=>'filter_peserta_',
    'filter-polis_id'=>'filter_polis_id_','filter-keyword'=>'filter_keyword_'];
    public $check_id=[],$data,$extra_kontribusi,$reassign=false,$ul,$filter_peserta,$assign_id=[],$filter_polis_id,$filter_keyword;
    public $check_all=0;
    public function render()
    {
        $kepesertaan = $this->get_data();
        
        return view('livewire.reas.skip')->with(['kepesertaan'=>$kepesertaan->get()]);
    }

    public function updated($propertName)
    {
        $this->emit('data_assign_or_',$this->assign_id);
    }

    public function get_data()
    {
        $kepesertaan = Kepesertaan::select('kepesertaan.*',
                                'pengajuan.dn_number',
                                \DB::raw('pengajuan.no_pengajuan as p_no_pengajuan')
                                )->with(['polis'])
                            ->join('pengajuan','pengajuan.id','=','kepesertaan.pengajuan_id')->with(['polis'])->where('kepesertaan.reas_id',$this->data->id)->where('kepesertaan.status_reas',2);

        if($this->ul) $kepesertaan->where('ul_reas',$this->ul);
        if($this->filter_peserta) $kepesertaan->where('is_double_reas',$this->filter_peserta);
        if($this->filter_keyword)$kepesertaan->where('pengajuan.dn_number','LIKE',"%{$this->filter_keyword}%");

        return $kepesertaan;
    }

    public function filter_keyword_($keyword)
    {
        $this->filter_keyword = $keyword;
    }

    public function filter_polis_id_($id)
    {
        $this->filter_polis_id = $id;
    }

    public function filter_peserta_($filter_peserta)
    {
        $this->filter_peserta = $filter_peserta;
    }

    public function filter_ul($ul)
    {
        $this->ul = $ul;
    }

    public function mount(Reas $data)
    {
        $this->data = $data;
    }

    public function set_reassign($boolean)
    {
        $this->reassign = $boolean;
    }

    public function checked_all()
    {
        if($this->check_all==1){
            $kepesertaan = $this->get_data(); 
            foreach($this->get_data()->get() as $item){
                $this->assign_id[$item->id] = $item->id;
            }
        }
        if($this->check_all==0){
            $this->assign_id = [];
        }

        $this->emit('data_assign_or_',$this->assign_id);
    }
}
