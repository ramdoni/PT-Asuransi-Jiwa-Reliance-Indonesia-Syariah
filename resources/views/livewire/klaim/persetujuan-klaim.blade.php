<div class="table-responsive">
    <table class="table ml-2">
        <tr style="background:#17a2b84a">
            <th>1. Head of Dept. Claim Syariah</th>
            <th>Tanggal</th>
        </tr>
        <tr>
            <td>
                @if(($data->head_klaim_status=="" || $is_edit_head_klaim) and (\Auth::user()->user_access_id==2 || \Auth::user()->user_access_id==1))
                    <div class="row">
                        <div class="form-group col-md-4">
                            <select class="form-control" wire:model="head_klaim_status">
                                <option value=""> -- Keputusan -- </option>
                                @foreach($keputusa_arr as $k => $val)
                                    <option value="{{$k}}">{{$val}}</option>
                                @endforeach
                            </select>
                            @error('head_klaim_status')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" placeholder="Catatan" wire:model="head_klaim_note"></textarea>
                        @error('head_klaim_note')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="form-group">
                        <span wire:loading wire:target="save_head_klaim">
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                        <a href="javascript:void(0)" wire:loading.remove wire:target="save_head_klaim" class="btn btn-info" wire:click="save_head_klaim"><i class="fa fa-check-circle"></i> Submit</a>
                        @if($is_edit_head_klaim)
                            <a href="javascript:void(0)" class="ml-2 text-danger" wire:click="$set('is_edit_head_klaim',false)">Cancel</a>
                        @endif
                    </div>
                @else
                    <div class="my-3">
                        <span class="badge badge-info">{{@$keputusa_arr[$data->head_klaim_status]}}</span>
                        {{$data->head_klaim_note}} 
                        @if(\Auth::user()->user_access_id==2)
                            <a href="javascript:void(0)" wire:click="$set('is_edit_head_klaim',true)"><i class="fa fa-edit"></i> edit</a>
                        @endif
                    </div>
                @endif
            </td>
            <td>
                @if($data->head_klaim_date)
                    {{date('d-F-Y',strtotime($data->head_klaim_date))}}
                @endif
            </td>
        </tr>
        <tr style="background:#17a2b84a">
            <th>2. Head of Technic Syariah</th>
            <th>Tanggal</th>
        </tr>
        <tr>
            <td>
                @if(($data->head_teknik_status=="" || $is_edit_head_teknik) and \Auth::user()->user_access_id==3)
                    <div class="row">
                        <div class="form-group col-md-4">
                            <select class="form-control" wire:model="head_teknik_status">
                                <option value=""> -- Keputusan -- </option>
                                @foreach($keputusa_arr as $k => $val)
                                    <option value="{{$k}}">{{$val}}</option>
                                @endforeach
                            </select>
                            @error('head_teknik_status')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" placeholder="Catatan" wire:model="head_teknik_note"></textarea>
                        @error('head_teknik_note')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <div class="form-group">
                        <span wire:loading wire:target="save_head_teknik">
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                        <a href="javascript:void(0)" wire:loading.remove wire:target="save_head_teknik" class="btn btn-info" wire:click="save_head_teknik"><i class="fa fa-check-circle"></i> Submit</a>
                        @if($is_edit_head_teknik)
                            <a href="javascript:void(0)" class="ml-2 text-danger" wire:click="$set('is_edit_head_teknik',false)">Cancel</a>
                        @endif
                    </div>
                @else
                    @if($data->head_teknik_status)
                        <div class="my-3">
                            <span class="badge badge-info">{{@$keputusa_arr[$data->head_teknik_status]}}</span>
                            {{$data->head_teknik_note}}
                            @if(\Auth::user()->user_access_id==3)
                                <a href="javascript:void(0)" class="ml-2" wire:click="$set('is_edit_head_teknik',true)"><i class="fa fa-edit"></i> edit</a>
                            @endif
                        </div>
                    @endif
                @endif
            </td>
            <td>
                @if($data->head_teknik_date)
                    {{date('d-F-Y',strtotime($data->head_teknik_date))}}
                @endif
            </td>
        </tr>

        <tr style="background:#17a2b84a">
            <th>3. Head of Division Syariah</th>
            <th>Tanggal</th>
        </tr>
        <tr>
            <td>
                @if(($is_edit_head_devisi or $data->head_devisi_status=="") and \Auth::user()->user_access_id==4)
                    <div class="row">
                        <div class="form-group col-md-4">
                            <select class="form-control" wire:model="head_devisi_status">
                                <option value=""> -- Keputusan -- </option>
                                @foreach($keputusa_arr as $k => $val)
                                    <option value="{{$k}}">{{$val}}</option>
                                @endforeach
                            </select>
                            @error('head_devisi_status')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" placeholder="Catatan" wire:model="head_devisi_note"></textarea>
                        @error('head_devisi_note')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                        @if($head_devisi_status==2)
                            <hr />
                            <label>Detail Penolakan</label>
                            <textarea class="form-control" wire:model="detail_penolakan"></textarea>
                        @endif
                    </div>
                    <div class="form-group">
                        <span wire:loading wire:target="save_devisi_syariah">
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                        <a href="javascript:void(0)" wire:loading.remove wire:target="save_devisi_syariah" class="btn btn-info" wire:click="save_devisi_syariah"><i class="fa fa-check-circle"></i> Submit</a>
                        @if($is_edit_head_devisi)
                            <a href="javascript:void(0)" class="ml-2 text-danger" wire:click="$set('is_edit_head_devisi',false)">Cancel</a>
                        @endif
                    </div>
                @else
                    @if($data->head_devisi_status)
                        <div class="my-3">
                            <span class="badge badge-info">{{@$keputusa_arr[$data->head_devisi_status]}}</span>
                            {{$data->head_devisi_note}}
                            @if(\Auth::user()->user_access_id==4)
                                <a href="javascript:void(0)" class="ml-2" wire:click="$set('is_edit_head_devisi',true)"><i class="fa fa-edit"></i> edit</a>
                            @endif
                            @if($data->head_devisi_status==2) 
                                <hr />
                                <label>Detail Penolakan <a href="javascript:void(0)" wire:click="$set('edit_detail_penolakan',true)"><i class="fa fa-edit"></i></a></label>
                                @if($edit_detail_penolakan)
                                    <label>Detail Penolakan</label>
                                    <textarea class="form-control mb-2" wire:model="detail_penolakan" style="min-height: 300px;"></textarea>
                                    <a href="javascript:void(0)" wire:click="saveDetailPenolakan"><i class="fa fa-save"></i> Simpan</a>
                                    <a href="javascript:void(0)" wire:click="$set('edit_detail_penolakan',false)" class="text-danger ml-3"><i class="fa fa-close"></i> Batal</a>
                                @else
                                    <p>{!!nl2br($data->detail_penolakan)!!}</p>
                                @endif
                            @endif
                        </div>
                    @endif
                @endif
            </td>
            <td>
                @if($data->head_devisi_date)
                    {{date('d-F-Y',strtotime($data->head_devisi_date))}}
                @endif
            </td>
        </tr>
        <tr style="background:#17a2b84a">
            <th>4. Direksi 1</th>
            <th>Tanggal</th>
        </tr>
        <tr>
            <td>
                @if($data->nilai_klaim_disetujui>150000000)
                    @if(\Auth::user()->user_access_id==4 and ($data->direksi_1_status=="" || $is_edit_direksi_1))
                        <div class="row">
                            <div class="form-group col-md-4">
                                <select class="form-control" wire:model="direksi_1_status">
                                    <option value=""> -- Keputusan -- </option>
                                    @foreach($keputusa_arr as $k => $val)
                                        <option value="{{$k}}">{{$val}}</option>
                                    @endforeach
                                </select>
                                @error('direksi_1_status')
                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" placeholder="Catatan" wire:model="direksi_1_note"></textarea>
                        </div>
                        <div class="form-group" style="width:40%">
                            <input type="file" class="form-control" wire:model="direksi_1_file" />
                            @error('direksi_1_file')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                        <div class="form-group">
                            <span wire:loading wire:target="save_direksi1,direksi_1_file">
                                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                <span class="sr-only">{{ __('Loading...') }}</span>
                            </span>
                            <a href="javascript:void(0)" wire:loading.remove wire:target="save_direksi1,direksi_1_file" class="btn btn-info" wire:click="save_direksi1"><i class="fa fa-check-circle"></i> Submit</a>
                            @if($is_edit_direksi_1)
                                <a href="javascript:void(0)" class="ml-2 text-danger" wire:click="$set('is_edit_direksi_1',false)">Cancel</a>
                            @endif
                        </div>
                    @else
                        @if($data->direksi_1_status)
                            <div class="my-3">
                                <span class="badge badge-info">{{@$keputusa_arr[$data->direksi_1_status]}}</span>
                                {{$data->direksi_1_note}}
                                @if($data->direksi_1_file)
                                    <a href="{{asset($data->direksi_1_file)}}" target="_blank"><i class="fa fa-download"></i></a>
                                @endif
                                @if(\Auth::user()->user_access_id==4)
                                    <a href="javascript:void(0)" class="ml-2" wire:click="$set('is_edit_direksi_1',true)"><i class="fa fa-edit"></i> edit</a>
                                @endif
                            </div>
                        @endif
                    @endif
                @endif
            </td>
            <td>
                @if($data->direksi_1_date)
                    {{date('d-F-Y',strtotime($data->direksi_1_date))}}
                @endif
            </td>
        </tr>
        <tr style="background:#17a2b84a">
            <th>5. Direksi 2</th>
            <th>Tanggal</th>
        </tr>
        <tr>
            <td>
                @if($data->nilai_klaim_disetujui > 200000000)
                    @if(\Auth::user()->user_access_id==4 and ($data->direksi_2_status=="" || $is_edit_direksi_2))
                        <div class="row">
                            <div class="form-group col-md-4">
                                <select class="form-control" wire:model="direksi_2_status">
                                    <option value=""> -- Keputusan -- </option>
                                    @foreach($keputusa_arr as $k => $val)
                                        <option value="{{$k}}">{{$val}}</option>
                                    @endforeach
                                </select>
                                @error('direksi_2_status')
                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea class="form-control" placeholder="Catatan" wire:model="direksi_2_note"></textarea>
                        </div>
                        <div class="form-group" style="width:40%">
                            <input type="file" class="form-control" wire:model="direksi_2_file" />
                            @error('direksi_2_file')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                        <div class="form-group">
                            <span wire:loading wire:target="save_direksi_2,direksi_2_file">
                                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                <span class="sr-only">{{ __('Loading...') }}</span>
                            </span>
                            <a href="javascript:void(0)" wire:loading.remove wire:target="save_direksi2,direksi_2_file" class="btn btn-info" wire:click="save_direksi2"><i class="fa fa-check-circle"></i> Submit</a>
                            @if($is_edit_direksi_2)
                                <a href="javascript:void(0)" class="ml-2 text-danger" wire:click="$set('is_edit_direksi_2',false)">Cancel</a>
                            @endif
                        </div>
                    @else
                        @if($data->direksi_2_status)
                            <div class="my-3">
                                <span class="badge badge-info">{{@$keputusa_arr[$data->direksi_2_status]}}</span>
                                {{$data->direksi_2_note}}
                                @if($data->direksi_2_file)
                                    <a href="{{asset($data->direksi_2_file)}}" target="_blank"><i class="fa fa-download"></i></a>
                                @endif
                                @if(\Auth::user()->user_access_id==4)
                                    <a href="javascript:void(0)" class="ml-2" wire:click="$set('is_edit_direksi_2',true)"><i class="fa fa-edit"></i> edit</a>
                                @endif
                            </div>
                        @endif
                    @endif
                @endif
            </td>
            <td>
                @if($data->direksi_1_date)
                    {{date('d-F-Y',strtotime($data->direksi_2_date))}}
                @endif
            </td>
        </tr>
    </table>
    <p>
        Persetujuan Klaim
    </p>
    <ul>
        <li>0 s/d  Rp 50.000.000 - Head Departemen Claim Unit Syariah</li>
        <li>Rp 50.000.000 s/d  Rp 150.000.000 - Head Devision Operational Unit Syariah</li>
        <li>Rp 150.000.000 s/d  Rp 200.000.000 - 1 Direksi</li>
        <li> > Rp 200.000.000 - 2 Direksi</li>
    </ul>
</div>
