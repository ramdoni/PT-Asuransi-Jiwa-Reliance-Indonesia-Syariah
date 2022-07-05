<?php 
    /*
    |--------------------------------------------------------------------------
    | Default Vars
    |--------------------------------------------------------------------------
    |
    | variabel ini dibuat untuk kebutuhan aplikasi
    | variable data yang sudah di define disini akan menjadi standar data yang digunakan di aplikasi
    | kamu bisa menambahkan data-data lain untuk kebutuhan aplikasi
    |
    | 
    |
    */

return [
    'distribution_type' =>['CROSS SELLING','AGENCY','BROKER ASURANSI','CO INSURANCE','BANCASSURANCE','DIRECT MARKETING'],
    'line_bussines' =>['DWIGUNA','JANGKAWARSA','EKAWARSA','KECELAKAAN'],
    'reason_reject' => ['Decline, Usia masuk diluar ketentuan polis',
                         'Decline, Usia masuk ditambah masa asuransi diluar ketentuan polis',
                         'Decline, Masa asuransi diluar ketentuan polis',
                         'Decline, Mengalami obesitas grade I / II / III ; menderita penyakit',
                         'Decline, Masa asuransi telah berakhir (mature)',
                         'Postpone, Masa asuransi belum dimulai',
                         'Postpone, Mohon konfirmasinya mengenai Tgl. Lahir / Tgl. Mulai / Tgl. Akhir',
                         'Postpone, Mohon melengkapi SPK / Medis A B C D E',
                         'Postpone, Sampai dengan hasil MCU Medis A B C D E diterima',
                         'Postpone, Sampai dengan persetujuan Extra Mortalita diterima karena',
                         'Postpone, Mohon konfirmasinya mengenai perbedaan data peserta excel dengan SPK',
                         'Postpone, Mohon melengkapi jawaban deklarasi no … pada SPK']
];