<?php

namespace App\Http\Livewire\CustomReport;

use Livewire\Component;
use App\Models\CustomReport;

class Index extends Component
{
    public function render()
    {
        $data = CustomReport::get();

        return view('livewire.custom-report.index')->with(['data'=>$data]);
    }
}
