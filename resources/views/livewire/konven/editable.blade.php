<div  x-data="{ insert:false }">
    <div class="form-group" x-show="insert" @click.away="insert = false">
        @if($field=='line_bussines')
            <select class="form-control" wire:model="value">
                <option value=""> -- Select -- </option>
                @foreach(config('vars.line_bussines') as $item)
                    <option>{{$item}}</option>
                @endforeach
            </select>
        @elseif($field=='channel_type')
            <select class="form-control" wire:model="value">
                <option value=""> -- Select -- </option>
                @foreach(config('vars.distribution_type') as $item)
                    <option>{{$item}}</option>
                @endforeach
            </select>
        @else
            <input type="text" @keyup.escape="insert = false" placeholder="{{$field}}" class="form-control" wire:keydown.enter="save" x-on:keydown.enter="insert = false" x- wire:model="value" />
        @endif
        <a href="javascript:void(0)" x-show="insert==true" @click="insert = false" wire:click="save"><i class="fa fa-save"></i></a>
    </div>
    <a href="javascript:;" x-show="insert==false" @click="insert = true">
        @if(is_int($value))
            {{format_idr($value)}}
        @else
            {!!$value?$value:'<i class="text-muted"><u>empty</u></i>'!!}
        @endif
    </a>
    <div wire:loading wire:target="save">
        <i class="fa fa-refresh fa-spin fa-1x fa-fw"></i>
        <span class="sr-only">Loading...</span>
    </div> 
</div>
