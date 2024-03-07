<?php

namespace App\Http\Livewire\Reas;

use Livewire\Component;
use App\Models\Reas;
use App\Models\Kepesertaan;
use App\Models\Pengajuan;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $filter_keyword,$filter_status,$filter_polis,$filter_reasuradur_id;
    public function render()
    {
        $data  = Reas::select('reas.*')
                        ->with(['pengajuan','reasuradur','rate_uw'])
                        ->withCount('kepesertaan')->orderBy('reas.id','DESC')
                        ->join('pengajuan','pengajuan.reas_id','=','reas.id')
                        ->join('polis','polis.id','=','pengajuan.polis_id');

        if($this->filter_keyword) $data->where(function($table){
            $table->where('reas.no_pengajuan','LIKE',"%{$this->filter_keyword}%")
            ->orWhere('reasuradur.name','LIKE',"%{$this->filter_keyword}%");
        });

        if($this->filter_polis)
            $data->where(function($table){
                $table->where('polis.no_polis','LIKE',"%{$this->filter_polis}%")
                    ->orWhere('polis.nama','LIKE',"%{$this->filter_polis}%");
            });
        
        if($this->filter_reasuradur_id) $data->where('reas.reasuradur_id',$this->filter_reasuradur_id);
        if($this->filter_status!="") $data->where('reas.status',$this->filter_status);

        return view('livewire.reas.index')->with(['data'=>$data->paginate(100)]);
    }

    public function clear_filter()
    {
        $this->reset('filter_status','filter_reasuradur_id','filter_polis','filter_keyword');
    }

    public function delete(Reas $id)
    {
        Kepesertaan::where('reas_id',$id->id)->update(['reas_id'=>null,'status_reas'=>null]);

        Pengajuan::where('reas_id',$id->id)->update(['reas_id'=>null]);

        $id->delete();

        session()->flash('message-success',__('Data berhasil dihapus'));

        return redirect()->route('reas.index');
    }
}
