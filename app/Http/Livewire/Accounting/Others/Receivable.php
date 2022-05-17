<?php

namespace App\Http\Livewire\Accounting\Others;

use Livewire\Component;
use App\Models\Income;

class Receivable extends Component
{
    public $keyword;
    public function render()
    {
        $data = Income::orderBy('id','desc')->where('is_others',1);
        
        if($this->keyword) $data = $data->where('description','LIKE', "%{$this->keyword}%")
                                        ->orWhere('no_voucher','LIKE',"%{$this->keyword}%")
                                        ->orWhere('reference_no','LIKE',"%{$this->keyword}%")
                                        ->orWhere('client','LIKE',"%{$this->keyword}%");
                                        
        return view('livewire.accounting.others.receivable')->with(['data'=>$data->paginate(100)]);
    }
}
