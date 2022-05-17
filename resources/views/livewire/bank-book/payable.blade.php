@section('title', __('Account Payable'))
@section('parentPageTitle', __('Bank Book'))
<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <div class="row">
                    <div class="col-md-1">
                        <div class="pl-3 pt-2 form-group" x-data="{open_dropdown:false}" @click.away="open_dropdown = false">
                            <a href="javascript:void(0)" x-on:click="open_dropdown = ! open_dropdown" class="dropdown-toggle">
                                 Filter <i class="fa fa-search-plus"></i>
                            </a>
                            <div class="dropdown-menu show-form-filter" x-show="open_dropdown">
                                <form class="p-2">
                                    <div class="from-group my-2">
                                        <select class="form-control" wire:model="filter_status">
                                            <option value=""> - Status - </option>
                                            <option value="0">Unidentity</option>
                                            <option value="1">Settle</option>
                                        </select>
                                    </div>
                                    <div class="from-group my-2">
                                        <input type="number" class="form-control" wire:model="filter_amount" placeholder="Amount" />
                                    </div>
                                    <div class="from-group my-2" wire:ignore>
                                        <select class="form-control filter_from_bank">
                                            <option value=""> -- Bank Company -- </option>
                                            @foreach(\App\Models\BankAccount::where('is_client',0)->where('status',1)->get() as $k=>$item)
                                                <option value="{{$item->id}}">{{isset($item->no_rekening) ? $item->no_rekening .'- '.$item->bank.' an '. $item->owner : '-'}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <a href="javascript:void(0)" wire:click="clear_filter()"><small>Clear filter</small></a>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        @if($check_id)
                            <a href="javascript:void(0)" class="btn btn-info" data-toggle="modal" data-target="#modal_add"><i class="fa fa-plus"></i> Settle</a>
                        @endif
                        <span wire:loading>
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table hovered m-b-0 c_list">
                        <thead>
                            <tr style="background:#eee">
                                <th>No</th>
                                <th class="text-center">Settle</th>
                                <th class="text-center">Status</th>
                                <th>Voucher Number</th>
                                <th>Payment Date</th>
                                <th>Voucher Date</th>
                                <th class="text-right">Amount</th>
                                <th>Bank</th>
                                <th>Note</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php($num=$data->firstItem())
                            @foreach($data as $item)
                                <tr>
                                    <td>{{$num}}</td>
                                    <td class="text-center">
                                        @if($item->status==0)
                                            <input type="checkbox" wire:model="check_id" value="{{$item->id}}">
                                        @else
                                            {{date('d-M-Y',strtotime($item->date_pairing))}}
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($item->status==0)
                                            <span class="badge badge-warning">Open</span>
                                        @elseif($item->status==2)
                                            <span class="badge badge-danger">On Hold</span>
                                        @else
                                            <a href="javascript:void(0)" data-toggle="modal" wire:click="$emit('setid',{{$item->id}})" data-target="#modal_detail_transaction" class="badge badge-success">Post</a>
                                        @endif
                                    </td>
                                    <td>{{$item->no_voucher}}</td>
                                    <td>{{date('d-m-Y',strtotime($item->payment_date))}}</td>
                                    <td>{{date('d-m-Y',strtotime($item->created_at))}}</td>
                                    <td class="text-right">{{format_idr($item->amount)}}</td>
                                    <td>{{isset($item->from_bank->no_rekening) ? $item->from_bank->no_rekening .'- '.$item->from_bank->bank.' an '. $item->from_bank->owner : '-'}}</td>
                                    <td>{{$item->note}}</td>
                                    <td></td>
                                </tr>
                                @php($num++)
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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
    <script>
        $('.filter_from_bank').select2();
        $('.filter_from_bank').on('change', function (e) {
            var data = $(this).select2("val");
            @this.set('filter_from_bank', data);
        });
        $('.filter_to_bank').select2();
        $('.filter_to_bank').on('change', function (e) {
            var data = $(this).select2("val");
            @this.set('filter_to_bank', data);
        });
        Livewire.on('clear-filter',()=>{
            $('.filter_from_bank').val(null).trigger('change');
            $('.filter_to_bank').val(null).trigger('change');
        })
    </script>
@endpush
<div wire:ignore.self class="modal fade" id="modal_detail_transaction" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('bank-book.payable-detail',key(123))
</div>
<div wire:ignore.self class="modal fade" id="modal_add" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('bank-book.payable-insert')
</div>