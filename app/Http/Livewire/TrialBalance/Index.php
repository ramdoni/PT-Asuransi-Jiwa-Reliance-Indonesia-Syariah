<?php

namespace App\Http\Livewire\TrialBalance;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Coa;

class Index extends Component
{
    protected $data;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $data = Coa::with('journal')->get();
        
        return view('livewire.trial-balance.index')->with(['data'=>$data]);
    }

    public function mount()
    {

    }
}