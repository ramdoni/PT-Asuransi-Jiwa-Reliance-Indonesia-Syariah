<?php

namespace App\Http\Livewire\BankBook;

use Livewire\Component;

class Editable extends Component
{
    public $data,$field,$value,$edit=false,$type_input='text';

    public function render()
    {
        return view('livewire.bank-book.editable');
    }

    public function mount($data,$field)
    {
        $this->data = $data;$this->field = $field;$this->value = $data->$field;
        if($field=='payment_date' ||  $field=='created_at') $this->type_input = 'date';
        if($field=='amount') $this->type_input = 'number';
    }
    public function set_edit($condition)
    {
        $this->edit = $condition;
    }
    public function save()
    {
        $field = $this->field;
        $this->data->$field = $this->value;
        $this->data->save();

        \LogActivity::add("Bank Book - Edit {$this->data->id}");

        $this->edit=false;
        $this->emit('message-success','data saved successfully');
    }
}
