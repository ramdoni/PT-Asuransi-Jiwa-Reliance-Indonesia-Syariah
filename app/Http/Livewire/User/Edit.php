<?php

namespace App\Http\Livewire\User;

use Livewire\Component;
use App\Models\UserAccess;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;
    public $data;
    public $name;
    public $email;
    public $password;
    public $telepon;
    public $address;
    public $user_access_id;
    public $message,$is_supervisor,$ttd;

    protected $rules = [
        'name' => 'required|string',
        'email' => 'required',
        //'password' => 'required|string',
        'telepon' => 'required',
        'user_access_id' => 'required',
    ];

    public function render()
    {
        return view('livewire.user.edit')
                        ->with([
                            'access' => UserAccess::all(),
                            'data' => $this->data
                        ]);
    }

    public function mount($id)
    {
        $this->data = User::find($id);
        $this->name = $this->data->name;
        $this->email = $this->data->email;
        $this->telepon = $this->data->telepon;
        $this->address = $this->data->address;
        $this->user_access_id = $this->data->user_access_id;
        $this->is_supervisor = $this->data->is_supervisor;
    }

    public function save(){

        if($this->ttd){
            $this->rules['ttd'] = 'image|max:1024';
        }

        $this->validate();
        
        $this->data->name = $this->name;
        $this->data->email = $this->email;
        if($this->password!="") $this->data->password = Hash::make($this->password);
        $this->data->telepon = $this->telepon;
        $this->data->address = $this->address;
        $this->data->user_access_id = $this->user_access_id;
        $this->data->is_supervisor = $this->is_supervisor;
        $this->data->save();

        if($this->ttd){
            $ttd = 'ttd.'.$this->ttd->extension();
            $this->ttd->storePubliclyAs("public/ttd/{$this->data->id}",$ttd);
            $this->data->ttd ="storage/ttd/{$this->data->id}/{$ttd}";
            $this->data->save();
        }

        session()->flash('message-success',__('Data saved successfully'));
        
        return redirect()->route('users.edit',['id'=>$this->data->id]);
    }
}
