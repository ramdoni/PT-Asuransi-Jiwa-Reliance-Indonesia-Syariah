<?php

namespace App\Http\Livewire\Endorsement;

use Livewire\Component;
use App\Models\Endorsement;
use App\Models\Kepesertaan;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $selected_id,$filter_keyword;

    public function render()
    {
        $data = Endorsement::orderBy('id','DESC');

        return view('livewire.endorsement.index')->with(['data'=>$data->paginate(100)]);
    }

    public function delete()
    {
        Endorsement::find($this->selected_id)->delete();

        Kepesertaan::where('endorsement_id',$this->selected_id)->update(['endorsement_id'=>null]);

        $this->emit('message-success','Deleted');$this->emit('modal','hide');
    }
}
