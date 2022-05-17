@section('title', 'Claim Payable')
@section('parentPageTitle', 'Expense')
<div class="card">
    <div class="body">
        <form wire:submit.prevent="save">
            <div class="clearfix row">
                <div class="col-md-5">
                    <table class="table table-striped table-hover m-b-0 c_list">
                        <tbody>
                            <tr>
                                <th>{{ __('No Polis') }}</th>
                                <td>{{isset($expense->policy->no_polis)?$expense->policy->no_polis:'-'}}</td>
                            </tr>
                            <tr>
                                <th>Type</th>
                                <td>{{$type==1?"Konven":"Syariah"}}</td>
                            </tr>
                            <tr>
                                <th>Payment Amount</th>
                                <td>{{format_idr($nilai_klaim)}}</td>
                            </tr>
                            <tr>
                                <th>Reference No</th>
                                <td>{{$reference_no}}</td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td>{{$description}}</td>
                            </tr>
                            @if($is_readonly)
                                <tr>
                                    <th>Distribution Channel</th>
                                    <td>{{isset($expense->distribution_channel->name)?$expense->distribution_channel->name:'-'}}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <br />
                    @if(!$is_readonly)
                        <div class="form-group">
                            <label>Distribution Channel</label>
                            <select class="form-control" wire:model="distribution_channel_id">
                                <option value="">-- Select -- </option>
                                @foreach($distribution_channel as $item)
                                    <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                            @error('distribution_channel_id')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                    @endif
                    <div class="form-group">
                        <label>{{__('Peserta')}}</label>
                        @foreach($add_pesertas as $k => $v)
                            <div class="form-group">
                                <input type="text" class="form-control mb-2" wire:model="no_peserta.{{$k}}" {{$is_readonly?'disabled':''}} placeholder="No Peserta" />
                                <input type="text" class="form-control" wire:model="nama_peserta.{{$k}}" {{$is_readonly?'disabled':''}} placeholder="Nama Peserta" />
                                @if(!$is_readonly)
                                <a href="javascript:;" class="text-danger" wire:click="delete_peserta({{$k}})"><i class="fa fa-trash"></i> Delete</a>
                                @endif
                            </div>
                        @endforeach
                        @foreach($add_pesertas_temp as $k => $v)
                            <div class="form-group">
                                <input type="text" class="form-control mb-2" wire:model="no_peserta_temp.{{$k}}" placeholder="No Peserta" />
                                <input type="text" class="form-control" wire:model="nama_peserta_temp.{{$k}}" placeholder="Nama Peserta" />
                                <a href="javascript:;" class="text-danger" wire:click="delete_peserta_temp({{$k}})"><i class="fa fa-trash"></i> Delete</a>
                            </div>
                        @endforeach
                        @if(!$is_readonly)
                        <a href="javascript:;" wire:click="add_peserta"><i class="fa fa-plus"></i> Add Peserta</a>
                        @endif
                    </div>
                </div>
                <div class="col-md-7">
                    <h6>Settle</h6>
                    @if($error_settle)
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <i class="fa fa-times-circle"></i> {{$error_settle}}
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr style="background:#eee">
                                    <th style="width:50px;">No</th>
                                    <th>Type</th>
                                    <th>Credit Note / Kwitansi</th>
                                    <th>Description</th>
                                    <th class="text-right">Amount</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($expense->settle)
                                    @foreach($expense->settle as $num => $item)
                                        <tr>
                                            <td>{{$num+1}}</td>
                                            <td>
                                                {{$item->type==1?'Voucher':''}}
                                                {{$item->type==2?'Premium Receivable':''}}
                                                {{$item->type==3?'Error Suspense Account':''}}
                                            </td>
                                            <td>{{$item->credit_note}}</td>
                                            <td>{{$item->description}}</td>
                                            <td class="text-right">{{format_idr($item->amount)}}</td>
                                        </tr>
                                        @php($total_payment_amount += $item->amount)
                                    @endforeach
                                @endif
            
                                @foreach($payment_ids as $k => $item)
                                    <tr>
                                        <td>{{$k+1}}</td>
                                        <td>
                                            <select class="form-control" wire:model="payment_type.{{$k}}">
                                                <option value=""> -- Type -- </option>
                                                <option value="1">Voucher</option>
                                                <option value="2">Premium Receivable</option>
                                                <option value="3">Error Suspense Account</option>
                                                <option value="4">Premium Suspend</option>
                                            </select>
                                        </td>
                                        <td>
                                            @if($payment_type[$k]==1)
                                                <select wire:ignore class="form-control select-voucher"  id="transaction_ids.{{$k}}">
                                                    <option value=""> -- Select Voucher -- </option>
                                                </select>
                                            @endif
                                            @if($payment_type[$k]==2)
                                                <select wire:ignore  class="form-control select-premi" id="transaction_ids.{{$k}}">
                                                    <option value=""> -- Select Premium Receivable -- </option>
                                                </select>
                                            @endif
                                            <span wire:loading wire:target="payment_type.{{$k}}">
                                                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                                <span class="sr-only">Loading...</span>
                                            </span>
                                        </td>
                                        <td>
                                            @if($payment_type[$k]==1  and $transaction_ids[$k])
                                                {{isset($payment_rows[$k]->note) ? $payment_rows[$k]->note:'-'}}
                                            @endif
                                            @if($payment_type[$k]==2 and $transaction_ids[$k])
                                                {{isset($payment_rows[$k]->client) ? $payment_rows[$k]->client:'-'}}
                                            @endif
                                            @if($payment_type[$k]==3)
                                                <textarea class="form-control" wire:model="payment_description.{{$k}}" placeholder="Credit Note / Kwitansi / Description"></textarea>
                                            @endif
                                        </td>
                                        <td>
                                            @if($payment_type[$k]==1 || $payment_type[$k]==2)
                                                {{isset($amounts[$k]) ? format_idr($amounts[$k]):'0'}}
                                            @endif
                                            
                                            @if($payment_type[$k]==3)
                                                <input type="number" class="form-control" wire:model="amounts.{{$k}}" placeholder="Amount" />
                                            @endif
                                        </td>
                                        <td>
                                            <span wire:loading wire:target="delete_payment_type">
                                                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                                <span class="sr-only">Loading...</span>
                                            </span>
                                            <a href="javascript:void(0)" wire:loading.remove wire:target="delete_payment_type" class="text-danger" wire:click="delete_payment_type({{$k}})"><i class="fa fa-trash"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                                @if(!$is_readonly)
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <span wire:loading wire:target="add_payment">
                                                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                                <span class="sr-only">Loading...</span>
                                            </span>
                                            <a href="javascript:void(0)" wire:loading.remove wire:target="add_payment" wire:click="add_payment"><i class="fa fa-plus"></i> Add Row</a>
                                        </td>
                                    </tr>
                                @endif
                            </tbody>
                            <tfoot>
                                <tr style="background:#eee">
                                    <th colspan="4" class="text-right">Total</th>
                                    <th class="text-right">{{format_idr($total_payment_amount)}}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <hr />
            <a href="javascript:void0()" class="mx-2" onclick="history.back()"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
            @if($total_payment_amount==$nilai_klaim)
                <button type="submit" class="ml-3 btn btn-primary"><i class="fa fa-save"></i> {{ __('Settle') }}</button>
            @endif
            <div wire:loading wire:target="save">
                <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                <span class="sr-only">Loading...</span>
            </div>
        </form>
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
        var select_premi,select_voucher;
        Livewire.on('select-type',()=>{
            select_premi = $('.select-premi').select2({
                placeholder: " -- select -- ",
                ajax: {
                    url: '{{route('ajax.get-premium-receivable')}}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.reference_no + " - " + item.client +" - Rp. "+item.nominal,
                                id: item.id
                            }
                        })
                    };
                    },
                    cache: true
                }
            });
            $('.select-premi').on('change', function (e) {
                let elementName = $(this).attr('id');
                var data = $(this).select2("val");
                @this.set(elementName, data);
            });

            select_voucher = $('.select-voucher').select2({
                placeholder: " -- select -- ",
                ajax: {
                    url: '{{route('ajax.get-voucher-payable')}}',
                    dataType: 'json',
                    delay: 250,
                    processResults: function (data) {
                    return {
                        results:  $.map(data, function (item) {
                            return {
                                text: item.no_voucher + " - " +  " / "+item.from_bank_ +" - Rp. "+item.amount,
                                id: item.id
                            }
                        })
                    };
                    },
                    cache: true
                }
            });
            $('.select-voucher').on('change', function (e) {
                let elementName = $(this).attr('id');
                var data = $(this).select2("val");
                @this.set(elementName, data);
            });
        })
    </script>
@endpush