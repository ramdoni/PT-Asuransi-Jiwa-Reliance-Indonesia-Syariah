<?php

namespace App\Http\Livewire\Epolicy;

use Livewire\Component;
use App\Models\Epolicy;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $data = Epolicy::orderBy('id','DESC');

        return view('livewire.epolicy.index')->with(['data'=>$data->paginate(100)]);
    }
}
