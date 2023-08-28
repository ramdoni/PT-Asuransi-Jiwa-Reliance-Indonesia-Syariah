<?php

namespace App\Http\Livewire\Polis;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Polis;

class Index extends Component
{
    protected $listeners = ['reload-page'=>'$refresh'];
    public $filter_keyword,$selected_id;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public function render()
    {
        $data = Polis::with(['produk','provinsi'])
                        // ->withCount(['rate_','uw_limit_'])
                        ->orderBy('id','desc');
        
        if($this->filter_keyword) $data->where(function($table){
            foreach(\Illuminate\Support\Facades\Schema::getColumnListing('polis') as $column){
                $table->orWhere($column,'LIKE',"%{$this->filter_keyword}%");
            }
        });

        return view('livewire.polis.index')->with(['data'=>$data->paginate(500)]);
    }

    public function clear_filter()
    {
        $this->reset('filter_keyword');
    }

    public function delete()
    {
        $polis = Polis::find($this->selected_id);
        if($polis){
            /**
             * Jika status sudah issued harus butuh action dari HEAD SYARIAH
             */
            if(\Auth::user()->user_access_id==4 and $polis->status_approval==1){
                $polis->delete();
            }
            
            if($polis->status_approval==0) $polis->delete();
        }
    }
}
