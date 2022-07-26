<?php

namespace App\Http\Livewire\Peserta;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Kepesertaan;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $data = Kepesertaan::orderBy('id','DESC');

        return view('livewire.peserta.index')->with(['data'=>$data->paginate(100)]);
    }
}
