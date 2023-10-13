<?php

namespace App\Http\Livewire\ReasCancel;

use Livewire\Component;
use App\Models\ReasCancel;
use App\Models\Kepesertaan;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $selected_id;
    public function render()
    {
        $data = ReasCancel::orderBy('id','DESC');

        return view('livewire.reas-cancel.index')->with(['data'=>$data->paginate(100)]);
    }

    public function delete()
    {
        ReasCancel::find($this->selected_id)->delete();
        Kepesertaan::where('reas_cancel_id',$this->selected_id)->update(['reas_cancel_id'=>null]);

        $this->emit('message-success','Reas berhasil dihapus');$this->emit('modal','hide');
    }
}
