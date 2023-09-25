<?php

namespace App\Http\Livewire\MemoUjroh;

use Livewire\Component;
use App\Models\MemoUjroh;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    
    public function render()
    {
        $data = MemoUjroh::orderBy('id','DESC');

        return view('livewire.memo-ujroh.index')->with(['data'=>$data->paginate(100)]);
    }
}
