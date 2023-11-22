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
                        <td>Pembayaran Credit Note atas Pembatalan Peserta {{isset($data->polis->nama) ? $data->polis->nama : ''}}</td>
                    </tr>
                </table>
                <hr />
                <p style="text-align:justify;">Dengan hormat,<br />
                Bersama ini disampaikan Daftar Pembatalan Peserta dan Credit Note atas Pembatalan Peserta sejak awal sesuai dengan pengajuan melalui email pada tanggal 
                {{date('d F Y',strtotime($data->tanggal_pengajuan))}}, mohon dapat dilakukan pembayaran dengan data sebagai berikut:
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
                        <td>Kontribusi DN</td>
                        <td> : </td>
                        <td>{{format_idr($kontribusi_dn)}}</td>
                        <td>Total Kontribusi Gross Cancel</td>
                        <td>Rp</td>
                        <td>{{format_idr($data->total_kontribusi_gross)}}</td>
                    </tr>
                    <tr>
                        <td>Jumlah Peserta</td>
                        <td> : </td>
                        <td>{{$data->total_peserta}}</td>
                        <td>Kontribusi Tambahan</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td> : </td>
                        <td></td>
                        <td>Potongan Langsung</td>
                        <td>{{$data->polis->potong_langsung}}%</td>
                        <td>{{format_idr($data->total_potongan_langsung)}}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            @if($data->polis->ppn)
                                PPn
                            @endif
                        </td>
                        <td>
                            @if($data->polis->ppn)
                                {{$data->polis->ppn ? $data->polis->ppn : 0}}%
                            @endif
                        </td>
                        <td>
                            @if($data->polis->ppn)
                                {{format_idr($data->ppn)}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td> : </td>
                        <td></td>
                        <td>
                            @if($data->polis->pph)
                                PPh
                            @endif
                        </td>
                        <td>
                            @if($data->polis->pph)
                                {{$data->polis->pph ? $data->polis->pph : 0}}%
                            @endif
                        </td>
                        <td>
                            @if($data->polis->pph)
                                {{format_idr($data->pph_amount)}}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Nomor Peserta</td>
                        <td> : </td>
                        <td>
                            {{$no_peserta_awal}}
                        </td>
                        <td>
                            @if($data->total_peserta>1)
                                s/d
                            @endif
                        </td>
                        <td>
                            @if($data->total_peserta>1)
                                {{$no_peserta_akhir}}
                            @endif
                        </td>
                        <td></td>
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
                        <td>Tgl. Cancel</td>
                        <td> : </td>
                        <td>{{date('d F Y',strtotime($data->tanggal_pengajuan))}}</td>
                        <td></td>
                        <td></td>
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
                        <td colspan="6"><i>*Note: Credit Note dapat dibayarkan setelah pembayaran tagihan kontribusi telah diterima oleh Reliance Life unit syariah</i></td>
                    </tr>
                    <tr>
                        <td colspan="3" style="padding-top:20px;"><strong>TOTAL PEMBAYARAN CREDIT NOTE</strong></td>
                        <th colspan="3" class="text-right">Rp. {{format_idr($data->total_kontribusi-$data->total_potongan_langsung)}}</td>
                    </tr>
                </table>
                <p>Demikian disampaikan,atas perhatian dan kerjasamanya diucapkan terima kasih.</p>
                <table style="width: 100%;">
                    <tr>
                        <td style="position:relative;width:70%;">
                            Hormat kami
                            <br />
                            <br />
                            <br />
                            <br />
                            @if(isset($data->requester->ttd))
                                <img src="{{public_path($data->requester->ttd)}}" style="width: 120px;z-index:2;position:absolute;top:20px;" />
                            @endif
                            <br>
                            <br>
                            <strong style="z-index: 3">{{isset($data->requester->name) ? $data->requester->name : '-'}}</strong>
                            <br>
                            <span style="z-index: 3">Dept. Underwriting Syariah</span>
                        </td>
                        <td style="width:30%;">
                            Diterima oleh,<br />
                            <br>
                            <br>
                            <br>
                            <br>
                            (.....................................) <br>
                            Div. Finance
                        </td>
                    </tr>
                </table>
                <br />
                <br />
                <table style="width:100%" class="border">
                    <tr>
                        <th>No</th>
                        <th class="text-left">Nomor Peserta</th>
                        <th class="text-left">Nomor DN</th>
                        <th class="text-left">Tanggal DN</th>
                        <th class="text-right">Kontribusi DN</th>
                    </tr>
                    @foreach($data->kepesertaan as $k => $i)
                        <tr>
                            <td class="text-center">{{$k+1}}</td>
                            <td>{{$i->no_peserta}}</td>
                            <td>{{$i->pengajuan->dn_number}}</td>
                            <td>{{$i->pengajuan->head_syariah_submit ? date('d-M-Y',strtotime($i->pengajuan->head_syariah_submit)) : date('d-M-Y',strtotime($i->pengajuan->created_at))}}</td>
                            <td class="text-right">{{format_idr($i->total_kontribusi_dibayar)}}</td>
                        </tr>
                    @endforeach
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
                    <td width="70%">No : {{$data->nomor}}</td>
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
                    <th style="border-bottom: 1px solid;">JUMLAH (Rp)</th>
                </tr>
                <tr>
                    <td style="border-right:1px solid;padding-left: 10px;">
                        <p>
                            Pembatalan Kepesertaan Asuransi produk <strong>RELIANCE PEMBIAYAAN SYARIAH</strong>
                            dengan No Polis <strong>{{$data->polis->no_polis}}</strong> dan Jumlah Peserta {{$data->total_peserta}} orang (No Peserta 
                            @if($data->total_peserta==1)) {{$no_peserta_awal}}
                            @else
                            {{$no_peserta_akhir}} - {{$no_peserta_akhir}}
                            @endif
                        </p>
                        <table style="margin-left: 50px;">
                            <tr>
                                <td>Kontribusi Standar</td>
                            </tr>
                            <tr>
                                <td>Kontribusi Tambahan</td>
                            </tr>
                            <tr>
                                <td>Potongan Langsung</td>
                            </tr>
                            <tr>
                                <td>Ujroh Brokerage</td>
                            </tr>
                            <tr>
                                <td>PPN</td>
                            </tr>
                            <tr>
                                <td>PPH</td>
                            </tr>
                        </table>
                        <br>
                    </td>
                    <td style="width: 180px;">
                        <table style="width:100%;margin-top: 45px;">
                            <tr>
                                <td class="text-right">{{format_idr($data->total_kontribusi_gross)}}</td>
                            </tr>
                            <tr>
                                <td class="text-right">{{format_idr($data->total_kontribusi_tambahan)}}</td>
                            </tr>
                            <tr>    
                                <td class="text-right">{{format_idr($data->total_potongan_langsung)}}</td>
                            </tr>
                            <tr>
                                <td class="text-right">{{format_idr($data->total_ujroh_brokerage)}}</td>
                            </tr>
                            <tr>
                                <td class="text-right">{{format_idr($data->total_ppn)}}</td>
                            </tr>
                            <tr>
                                <td class="text-right">{{format_idr($data->total_pph)}}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="border-right:1px solid;border-top:1px solid;border-bottom:1px solid;text-align:center">
                        <strong>Total Refund Kontribusi</strong>
                    </td>
                    <td style="border-top:1px solid;border-bottom: 1px solid;text-align:right;font-weight:bold;">
                        {{format_idr($data->total_kontribusi - $data->total_potongan_langsung)}}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align:center">Terbilang : {{terbilang($data->total_kontribusi - $data->total_potongan_langsung)}}</td>
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
                                <td> : Pembatalan Kepesertaan Asuransi</td>
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
                Sehubungan dengan pengajuan Pembatalan Data Peserta yang diterima atas Penutupan Asuransi Jiwa, sebagai berikut.
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
                    <th class="text-left">Daftar Pembatalan Peserta Asuransi Kumpulan</th>
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
                                <td class="text-right"> {{$no_peserta_awal}}</td>
                                <td>@if($no_peserta_akhir) s/d @endif</td>
                                <td>{{$no_peserta_akhir}}</td>
                            </tr>
                            <tr>
                                <td>Total Manfaat Asuransi</td>
                                <td> : Rp.</td>
                                <td class="text-right">{{format_idr($data->total_manfaat_asuransi)}}</td>
                                <td></td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>Kontribusi Gross Cancel</td>
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
                    <th class="text-left">Credit Note</th>
                </tr>
            </table>
            <p style="text-align:justify">
                Dapat diinformasikan bahwa seluruh peserta dalam daftar pembatalan peserta asuransi kumpulan diberlakukan efektif pembatalan per tanggal 
                {{$data->tanggal_efektif ? date('d F Y',strtotime($data->tanggal_efektif)) : '-'}}.
            </p>
            <p>Apabila terdapat pertanyaan, silahkan menghubungi kami pada hotline 021-5793 0008, di hari Senin - Jumat pukul 09:00 - 17:00 WIB dengan DIV. Underwriting Unit Syariah</p>
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
            <h3 style="text-align:center;">DAFTAR PEMBATALAN KEPESERTAAN ASURANSI JIWA KUMPULAN SYARIAH</h3>
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
                    <th>NILAI MANFAAT ASURANSI</th>
                    <th>TOTAL KONTRIBUSI</th>
                    <th>PENGEMBALIAN KONTRIBUSI</th>
                    <th>PENGEMBALIAN KONTRIBUSI NETTO</th>
                    <th>UW LIMIT</th>
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
                        <td class="text-right">{{format_idr($item->kontribusi)}}</td>
                        <td class="text-right">{{format_idr($item->total_kontribusi_dibayar)}}</td>
                        <td>{{$item->uw}}</td>
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
                        <th class="text-right">{{format_idr($data->total_kontribusi_gross)}}</th>
                        <th class="text-right">{{format_idr($data->total_kontribusi_gross)}}</th>
                        <th class="text-right">{{format_idr($data->total_kontribusi)}}</th>
                        <th></th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
            <br /><br /><br />

            <div class="text-center" style="position:relative;float: right;width:200px;">
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