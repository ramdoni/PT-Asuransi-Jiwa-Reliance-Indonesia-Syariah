<html>
    <head>
        <title>Nomor Pengajuan : {{$data->no_pengajuan}}</title>
        <style>
            /* @page { margin: 0px; } */
            /* body { margin: 0px; } */
            * {
                font-family: Arial, Helvetica, sans-serif;
                font-size:12px;
            }
            h1 {font-size: 20px;}
            .container {
                padding-left:20px;
                padding-right:20px;
                position:relative;
            }
            .text-center {
                text-align:center;
            }
            .text-right {
                text-align:right;
            }
            .text-left {
                text-align:left;
            }
            table {
                border-collapse: separate; 
                border-spacing: 0em;
            }
            table.style1 tr td {padding-top:5px;}
            table.border {
                border-left: 1px solid black;
                border-bottom: 1px solid black;
            }
            table.border tr td,table.border tr th {
                border-top:1px solid #000;
                border-right:1px solid #000;
                padding:2px 5px;
                margin:0;
            }
            ol {
                margin:0 0 1.5em;
                padding:0;
                counter-reset:item;
            }
            
            ol>li {
                margin:0;
                padding:0 0 0 2em;
                text-indent:-2em;
                list-style-type:none;
                counter-increment:item;
            }
            
            ol>li:before {
                display:inline-block;
                width:1.5em;
                padding-right:0.5em;
                font-weight:bold;
                text-align:right;
                content:counter(item) ".";
            }
            .page-break {
                page-break-after: always;
            }
            .container-peserta {
                font-size: 10px !important;
                padding-left: 0;
                padding-right: 0;
                margin-left:0;
                margin-right:0;
            }
            .table-peserta {
                font-size: 10px;
                width:100%;
                border-spacing: 0;
            }
            .table-peserta tr th {
                border-top: 2px solid;
                border-bottom: 1px solid;
                font-size: 8px;
            }
            .table-peserta tr td {
                border-bottom: 1px solid;
                font-size: 8px;
            }
            .border-outside {
                border: 1px solid #000;
                border-spacing: 2px;
            }
            .border-outside tr td {
                padding:3px;
                border: 2px solid #000;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <img src="{{public_path('assets/img/surat-bg-top.png')}}?v=1" style="width: 100%;" />
            <hr style="margin-bottom: 5px;" />
            <h1 class="text-center">DEBIT NOTE</h1>
            <hr />
            <table style="width: 100%;">
                <tr>
                    <td width="70%">No : {{$data->no_pengajuan}}</td>
                    <td width="30%;">Jakarta, {{date('d F Y',strtotime($data->tanggal_pengajuan))}}</td>
                </tr>
            </table>
            <p>
                Kepada Yth:<br />
                <strong>{{$data->polis->nama}}</strong><br />
                {{$data->polis->alamat}}
            </p>
            <table style="border:1px solid;width:100%;">
                <tr>
                    <th style="border-bottom: 1px solid;border-right:1px solid;padding-top:10px;padding-bottom:10px;">KETERANGAN</th>
                    <th style="border-bottom: 1px solid;">JUMLAH (Rp)</th>
                </tr>
                <tr>
                    <td style="border-right:1px solid;padding-left: 10px;">
                        <p>
                           Tagihan Perubahan Kepesertan Asuransi produk <strong>{{isset($data->polis->produk->nama) ? $data->polis->produk->nama : '-'}}
                            </strong> dengan No Polis <strong>{{$data->polis->no_polis}}</strong> dan Jumlah Peserta {{$data->total_peserta}} orang (No Peserta {{implode(",",$list_no_peserta)}})
                        </p>
                        <table style="margin-left: 50px;">
                            <tr>
                                <td>Kontribusi Netto Awal</td>
                            </tr>
                            <tr>
                                <td>Kontribusi Netto Perubahan</td>
                            </tr>
                        </table>
                        <br>
                    </td>
                    <td>
                        <table style="width:100%;margin-top: 45px;">
                            <tr>
                                <td class="text-right">{{format_idr($data->total_kontribusi_gross)}}</td>
                            </tr>
                            <tr>
                                <td class="text-right">{{format_idr($data->kontribusi_netto_perubahan)}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="border-right:1px solid;border-top:1px solid;border-bottom:1px solid;text-align:center">
                        <strong>Total Refund Kontribusi</strong>
                    </td>
                    <td style="border-top:1px solid;border-bottom: 1px solid;text-align:right;font-weight:bold;">
                        @if($data->total_kontribusi_gross > $data->kontribusi_netto_perubahan)
                            {{format_idr($data->total_kontribusi_gross - $data->kontribusi_netto_perubahan)}}
                        @else
                            {{format_idr($data->kontribusi_netto_perubahan - $data->total_kontribusi_gross)}}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:center">Terbilang : 
                        @if($data->total_kontribusi_gross > $data->kontribusi_netto_perubahan)
                            {{terbilang($data->total_kontribusi_gross - $data->kontribusi_netto_perubahan)}}
                        @else
                            {{terbilang($data->kontribusi_netto_perubahan - $data->total_kontribusi_gross)}}
                        @endif
                    </td>
                </tr>
            </table>
            <div style="position: relative;">
                <p style="z-index:1">Hormat Kami,<br />
                    <strong>PT ASURANSI JIWA RELIANCE INDONESIA UNIT SYARIAH</strong>
                </p>
                <br />
                <br />
                <br />
                <img src="{{public_path('assets/img/ttd-ahmad-syafei.png')}}" style="width: 130px;z-index:2;position:absolute;top:20px;" />
                <p>
                    <strong><u>Ahmad Syafei</u></strong><br />
                    Head Of Divisi Teknik Syariah
                </p>
            </div>
            <img src="{{public_path('assets/img/surat-bg-footer.png')}}" style="width: 100%;position: absolute;bottom:0;" />
        </div>
        <div class="page-break"></div>
        <div class="container">
            <img src="{{public_path('assets/img/surat-bg-top.png')}}" style="width: 100%;" />
            <h5 class="text-center">
                BISMILLAAHIRAHMANIRRAHIM
            </h5>
            <table style="width:100%">
                <tr>
                    <td style="width:60%;">Jakarta, {{date('d F Y',strtotime($data->tanggal_pengajuan))}}</td>
                    <td style="width:40%;">
                        <table>
                            <tr>
                                <td>Nomor</td>
                                <td> : {{$data->no_pengajuan}}</td>
                            </tr>
                            <tr>
                                <td>Perihal</td>
                                <td> :  Perubahan Kepesertaan Asuransi </td>
                            </tr>
                            <tr>
                                <td>Lamp</td>
                                <td> : 2 (dua) berkas</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <p style="width: 70%;">
                Kepada Yth.<br />
                <strong>{{$data->polis->nama}}</strong><br />
                {{$data->polis->alamat}}
            </p>
            <p>Dengan hormat,</p>
            <p style="text-align:justify">
                Terimakasih atas kepercayaan yang telah diberikan kepada kami sebagai mitra untuk memberikan perlindungan 
                kepada nasabah Anda.<br /> 
                Sehubungan dengan pengajuan Perubahan Data Peserta yang diterima atas Penutupan Asuransi Jiwa, sebagai
                berikut :
            </p> 
            <table>
                <tr>
                    <td>Nomor Polis</td>
                    <td>{{$data->polis->no_polis}}</td>
                </tr>
                <tr>
                    <td>Pemegang Polis</td>
                    <td>{{$data->polis->nama}}</td>
                </tr>
                <tr>
                    <td>Nama Produk</td>
                    <td>{{$data->polis->produk->nama}}</td>
                </tr>
            </table>
            <p>Dengan ini kami lampirkan :</p>
            <p><b>1. Surat Endorsement</b></p>
            <p><b>2. Daftar Perubahan Peserta Asuransi Kumpulan</b></p>
            <table style="margin-left: 20px; width: 80%;">
                <tr>
                    <td style="width: 200px;">Total Peserta</td>
                    <td> : {{$data->total_peserta}}</td>
                </tr>
                <tr>
                    <td>No. Peserta</td>
                    <td> : {{implode(",",$list_no_peserta)}}</td>
                </tr>
                <tr>
                    <td>Total Manfaat Asuransi Perubahan</td>
                    <td> : {{format_idr($data->basic_perubahan)}}</td>
                </tr>
                <tr>
                    <td>Total Kontribusi Gross Perubahan</td>
                    <td> : {{format_idr($data->kontribusi_netto_perubahan)}}</td>
                </tr>
                
            </table>
            <p style="text-align:justify">Dapat diinformasikan bahwa seluruh peserta dalam Daftar Perubahan Peserta Asuransi Kumpulan diberlakukan 
                efektif perubahan dengan ketentuan “subject to no claim” per tanggal {{date('d F Y',strtotime($data->tanggal_pengajuan))}}.
                Apabila terdapat pertanyaan, silahkan menghubungi kami pada hotline 021–5793 0008, di hari Senin – Jumat pukul 
                09.00 – 17.00 WIB dengan Div. Underwriting Unit Syariah.
            </p>
            <br />
            <br />
            <div style="position: relative;">
                <p style="z-index:1">Hormat Kami,<br />
                    <strong>PT ASURANSI JIWA RELIANCE INDONESIA UNIT SYARIAH</strong>
                </p>
                <br />
                <br />
                <br />
                <img src="{{public_path('assets/img/ttd-ahmad-syafei.png')}}" style="width: 130px;z-index:2;position:absolute;top:20px;" />
                <p>
                    <strong><u>Ahmad Syafei</u></strong><br />
                    Head Of Divisi Teknik Syariah
                </p>
            </div>
        </div>
        <div class="page-break"></div>
        <div class="container">
            <img src="{{public_path('assets/img/surat-bg-top.png')}}" style="width: 100%;" />
            <h5 class="text-center">
                BISMILLAAHIRAHMANIRRAHIM
            </h5>
            <hr />
            <h5 class="text-center">
                SURAT ENDORSEMENT<br />
                (PENGESAHAN {{isset($data->jenis_perubahan->name) ? strtoupper($data->jenis_perubahan->name) : ''}})
            </h5>
            <hr />
            <table>
                <tr>
                    <td>No Endorsement</td>
                    <td>: {{$data->no_pengajuan}}</td>
                </tr>
                <tr>
                    <td>No Polis</td>
                    <td>: {{$data->polis->no_polis ? $data->polis->no_polis : '-'}}</td>
                </tr>
                <tr>
                    <td>Pemegang Polis</td>
                    <td>: {{$data->polis->nama ? $data->polis->nama : '-'}}</td>
                </tr>
            </table>
            <br />
            <div style="border:1px solid #000000;padding: 1px;">
                <div style="border:2px solid #000000;padding: 4px;">
                    <p class="text-center">
                        Endorsment ini harus diletakan pada daftar perubahan asli, Endorsmentnya saja tidak berlaku
                    </p>
                </div>
            </div>
            <p style="text-align:justify">
                Dengan ini dicatat dan disetujui bahwa Pemegang Polis {{isset($data->polis->nama) ? $data->polis->nama : '-'}} mengalami perubahan uang asuransi adalah sebagai berikut :
            </p>
            <table style="width:100%;" class="border-outside">
                <tr style="font-weight:bold;">
                    <td>No.Kepesertaan</td>
                    <td>Nama</td>
                    <td>Jenis Perubahan</td>
                    <td>Data Awal</td>
                    <td>Data Perubahan</td>
                </tr>
                <tr>
                    <td style="height:100px" class="text-center">{{implode(", ",$list_no_peserta)}}</td>
                    <td class="text-center">{!!implode("<br />",$list_nama_peserta)!!}</td>
                    <td class="text-center">{{isset($data->jenis_perubahan->name) ? $data->jenis_perubahan->name : '-'}}</td>
                    <td class="text-center">
                        {!!implode("<br />",json_decode($data->value_perubahan_before))!!}
                    </td>
                    <td class="text-center">
                        {!!implode("<br />",json_decode($data->value_perubahan_after))!!}
                    </td>
                </tr>
            </table>
            <p>
            Selain {{isset($data->jenis_perubahan->name) ? $data->jenis_perubahan->name : ''}}, ketentuan dan syarat lain di dalam daftar perubahan peserta tersebut tidak mengalami perubahan.
            </p>
            <br />
            <br />
            <div style="position: relative;">
                <p style="z-index:1">Hormat Kami,<br />
                    <strong>PT ASURANSI JIWA RELIANCE INDONESIA UNIT SYARIAH</strong>
                </p>
                <br />
                <br />
                <br />
                <img src="{{public_path('assets/img/ttd-ahmad-syafei.png')}}" style="width: 130px;z-index:2;position:absolute;top:20px;" />
                <p>
                    <strong><u>Ahmad Syafei</u></strong><br />
                    Head Of Divisi Teknik Syariah
                </p>
            </div>
        </div>
        
        <div class="page-break"></div>
        <div class="container" style="font-size: 10px !important;">
            <img src="{{public_path('assets/img/surat-bg-top.png')}}" style="width: 100%;" />
            <h3 style="text-align:center;">DAFTAR PERUBAHAN KEPESERTAAN ASURANSI JIWA KUMPULAN SYARIAH</h3>
            <table>
                <tr>
                    <td>NOMOR POLIS</td>
                    <td> : </td>
                    <td>{{$data->polis->no_polis}}</td>
                </tr>
                <tr>
                    <td>PEMEGANG POLIS</td>
                    <td> : </td>
                    <td>{{$data->polis->nama}}</td>
                </tr>
                <tr>
                    <td>PRODUK ASURANSI</td>
                    <td> : </td>
                    <td>{{$data->polis->produk->nama}}</td>
                </tr>
                <tr>
                    <td>CARA PEMBAYARAN KONTRIBUSI</td>
                    <td> : </td>
                    <td>SEKALIGUS</td>
                </tr>
            </table>
            <p><strong>DATA SEBELUM PERUBAHAN</strong></p>
            <table class="table-peserta" style="margin-top: 10px;width:100%">
                <tr>
                    <th>NO.</th>
                    <th>NO PESERTA</th>
                    <th>NAMA PESERTA</th>
                    <th class="text-center">TGL. LAHIR</th>
                    <th class="text-center">USIA</th>
                    <th class="text-center">MULAI ASURANSI</th>
                    <th class="text-center">AKHIR ASURANSI</th>
                    <th class="text-right">NILAI MANFAAT ASURANSI</th>
                    <th class="text-right">TOTAL KONTRIBUSI</th>
                    <th>UW LIMIT</th>
                </tr>
                @foreach($data->pesertas as $k => $json)
                    @php($item = json_decode($json->before_data))
                    <tr>
                        <td>{{$k+1}}</td>
                        <td>{{$item->no_peserta}}</td>
                        <td>{{$item->nama}}</td>
                        <td class="text-center">{{date('d-M-y',strtotime($item->tanggal_lahir))}}</td>
                        <td class="text-center">{{$item->usia}}</td>
                        <td class="text-center">{{date('d-M-y',strtotime($item->tanggal_mulai))}}</td>
                        <td class="text-center">{{date('d-M-y',strtotime($item->tanggal_akhir))}}</td>
                        <td class="text-right">{{format_idr($item->basic)}}</td>
                        <td class="text-right">{{format_idr($item->kontribusi)}}</td>
                        <td class="text-center">{{$item->uw}}</td>
                    </tr>
                @endforeach
                <tfoot>
                    <tr>
                        <th></t>
                        <th class="text-center">TOTAL</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th class="text-right">{{format_idr($data->total_manfaat_asuransi)}}</th>
                        <th class="text-right">{{format_idr($data->total_kontribusi)}}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
            <br />
            <br />
            <p><strong>DATA SETELAH PERUBAHAN</strong></p>
            <table class="table-peserta" style="width:100%">
                <tr>
                    <th>NO.</th>
                    <th>NO PESERTA</th>
                    <th>NAMA PESERTA</th>
                    <th class="text-center">TGL. LAHIR</th>
                    <th class="text-center">USIA</th>
                    <th class="text-center">MULAI ASURANSI</th>
                    <th class="text-center">AKHIR ASURANSI</th>
                    <th class="text-right">NILAI MANFAAT ASURANSI</th>
                    <th class="text-right">TOTAL KONTRIBUSI</th>
                    <th>UW LIMIT</th>
                </tr>
                @foreach($data->pesertas as $k => $json)
                    @php($item = json_decode($json->after_data))
                    <tr>
                        <td>{{$k+1}}</td>
                        <td>{{$item->no_peserta}}</td>
                        <td>{{$item->nama}}</td>
                        <td class="text-center">{{date('d-M-y',strtotime($item->tanggal_lahir))}}</td>
                        <td class="text-center">{{$item->usia}}</td>
                        <td class="text-center">{{date('d-M-y',strtotime($item->tanggal_mulai))}}</td>
                        <td class="text-center">{{date('d-M-y',strtotime($item->tanggal_akhir))}}</td>
                        <td class="text-right">{{format_idr($item->basic)}}</td>
                        <td class="text-right">{{format_idr($item->kontribusi)}}</td>
                        <td class="text-center">{{$item->uw}}</td>
                    </tr>
                @endforeach
                <tfoot>
                    <tr>
                        <th></t>
                        <th class="text-center">TOTAL</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th class="text-right">{{format_idr($data->total_manfaat_asuransi)}}</th>
                        <th class="text-right">{{format_idr($data->total_kontribusi)}}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>

            <br /><br /><br />

            <div class="text-center" style="position:relative;float:right;width:200px;">
                <p>
                    <span style="z-index:3;">Jakarta {{date('d F Y',strtotime($data->tanggal_pengajuan))}},</span> <br />
                    <br />
                    <br />
                    <br />
                    <br />
                    <img src="{{public_path('assets/img/ttd-underwriting.png')}}" style="width: 130px;z-index:2;position:absolute;top:20px;" />
                    <p>
                        <hr />
                        Underwriting
                    </p>
                    <br />
                </p>
            </div>
            <img src="{{public_path('assets/img/surat-bg-footer.png')}}" style="width: 100%;position: absolute;bottom:0;" />
        </div>
    </body>
</html>