<?php

namespace App\Http\Livewire\MemoUjroh;

use Livewire\Component;
use App\Models\MemoUjroh;
use App\Models\Pengajuan;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $selected_id,$filter_keyword,$filter_polis;

    public function render()
    {
        $data = MemoUjroh::select('memo_ujroh.*')->orderBy('memo_ujroh.id','DESC')
                            ->join('polis','polis.id','=','memo_ujroh.polis_id');
        
        if($this->filter_keyword) $data->where('memo_ujroh.nomor','LIKE',"%{$this->filter_keyword}%");

        if($this->filter_polis) $data->where(function($table){
            $table->where('polis.no_polis','LIKE',"%{$this->filter_polis}%")
                    ->orWhere('polis.nama','LIKE',"%{$this->filter_polis}%");
        });
        
        return view('livewire.memo-ujroh.index')->with(['data'=>$data->paginate(100)]);
    }

    public function delete()
    {
        MemoUjroh::find($this->selected_id)->delete();

        Pengajuan::where('memo_ujroh_id',$this->selected_id)->update(['memo_ujroh_id'=>null]);
        
        $this->emit('message-success','Pengajuan berhasil dihapus');$this->emit('modal','hide');
    }
}