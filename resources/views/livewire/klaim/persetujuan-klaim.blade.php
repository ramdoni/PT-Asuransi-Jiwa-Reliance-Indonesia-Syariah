<div>
    <table class="table ml-2">
        <tr style="background:#17a2b84a">
            <th>1. Head of Dept. Claim Syariah</th>
            <th>Tanggal</th>
        </tr>
        <tr>
            <td>
                @if($data->status==0 and (\Auth::user()->user_access_id==2 || \Auth::user()->user_access_id==1))
                    <div class="row">
                        <div class="form-group col-md-4">
                            <select class="form-control" wire:model="head_klaim_status">
                                <option value=""> -- Keputusan -- </option>
                                <option value="1">Terima</option>
                                <option value="2">Tolak</option>
                                <option value="3">Tunda</option>
                                <option value="4">Investigasi</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" placeholder="Catatan" wire:model="head_klaim_note"></textarea>
                    </div>
                    <div class="form-group">
                        <span wire:loading wire:target="save_head_klaim">
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                        <a href="javascript:void(0)" wire:loading.remove wire:target="save_head_klaim" class="btn btn-info" wire:click="save_head_klaim"><i class="fa fa-check-circle"></i> Submit</a>
                    </div>
                @else
                    <div class="my-3">
                        <span class="badge badge-info">{{$keputusa_arr[$data->head_klaim_status]}}</span>
                        {{$data->head_klaim_note}}
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
                @if($data->status==1 and \Auth::user()->user_access_id==3)
                    <div class="row">
                        <div class="form-group col-md-4">
                            <select class="form-control" wire:model="head_teknik_status">
                                <option value=""> -- Keputusan -- </option>
                                <option value="1">Terima</option>
                                <option value="2">Tolak</option>
                                <option value="3">Tunda</option>
                                <option value="4">Investigasi</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" placeholder="Catatan" wire:model="head_teknik_note"></textarea>
                    </div>
                    <div class="form-group">
                        <span wire:loading wire:target="save_head_teknik">
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                        <a href="javascript:void(0)" wire:loading.remove wire:target="save_head_teknik" class="btn btn-info" wire:click="save_head_teknik"><i class="fa fa-check-circle"></i> Submit</a>
                    </div>
                @else
                    <div class="my-3">
                        <span class="badge badge-info">{{$keputusa_arr[$data->head_teknik_status]}}</span>
                        {{$data->head_teknik_note}}
                    </div>
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
                @if($data->status==2 and \Auth::user()->user_access_id==4)
                    <div class="row">
                        <div class="form-group col-md-4">
                            <select class="form-control" wire:model="head_devisi_status">
                                <option value=""> -- Keputusan -- </option>
                                <option value="1">Terima</option>
                                <option value="2">Tolak</option>
                                <option value="3">Tunda</option>
                                <option value="4">Investigasi</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" placeholder="Catatan" wire:model="head_devisi_note"></textarea>
                    </div>
                    <div class="form-group">
                        <span wire:loading wire:target="save_devisi_syariah">
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                        <a href="javascript:void(0)" wire:loading.remove wire:target="save_devisi_syariah" class="btn btn-info" wire:click="save_devisi_syariah"><i class="fa fa-check-circle"></i> Submit</a>
                    </div>
                @else
                    <div class="my-3">
                        <span class="badge badge-info">{{$keputusa_arr[$data->head_devisi_status]}}</span>
                        {{$data->head_devisi_note}}
                    </div>
                @endif
            </td>
            <td>
                @if($data->head_devisi_date)
                    {{date('d-F-Y',strtotime($data->head_devisi_date))}}
                @endif
            </td>
        </tr>
    </table>
</div>
