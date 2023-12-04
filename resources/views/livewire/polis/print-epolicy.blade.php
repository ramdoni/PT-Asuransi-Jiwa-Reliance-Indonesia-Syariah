<html>
    <head>
        <title>Nomor Polis : {{$data->no_polis}}</title>
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
            table tr td {
                padding: 5px;
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
            <p class="text-center"><b>Bismillahirrohmaanirrohiim</b></p>
            <h3 class="text-center">POLIS ASURANSI JIWA SYARIAH KUMPULAN</h3>
            <p class="text-center">{{$data->produk->nama}}</p>
            <p class="text-center">Nomor : {{$data->no_polis}}</p>
            <br />
            <br />
            <p class="text-center">antara</p>
            <br />
            <br />
            <h3 class="text-center">PT. Asuransi Jiwa Reliance Indonesia<br /> Unit Syariah</h3>
            <p class="text-center"><i>(selanjutnya disebut "Pengelola")</i></p>
            <p class="text-center">dengan</p>
            <h3 class="text-center">{{$data->nama}}</h3>
            <p class="text-center">{{$data->alamat}}</p>
            <br />
            <p style="text-align:justify;">Pengelola dengan ini setuju untuk membayar Manfaat Asuransi atas diri Peserta berdasarkan syarat dan ketentuan sebagaimana tercantum di dalam Ikhtisar Polis, Ketentuan Umum dan Khusus Polis termasuk dan tidak terbatas pada Surat Permohonan Asuransi Jiwa Syariah Kumpulan yang dilampirkan pada Polis ini yang merupakan bagian yang tidak terpisahkan dari Polis ini.</p>
            <p style="text-align:justify;">Berdasarkan ketentuan tersebut di atas, Pengelola telah mengeluarkan Polis ini di Jakarta dan akan berlaku sesuai dengan Ketentuan Umum Polis Asuransi Jiwa Syariah.</p>
            <p class="text-center">Jakarta, {{date('d F Y',strtotime($data->updated_at))}}</p>
            <p class="text-center" style="font-weight:bold;"><i>Pengelola,</i></p>
            <p class="text-center">PT. Asuransi Jiwa Reliance Indonesia<br />Unit Syariah</p>
            <br />
            <br />
            <br />
            <br />
            <br />
            <p class="text-center"><u><b>TAHTINA NUR ANGGRAENI</b></u><br />Unit Syariah</p>
        </div>
        <div class="page-break"></div>
        <div class="container">
            <h3 style="font-weight:bold;font-size: 14px;text-align:center;">IKHTISAR POLIS</h3>
            <br />
            <br />
            <table style="width:100%;">
                <tr>
                    <td style="width:40%">Jenis Asuransi</td>
                    <td> : </td>
                    <td style="width:60%">{{$data->produk->nama}}</td>
                </tr>
                <tr>
                    <td style="width:50%">Pemegang Polis</td>
                    <td> : </td>
                    <td style="width:50%">{{$data->nama}}</td>
                </tr>
                <tr>
                    <td>
                        Peserta
                    </td>
                    <td style="width: 15px;"> : </td>
                    <td>{{$data->peserta}}</td>
                </tr>
                <tr>
                    <td style="vertical-align:top;">Manfaat Asuransi</td>
                    <td style="vertical-align:top;"> : </td>
                    <td style="vertical-align:top;">{{$data->manfaat_asuransi}}</td>
                </tr>
                <tr>
                    <td>Cara Pembayaran Kontribusi</td>
                    <td> : </td>
                    <td>Sekaligus</td>
                </tr>
                <tr>
                    <td>Mata Uang</td>
                    <td> : </td>
                    <td>Rupiah</td>
                </tr>
                <tr>
                    <td>Iuran <i>Tabarru’</i></td>
                    <td> : </td>
                    <td>{{$data->iuran_tabbaru}}% dari Kontribusi yang dibayarkan</td>
                </tr>
                <tr>
                    <td><i>Ujrah</i> atas pengelolaan Polis untuk Pengelola </td>
                    <td> : </td>
                    <td>{{$data->ujrah_atas_pengelolaan}}% dari Kontribusi yang dibayarkan</td>
                </tr>
                <tr>
                    <td>Tanggal Berlakunya Polis</td>
                    <td> : </td>
                    <td>Sejak diakseptasi dan disetujui oleh Pengelola sesuai dengan ketentuan penerimaan kepesertaan asuransi</td>
                </tr>
                <tr>
                    <td style="vertical-align:top;">Penggunaan Dana <i>Tabarru’</i> hanya untuk</td>
                    <td style="vertical-align:top;"> : </td>
                    <td>
                        <table>
                            <tr>
                                <td style="width:15px;vertical-align:top;">a.</td>
                                <td>Pembayaran Manfaat Asuransi kepada Peserta yang mengalami Musibah</td>
                            </tr>
                            <tr>
                                <td>b.</td>
                                <td>Pembayaran Reasuransi</td>
                            </tr>
                            <tr>
                                <td>c.</td>
                                <td>Pembayaran kembali Qardh kepada Pengelola dan atau </td>
                            </tr>
                            <tr>
                                <td style="vertical-align:top;">d.</td>
                                <td>Pengembalian Dana <i>Tabarru’</i> yang jumlah pembayaran Kontribusi Dana Tabarru’ lebih besar dari seharusnya</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align:top;">Pengembalian Dana <i>Tabarru’</i></td>
                    <td style="vertical-align:top;"> : </td>
                    <td>
                        <table>
                            <tr>
                                <td style="width:15px;vertical-align:top;">a.</td>
                                <td>Jumlah pembayaran Kontribusi lebih besar dari seharusnya</td>
                            </tr>
                            <tr>
                                <td style="vertical-align:top;">b.</td>
                                <td>Penghentian Polis oleh Peserta sebelum Masa Asuransi berakhir</td>
                            </tr>
                            <tr>
                                <td style="vertical-align:top;">c.</td>
                                <td>Penghentian Polis oleh Pengelola sebelum Masa Asuransi berakhir</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align:top;"><i>Nisbah</i> Hasil Investasi Dana <i>Tabarru’</i></td>
                    <td style="vertical-align:top;"> : </td>
                    <td>
                        <table>
                            <tr>
                                <td style="width:15px;">a.</td>
                                <td>Dana <i>Tabarru’</i></td>
                                <td> : </td>
                                <td>40%</td>
                            </tr>
                            <tr>
                                <td style="width:20px;">b.</td>
                                <td>Pengelola</td>
                                <td> : </td>
                                <td>60%</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td  style="vertical-align:top;"><i>Surplus Underwriting</i></td>
                    <td  style="vertical-align:top;"> : </td>
                    <td>
                        <table>
                            <tr>
                                <td style="width:20px;">a.</td>
                                <td>Dana <i>Tabarru’</i></td>
                                <td> : </td>
                                <td>40%</td>
                            </tr>
                            <tr>
                                <td style="width:20px;">b.</td>
                                <td>Pemegang Polis</td>
                                <td> : </td>
                                <td>30%</td>
                            </tr>
                            <tr>
                                <td style="width:20px;">c.</td>
                                <td>Pengelola</td>
                                <td> : </td>
                                <td>30%</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>