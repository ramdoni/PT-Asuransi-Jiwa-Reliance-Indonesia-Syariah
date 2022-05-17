<?php

namespace App\Http\Livewire\Konven;

use Livewire\Component;
use App\Models\KonvenReinsurance;

class ReinsuranceEditable extends Component
{
    public $data,$field,$value;
    public function render()
    {
        return view('livewire.konven.reinsurance-editable');
    }

    public function mount($data,$field)
    {
        $this->data = $data;
        $this->field = $field;
        $this->value = $this->data->$field;
    }

    public function save()
    {
        $field = $this->field;
        $this->data->$field = $this->value;
        $this->data->save();
    }
}
