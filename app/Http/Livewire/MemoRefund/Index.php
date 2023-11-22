<?php

namespace App\Http\Livewire\MemoRefund;

use Livewire\Component;
use App\Models\Refund;
use App\Models\Kepesertaan;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $selected_id,$filter_keyword;

    public function render()
    {
        $data = Refund::orderBy('id','DESC');

        return view('livewire.memo-refund.index')->with(['data'=>$data->paginate(100)]);
    }

    public function delete()
    {
        Refund::find($this->selected_id)->delete();
        Kepesertaan::where('memo_refund_id',$this->selected_id)->update(['memo_refund_id'=>null]);

        $this->emit('message-success','Deleted');$this->emit('modal','hide');
    }
}
