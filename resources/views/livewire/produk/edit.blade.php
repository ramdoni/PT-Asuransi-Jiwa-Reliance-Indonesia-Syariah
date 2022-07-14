<div class="modal-dialog"  role="document">
    <div class="modal-content">
        <form wire:submit.prevent="save">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><i class="fa fa-plus"></i> Produk</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true close-btn">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Kode Produk</label>
                            <input type="text" class="form-control" wire:model="kode" />
                            @error('kode')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Nama Produk</label>
                            <input type="text" class="form-control" wire:model="nama" />
                            @error('nama')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Singkatan Produk</label>
                            <input type="text" class="form-control" wire:model="singkatan" />
                            @error('singkatan')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Klasifikasi</label>
                            <select class="form-control" wire:model="klasifikasi">
                                <option value=""> -- Pilih -- </option>
                                <option>AJK</option>
                                <option>GTL</option>
                            </select>
                            @error('klasfikasi')
                                <ul class="parsley-errors-list filled" id="parsley-id-29"><li class="parsley-required">{{ $message }}</li></ul>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-info"><i class="fa fa-save"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>