@section('title', 'Setting')
@section('parentPageTitle', 'Dashboard')

<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <ul class="nav nav-tabs">                                
                    <li class="nav-item" wire:ignore><a class="nav-link active" data-toggle="tab" href="#Settings">General</a></li>
                    <li class="nav-item" wire:ignore><a class="nav-link" data-toggle="tab" href="#polis">Running Number</a></li>
                    <li class="nav-item" wire:ignore><a class="nav-link" data-toggle="tab" href="#tab_memo_cancel">Memo Cancel</a></li>
                    <li class="nav-item" wire:ignore><a class="nav-link" data-toggle="tab" href="#tab_memo_endorse">Memo Endorse</a></li>
                    <li class="nav-item" wire:ignore><a class="nav-link" data-toggle="tab" href="#tab_memo_refund">Memo Refund</a></li>
                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane active" id="Settings" wire:ignore>
                    <div class="body">
                        <form wire:submit.prevent="save">
                            <div class="row">
                                <div class="col-md-3">
                                    <h6>Logo</h6>
                                    <div class="media photo">
                                        <div class="media-left m-r-15">
                                            @if($logoUrl)
                                            <img src="{{$logoUrl}}" class="user-photo media-object" style="height:50px;" alt="User">
                                            @endif
                                        </div>
                                        <div class="media-body">
                                            @error('logo')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                            <p>Upload your logo.
                                                <br> <em>Image should be at least 140px x 140px</em></p>
                                            <button type="button" class="btn btn-default-dark" id="btn-upload-photo"><i class="fa fa-upload"></i> Select File</button>
                                            <input type="file" wire:model="logo" id="filePhoto" class="sr-only">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <h6>Favicon</h6>
                                    <div class="media photo">
                                        <div class="media-left m-r-15">
                                            @if($faviconUrl)
                                            <img src="{{$faviconUrl}}" class="user-photo media-object" style="height:50px;" alt="User">
                                            @endif
                                        </div>
                                        <div class="media-body">
                                            @error('favicon')
                                            <p class="text-danger">{{$message}}</p>
                                            @enderror
                                            <p>Upload your Favicon.
                                                <br> <em>Image should be at least 16px x 16px</em></p>
                                            <button type="button" class="btn btn-default-dark" id="btn-upload-favicon"><i class="fa fa-upload"></i> Select File</button>
                                            <input type="file" wire:model="favicon" id="fileFavicon" class="sr-only">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update</button>
                        </form>
                    </div>
                    <hr />
                    <div class="body">
                        <h6>Basic Information</h6>
                        <form  wire:submit.prevent="updateBasic">
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">                                                
                                        <input type="text" class="form-control" placeholder="Company" wire:model="company">
                                    </div>
                                    <div class="form-group">                                                
                                        <input type="text" class="form-control" placeholder="Phone" wire:model="phone">
                                    </div>
                                    <div class="form-group">                                                
                                        <input type="text" class="form-control" placeholder="Email" wire:model="email">
                                    </div>
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="http://" wire:model="website">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-group">    
                                        <textarea class="form-control" wire:model="address" style="height:180px;" placeholder="Address"></textarea>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
                <div class="tab-pane" id="tab_memo_cancel" wire:ignore>
                    <div class="body">
                        <form  wire:submit.prevent="updateCancel">
                            <div class="row clearfix">   
                                <div class="form-group col-md-3">                                                
                                    <label>Running Nomor Cancel</label>
                                    <input type="text" class="form-control" placeholder="" wire:model="running_number_cancel">
                                </div>
                                <div class="form-group col-md-3">                                                
                                    <label>Running Nomor CN </label>
                                    <input type="text" class="form-control" placeholder="" wire:model="running_number_cancel_cn">
                                </div>
                                <div class="col-12">
                                    <hr />
                                    <button type="submit" class="btn btn-info">Simpan Perubahan</button>
                                </div>
                            </div>
                        </form>
                    </div>              
                </div>
                <div class="tab-pane" id="tab_memo_endorse" wire:ignore>
                    <div class="body">
                        <form  wire:submit.prevent="updateEndorse">
                            <div class="row clearfix">   
                                <div class="form-group col-md-3">                                                
                                    <label>Running Nomor Endorse</label>
                                    <input type="text" class="form-control" placeholder="" wire:model="running_number_endorse">
                                </div>
                                <div class="form-group col-md-3">                                                
                                    <label>Running Nomor Endorse CN/DN</label>
                                    <input type="text" class="form-control" placeholder="" wire:model="running_number_endorse_cn_dn">
                                </div>
                                <div class="col-12">
                                    <hr />
                                    <button type="submit" class="btn btn-info">Simpan Perubahan</button>
                                </div>
                            </div>
                        </form>
                    </div>              
                </div>
                <div class="tab-pane" id="tab_memo_refund" wire:ignore>
                    <div class="body">
                        <form  wire:submit.prevent="updateRefund">
                            <div class="row clearfix">   
                                <div class="form-group col-md-3">                                                
                                    <label>Running Nomor Refund</label>
                                    <input type="text" class="form-control" placeholder="" wire:model="running_number_refund">
                                </div>
                                <div class="form-group col-md-3">                                                
                                    <label>Running Nomor Refund CN</label>
                                    <input type="text" class="form-control" placeholder="" wire:model="running_number_refund_cn">
                                </div>
                                <div class="col-12">
                                    <hr />
                                    <button type="submit" class="btn btn-info">Simpan Perubahan</button>
                                </div>
                            </div>
                        </form>
                    </div>              
                </div>
                <div class="tab-pane" id="polis" wire:ignore>
                    <div class="body">
                        <form  wire:submit.prevent="updatePolis">
                            <div class="row clearfix">
                                <div class="form-group col-md-3">                                                
                                    <label>Running Number Nota Penutupan</label>
                                    <input type="text" class="form-control" placeholder="" wire:model="running_number_nota_penutupan">
                                </div>
                                <div class="form-group col-md-3">                                                
                                    <label>Running Number SB</label>
                                    <input type="text" class="form-control" placeholder="" wire:model="running_number_sb">
                                </div>
                                <div class="form-group col-md-3">                                                
                                    <label>Running Nomor Surat</label>
                                    <input type="text" class="form-control" placeholder="" wire:model="running_surat">
                                </div>
                                <div class="form-group col-md-3">                                                
                                    <label>Running Nomor Memo Ujroh</label>
                                    <input type="text" class="form-control" placeholder="" wire:model="running_number_memo_ujroh">
                                </div>
                                <div class="form-group col-md-3">                                                
                                    <label>Running Nomor Recovery Claim</label>
                                    <input type="text" class="form-control" placeholder="" wire:model="running_number_recovery_claim">
                                </div>
                                <div class="form-group col-md-3">                                                
                                    <label>Running Nomor DN Recovery Claim</label>
                                    <input type="text" class="form-control" placeholder="" wire:model="running_number_dn_recovery_claim">
                                </div>
                                <div class="form-group col-md-3">                                                
                                    <label>Running Nomor Tagihan SOA</label>
                                    <input type="text" class="form-control" placeholder="" wire:model="running_number_tagihan_soa">
                                </div>
                                <div class="col-12">
                                    <hr />
                                    <button type="submit" class="btn btn-info">Simpan Perubahan</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('page-script')
    $(function() {
        // favicon upload
        $('#btn-upload-favicon').on('click', function() {
            $(this).siblings('#fileFavicon').trigger('click');
        });

        // photo upload
        $('#btn-upload-photo').on('click', function() {
            $(this).siblings('#filePhoto').trigger('click');
        });

        // plans
        $('.btn-choose-plan').on('click', function() {
            $('.plan').removeClass('selected-plan');
            $('.plan-title span').find('i').remove();

            $(this).parent().addClass('selected-plan');
            $(this).parent().find('.plan-title').append('<span><i class="fa fa-check-circle"></i></span>');
        });
    });

@stop
