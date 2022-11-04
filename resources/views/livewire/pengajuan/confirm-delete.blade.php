<div>
    @if($confirm_delete)
        <a href="javascript:void(0)" wire:loading.remove wire:target="delete" wire:click="delete" class="text-danger px-2">Ya</a>
        <a href="javascript:void(0)" wire:loading.remove wire:target="confirm_delete" wire:click="$set('confirm_delete',false)" class="text-success">Tidak</a>
    @else
        <a href="javascript:void(0)" wire:loading.remove wire:target="confirm_delete" wire:click="$set('confirm_delete',true)"><i class="fa fa-trash text-danger"></i></a>
    @endif
    <span wire:loading>
        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
        <span class="sr-only">{{ __('Loading...') }}</span>
    </span>
</div>
