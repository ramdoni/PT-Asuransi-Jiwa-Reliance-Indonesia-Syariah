@section('title', 'Account Receivable')
@section('parentPageTitle', 'Premium Deposit')
<div class="clearfix row">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4">
                <div class="card mb-0">
                    <div class="body py-2">
                        <div class="number">
                            <h6 class="text-info">Total</h6>
                            <span>{{ format_idr($total) }}</span>
                        </div>
                    </div>
                    <div class="progress progress-xs progress-transparent custom-color-blue m-b-0">
                        <div class="progress-bar" data-transitiongoal="87" aria-valuenow="87" style="width: 100%;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-0">
                    <div class="body py-2">
                        <div class="number">
                            <h6 class="text-success">Used</h6>
                            <span>{{ format_idr($teralokasi) }}</span>
                        </div>
                    </div>
                    <div class="progress progress-xs progress-transparent custom-color-green m-b-0">
                        <div class="progress-bar" data-transitiongoal="87" aria-valuenow="87" style="width: 100%;">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card  mb-0">
                    <div class="body py-2">
                        <div class="number">
                            <h6 class="text-warning">Balance</h6>
                            <span>{{ format_idr($balance) }}</span>
                        </div>
                    </div>
                    <div class="progress progress-xs progress-transparent custom-color-yellow  m-b-0">
                        <div class="progress-bar" data-transitiongoal="87" aria-valuenow="87" style="width: 100%;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <div class="mb-2 row">
                    <div class="col-md-2">
                        <input type="text" class="form-control" wire:model="keyword" placeholder="Searching..." />
                    </div>
                    <div class="col-md-2">
                        <select class="form-control" wire:model="status">
                            <option value=""> --- Status --- </option>
                            <option value="1"> Outstanding </option>
                            <option value="2"> Complete</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="text" wire:ignore class="form-control payment_date" placeholder="Created Date" />
                    </div>
                    <div class="col-md-2">
                        <span wire:loading>
                            <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-hover m-b-0 c_list">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th class="text-center">Status</th>
                                <th>Voucher Number</th>
                                <th>Created Date</th>
                                <th>Reference No</th>
                                <th class="text-right">Payment Amount</th>
                                <th class="text-right">Used</th>
                                <th class="text-right">Balance</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $k => $item)
                                <tr>
                                    <td style="width: 50px;">{{ $k + 1 }}</td>
                                    <td class="text-center">
                                        <a href="{{ route('income.titipan-premi.detail', ['id' => $item->id]) }}">
                                            @if ($item->status == 1)
                                                <span class="badge badge-warning">Outstanding</span>
                                            @endif
                                            @if ($item->status == 2)
                                                <span class="badge badge-success">Completed</span>
                                            @endif
                                        </a>
                                    </td>
                                    <td>
                                        @if(isset($item->bank_book->no_voucher))
                                            <a href="javascript:void(0)" wire:click="$emit('set-voucher',{{$item->id}})" data-toggle="modal" data-target="#modal_detail_voucher">{{$item->bank_book->no_voucher}}</a>
                                        @endif

                                        {{-- @if(isset($item->bank_books_direct))
                                            @foreach($item->bank_books_direct as $k => $bank_book)
                                                @if($k>0) @continue @endif
                                                @if($bank_book->no_voucher) 
                                                    <a href="javascript:void(0)" wire:click="$emit('set-voucher',{{$item->id}})" data-toggle="modal" data-target="#modal_detail_voucher">{{$bank_book->no_voucher}}</a>
                                                @endif
                                            @endforeach
                                            @if($item->bank_books->count()>1) <a href="javascript:void(0)" wire:click="$emit('set-voucher',{{$item->id}})" data-toggle="modal" data-target="#modal_detail_voucher"><i class="fa fa-plus"></i></a> @endif
                                        @endif --}}
                                    </td>
                                    <td>{{ date('d M Y', strtotime($item->created_at)) }}</td>
                                    <td>{{ $item->description ? $item->description : '-' }}</td>
                                    <td class="text-right">{{ isset($item->nominal) ? format_idr($item->nominal) : '-' }}</td>
                                    <td class="text-right">{{ format_idr($item->payment_amount) }}</td>
                                    <td class="text-right">{{ format_idr($item->outstanding_balance) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <br />
                {{ $data->links() }}
            </div>
        </div>
    </div>
</div>

@push('after-scripts')
    <script type="text/javascript" src="{{ asset('assets/vendor/daterange/moment.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendor/daterange/daterangepicker.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendor/daterange/daterangepicker.css') }}" />
    <script>
        $('.payment_date').daterangepicker({
            locale: {
                cancelLabel: 'Clear'
            },
            autoUpdateInput: false,
            opens: 'left'
        }, function(start, end, label) {
            @this.set("payment_date_from", start.format('YYYY-MM-DD'));
            @this.set("payment_date_to", end.format('YYYY-MM-DD'));

            $('.payment_date').val(start.format('DD/MM/YYYY') + '-' + end.format('DD/MM/YYYY'));
        });

    </script>
@endpush
<div class="modal fade" wire:ignore.self id="modal_detail_voucher" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    @livewire('income-reinsurance.detail-voucher')
</div>