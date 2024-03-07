<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class Setting extends Component
{
    use WithFileUploads;

    public $logoUrl;
    public $logo;
    public $faviconUrl;
    public $favicon;
    public $message;
    public $company;
    public $email;
    public $phone;
    public $website;
    public $address,$running_number_nota_penutupan,$running_number_sb,$running_surat,$running_number_memo_ujroh,
            $running_number_refund,$running_number_refund_cn,$running_number_dn_recovery_claim,$running_number_tagihan_soa,
            $running_number_endorse,$running_number_cancel,$running_number_cancel_cn,$running_number_endorse_cn_dn;
        
    public function render()
    {
        return view('livewire.setting')->with(['title'=>'General']);
    }

    public function mount()
    {
        $this->company = get_setting('company');
        $this->email = get_setting('email');
        $this->phone = get_setting('phone');
        $this->website = get_setting('website');
        $this->address = get_setting('address');
        $this->logoUrl = get_setting('logo');
        $this->faviconUrl = get_setting('favicon');
        $this->running_number_nota_penutupan = get_setting('running_number_nota_penutupan');
        $this->running_number_sb = get_setting('running_number_sb');
        $this->running_surat = get_setting('running_surat');
        $this->running_number_memo_ujroh = get_setting('running_number_memo_ujroh');
        $this->running_number_refund = get_setting('running_number_refund');
        $this->running_number_refund_cn = get_setting('running_number_refund_cn');
        $this->running_number_recovery_claim = get_setting('running_number_recovery_claim');
        $this->running_number_dn_recovery_claim = get_setting('running_number_dn_recovery_claim');
        $this->running_number_dn_recovery_claim = get_setting('running_number_dn_recovery_claim');
        $this->running_number_tagihan_soa = get_setting('running_number_tagihan_soa');
        $this->running_number_endorse = get_setting('running_number_endorse');
        $this->running_number_endorse_cn_dn = get_setting('running_number_endorse_cn_dn');
        $this->running_number_cancel = get_setting('running_number_cancel');
        $this->running_number_cancel_cn = get_setting('running_number_cancel_cn');

        \LogActivity::add("Setting");
    }

    public function updateBasic()
    {
        update_setting('company',$this->company);
        update_setting('email',$this->email);
        update_setting('phone',$this->phone);
        update_setting('website',$this->website);
        update_setting('address',$this->address);

        \LogActivity::add("Setting Update");
    }

    public function updateEndorse()
    {
        update_setting('running_number_endorse',$this->running_number_endorse);
        update_setting('running_number_endorse_cn_dn',$this->running_number_endorse_cn_dn);

        $this->emit('message-success',__('Data saved successfully'));
    }

    public function updateCancel()
    {
        update_setting('running_number_cancel',$this->running_number_cancel);
        update_setting('running_number_cancel_cn',$this->running_number_cancel_cn);

        $this->emit('message-success',__('Data saved successfully'));
    }

    public function updateRefund()
    {
        update_setting('running_number_refund',$this->running_number_refund);
        update_setting('running_number_refund_cn',$this->running_number_refund_cn);

        $this->emit('message-success',__('Data saved successfully'));
    }

    public function updatePolis()
    {
        update_setting('running_number_nota_penutupan',$this->running_number_nota_penutupan);
        update_setting('running_number_sb',$this->running_number_sb);
        update_setting('running_surat',$this->running_surat);
        update_setting('running_number_memo_ujroh',$this->running_number_memo_ujroh);
        update_setting('running_number_recovery_claim',$this->running_number_recovery_claim);
        update_setting('running_number_dn_recovery_claim',$this->running_number_dn_recovery_claim);
        update_setting('running_number_tagihan_soa',$this->running_number_tagihan_soa);
        
        \LogActivity::add("Setting Update Polis");

        session()->flash('message-success',__('Data saved successfully'));

        return redirect()->to('setting');
    }

    public function save()
    {
        if($this->logo!=""){
            $this->validate([
                'logo' => 'image:max:1024', // 1Mb Max
            ]);
            $name = 'logo'.date('Ymdhis').'.'.$this->logo->extension();
            $this->logo->storePubliclyAs('public',$name);
            
            \LogActivity::add("Update Logo");

            update_setting('logo',$name);
        }

        if($this->favicon!=""){
            $this->validate([
                'favicon' => 'max:1024', // 1Mb Max
            ]);
            $name = 'favicon'.date('YmdHis').'.'.$this->favicon->extension();
            $this->favicon->storePubliclyAs('public',$name);

            \LogActivity::add("Update Favicon");

            update_setting('favicon',$name);
        }
        session()->flash('message-success',__('Data saved successfully'));

        return redirect()->to('setting');
    }
}