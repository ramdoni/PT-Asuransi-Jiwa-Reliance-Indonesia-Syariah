<html>
    <head>
        <title>Nomor Pengajuan : {{$data->nomor}}</title>
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
            
            /* table.border tr{
                border:1px solid #000;
            } */
            /* table.border tr td:first-child{
                border-left: 0.5px solid #000;
                border-top: 0.5px solid #000;
            }
            table.border tr td:last-child{
                border-right: 0.5px solid #000;
            } */

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
        </style>
    </head>
    <body>
        @if(isset($_GET['is_finance']))
            <div class="container">
                <h1 class="text-center">INTERNAL MEMO</h1>
                <hr />
                <table>
                    <tr>
                        <td>Kepada</td>
                        <td style="width: 10px;"> : </td>
                        <td>Dept. Finance</td>
                    </tr>
                    <tr>
                        <td>Dari</td>
                        <td> : </td>
                        <td>Dept. Underwriting Syariah</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td> : </td>
                        <td>{{date('d F Y',strtotime($data->tanggal_pengajuan))}}</td>
                    </tr>
                    <tr>
                        <td>Nomor</td>
                        <td> : </td>
                        <td>{{$data->nomor}}</td>
                    </tr>
                    <tr>
                        <td style="padding-right: 10px;">No. Credit Note</td>
                        <td> : </td>
                        <td>{{$data->nomor_cn}}</td>
                    </tr>
                    <tr>
                        <td>Perihal</td>
                        <td> : </td>
                        <td>{{$data->perihal_internal_memo}}</td>
                    </tr>
                </table>
                <hr />
                <p>Dengan hormat,<br /><br />
                    Bersama ini disampaikan Daftar Pengurangan Peserta dan Credit Note atas Pengurangan Peserta sesuai dengan pengajuan
                    melalui email pada tanggal {{date('d F Y',strtotime($data->tanggal_pengajuan))}}, mohon dapat dilakukan pembayaran dengan data sebagai berikut:
                </p>
                <table style="border:1px solid;padding:5px;width:100%;">
                    <tr>
                        <td>Pemegang Polis </td>
                        <td> : </td>
                        <td colspan="3">{{$data->polis->nama}}</td>
                    </tr>
                    <tr>
                        <td>Jenis Asuransi </td>
                        <td> : </td>
                        <td>{{$data->polis->produk->nama}}</td>
                    </tr>
                    <tr>
                        <td>Nomor Polis </td>
                        <td> : </td>
                        <td>{{$data->polis->no_polis}}</td>
                    </tr>
                    <tr>
                        <td>Jumlah Peserta </td>
                        <td> : </td>
                        <td>{{$data->total_peserta}}</td>
                    </tr>
                    <tr>
                        <td>Nomor Peserta </td>
                        <td> : </td>
                        <td>
                            @if($data->nomor_peserta_awal)
                                {{$data->nomor_peserta_awal}}
                            @endif
                        </td>
                        <td>
                            @if($data->nomor_peserta_akhir)
                                s/d
                            @endif
                        </td>
                        <td>
                            @if($data->nomor_peserta_akhir)
                                {{$data->nomor_peserta_akhir}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Periode Asuransi </td>
                        <td> : </td>
                        <td>
                            @if($data->periode_awal)
                                {{date('d M Y',strtotime($data->periode_awal))}}
                            @endif
                        </td>
                        <td>
                            @if($data->periode_akhir)
                                s/d
                            @endif
                        </td>
                        <td>
                            @if($data->periode_akhir)
                                {{date('d M Y',strtotime($data->periode_akhir))}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Manfaat Asuransi</td>
                        <td> : </td>
                        <td>{{format_idr($data->total_manfaat_asuransi)}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Tanggal Pengurangan</td>
                        <td> : </td>
                        <td>{{date('d F Y',strtotime($data->tanggal_pengajuan))}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="5"><br /></td>
                    </tr>
                    <tr>
                        <td>No Debit Note</td>
                        <td> : </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Tgl Debit Note</td>
                        <td> : </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Kontribusi DN</td>
                        <td> : </td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Tujuan Pembayaran</td>
                        <td> : </td>
                        <td>{{$data->tujuan_pembayaran}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Nama Bank</td>
                        <td> : </td>
                        <td>{{$data->nama_bank}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>No. Rekening</td>
                        <td> : </td>
                        <td>{{$data->no_rekening}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Tgl. Jatuh Tempo</td>
                        <td> : </td>
                        <td>{{date('d F Y',strtotime($data->tgl_jatuh_tempo))}}</td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td colspan="6"><i>*Note: Credit Note dapat dibayarkan setelah pembayaran tagihan premi telah diterima oleh Reliance Life</i></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="padding-top:20px;"><strong>TOTAL PEMBAYARAN CREDIT NOTE</strong></td>
                        <th colspan="3" class="text-right">Rp. {{format_idr($data->total_kontribusi_gross)}}</td>
                    </tr>
                </table>
                <br />
                <br />
                <table style="width:95%;margin:auto;">
                    <tr>
                        <td colspan="2"><p>Demikian disampaikan, atas perhatian dan kerjasamanya diucapkan terima kasih</p></td>
                    </tr>
                    <tr>
                        <td style="width:65%;">
                            <p>
                                <br />
                                <br />
                                <br />
                                @if(isset($data->user_created->name))
                                    @if($data->user_created->ttd)
                                        <img src="{{asset($data->user_created->ttd)}}" style="height: 50px;" />
                                    @endif
                                @endif
                                <u>{{isset($data->user_created->name) ? $data->user_created->name : ''}}</u>
                                <p>Dep. Underwriting Syariah</p>
                            </p>
                        </td>
                        <td style="width:35%;">
                            <p>Diterima oleh,
                            <br>
                            <br>
                            <br>
                            <br>
                            <br>
                            (..................................)<br />
                            Dept. Finance
                            </p>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="page-break"></div>
        @endif
        <div class="container">
            <img src="{{public_path('assets/img/surat-bg-top.png')}}?v=1" style="width: 100%;" />
            <hr style="margin-bottom: 5px;" />
            <h1 class="text-center">CREDIT NOTE</h1>
            <hr />
            <table style="width: 100%;">
                <tr>
                    <td width="70%">No : {{$data->nomor_cn}}</td>
                    <td width="30%;">Jakarta, {{date('d F Y',strtotime($data->tanggal_pengajuan))}}</td>
                </tr>
            </table>
            <p>
                Kepada Yth:<br />
                <strong>{{$data->polis->nama}}</strong><br />
                {{$data->polis->alamat}}
            </p>
            <table style="border:1px solid">
                <tr>
                    <th style="border-bottom: 1px solid;border-right:1px solid;padding-top:10px;padding-bottom:10px;">KETERANGAN</th>
                    <th style="border-bottom: 1px solid;">  REFUND KONTRIBUSI (Rp)</th>
                </tr>
                <tr>
                    <td style="border-right:1px solid;padding-left:10px;padding-top:50px;padding-bottom:40px;">
                        <p>
                            Pengurangan Kepesertaan Asuransi Unit Syariah produk <strong>{{isset($data->polis->produk->nama) ? $data->polis->produk->nama : '-'}}</strong>
                            dengan No Polis <strong>{{$data->polis->no_polis}}</strong> dan Jumlah Peserta {{$data->total_peserta}} orang (No Peserta 
                            @if($data->no_peserta_awal and $data->no_peserta_akhir=="")  
                                {{$data->no_peserta_awal}}
                            @endif
                            @if($data->no_peserta_awal and $data->no_peserta_akhir)  
                                {{$data->no_peserta_awal}} - {{$data->no_peserta_akhir}}
                            @endif
                        </p>
                    </td>
                    <td style="width: 180px;padding-top:40px;padding-bottom:40px;" class="text-right">
                        <strong>{{format_idr($data->total_kontribusi_gross)}}</strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Terbilang : {{terbilang($data->total_kontribusi_gross)}}</td>
                </tr>
            </table>
            <br />
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
            <img src="{{public_path('assets/img/surat-bg-footer.png')}}" style="width: 100%;position: absolute;bottom:0;" />
        </div>
        <div class="page-break"></div>
        <div class="container">
            <img src="{{public_path('assets/img/surat-bg-top.png')}}" style="width: 100%;" />
            <h5 class="text-center">
                BISMILLAAHIRAHMANIRRAHIM
            </h5>
            <table style="width: 100%">
                <tr>
                    <td style="width: 50%;">Jakarta, {{date('d F Y',strtotime($data->created_at))}}</td>
                    <td style="width: 50%;">
                        <table>
                            <tr>
                                <td>Nomor </td>
                                <td> : {{$data->nomor}}</td>
                            </tr>
                            <tr>
                                <td>Perihal </td>
                                <td> : Pengurangan Kepesertaan Asuransi</td>
                            </tr>
                            <tr>
                                <td>Lamp </td>
                                <td> : 2 (dua) berkas</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <p>
                Kepada Yth.<br />
                <strong>{{$data->polis->nama}}</strong><br />
                {{$data->polis->alamat}}
            </p>
            <p>
                Dengan hormat,<br /><br />
                Terimakasih atas kepercayaan yang telah diberikan kepada kami sebagai mitra untuk memberikan perlindungan
                kepada nasabah Anda.<br />
                Sehubungan dengan pengajuan Pengurangan Data Peserta yang diterima atas Penutupan Asuransi Jiwa Unit Syariah, sebagai berikut.
                <table>
                    <tr>
                        <td>Nomor Polis</td>
                        <td> : {{$data->polis->no_polis}}</td>
                    </tr>
                    <tr>
                        <td>Pemegang Polis</td>
                        <td> : {{$data->polis->nama}}</td>
                    </tr>
                    <tr>
                        <td>Jenis Asuransi</td>
                        <td> : {{isset($data->polis->produk->nama) ? $data->polis->produk->nama : '-'}}</td>
                    </tr>
                </table>
            </p>
            <p>Dengan ini kami melampirkan</p>
            <table style="width: 100%;">
                <tr>
                    <th>1. </th>
                    <th class="text-left">Daftar Pengurangan Peserta Asuransi Kumpulan</th>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <table style="width: 100%;">
                            <tr>
                                <td>Total Peserta </td>
                                <td> : </td>
                                <td class="text-right">{{$data->total_peserta}}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>No. Peserta</td>
                                <td> : </td>
                                <td class="text-right"> {{$data->no_peserta_awal}}</td>
                                <td>@if($data->no_peserta_akhir) s/d @endif</td>
                                <td>{{$data->no_peserta_akhir}}</td>
                            </tr>
                            <tr>
                                <td>Manfaat Asuransi</td>
                                <td> : Rp.</td>
                                <td class="text-right">{{format_idr($data->total_manfaat_asuransi)}}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Pengembalian Kontribusi</td>
                                <td> : Rp. </td>
                                <td class="text-right">{{format_idr($data->total_kontribusi_gross)}}</td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th>2.</th>
                    <th class="text-left">Daftar Kepesertaan Tertunda Asuransi Syariah</th>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <table style="width: 100%;">
                            <tr>
                                <td>Total Peserta </td>
                                <td> : </td>
                                <td class="text-right"></td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Total Manfaat Asuransi </td>
                                <td> : </td>
                                <td class="text-right"></td>
                                <td></td>
                                <td></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <th>3.</th>
                    <th class="text-left">Credit Note</th>
                </tr>
            </table>
            <p>
                Dapat diinformasikan bahwa seluruh peserta dalam Daftar Pengurangan Kepesertaan asuransi Asuransi Syariah diberlakukan efektif pembatalan per tanggal 
                sesuai dengan Daftar Pengurangan Peserta terlampir.<br />
                Apabila terdapat pertanyaan, silahkan menghubungi kami pada hotline 021-5793 0008, di hari Senin - Jumat pukul 09:00 - 17:00 WIB dengan Dept. Underwriting Unit Syariah
            </p>
            <p>Demikian disampaikan, atas perhatian dan kerjasamanya diucapkan terima kasih.</p>
            <div style="position:relative;">
                <p>
                    Hormat kami,<br />
                    <strong>PT. ASURANSI JIWA RELIANCE INDONESIA UNIT SYARIAH</strong>
                    <br />
                    <br />
                    <br />
                    <br />
                    <img src="{{public_path('assets/img/ttd-ahmad-syafei.png')}}" style="width: 130px;z-index:2;position:absolute;top:20px;" />
                    <strong><u>Ahmad Syafei</u></strong><br />
                    Head of Divisi Teknik Syariah
                </p>
            </div>
            <img src="{{public_path('assets/img/surat-bg-footer.png')}}" style="width: 100%;position: absolute;bottom:0;" />
        </div>
        <div class="page-break"></div>
        <div class="container container-peserta">
            <img src="{{public_path('assets/img/surat-bg-top.png')}}" style="width: 100%;" />
            <h3 style="text-align:center;">DAFTAR REFUND KEPESERTAAN ASURANSI JIWA KUMPULAN SYARIAH</h3>
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
            <table class="table-peserta" style="margin-top: 10px;">
                <tr>
                    <th>NO.</th>
                    <th style="width: 100px;">NO PESERTA</th>
                    <th style="width: 100px;">NAMA PESERTA</th>
                    <th style="width: 50px;">TGL. LAHIR</th>
                    <th>USIA</th>
                    <th>MULAI ASURANSI</th>
                    <th>AKHIR ASURANSI</th>
                    <th>TLG. EFEKTIF</th>
                    <th>NILAI MANFAAT ASURANSI</th>
                    <th>PENGEMBALIAN ASURANSI</th>
                </tr>
                @foreach($data->kepesertaan as $k => $item)
                    <tr>
                        <td>{{$k+1}}</td>
                        <td>{{$item->no_peserta}}</td>
                        <td>{{$item->nama}}</td>
                        <td>{{date('d-m-y',strtotime($item->tanggal_lahir))}}</td>
                        <td>{{$item->usia}}</td>
                        <td>{{date('d-m-y',strtotime($item->tanggal_mulai))}}</td>
                        <td>{{date('d-m-y',strtotime($item->tanggal_akhir))}}</td>
                        <td class="text-right">{{format_idr($item->basic)}}</td>
                        <td class="text-right">{{format_idr($item->kontribusi)}}</td>
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
                        <th></th>
                        <th class="text-right">{{format_idr($data->total_manfaat_asuransi)}}</th>
                        <th class="text-right">{{format_idr($data->total_kontribusi)}}</th>
                    </tr>
                </tfoot>
            </table>
            <img src="{{public_path('assets/img/surat-bg-footer.png')}}" style="width: 100%;position: absolute;bottom:0;" />
        </div>
    </body>
</html>