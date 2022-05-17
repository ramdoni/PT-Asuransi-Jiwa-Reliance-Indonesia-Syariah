<div  x-data="{ insert:false }">
    <div class="form-group" x-show="insert" @click.away="insert = false">
        <input type="text" @keyup.escape="insert = false" placeholder="{{$field}}" class="form-control" wire:keydown.enter="save" x-on:keydown.enter="insert = false" x- wire:model="value" />
    </div>
    <a href="javascript:;" x-show="insert==false" @click="insert = true">
        @if(is_int($value))
            {{format_idr($value)}}
        @else
            {{$value?$value:'........'}}
        @endif
    </a>
    <div wire:loading wire:loading.target="save">
        <i class="fa fa-refresh fa-spin fa-1x fa-fw"></i>
        <span class="sr-only">Loading...</span>
    </div> 
</div>
