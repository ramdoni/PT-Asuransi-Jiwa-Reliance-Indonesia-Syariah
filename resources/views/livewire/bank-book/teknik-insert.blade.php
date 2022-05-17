@section('title', __('Teknik'))
@section('parentPageTitle', 'Insert')

<div class="row clearfix">
    <div class="col-md-6">
        <div class="card">
            <div class="body">
                <form id="basic-form" method="post" wire:submit.prevent="save">
                    <div class="form-group">
                        <label>Voucher</label>
                        <select class="form-control" id="select-bank-book">
                            <option value="">-- select --</option>
                            @foreach($bank_books as $item)
                                <option value="{{$item->id}}">{{$item->no_voucher}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Payment Type') }}</label>
                        <select class="form-control" wire:model="payment_type">
                            <option value=""> -- select -- </option>
                            <option value="1">Voucher</option>
                            <option value="1">Voucher</option>
                        </select>
                        @error('name')
                            <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                        @enderror
                    </div>
                    <hr>
                    <a href="{{route('bank-book.teknik')}}"><i class="fa fa-arrow-left"></i> {{ __('Back') }}</a>
                    <button type="submit" class="btn btn-primary ml-3"><i class="fa fa-save"></i> {{ __('Submit') }}</button>
                </form>
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
        select__2 = $('#select-bank-book').select2();
        $('#select-bank-book').on('change', function (e) {
            let elementName = $(this).attr('id');
            var data = $(this).select2("val");
            @this.set(elementName, data);
        });
        var selected__ = $('#select-bank-book').find(':selected').val();
        if(selected__ !="") select__2.val(selected__);
    </script>
@endpush