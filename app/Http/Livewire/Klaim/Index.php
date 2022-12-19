<?php

namespace App\Http\Livewire\Klaim;

use Livewire\Component;
use App\Models\Klaim;
use App\Models\Kepesertaan;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $filter_keyword;
    public function render()
    {
        $data = Klaim::select('klaim.*')->with(['kepesertaan','polis','provinsi','kabupaten'])->orderBy('klaim.id','DESC')
                ->join('kepesertaan','kepesertaan.id','=','klaim.kepesertaan_id','LEFT');
        if($this->filter_keyword) $data->where(function($table){
            foreach(\Illuminate\Support\Facades\Schema::getColumnListing('klaim') as $column){
                $table->orWhere('klaim.'.$column,'LIKE',"%{$this->filter_keyword}%");
            }
            $table->orWhere('kepesertaan.no_peserta','LIKE',"%{$this->filter_keyword}%");
        });

        return view('livewire.klaim.index')->with(['data'=>$data->paginate(100)]);
    }

    public function mount()
    {
        \LogActivity::add("Klaim");
    }

    public function delete(Klaim $data)
    {
        Kepesertaan::where('klaim_id',$data->id)->update(['klaim_id'=>null]);

        $data->delete();

        session()->flash('message-success',__('Data berhasil di hapus'));

        return redirect()->route('klaim.index');
    }
}
