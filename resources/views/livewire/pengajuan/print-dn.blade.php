<html>
    <head>
        <title>Debit Note Number : {{$data->dn_number}}</title>
        <style>
            @page { margin: 0px; }
            body { margin: 0px; }
            * {
                font-family: Arial, Helvetica, sans-serif;
                font-size:12px;
            }
            p {
                font-size:14px;
            }
            h1 {font-size: 30px;}
            .container {padding-left:70px;padding-right:70px;}
            table.style1 tr td {padding-top:5px;}
            table.border{border:2px solid #000;}
            table.border tr td,table.border tr th {
                border:1px solid #000;
                border-bottom:2px solid #000;
                border-right:2px solid #000;
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
        </style>
    </head>
    <body>
        <div class="container">
            <br />
            <hr style="border-bottom:1px solid #000;border-top:3px solid #000;height:2px;border-right:0;border-left:0;" />
            <h1 style="text-align:center">Debit Note</h1>
            <hr style="border-bottom:3px solid #000;border-top:1px solid #000;height:2px;border-right:0;border-left:0;" />
            <table width="100%">
                <tr>
                    <td style="width: 50%">
                        No : {{$data->dn_number}}
                    </td>
                    <td style="width: 50%;text-align:right;">
                        Jakarta, {{date('d F Y')}}
                    </td>
                </tr>
            </table>
            <p style="width:50%;">
                    <strong>Kepada Yth: </strong><br />
                    <label style="text-transform: uppercase;"><strong>{{$data->polis->nama}}</strong><br />
                    {{$data->polis->alamat}}</label>
            </p>
            <table style="width:100%" class="border">
                <tr>
                    <th style="padding-top:10px;padding-bottom:10px;">KETERANGAN</th>
                    <th>JUMLAH (Rp)</th>
                </tr>
                <tr>
                    <td colspan="2" style="padding-top:10px;padding-bottom:10px;">
                        Tagihan Penutupan Asuransi produk <strong>RELIANCE PEMBIAYAAN SYARIAH</strong> dengan No Polis {{$data->polis->no_polis}} dan Jumlah Peserta {{$data->kepesertaan->where('status_akseptasi',1)->count()}} orang (No Peserta {{$data->no_peserta_awal}} {{isset($data->no_peserta_akhir) ? " - {$data->no_peserta_akhir}" : '' }}  ).                     
                    </td>
                </tr>
                <tr>
                    <td style="padding-left: 20px">Kontribusi</td>
                    <td style="text-align: right;">{{ format_idr($kontribusi)}}</td>
                </tr>
                <tr>
                    <td style="padding-left: 20px">Extra Kontribusi</td>
                    <td style="text-align: right;">{{ format_idr($extra_kontribusi)}}</td>
                </tr>
                <tr>
                    <td style="padding-left: 20px">Potong Langsung 10%</td>
                    <td style="text-align: right;">{{format_idr($potongan_langsung)}}</td>
                </tr>
                <tr>
                    <td style="padding-left: 20px">PPN 0%</td>
                    <td style="text-align: right;">
                    </td>
                </tr>
                <tr>
                    <td style="padding-left: 20px">PPh 0%</td>
                    <td style="text-align: right;"></td>
                </tr>
                <tr>
                    <td style="padding-left: 20px">Biaya Polis dan Materai</td>
                    <td style="text-align: right;"></td>
                </tr>
                <tr>
                    <td style="padding-left: 20px">Biaya Sertifikat/Kartu @ Rp0</td>
                    <td style="text-align: right;"></td>
                </tr>
                
                
            </table>
            <p>Pembayaran Kontribusi dapat dilakukan melalui transfer ke rekening sebagai berikut:</p>
            <table>
                <tr>
                    <td><strong>Bank</strong></td>
                    <td><strong>: Bank Syariah Indonesia</strong></td>
                </tr>
                <tr>
                    <td><strong>Atas Nama</strong></td>
                    <td><strong>: PT Asuransi Jiwa Reliance Indonesia</strong></td>
                </tr>
                <tr>
                    <td><strong>Nomor Rekening</strong></td>
                    <td><strong>: 8382828230</strong></td>
                </tr>
            </table>
            <p>
                Hormat Kami,<br />
                <strong>PT ASURANSI JIWA RELIANCE INDONESIA UNIT SYARIAH</strong>
                <br />
                <br />
                <br />
                <b><u>{{$head_teknik}}</u></b><br />
                Head of Teknik Syariah<br />
                <small style="font-size:10px;">Catatan: Harap pembayaran kontribusi mencantumkan nomor Debit Note.</small>
            </p>
            <div style="page-break-after: always;"></div>
            <br />
            <br />
            <br />
            <h2 style="text-align: center;">BISMILLAAHIRAHMANIRRAHIM</h2>
            <table width="100%">
                <tr>
                    <td style="width: 50%">
                        Jakarta, {{date('d F ')}}
                    </td>
                    <td style="width: 50%">
                        <table>
                            <tr>
                                <td>Nomor</td>
                                <td> : {{$data->dn_number}}</td>
                            </tr>
                            <tr>
                                <td>Perihal</td>
                                <td> : Penerimaan Kepesertaan Asuransi</td>
                            </tr>
                            <tr>
                                <td>Lamp</td>
                                <td> : 2 (Dua)</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <p><strong>Kepada Yth: <br />
                {{$data->polis->nama}}</strong><br />
                {{$data->polis->alamat}}
            </p>
            <p style="text-align: justify">
                Dengan hormat,<br/>
                Terimakasih atas kepercayaan yang telah diberikan kepada kami sebagai mitra untuk memberikan perlindungan kepada nasabah Anda. 
            </p>
            <p style="text-align: justify">
                Sehubungan dengan pengajuan Data Peserta yang diterima atas Penutupan Asuransi Jiwa Syariah, sebagai berikut :
            </p>
            <table style="width:100%;">
                <tr>
                    <td style="width:40%;"><strong>Nomor Polis</strong></td>
                    <td> : {{$data->polis->no_polis}}</td>
                </tr>
                <tr>
                    <td><strong>Pemegang Polis</strong></td>
                    <td> : {{$data->polis->nama}}</td>
                </tr>
                <tr>
                    <td><strong>Nama Produk</strong></td>
                    <td> : {{$data->polis->produk->nama}}</td>
                </tr>
            </table>
            <p>Dengan ini kami lampirkan : </p>
            <p><strong>1 Daftar Kepesertaan Asuransi Jiwa Kumpulan Syariah</strong></p>
            <br />
            <br />
            <table style="width:70%;">
                <tr>
                    <td style="width:30%">Total Peserta</td>
                    <td style="width: 50px;"> : </td>
                    <td style="text-align:right;">{{$data->kepesertaan->where('status_akseptasi',1)->count()}}</td>
                </tr>
                <tr>
                    <td>No. Peserta</td>
                    <td> : </td>
                    <td style="text-align:right;">{{$data->no_peserta_awal}} {{isset($data->no_peserta_akhir) ? " - {$data->no_peserta_akhir}" : '' }}</td>
                </tr>
                <tr>
                    <td>Total Nilai Manfaat Asuransi</td>
                    <td> : Rp</td>
                    <td style="text-align:right;">{{ format_idr($data->kepesertaan->where('status_akseptasi',1)->sum('basic')) }}</td>
                </tr>
                <tr>
                    <td>Total Kontribusi</td>
                    <td> : Rp </td>
                    <td style="text-align:right;">{{ format_idr($data->kepesertaan->where('status_akseptasi',1)->sum('kontribusi')) }}</td>
                </tr>
            </table>
            <p><strong>2. Daftar Kepesertaan Tertunda Asuransi Jiwa Kumpulan Syariah</strong></p>
            <table style="width:70%;">
                <tr>
                    <td style="width:30%">Total Peserta</td>
                    <td style="width: 50px;"> : </td>
                    <td style="text-align: right"> {{$data->kepesertaan->whereIn('status_akseptasi',[2,3])->count()}}</td>
                </tr>
                <tr>
                    <td>Total Nilai Manfaat Asuransi</td>
                    <td> : Rp </td>
                    <td style="text-align: right"> {{format_idr($data->kepesertaan->whereIn('status_akseptasi',[2,3])->sum('basic'))}}</td>
                </tr>
            </table>
            <br/>
            <p><strong>3. Debit Note</strong></p>
            <p style="text-align: justify">Dapat diinformasikan bahwa Data Kepesertaan tersebut telah diakseptasi sesuai dengan ketentuan penerimaan kepesertaan yang tercantum di dalam Polis. Apabila terdapat pertanyaan, silahkan menghubungi kami pada hotline 021–5793 0008, di hari Senin – Jumat pukul 09.00 – 17.00 WIB dengan Dept. Underwriting Syariah.</p>
            <p>Demikian disampaikan, atas perhatian dan kerjasamanya diucapkan terima kasih</p>
            <p>
                Hormat kami<br />
                <strong>PT. ASURANSI JIWA RELIANCE INDONESIA UNIT SYARIAH</strong>
                <br />
                <br />
                <br />
                <b><u>{{$head_teknik}}</u></b><br />
                Head of Teknik Syariah<br />
            </p>
        </div>
    </body>
</html>