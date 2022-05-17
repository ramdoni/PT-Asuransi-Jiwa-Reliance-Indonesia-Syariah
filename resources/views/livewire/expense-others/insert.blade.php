@section('title', 'Others Expense')
@section('parentPageTitle', 'Expense')
<div class="clearfix row">
    <div class="col-md-7">
        <div class="card">
            <div class="body">
                <form id="basic-form" method="post" wire:submit.prevent="save('Submit')">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="px-0 form-group col-md-12">
                                <label>{{ __('Recipient') }}</label>
                                <input type="text" class="form-control" wire:model="recipient" />
                                @error('recipient')
                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>{{ __('Reference Date') }} *<small>{{__('Default today')}}</small></label>
                                <input type="date" class="form-control" wire:model="reference_date" />
                                @error('reference_date')
                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="px-0 form-group col-md-12">
                                <label>{{ __('Reference No') }}</label>
                                <input type="text" class="form-control" wire:model="reference_no" />
                                @error('reference_no')
                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                @enderror
                            </div>
                            <div class="px-0 form-group col-md-12">
                                <label>{{ __('Description') }}</label>
                                <textarea class="form-control" wire:model="description" placeholder="Description"></textarea>
                            </div>
                        </div>
                    </div>
                    <hr />
                    @foreach($add_payment as $k => $item)
                        <div class="row">
                            {{-- <div class="col-md-4">
                                <div class="form-group">
                                    <label>{{ __('Transaction Type') }}</label>
                                    <select class="form-control select_transaction_type" id="add_payment_transaction_type.{{$k}}" wire:model="add_payment_transaction_type.{{$k}}">
                                        <option value=""> --- Select --- </option>
                                        @foreach(\App\Models\Coa::where('is_others_expense',1)->groupBy('coa_group_id')->get() as $group)
                                            <optgroup label="{{isset($group->group->name) ? $group->group->name : ''}}">
                                                @foreach(\App\Models\Coa::where(['is_others_expense'=>1,'coa_group_id'=>$group->coa_group_id])->get() as $coa)
                                                    <option value="{{$coa->id}}">{{$coa->name}} ({{$coa->code}})</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    <a href="javascript:;" title="Delete" wire:click="delete({{$k}})" class="text-danger"><i class="fa fa-trash"></i> Delete</a>
                                    @error('add_payment_transaction_type.'.$k)
                                    <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </div>
                            </div> --}}

                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>{{ __('Description') }}</label>
                                    <input type="text" class="form-control" wire:model="add_payment_description.{{$k}}" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="text-right">{{ __('Amount (Rp)') }}</label>
                                    <input type="text" {{$is_readonly?'disabled':''}} class="form-control format_number text-right" wire:ignore wire:model="add_payment_amount.{{$k}}" />
                                    @error('add_payment_amount')
                                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                                    @enderror
                                </div>
                                <a href="javascript:;" style="position: absolute;right: 14px;" title="Delete" wire:click="delete({{$k}})" class="text-danger"><i class="fa fa-trash"></i></a>
                            </div>
                        </div>
                    @endforeach
                    <div class="row">
                        <div class="col-md-8 text-right">
                            <label>Total</label>
                        </div>
                        <div class="col-md-4 text-right">
                            <label>{{format_idr($payment_amount)}}</label>
                        </div>
                    </div>
                    <a href="javascript:;" wire:click="addPayment"><i class="fa fa-plus"></i> Add Row</a>
                    <hr>
                    <a href="javascript:void0()" onclick="history.back()"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                    <button type="submit" class="ml-3 btn btn-primary"><i class="fa fa-save"></i> {{ __('Submit') }}</button>
                    <span wire:loading wire:target="save">
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
            init_form();
        });
        function init_form(){
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
            $('.format_number').priceFormat({
                prefix: '',
                centsSeparator: '.',
                thousandsSeparator: '.',
                centsLimit: 0
            });
            
            select__2 = $('.to_bank_account').select2();
            $('.to_bank_account').on('change', function (e) {
                let elementName = $(this).attr('id');
                var data = $(this).select2("val");
                @this.set(elementName, data);
            });
            var selected__ = $('.to_bank_account').find(':selected').val();
            if(selected__ !="") select__2.val(selected__);
        }   
        setTimeout(function(){
            init_form()
        })
    </script>
@endpush