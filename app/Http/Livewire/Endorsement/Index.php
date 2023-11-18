<?php

namespace App\Http\Livewire\Endorsement;

use Livewire\Component;
use App\Models\Refund;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $selected_id,$filter_keyword;

    public function render()
    {
        $data = Refund::orderBy('id','DESC');

        return view('livewire.endorsement.index')->with(['data'=>$data->paginate(100)]);
    }
}
