<?php

namespace App\Http\Livewire\MemoRefund;

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

        return view('livewire.memo-refund.index')->with(['data'=>$data->paginate(100)]);
    }
}
