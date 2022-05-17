<div class="modal-content">
    <form wire:submit.prevent="save">
        <div class="row p-3">
            <div class="col-md-2">
                <input type="number" class="form-control" wire:model="keyword" placeholder="Searching Amount" />
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" wire:model="peserta" placeholder="Peserta" />
            </div>
            <div class="col-md-7">
                <label class="mt-2">Total : Rp. {{format_idr($total)}}</label>
                <div wire:loading class="mt-1 ml-3">
                    <i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i>
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover m-b-0 c_list">
                    <thead>
                        <tr>
                            <th></th>                                          
                            <th>No Voucher</th>                                      
                            <th>Record Date</th>         
                            <th>Debit Note / Kwitansi</th>           
                            <th>No / Nama Peserta</th>
                            <th>Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($claim as $k => $item)
                        <tr>
                            <td>
                                <div class="form-group mb-0">
                                    <label class="fancy-checkbox">
                                        <input type="checkbox" wire:model="check_id" value="{{$item->id}}" required data-parsley-errors-container="#error-checkbox">
                                        <span></span>
                                    </label>
                                </div>
                            </td>
                            <td><a href="{{route('expense.claim.detail',['id'=>$item->id])}}" target="_blank">{!!no_voucher($item)!!}</a></td>
                            <td>{{date('d M Y', strtotime($item->created_at))}}</td>
                            <td>{{$item->reference_no ? $item->reference_no : '-'}}</td>
                            <td>
                                @if(isset($item->pesertas))
                                    @foreach($item->pesertas as $peserta)
                                        <span>{{$peserta->no_peserta}} / {{$peserta->nama_peserta}}</span><br />
                                    @endforeach
                                @endif
                            </td>
                            <td>{{isset($item->payment_amount) ? format_idr($item->payment_amount) : '-'}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <br />
            {{$claim->links()}}
        </div>
        <div class="modal-footer text-left" style="justify-content:left">
            @if($total!=0)
                <a href="javascript:void(0)" class="btn btn-info float-left" wire:click="submit">Submit</a>
            @endif
            <a href="#" data-dismiss="modal"><i class="fa fa-times"></i> Close</a>
        </div>
    </form>
</div>
@push('after-scripts')
<script>
    select__2 = $('.titipan_from_bank_account').select2();
    $('.titipan_from_bank_account').on('change', function (e) {
        var data = $(this).select2("val");
        @this.set('from_bank_account_id', data);
    });
    var selected__ = $('.titipan_from_bank_account').find(':selected').val();
    if(selected__ !="") select__2.val(selected__);
    
</script>
@endpush