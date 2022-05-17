<div>
    @if(isset($data))
        @if(!$edit)
            @if($data->status==0)
                <a href="javascript:void(0)" wire:loading.remove wire:click="set_edit(true)">{{is_numeric($data->$field)?format_idr($data->$field):$data->$field}}</a>
            @else
                {{is_numeric($data->$field)?format_idr($data->$field):$data->$field}}
            @endif
        @endif
        <span wire:loading wire:target="save">
            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
            <span class="sr-only">{{ __('Loading...') }}</span>
        </span>
        <span wire:loading wire:target="set_edit">
            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
            <span class="sr-only">{{ __('Loading...') }}</span>
        </span>
        @if($edit)
            <div wire:loading.remove wire:target="save">
                @if($field=='type')
                    <select class="form-control" wire:model="value">
                        <option value=""> -- Type -- </option>
                        <option value="R">R - Receivable</option>
                        <option value="P">P - Payable</option>
                    </select>
                @else
                    <input type="{{$type_input}}" class="form-control {{$type_input=="number"?"text-right":""}}" wire:model="value" wire:keydown.enter="save" />
                @endif
                <div class="text-center">
                    <a href="javascript:void(0)" class="text-info" wire:click="save"><i class="fa fa-save"></i></a>
                    <a href="javascript:void(0)" class="text-danger" wire:click="set_edit(false)"><i class="fa fa-times"></i></a>
                </div>
            </div>
        @endif
    @endif
</div>
