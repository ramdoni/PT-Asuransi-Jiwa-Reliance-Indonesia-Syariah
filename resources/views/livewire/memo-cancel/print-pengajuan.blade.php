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
        <div class="container">
            <img src="{{asset('assets/img/surat-bg-top.png')}}?v=1" style="width: 100%;" />
            <h1 class="text-center">CREDIT NOTE</h1>
            <hr style="margin-bottom: 5px;" />
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
                            dengan No Polis <strong>{{$data->polis->no_polis}}</strong> dan Jumlah Peserta 10 orang (No Peserta )
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
                    <td style="border-right:1px solid;border-top:1px solid;border-bottom:1px solid;">
                        <strong>Total Refund Kontribusi</strong>
                    </td>
                    <td style="border-top:1px solid;border-bottom: 1px solid;text-align:right;">
                        {{format_idr($data->total_manfaat_asuransi)}}
                    </td>
                </tr>
                <tr>
                    <td colspan="2">Terbilang : {{terbilang($data->total_manfaat_asuransi)}}</td>
                </tr>
            </table>
            <div style="position: relative;">
                <p style="z-index:1">Hormat Kami,<br />
                    <strong>PT ASURANSI JIWA RELIANCE INDONESIA UNIT SYARIAH</strong>
                </p>
                <br />
                <br />
                <br />
                <img src="{{asset('assets/img/ttd-ahmad-syafei.png')}}" style="width: 130px;z-index:2;position:absolute;top:20px;" />
                <p>
                    <strong><u>Ahmad Syafei</u></strong><br />
                    Head Of Divisi Teknik Syariah
                </p>
            </div>
            <img src="{{asset('assets/img/surat-bg-footer.png')}}" style="width: 100%;position: absolute;bottom:0;" />
        </div>
        <div class="page-break"></div>
        <div class="container">
            <img src="{{asset('assets/img/surat-bg-top.png')}}" style="width: 100%;" />
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
                                <td>s/d</td>
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
            <p>
                Dapat diinformasikan bahwa seluruh peserta dalam daftar pembatalan peserta asuransi kumpulan diberlakukan efektif pembatalan per tanggal 
                {{$data->tanggal_efektif ? date('d F Y',strtotime($data->tanggal_efektif)) : '-'}}.<br />
                Apabila terdapat pertanyaan, silahkan menghubungi kami pada hotline 021-5793 0008, di hari Senin - Jumat pukul 09:00 - 17:00 WIB dengan DIV. Underwriting Unit Syariah
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
                    <img src="{{asset('assets/img/ttd-ahmad-syafei.png')}}" style="width: 130px;z-index:2;position:absolute;top:20px;" />
                    <strong><u>Ahmad Syafei</u></strong><br />
                    Head of Divisi Teknik Syariah
                </p>
            </div>
            <img src="{{asset('assets/img/surat-bg-footer.png')}}" style="width: 100%;position: absolute;bottom:0;" />
        </div>
        <div class="page-break"></div>
        <div class="container container-peserta">
            <img src="{{asset('assets/img/surat-bg-top.png')}}" style="width: 100%;" />
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
                    <th>KETERANGAN</th>
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
                        <td>{{$item->keterangan}}</td>
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
                        <th></th>
                    </tr>
                </tfoot>
            </table>
            <img src="{{asset('assets/img/surat-bg-footer.png')}}" style="width: 100%;position: absolute;bottom:0;" />
        </div>
    </body>
</html>