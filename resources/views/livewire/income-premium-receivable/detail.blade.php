@section('title', 'Premium Receivable '.$data->no_voucher)
@section('parentPageTitle', 'Income')
<div class="clearfix">
    <div class="card">
        <div class="body">
            <form id="basic-form" method="post" wire:submit.prevent="save">
                <div class="row">
                    <div class="col-md-6">
                        @error('is_submit')
                        <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                        <table class="table pl-0 mb-0 table-nowrap">
                            <tr>
                                <th style="width:30%">{{ __('Policy Number / Policy Holder')}}</th>
                                <td style="width:70%">{{$data->client}}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Debit Note / Kwitansi Number')}}</th>
                                <td>
                                    <a href="#"  wire:click="$set('showDetail','underwriting')" title="Detail Debit Note / Kwitansi Number">{{$data->reference_no}}</a>
                                    @if($data->status==1)
                                        <span class="badge badge-warning" title="Handling Fee belum bisa di proses sebelum Status Premi diterima.">Unpaid</span>
                                    @endif
                                    @if($data->status==2)
                                        <span class="badge badge-success" title="Premi Paid">Paid</span>
                                    @endif
                                    @if($data->status==3)
                                        <span class="badge badge-warning" title="Outstanding">Outstanding</span>
                                    @endif
                                    @if($data->status==4)
                                        <span class="badge badge-danger" title="Premi Cancel">Cancel</span>
                                    @endif
                                    {!!flag($data)!!}
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('Due Date')}}</th>
                                <td>
                                    {{date('d M Y', strtotime($data->due_date))}} 
                                    @if(!$is_readonly)
                                        <a href="javascript:;" data-toggle="modal" data-target="#modal_extend_due_date"><i class="fa fa-plus"></i> Extend due date</a>
                                    @endif
                                </td>
                                    
                            </tr>
                            <tr>
                                <th>{{ __('Reference Date')}}</th>
                                <td>{{$data->reference_date}}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Premium Receivable')}}</th>
                                <td>{{format_idr($data->nominal)}}</td>
                            </tr>

                            @if($data->type==1)
                                @if($data->cancelation_konven->count())
                                <tr>
                                    <th>{{ __('Cancelation')}}</th>
                                    <td>
                                        @foreach($data->cancelation_konven as $cancel)
                                        <p>{!!format_idr($cancel->nominal).' - <a href="javascript:void(0);" class="text-danger" title="Klik Detail" wire:click="showDetailCancelation('.$cancel->id.')">'.$cancel->konven->no_credit_note.'</a>'!!}</p> 
                                        @endforeach
                                    </td>
                                </tr> 
                                @endif
                                @if($data->endorsement_konven->count())
                                <tr>
                                    <th>{{ __('Endorsement')}}</th>
                                    <td>
                                        @foreach($data->endorsement_konven as $cancel)
                                        <p>{!!format_idr($cancel->nominal).' - <a href="javascript:void(0);" class="text-danger" title="Klik Detail" wire:click="showDetailCancelation('.$cancel->id.')">'.$cancel->konven->no_credit_note.'</a>'!!}</p> 
                                        @endforeach
                                    </td>
                                </tr> 
                                @endif
                            @endif
                            @if($data->type==2)
                                @if($data->cancelation_syariah->count())
                                <tr>
                                    <th>{{ __('Cancelation')}}</th>
                                    <td>
                                        @foreach($data->cancelation_syariah as $cancel)
                                        <p>{!!format_idr($cancel->nominal).' - <a href="javascript:void(0);" class="text-danger" title="Klik Detail" wire:click="showDetailCancelation('.$cancel->transaction_id.')">'.$cancel->syariah->no_credit_note.'</a>'!!}</p> 
                                        @endforeach
                                    </td>
                                </tr> 
                                @endif
                                @if($data->endorsement_syariah->count())
                                <tr>
                                    <th>{{ __('Endorsement')}}</th>
                                    <td>
                                        @foreach($data->endorsement_syariah as $endors)
                                        <p>{!!format_idr($endors->nominal).' - <a href="javascript:void(0);" class="text-danger" title="Klik Detail" wire:click="showDetailCancelation('.$endors->transaction_id.')">'.$endors->syariah->no_dn_cn.'</a>'!!}</p> 
                                        @endforeach
                                    </td>
                                </tr> 
                                @endif
                            @endif
                            <tr>
                                <th>{{ __('Outstanding')}}</th>
                                <td>{{format_idr($outstanding_balance)}}</td>
                            </tr>
                            <tr>
                                <th>{{__('Description')}}</th>
                                <td>
                                    <textarea style="height:100px;" {{$is_readonly?'disabled':''}} class="form-control" wire:model="description"></textarea>
                                </td>
                            </tr>
                            <tr>
                                <tr>
                                    <th>Distribution Channel</th>
                                    <td>
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
                                    </td>
                                </tr>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
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
                                        <th>Debit Note / Kwitansi</th>
                                        <th>Description</th>
                                        <th>Amount</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($data->status==2 and $data->settle)
                                        @foreach($data->settle as $num => $item)
                                            <tr>
                                                <td>{{$num+1}}</td>
                                                <td>
                                                    {{$item->type==2?'Premim Deposit':''}}
                                                    {{$item->type==3?'Offset Claim Payable':''}}
                                                    {{$item->type==4?'Error Suspense Account':''}}
                                                </td>
                                                <td></td>
                                                <td></td>
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
                                                    <option value="2">Premium Deposit</option>
                                                    <option value="3">Offset Claim Payable</option>
                                                    <option value="4">Error Suspense Account</option>
                                                </select>
                                            </td>
                                            <td>
                                                @if($payment_type[$k]==2)
                                                    <select class="form-control" wire:model="payment_ids.{{$k}}">
                                                        <option value=""> -- Select Premium Deposit -- </option>
                                                        @foreach($premium_deposits as $premium)
                                                            <option value="{{$premium->id}}">{{$premium->reference_no}} - {{format_idr($premium->nominal)}}</option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                                @if($payment_type[$k]==3)
                                                    <select class="form-control" wire:model="payment_ids.{{$k}}">
                                                        <option value=""> -- Select Offset Claim Payable -- </option>
                                                        @foreach($claims as $claim)
                                                            <option value="{{$claim->id}}">{{$claim->reference_no}} - {{format_idr($claim->outstanding_balance=="" ? $claim->payment_amount : $claim->outstanding_balance)}}</option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                                <span wire:loading wire:target="payment_type.{{$k}}">
                                                    <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                                                    <span class="sr-only">Loading...</span>
                                                </span>
                                            </td>
                                            <td>
                                                @if($payment_type[$k]==2  and $payment_ids[$k])
                                                    {{isset($payment_rows[$k]->description) ? $payment_rows[$k]->description:'-'}}
                                                @endif
                                                @if($payment_type[$k]==3 and $payment_ids[$k])
                                                    @php($claim_peserta = \App\Models\ExpensePeserta::where('expense_id',$payment_ids[$k])->get())
                                                    @foreach($claim_peserta as $peserta)
                                                        <span>{{$peserta->no_peserta}} / {{$peserta->nama_peserta}}</span><br />
                                                    @endforeach
                                                @endif
                                                @if($payment_type[$k]==4)
                                                    <textarea class="form-control" wire:model="payment_ids.{{$k}}" placeholder="Debit Note / Kwitansi / Description"></textarea>
                                                @endif
                                            </td>
                                            <td>
                                                @if($payment_type[$k]==2 and $payment_ids[$k])
                                                    {{isset($payment_rows[$k]->nominal) ? format_idr($payment_rows[$k]->nominal):'-'}}
                                                @endif
                                                @if($payment_type[$k]==3 and $payment_ids[$k])
                                                    {{isset($payment_rows[$k]->payment_amount) ? format_idr($payment_rows[$k]->payment_amount?$payment_rows[$k]->outstanding_balance:$payment_rows[$k]->payment_amount):'-'}}
                                                @endif
                                                @if($payment_type[$k]==4)
                                                    <input type="number" class="form-control" wire:model="payment_amounts.{{$k}}" placeholder="Amount" />
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
                                                <a href="javascript:void(0)" wire:loading.remove wire:target="add_payment" wire:click="add_payment"><i class="fa fa-plus"></i> Add Row</a></td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr style="background:#eee">
                                        <th colspan="4" class="text-right">Outstanding</th>
                                        <th class="text-right">{{format_idr($data->nominal - $total_payment_amount)}}</th>
                                        <th></th>
                                    </tr>
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
                <a href="javascript:void0()" onclick="history.back()"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                @if(!$is_readonly)
                    @if($data->nominal == $total_payment_amount)
                        <button type="submit" class="ml-3 float-right btn btn-primary btn-sm"><i class="fa fa-save"></i> {{ __('Settle') }}</button>
                    @endif
                    <button type="button" class=" ml-3 btn btn-danger btn-sm" wire:click="$emit('emit-cancel',{{$data->id}})" data-target="#modal_cancel" data-toggle="modal""><i class="fa fa-times"></i> {{ __('Premi tidak tertagih') }}</button>
                    <span wire:loading wire:target="save">
                        <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                        <span class="sr-only">Loading...</span>
                    </span>
                @endif
                @if($data->status==2 and $is_otp_editable==false and $data->transaction_table !='Migration')
                    <a href="javascript:;" class="btn btn-danger ml-3" data-toggle="modal" data-target="#modal_konfirmasi_otp"><i class="fa fa-edit"></i> Edit </a>
                @endif
            </form>
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="modal_konfirmasi_otp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <livewire:income-premium-receivable.konfirmasi-otp />
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="modal_add_bank" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <livewire:income-premium-receivable.add-bank />
        </div>
    </div>
    <div wire:ignore.self class="modal fade" id="modal_extend_due_date" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <livewire:income-premium-receivable.extend-due-date :data="$data"/>
        </div>
    </div>
</div>
@push('after-scripts')
<script src="{{ asset('assets/js/jquery.priceformat.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/vendor/select2/css/select2.min.css') }}"/>
<script src="{{ asset('assets/vendor/select2/js/select2.min.js') }}"></script>
<style>
    .select2-container .select2-selection--single {height:36px;padding-left:10px;}
    .select2-container .select2-selection--single .select2-selection__rendered{padding-top:3px;}
    .select2-container--default .select2-selection--single .select2-selection__arrow{top:4px;right:10px;}
    .select2-container {width: 100% !important;}
</style>
<script>
Livewire.on('otp-editable',()=>{
    $("#modal_konfirmasi_otp").modal('hide');
});
Livewire.on('set-claim',(id)=>{
    $(".modal").modal("hide");
});
Livewire.on('set-titipan-premi',(id)=>{
    $("#modal_add_titipan_premi").modal("hide");
});
Livewire.on('refresh-page',()=>{
    $("#modal_add_bank").modal("hide");
    $("#modal_extend_due_date").modal("hide");
    setTimeout(function(){
        init_form();
    },500);
});
document.addEventListener("livewire:load", () => {
    init_form();
});
$(document).ready(function() {
    setTimeout(function(){
        init_form()
    })
});
var select__2;
function init_form(){
    $('.format_number').priceFormat({
        prefix: '',
        centsSeparator: '.',
        thousandsSeparator: '.',
        centsLimit: 0
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
Livewire.on('init-form',()=>{
    init_form();
});
Livewire.on('emit-add-bank',id=>{
    $("#modal_add_bank").modal('hide');
    select__2.val(id);
})
</script>
@endpush
<div wire:ignore.self class="modal fade" id="modal_add_titipan_premi" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="min-width:90%;" role="document">
        <livewire:income-premium-receivable.add-titipan-premi />
    </div>
</div>
<div wire:ignore.self class="modal fade" id="modal_cancel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <livewire:income-premium-receivable.cancel />
    </div>
</div>
<div wire:ignore.self class="modal fade" id="modal_add_claim_payable" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="min-width:90%;">
        <livewire:income-premium-receivable.add-claim-payable :data="$data"/>
    </div>
</div>
<div wire:ignore.self class="modal fade" id="modal_add_voucher" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="min-width:90%;">
        <livewire:income-premium-receivable.add-voucher :data="$data"/>
    </div>
</div>