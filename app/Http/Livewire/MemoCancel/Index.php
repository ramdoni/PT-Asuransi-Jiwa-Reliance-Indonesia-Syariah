<?php

namespace App\Http\Livewire\MemoCancel;

use Livewire\Component;
use App\Models\MemoCancel;
use App\Models\Kepesertaan;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $selected_id;

    public function render()
    {
        $data = MemoCancel::orderBy('id','DESC');

        return view('livewire.memo-cancel.index')->with(['data'=>$data->paginate(100)]);
    }

    public function delete()
    {
        MemoCancel::find($this->selected_id)->delete();
        Kepesertaan::where('memo_cancel_id',$this->selected_id)->update(['memo_cancel_id'=>null]);

        $this->emit('message-success','Memo berhasil dihapus');$this->emit('modal','hide');
    }
}
