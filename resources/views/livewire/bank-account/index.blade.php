@section('title', 'Bank Account')
@section('parentPageTitle', 'Home')

<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="header row">
                <div class="col-md-2">
                    <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
                </div>
                <div class="col-md-1">
                    <a href="javascript:void(0)" wire:click="$set('insert',true);" class="btn btn-primary"><i class="fa fa-plus"></i> Bank Account</a>
                    <span wire:loading wire:target="insert">
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span>
                    </span>
                </div>
            </div>
            <div class="body pt-0">
                <div class="table-responsive">
                    <table class="table table-striped m-b-0 c_list">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Code</th>                                    
                                <th>Bank</th>                                    
                                <th>No Rekening</th>                                    
                                <th>Owner</th>                                    
                                <th>Cabang</th>
                                <th>Open Balance</th>
                                <th>Active Date</th>
                                <th>Chart of Account</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if($insert)
                                <tr>
                                    <td></td>
                                    <td>
                                        <input type="text" class="form-control" wire:model="code" />
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" wire:model="bank" />
                                        @error('bank')
                                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" wire:model="no_rekening" />
                                        @error('no_rekening')
                                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" wire:model="owner" />
                                        @error('owner')
                                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" wire:model="cabang" />
                                        @error('cabang')
                                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="number" class="form-control" wire:model="open_balance" />
                                        @error('open_balance')
                                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="date" class="form-control" wire:model="active_date" />
                                        @error('active_date')
                                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                        @enderror
                                    </td>
                                    <td>
                                        <select class="form-control select2" wire:model="coa_id">
                                            <option value=""> --- Account -- </option>
                                            @foreach(\App\Models\CoaGroup::orderBy('name','ASC')->get() as $group)
                                                <optgroup label="{{$group->name}}">
                                                    @foreach(\App\Models\Coa::where('coa_group_id',$group->id)->orderBy('name','ASC')->get() as $i)
                                                    <option value="{{$i->id}}">{{$i->name}} / {{$i->code}}</option>
                                                    @endforeach
                                                </optgroup>
                                            @endforeach
                                        </select>
                                        @error('coa_id')
                                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                        @enderror
                                    </td>
                                    <td>
                                        <span wire:loading wire:target="save">
                                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                            <span class="sr-only">{{ __('Loading...') }}</span>
                                        </span>
                                        <a href="javascript:void(0)" wire:loading.remove wire:click="save" class="text-success mr-2"><i class="fa fa-save"></i></a>
                                        <a href="javascript:void(0)" wire:loading.remove wire:click="$set('insert',false)"><i class="fa fa-times"></i></a>
                                    </td>
                                </tr>
                            @endif
                            @foreach($data as $k => $item)
                                <tr>
                                    <td style="width: 50px;">{{$k+1}}</td>
                                    <td><a href="{{route('bank-account.edit',['id'=>$item->id])}}">{{$item->code}}</a></td>
                                    <td><a href="{{route('bank-account.edit',['id'=>$item->id])}}">{{$item->bank}}</a></td>
                                    <td>{{$item->no_rekening}}</td>
                                    <td>{{$item->owner}}</td>
                                    <td>{{$item->cabang}}</td>
                                    <td>{{format_idr($item->open_balance)}}</td>
                                    <td>{{$item->active_date?date('Y-m-d',strtotime($item->active_date)):'-'}}</td>
                                    <td>{{isset($item->coa->name)?$item->coa->code.' - '.$item->coa->name : ''}}</td>
                                    <td>
                                        @if($item->status==0 || $item->status=="")
                                            <a href="javascript:void(0)" class="badge badge-danger">Inactive</a>
                                        @else
                                            <a href="javascript:void(0)" class="badge badge-success">Active</a>
                                        @endif
                                    </td>
                                    <td><a href="javascript:void(0)" wire:click="delete({{$item->id}})" class="text-danger"><i class="fa fa-trash"></i></a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <br />
                {{$data->links()}}
            </div>
        </div>
    </div>
</div>