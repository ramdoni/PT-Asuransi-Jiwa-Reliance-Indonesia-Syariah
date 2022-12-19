@section('sub-title','Pengaturan')
@section('title', 'Klaim')
<div class="clearfix row">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                    <ul class="nav nav-tabs">
                        <li class="nav-item"><a class="nav-link active show" data-toggle="tab" href="#tab_jenis_klaim">Jenis Klaim </a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab_sebab_tolak">Sebab Tolak </a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab_sebab_tolak_sumber">Sumber Informasi Penolakan</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab_kategori_penyakit">Kategori Penyakit</a></li>
                        <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tab_organ">Organ yang mencakup</a></li>
                    </ul>
                    <div class="tab-content px-0">
                        <div class="tab-pane active show" id="tab_jenis_klaim">
                            @livewire('klaim.pengaturan-jenis-klaim')                      
                        </div>
                        <div class="tab-pane" id="tab_sebab_tolak">
                            @livewire('klaim.pengaturan-sebab-tolak')                      
                        </div>
                        <div class="tab-pane" id="tab_sebab_tolak_sumber">
                            @livewire('klaim.pengaturan-sebab-tolak-sumber')              
                        </div>
                        <div class="tab-pane" id="tab_kategori_penyakit">
                            @livewire('klaim.pengaturan-kategori-penyakit')              
                        </div>
                        <div class="tab-pane" id="tab_organ">
                            @livewire('klaim.pengaturan-organ')              
                        </div>
                    </div>
                    <hr />
                </form>
            </div>
        </div>
    </div>
</div>