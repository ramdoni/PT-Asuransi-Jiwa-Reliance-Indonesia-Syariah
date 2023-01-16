<html>
    <head>
        <title>Klaim Number : {{$data->no_pengajuan}}</title>
        <style>
            @page { 
                margin-left: 1cm;
                margin-right: 0.50cm;
                size: 210mm 297mm; 
            }
            body { margin: 0px; }
            * {
                font-family: Arial, Helvetica, sans-serif;
                font-size:7pt;
            }
            h1 {font-size: 14pt;}
            table {border-collapse: collapse;}
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
            .text-left{text-align:left;}
        </style>
    </head>
    <body>
        <div class="container">
            <br />
            <br />
            <br />
            <br />
            <h1 style="text-align:center;margin-bottom:0;padding-bottom:0;">PERSETUJUAN PEMBAYARAN KLAIM<br />(MANFAAT ASURANSI)</h1>
            <p style="text-align:center;font-size:11pt;margin-top:0;padding-top:0;">Nomor : {{$data->no_mak}}</p>
            <h3 style="font-size:18px;margin-bottom:0;paddin-bottom:0;">I. Data Kepesertaan</h3>
            <div style="width:34%;float:left;">
                <table width="100%">
                    <tr>
                        <th style="text-align:left;">Data Peserta</th>
                    </tr>
                    <tr>
                        <td>Nomor Polis</td>
                        <td> : </td>
                        <td style="border-bottom:1px solid;padding-bottom:5px">{{isset($data->polis->no_polis) ? $data->polis->no_polis : '-'}}</td>
                    </tr>
                    <tr>
                        <td>Pemegang Polis</td>
                        <td> : </td>
                        <td style="border-bottom:1px solid;">{{isset($data->polis->nama) ? $data->polis->nama : '-'}}</td>
                    </tr>
                    <tr>
                        <td>Produk As</td>
                        <td> : </td>
                        <td style="border-bottom:1px solid;">{{isset($data->polis->produk->singkatan) ? $data->polis->produk->singkatan : '-'}}</td>
                    </tr>
                    <tr>
                        <td>No Peserta</td>
                        <td> : </td>
                        <td style="border-bottom:1px solid;">{{isset($data->kepesertaan->no_peserta) ? $data->kepesertaan->no_peserta : '-'}}</td>
                    </tr>
                    <tr>
                        <td>Nama Peserta</td>
                        <td> : </td>
                        <td style="border-bottom:1px solid;">{{isset($data->kepesertaan->nama) ? $data->kepesertaan->nama : '-'}}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Lahir</td>
                        <td> : </td>
                        <td style="border-bottom:1px solid;">{{isset($data->kepesertaan->tanggal_lahir) ? $data->kepesertaan->tanggal_lahir : '-'}}</td>
                    </tr>
                    <tr>
                        <td>Usia Masuk As</td>
                        <td> : </td>
                        <td style="border-bottom:1px solid;">{{hitung_umur($data->kepesertaan->tanggal_lahir,1,$data->kepesertaan->tanggal_mulai)}}</td>
                    </tr>
                    <tr>
                        <td>Masa Asuransi</td>
                        <td> : </td>
                        <td style="border-bottom:1px solid;">{{isset($data->kepesertaan->masa_bulan)?$data->kepesertaan->masa_bulan .' Bulan':'-'}}</td>
                    </tr>
                    <tr>
                        <td>Periode As</td>
                        <td> : </td>
                        <td style="border-bottom:1px solid;"> 
                            @if(isset($data->kepesertaan->tanggal_mulai))
                                {{date('d F Y',strtotime($data->kepesertaan->tanggal_mulai))}} sd {{date('d F Y',strtotime($data->kepesertaan->tanggal_akhir))}}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Uang Asuransi</td>
                        <td> : </td>
                        <td style="border-bottom:1px solid;">{{isset($data->kepesertaan->basic) ? format_idr($data->kepesertaan->basic) : '-'}}</td>
                    </tr>
                </table>
            </div>
            <div style="width:34%;float:left;margin-left:5px;margin-right:5px;">
                <table width="100%">
                    <tr>
                        <th colspan="2" style="text-align:left;">
                            Data Pembayaran
                        </th>
                    </tr>
                    <tr>
                        <td style="width:50%;">Nomor DN</td>
                        <td> : </td>
                        <td style="border-bottom:1px solid;">
                            @if(isset($data->kepesertaan->pengajuan->no_pengajuan))
                                {{$data->kepesertaan->pengajuan->dn_number}}
                            @elseif(isset($data->kepesertaan->no_debit_note))
                                {{$data->kepesertaan->no_debit_note}}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>Kontribusi DN</td>
                        <td> : </td>
                        <td style="border-bottom:1px solid;">{{isset($data->kepesertaan->pengajuan->kontribusi) ? format_idr($data->kepesertaan->pengajuan->kontribusi+$data->kepesertaan->pengajuan->extra_kontribusi+$data->kepesertaan->pengajuan->extra_mortalita) : '-'}}</td>
                    </tr>
                    <tr>
                        <td>Tgl. Bayar Kontribusi</td>
                        <td> : </td>
                        <td style="border-bottom:1px solid;">{{isset($data->kepesertaan->pengajuan->payment_date) ? date('d-F-Y',strtotime($data->kepesertaan->pengajuan->payment_date)) : '-' }} </td>
                    </tr>
                    <tr>
                        <td>Kontribusi Peserta</td>
                        <td> : </td>
                        <td style="border-bottom:1px solid;">{{isset($data->kepesertaan->kontribusi) ? format_idr($data->kepesertaan->kontribusi) : '-'}}</td>
                    </tr>
                    <tr>
                        <th colspan="3" style="text-align:left;">Data Reasuransi</th>
                    </tr>
                    <tr>
                        <td>Reasuradur</td>
                        <td> : </td>
                        <td style="border-bottom:1px solid;">{{isset($data->kepesertaan->reas->reasuradur->name) ? $data->kepesertaan->reas->reasuradur->name : '-'}}</td>
                    </tr>
                    <tr>
                        <td>Type Reas</td>
                        <td> : </td>
                        <td style="border-bottom:1px solid;">{{isset($data->kepesertaan->reas->type_reas) ? $data->kepesertaan->reas->type_reas : '-'}}</td>
                    </tr>
                    <tr>
                        <td>Model Reas</td>
                        <td> : </td>
                        <td style="border-bottom:1px solid;">{{isset($data->kepesertaan->reas->rate_uw->model_reas) ? $data->kepesertaan->reas->rate_uw->model_reas : '-'}}</td>
                    </tr>
                    <tr>
                        <td>Max OR</td>
                        <td> : </td>
                        <td style="border-bottom:1px solid;">{{isset($data->kepesertaan->reas->rate_uw->max_or) ? format_idr($data->kepesertaan->reas->rate_uw->max_or) : '-'}}</td>
                    </tr>
                    <tr>
                        <td>Kadaluwarsa Reas</td>
                        <td> : </td>
                        <td style="border-bottom:1px solid;">{{isset($data->kepesertaan->kadaluarsa_reas_hari) ? $data->kepesertaan->kadaluarsa_reas_hari .' Hari Kalender' : '-'}}</td>
                    </tr>
                </table>
            </div>
            <div style="width:32%;float:left">
                <table width="100%">
                    <tr>
                        <th colspan="2" style="text-align:left;">
                            Ketentuan Asuransi
                        </th>
                        <tr>
                            <td>Grace Periode</td>
                            <td style="border-bottom:1px solid;"> : {{isset($data->kepesertaan->polis->masa_leluasa) ? $data->kepesertaan->polis->masa_leluasa .' Hari Kalender' : '-'}}</td>
                        </tr>
                        <tr>
                            <td>Retroaktif/Waiting Periode</td>
                            <td style="border-bottom:1px solid;"> : {{isset($data->polis->retroaktif) ? $data->polis->retroaktif .' Hari Kalender' : '-' }}</td>
                        </tr>
                        <tr>
                            <td>Kadaluwarsa Klaim</td>
                            <td style="border-bottom:1px solid;"> : {{$data->kadaluarsa_klaim_hari}} Hari Kalender</td>
                        </tr>
                        <tr>
                            <td>Tgl. Kadaluwarsa Klaim</td>
                            <td style="border-bottom:1px solid;"> : {{date('d-M-Y',strtotime($data->kadaluarsa_klaim_tanggal))}}</td>
                        </tr>
                        <tr>
                            <td>Share OR</td>
                            <td style="border-bottom:1px solid;"> : {{isset($data->kepesertaan->reas->or) ? $data->kepesertaan->reas->or : '-'}}</td>
                        </tr>
                        <tr>
                            <td>Share Reas</td>
                            <td style="border-bottom:1px solid;"> : {{isset($data->kepesertaan->reas->reas) ? $data->kepesertaan->reas->reas : '-'}}</td>
                        </tr>
                        <tr>
                            <td>Nilai Klaim OR</td>
                            <td style="border-bottom:1px solid;"> : {{isset($data->nilai_klaim_or) ? format_idr($data->nilai_klaim_or) : '-'}}</td>
                        </tr>
                        <tr>
                            <td>Nilai Klaim Reas</td>
                            <td style="border-bottom:1px solid;"> : {{isset($data->nilai_klaim_reas) ? format_idr($data->nilai_klaim_reas) : '-'}}</td>
                        </tr>
                        <tr>
                            <td>Tgl. Kadaluwarsa Reas</td>
                            <td style="border-bottom:1px solid;"> : {{isset($data->kadaluarsa_reas_tanggal) ? date('d-M-Y',strtotime($data->kadaluarsa_reas_tanggal)) : '-'}}</td>
                        </tr>
                    </tr>
                </table>
            </div>
            <div style="clear:both"></div>
            <h3 style="font-size:18px;margin-bottom:0;paddin-bottom:0;">II. Data Klaim</h3> 
            <div style="width:50%;float:left;">
                <table style="width:100%">
                    <tr>
                        <td style="width:30%;">Tanggal Meninggal</td>
                        <td style=""> : </td>
                        <td style="border-bottom:1px solid;width:70%">{{date('d-M-Y',strtotime($data->tanggal_meninggal))}}</td>
                    </tr>
                    <tr>
                        <td>Usia Polis</td>
                        <td> : </td>
                        <td style="border-bottom:1px solid;">{{hitung_umur($data->kepesertaan->tanggal_mulai,3,$data->tanggal_meninggal)}}</td>
                    </tr>
                    <tr>
                        <td>Nilai Klaim</td>
                        <td> : </td>
                        <td style="border-bottom:1px solid;">{{format_idr($data->nilai_klaim_disetujui)}}</td>
                    </tr>
                    <tr>
                        <td>Jenis Klaim</td>
                        <td> : </td>
                        <td style="border-bottom:1px solid;">{{$data->jenis_klaim}}</td>
                    </tr>
                </table>
            </div>
            <div style="width:50%;float:left;margin-left:10px;">
                <table style="width:100%">
                    <tr>
                        <td style="width:30%;">Tempat Klaim</td>
                        <td> : </td>
                        <td style="border-bottom:1px solid;width:70%;">{{$data->tempat_dan_sebab}}</td>
                    </tr>
                    <tr>
                        <td style="width:30%;">Sebab Klaim</td>
                        <td> : </td>
                        <td style="border-bottom:1px solid;width:70%;">{{$data->sebab}}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Pengajuan</td>
                        <td> : </td>
                        <td style="border-bottom:1px solid;">{{date('d-M-Y',strtotime($data->created_at))}}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Dok Lengkap</td>
                        <td> : </td>
                        <td style="border-bottom:1px solid;">{{date('d-M-Y',strtotime($data->tanggal_dok_lengkap))}}</td>
                    </tr>
                    <tr>
                        <td>Tanggal Proses</td>
                        <td> : </td>
                        <td style="border-bottom:1px solid;">{{date('d-M-Y',strtotime($data->head_devisi_date))}}</td>
                    </tr>
                </table>
            </div>
            <div style="clear:both"></div>
            <h3 style="font-size:18px;margin-bottom:0;paddin-bottom:0;">III. Persetujuan Klaim</h3> 
            <table style="width:100%">
                <tr>
                    <th style="text-align:left;border-left:1px solid;border-top:1px solid;border-bottom:1px solid;padding:10px;">1.Head of Dept. Claim Syariah</th>
                    <th style="border-top:1px solid;border-bottom:1px solid">:</th>
                    <th style="text-align:center;border-top:1px solid;border-bottom:1px solid;border-right:1px solid;border-left:1px solid;">Tanggal/Tanda Tangan</th>
                </tr>
                <tr>
                    <td colspan="2" style="padding:10px;border-bottom:1px solid;border-left:1px solid;border-right:1px solid;">{{$data->head_klaim_note}}</td>
                    <td style="text-align:center;border-bottom:1px solid;border-right:1px solid;border-left:1px solid;">{{$data->head_klaim_date ? date('d F Y',strtotime($data->head_klaim_date)):''}}</td>
                </tr>
                <tr>
                    <th style="padding:10px;text-align:left;border-left:1px solid;border-bottom:1px solid;">2.Head of Technic Syariah</th>
                    <th style="border-top:1px solid;border-bottom:1px solid;border-right:1px solid;">:</th>
                    <th style="text-align:center;border-top:1px solid;border-bottom:1px solid;border-right:1px solid;">Tanggal/Tanda Tangan</th>
                </tr>
                <tr>
                    <td colspan="2" style="padding:10px;border-top:1px solid;border-left:1px solid;border-bottom:1px solid;border-right:1px solid;">{{$data->head_teknik_note}}</td>
                    <td style="text-align:center;border-right:1px solid;border-top:1px solid;border-bottom:1px solid;">{{$data->head_teknik_date ? date('d F Y',strtotime($data->head_teknik_date)):''}}</td>
                </tr>
                <tr>
                    <th style="text-align:left;border-left:1px solid;border-bottom:1px solid;padding:10px;">3.Head of Division Syariah</th>
                    <th style="border-bottom:1px solid;">:</th>
                    <th style="text-align:center;border-bottom:1px solid;border-top:1px solid;border-left:1px solid;border-right:1px solid;">Tanggal/Tanda Tangan</th>
                </tr>
                <tr>
                    <td colspan="2" style="padding:10px;border-left:1px solid;border-bottom:1px solid;border-right:1px solid;">{{$data->head_devisi_note}}</td>
                    <td style="text-align:center;border-bottom:1px solid;border-right:1px solid;">{{$data->head_devisi_date ? date('d F Y',strtotime($data->head_devisi_date)):''}}</td>
                </tr>
                <tr>
                    <th style="text-align:left;padding:10px;border-left:1px solid;border-bottom:1px solid;">4.Direksi I</th>
                    <th style="border-bottom:1px solid;border-right:1px solid;">:</th>
                    <th style="border-bottom:1px solid;border-right:1px solid;">Tanggal/Tanda Tangan</th>
                </tr>
                <tr>
                    <td colspan="2" style="padding:10px;border-left:1px solid;border-bottom:1px solid;border-right:1px solid;">{{$data->direksi_1_note}}</td>
                    <td style="text-align:center;border-bottom:1px solid;border-right:1px solid;">{{$data->direksi_1_date ? date('d F Y',strtotime($data->direksi_1_date)):''}}</td>
                </tr>
                <tr>
                    <th style="text-align:left;border-bottom:1px solid;border-left:1px solid;padding:10px;">5.Direksi II</th>
                    <th style="border-right:1px solid;border-bottom:1px solid;">:</th>
                    <th style="border-bottom:1px solid;border-right:1px solid;">Tanggal/Tanda Tangan</th>
                </tr>
                <tr>
                    <td colspan="2" style="padding:10px;border-left:1px solid;border-bottom:1px solid;border-right:1px solid;">{{$data->direksi_2_note}}</td>
                    <td style="text-align:center;border-bottom:1px solid;border-right:1px solid;">{{$data->direksi_2_date ? date('d F Y',strtotime($data->direksi_2_date)):''}}</td>
                </tr>
            </table>
            <div style="page-break-after: always;"></div>
            <h1 style="text-align:center;">MONITOR DOKUMEN DAN ANALISA KLAIM</h1>
            <p style="text-align:center;font-size:11pt;margin-top:0;padding-top:0;">Nomor : {{$data->no_apv}}</p>
            <h3 style="font-size:18px;margin-bottom:0;paddin-bottom:0;">I. Kepesertaan</h3>
            <table>
                <tr>
                    <td>Nomor Polis</td>
                    <td> : </td>
                    <th class="text-left"> {{isset($data->polis->no_polis) ? $data->polis->no_polis : '-'}}</th>
                </tr>
                <tr>
                    <td>Pemegang Polis</td>
                    <td> : </td>
                    <th class="text-left" > {{isset($data->polis->nama) ? $data->polis->nama : '-'}}</th>
                </tr>
                <tr>
                    <td>Produk Asuransi</td>
                    <td> : </td>
                    <th class="text-left"> {{isset($data->polis->produk->singkatan) ? $data->polis->produk->singkatan : '-'}}</th>
                </tr>
                <tr>
                    <td>Nomor Peserta</td>
                    <td> : </td>
                    <th class="text-left"> {{isset($data->kepesertaan->no_peserta) ? $data->kepesertaan->no_peserta : '-'}}</th>
                </tr>
                <tr>
                    <td>Nama Peserta</td>
                    <td> : </td>
                    <th class="text-left"> {{isset($data->kepesertaan->nama) ? $data->kepesertaan->nama : '-'}}</th>
                </tr>
            </table>
            <h3 style="font-size:18px;margin-bottom:0;paddin-bottom:0;">II. Dokumen Pendukung</h3>
            <div style="float:left;width:50%;">
                <table style="width:100%;">
                    <tr>
                        <td style="padding:5px;">1</td>
                        <td>Formulir Pengajuan Klaim</td>
                        <td>
                            @if($data->formulir_pengajuan_klaim)
                                <div style="width:12px;height:13px;border:1px solid;padding-left:4px padding-bottom:4px;"> V </div>
                            @else
                                <div style="width:16px;height:13px;border:1px solid;"></div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:5px;">2</td>
                        <td>Surat Keterangan Meninggal dari Kelurahan/Kades</td>
                        <td>
                            @if($data->surat_keterangan_meninggal_kelurahan)
                                <div style="width:12px;height:13px;border:1px solid;padding-left:4px padding-bottom:4px;"> V </div>
                            @else
                                <div style="width:16px;height:13px;border:1px solid;"></div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:5px;">3</td>
                        <td>Surat Keterangan Meninggal Dunia dari RS</td>
                        <td>
                            @if($data->surat_keterangan_meninggal_rs)
                                <div style="width:12px;height:13px;border:1px solid;padding-left:4px padding-bottom:4px;"> V </div>
                            @else
                                <div style="width:16px;height:13px;;border:1px solid;"></div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:5px;">4</td>
                        <td>Copy Identitas Peserta Asuransi (KTP/kartu pst)</td>
                        <td>
                            @if($data->copy_ktp)
                                <div style="width:12px;height:13px;border:1px solid;padding-left:4px padding-bottom:4px;"> V </div>
                            @else
                                <div style="width:16px;height:13px;border:1px solid;"></div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:5px;">5</td>
                        <td>Copy Identitas Ahli Waris (KTP,KK)</td>
                        <td>
                            @if($data->copy_ktp_ahli_waris)
                                <div style="width:12px;height:13px;border:1px solid;padding-left:4px padding-bottom:4px;"> V </div>
                            @else
                                <div style="width:16px;height:13px;border:1px solid;"></div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:5px;">6</td>
                        <td>Resume Medis/Surat Keterangan Dokter</td>
                        <td>
                            @if($data->resume_medis)
                                <div style="width:12px;height:13px;border:1px solid;padding-left:4px padding-bottom:4px;"> V </div>
                            @else
                                <div style="width:16px;height:13px;border:1px solid;"></div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:5px;">7</td>
                        <td>Daftar Angsuran/Baki Debet</td>
                        <td>
                            @if($data->daftar_angsuran)
                                <div style="width:12px;height:13px;border:1px solid;padding-left:4px padding-bottom:4px;"> V </div>
                            @else
                                <div style="width:16px;height:13px;border:1px solid;"></div>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <div style="float:left;width:50%;">
                <table style="width:100%;">
                    <tr>
                        <td>8</td>
                        <td>Copy Akad Pembiayaan</td>
                        <td>
                            @if($data->copy_akad_pembiayaan)
                                <div style="width:12px;height:13px;border:1px solid;padding-left:4px padding-bottom:4px;"> V </div>
                            @else
                                <div style="width:16px;height:13px;border:1px solid;"></div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>9</td>
                        <td>Surat Kuasa</td>
                        <td>
                            @if($data->surat_kuasa)
                                <div style="width:12px;height:13px;border:1px solid;padding-left:4px padding-bottom:4px;"> V </div>
                            @else
                                <div style="width:16px;height:13px;border:1px solid;"></div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>10</td>
                        <td>Surat Keterangan Ahli Waris</td>
                        <td>
                            @if($data->surat_keterangan_ahli_waris)
                                <div style="width:12px;height:13px;border:1px solid;padding-left:4px padding-bottom:4px;"> V </div>
                            @else
                                <div style="width:16px;height:13px;border:1px solid;"></div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>11</td>
                        <td>Surat dari Pemegang Polis</td>
                        <td>
                            @if($data->surat_dari_pemegang_polis)
                                <div style="width:12px;height:13px;border:1px solid;padding-left:4px padding-bottom:4px;"> V </div>
                            @else
                                <div style="width:16px;height:13px;border:1px solid;"></div>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td>12</td>
                        <td>Dokumen Lainnya</td>
                        <td>
                            @if($data->dokumen_lain)
                                <div style="width:12px;height:13px;border:1px solid;padding-left:4px padding-bottom:4px;"> V </div>
                            @else
                                <div style="width:16px;height:13px;border:1px solid;"></div>
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <div style="clear:both"></div>
            <h3 style="font-size:18px;margin-bottom:0;paddin-bottom:0;">III. Analisa Klaim</h3>
            <table style="width:100%;">
                <tr>
                    <th style="border-bottom:1px solid;padding:10px;width:10px;text-align:left;">1</th>
                    <th style="border-bottom:1px solid;padding:10px;width:30%;text-align:left;">Sumber Informasi</th>
                    <td style="border-bottom:1px solid;padding:10px;width:10px;"> : </td>
                    <td style="border-bottom:1px solid;padding:10px;">{{$data->sumber_informasi}}</td>
                </tr>
                <tr>
                    <th style="border-bottom:1px solid;padding:10px;text-align:left;">2</th>
                    <th style="border-bottom:1px solid;padding:10px;text-align:left;">Sebab Meninggal</th>
                    <td style="border-bottom:1px solid;padding:10px;"> : </td>
                    <td style="border-bottom:1px solid;padding:10px;">{{$data->sebab_meninggal}}</td>
                </tr>
                <tr>
                    <th style="border-bottom:1px solid;padding:10px;text-align:left;">3</th>
                    <th style="border-bottom:1px solid;padding:10px;text-align:left;">Riwayat Penyakit</th>
                    <td style="border-bottom:1px solid;padding:10px;"> : </td>
                    <td style="border-bottom:1px solid;padding:10px;">{{$data->riwayat_penyakit}}</td>
                </tr>
                <tr>
                    <th style="border-bottom:1px solid;padding:10px;text-align:left;">4</th>
                    <th style="border-bottom:1px solid;padding:10px;text-align:left;">Tempat Meninggal</th>
                    <td style="border-bottom:1px solid;padding:10px;"> : </td>
                    <td style="border-bottom:1px solid;padding:10px;">{{$data->tempat_meninggal}}</td>
                </tr>
                <tr>
                    <th style="border-bottom:1px solid;padding:10px;text-align:left;">5</th>
                    <th style="border-bottom:1px solid;padding:10px;text-align:left;">Verifikasi via telfon</th>
                    <td style="border-bottom:1px solid;padding:10px;"> : </td>
                    <td style="border-bottom:1px solid;padding:10px;">{{$data->verifikasi_via_telpon}}</td>
                </tr>
                <tr>
                    <th style="border-bottom:1px solid;padding:10px;text-align:left;">6</th>
                    <th style="border-bottom:1px solid;padding:10px;text-align:left;">Analisa Medis</th>
                    <td style="border-bottom:1px solid;padding:10px;"> : </td>
                    <td style="border-bottom:1px solid;padding:10px;">{{$data->analisa_medis}}</td>
                </tr>
                <tr>
                    <th style="border-bottom:1px solid;padding:10px;text-align:left;">7</th>
                    <th style="border-bottom:1px solid;padding:10px;text-align:left;">Kesimpulan</th>
                    <td style="border-bottom:1px solid;padding:10px;"> : </td>
                    <td style="border-bottom:1px solid;padding:10px;">{{$data->kesimpulan}}</td>
                </tr>
            </table>
            <br />
            <br />
            <br />
            <table style="width:200px;float:right;text-align:center;">
                <tr>
                    <th style="text-align:left">
                        Jakarta,
                    </th>
                    <th style="text-align:right;border-bottom:1px solid; width:100px;">
                        {{date('Y')}}
                    </th>
                </tr>
                <tr>
                    <td colspan="2">
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                        <br />
                    </td>
                </tr>
                <tr>
                    <th colspan="2"><span style="border-bottom:1px solid;">(Estikomah)</span></th>
                </tr>
            </table>
        </div>
    </body>
</html>