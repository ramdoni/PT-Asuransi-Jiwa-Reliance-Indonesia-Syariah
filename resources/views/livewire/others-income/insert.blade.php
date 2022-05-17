@section('title', 'Account Receivable')
@section('parentPageTitle', 'Others')
<div class="clearfix row">
    <div class="col-md-7">
        <div class="card">
            <div class="body">
                <form id="basic-form" method="post" wire:submit.prevent="save('Submit')">
                    @foreach($add_payment as $k => $item)
                        <div class="row">
                            <div class="col-md-7">
                                <div class="form-group">
                                    <label>{{ __('Description') }}</label>
                                    <input type="text" class="form-control" wire:model="add_payment_description.{{$k}}" />
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label>{{ __('Payment Amount (Rp)') }}</label>
                                    <input type="text" {{$is_readonly?'disabled':''}} class="form-control format_number text-right" wire:ignore wire:model="add_payment_amount.{{$k}}" />
                                    @error('payment_amount')
                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <hr />
                    <a href="javascript:;" wire:click="addPayment"><i class="fa fa-plus"></i> Payment</a>
                    <hr>
                    <a href="javascript:void0()" onclick="history.back()"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                    <button type="submit" class="ml-3 btn btn-primary"><i class="fa fa-save"></i> {{ __('Submit') }}</button>
                    <span wire:loading>
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">{{ __('Loading...') }}</span>
                    </span>
                </form>
            </div>
        </div>
    </div>
</div>
<div wire:ignore.self class="modal fade" id="modal_add_bank" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <livewire:expense-others.add-bank />
    </div>
</div>
@push('after-scripts')
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/css/select2.min.css') }}"/>
<script src="{{ asset('assets/vendor/select2/js/select2.min.js') }}"></script>
<style>
    .select2-container .select2-selection--single {height:36px;padding-left:10px;}
    .select2-container .select2-selection--single .select2-selection__rendered{padding-top:3px;}
    .select2-container--default .select2-selection--single .select2-selection__arrow{top:4px;right:10px;}
    .select2-container {width: 100% !important;}
</style>
<script src="{{ asset('assets/js/jquery.priceformat.min.js') }}"></script>
<script>
    Livewire.on('emit-add-bank',()=>{
        $("#modal_add_bank").modal("hide");    
    });
    Livewire.on('init-form', () =>{
        setTimeout(function(){
            init_form();
        },500);
    });
    function init_form(){
        $('.format_number').priceFormat({
            prefix: '',
            centsSeparator: '.',
            thousandsSeparator: '.',
            centsLimit: 0
        });
        $(".select_transaction_type").each(function(){
            select_transaction_type = $(this).select2();
            $(this).on('change', function (e) {
                let elementName = $(this).attr('id');
                var data = $(this).select2("val");
                @this.set(elementName, data);
            });
            var selected_transaction_type = $(this).find(':selected').val();
            if(selected_transaction_type !="") select_transaction_type.val(selected_transaction_type);
        });
        select__2 = $('.from_bank_account').select2();
        $('.from_bank_account').on('change', function (e) {
            let elementName = $(this).attr('id');
            var data = $(this).select2("val");
            @this.set(elementName, data);
        });
        var selected__ = $('.from_bank_account').find(':selected').val();
        if(selected__ !="") select__2.val(selected__);
    }   
    setTimeout(function(){
        init_form()
    })
</script>
@endpush