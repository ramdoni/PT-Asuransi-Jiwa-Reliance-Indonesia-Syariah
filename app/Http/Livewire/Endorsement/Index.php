<?php

namespace App\Http\Livewire\Endorsement;

use Livewire\Component;
use App\Models\Endorsement;
use App\Models\EndorsementPeserta;
use App\Models\Kepesertaan;
use App\Models\ReasEndorse;
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

        $kepesertaan =  Kepesertaan::where('endorsement_id',$this->selected_id)->first();
        if($kepesertaan) $kepesertaan->update(['endorsement_id'=>null]);

        $end_kepesertaan = EndorsementPeserta::where('endorsement_id', $this->selected_id)->first();
        if($end_kepesertaan) $end_kepesertaan->delete();
        
        // find reas
        $reas = ReasEndorse::where('endorsement_id',$this->selected_id)->first();
        if($reas) $reas->delete();
        
        $this->emit('message-success','Deleted');$this->emit('modal','hide');
    }
}
