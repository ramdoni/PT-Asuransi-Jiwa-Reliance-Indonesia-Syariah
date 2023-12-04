<?php

namespace App\Http\Livewire\Tagihansoa;

use Livewire\Component;
use App\Models\Tagihansoa;
use App\Models\TagihansoaPengajuan;

class Index extends Component
{
    public $selected_id;
    public function render()
    {
        $data = Tagihansoa::orderBy('id','DESC');

        return view('livewire.tagihansoa.index')->with(['data'=>$data->paginate(100)]);
    }

    public function delete()
    {
        Tagihansoa::find($this->selected_id)->delete();
        TagihansoaPengajuan::where('tagihan_soa_id',$this->selected_id)->first();

        $this->emit('message-success','Deleted');$this->emit('modal','hide');
    }
}
