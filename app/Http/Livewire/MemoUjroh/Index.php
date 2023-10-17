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
    public $selected_id;

    public function render()
    {
        $data = MemoUjroh::orderBy('id','DESC');

        return view('livewire.memo-ujroh.index')->with(['data'=>$data->paginate(100)]);
    }

    public function delete()
    {
        MemoUjroh::find($this->selected_id)->delete();

        Pengajuan::where('memo_ujroh_id',$this->selected_id)->update(['memo_ujroh_id'=>null]);
        
        $this->emit('message-success','Pengajuan berhasil dihapus');$this->emit('modal','hide');
    }
}