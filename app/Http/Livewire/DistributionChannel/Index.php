<?php

namespace App\Http\Livewire\DistributionChannel;

use Livewire\Component;
use App\Models\DistributionChannel;

class Index extends Component
{
    public $type,$name,$description,$is_insert=false;
    public function render()
    {
        $data = DistributionChannel::orderBy('id','DESC');

        return view('livewire.distribution-channel.index')->with(['data'=>$data->paginate(100)]);
    }

    public function save()
    {
        $this->validate([
            'type'=>'required',
            'name'=>'required',
        ]);

        $data = new DistributionChannel();
        $data->name = $this->name;
        $data->type = $this->type;
        $data->save();

        $this->is_insert = false;
        $this->emit('message-success','Data saved');
    }

    public function delete(DistributionChannel $item)
    {
        $item->delete();

        $this->emit('message-success','Data deleted.');
    }
}
