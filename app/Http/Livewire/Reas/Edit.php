<?php

namespace App\Http\Livewire\Reas;

use App\Jobs\ReasCalculate;
use App\Models\Reas;
use Livewire\Component;
use App\Models\Kepesertaan;
use App\Models\ReasuradurRateUw;
use App\Models\ReasuradurRateRates;
use Livewire\WithPagination;

class Edit extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    protected $listeners = ['reload-page'=>'$refresh','set_calculate_reas'=>'set_calculate_reas'];
    public $data,$no_pengajuan,$tab_active='tab_draft',$check_id=[],$filter_status,$is_calculate=false,$perhitungan_usia;
    public $filter_ul_arr=[],$filter_ul;
    public function render()
    {
        $kepesertaan = Kepesertaan::with(['pengajuan','polis'])->where(['reas_id'=>$this->data->id,'status_akseptasi'=>1]);

        $draft = clone $kepesertaan;
        $reas = clone $kepesertaan;
        $or = clone $kepesertaan;

        if($this->filter_status) $kepesertaan->where('status_reas',$this->filter_status);

        return view('livewire.reas.edit')->with(['count_draft'=>$draft->where('status_reas',0)->count(),
                                                    'count_reas'=>$reas->where('status_reas',1)->count(),
                                                    'count_or'=>$or->where('status_reas',2)->count()]);
    }

    public function mount(Reas $id)
    {
        $this->data = $id;
        $this->perhitungan_usia = $this->data->perhitungan_usia;
        $this->no_pengajuan = $id->no_pengajuan;
        $this->filter_ul_arr = Kepesertaan::where('reas_id',$this->data->id)->groupBy('ul')->get();
    }

    public function updated($propertyName)
    {
        if($propertyName=='perhitungan_usia'){
            $this->data->perhitungan_usia = $this->perhitungan_usia;
            $this->data->save();
        }
        if($propertyName=='filter_ul'){
            $this->emit('filter-ul',$this->filter_ul);
        }
    }

    public function hitung()
    {
        $this->is_calculate = true;
        ReasCalculate::dispatch($this->data->id);
    }

    public function set_calculate_reas()
    {
        $this->is_calculate = false;
        $this->emit('reload-page');
    }

    public function submit_underwriting()
    {
        $this->data->status = 1;
        $this->data->save();

        session()->flash('message-success',__('Pengajuan berhasil submit'));

        return redirect()->route('reas.edit',$this->data->id);
    }

    public function submit_head_teknik()
    {
        $this->data->status = 2;
        $this->data->save();

        session()->flash('message-success',__('Pengajuan berhasil submit'));

        return redirect()->route('reas.edit',$this->data->id);
    }

    public function submit_head_syariah()
    {
        $this->data->status = 3;
        $this->data->save();

        session()->flash('message-success',__('Pengajuan berhasil submit'));

        return redirect()->route('reas.edit',$this->data->id);
    }
}
