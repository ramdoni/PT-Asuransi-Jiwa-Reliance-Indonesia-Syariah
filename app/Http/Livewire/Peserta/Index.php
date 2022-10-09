<?php

namespace App\Http\Livewire\Peserta;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Kepesertaan;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $filter_keyword,$filter_status_polis;
    public function render()
    {
        $data = Kepesertaan::orderBy('id','DESC')->with(['polis','polis.produk'])->whereNotNull('no_peserta');

        if($this->filter_keyword) $data->where(function($table){
            foreach(\Illuminate\Support\Facades\Schema::getColumnListing('kepesertaan') as $column){
                $table->orWhere($column,'LIKE',"%{$this->filter_keyword}%");
            }
        });

        if($this->filter_status_polis) $data->where('status_polis',$this->filter_status_polis);

        $total = clone $data;

        return view('livewire.peserta.index')->with(['data'=>$data->paginate(100),'total_kontribusi'=>$total]);
    }

    public function updated($propertyName)
    {
        $this->emit('init-data');
        $this->resetPage();
    }
}
