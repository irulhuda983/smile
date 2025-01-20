<?PHP
if($_REQUEST["kdkantor"] == '0'){
?>
{ 
text: 'Pilih menu',
id:'menurole',
children: [{
        text:'Data Pendukung',
        id:'101000000000000',
        expanded: false,
        iconCls: 'menu-data-support',
		children:[{
                        text:'Setup Kantor',
                        id:'101010000000000',
                        fungsi:'1',
                        leaf:true,
                        iconCls: 'menu-form'
                    },{
                        text:'Setup Bank',
                        id:'101020000000000',
                        fungsi:'1',
                        leaf:true,
                        iconCls: 'menu-form'
                    },{
                        text:'Notifikasi',
                        id:'101030000000000',
                        fungsi:'1',
                        leaf:true,
                        iconCls: 'menu-form'
                    }]
    },{
        text:'Operasional dan Pelayanan',
        id:'102000000000000',
        expanded: false,
        iconCls: 'menu-folder-heart',
        children:[{
                text:'Kepesertaan',
                id:'102010000000000',
                fungsi:'1',
                expanded: false,
                iconCls: 'menu-folder-user',
                children:[{
                        text:'Entry Penerimaan Iuran Jakons',
                        id:'102010100000000',
                        fungsi:'1',
                        leaf:true,
                        iconCls: 'menu-form'
                    },{
                        text:'Pelaporan',
                        id:'102010600000000',
                        fungsi:'1',
                        leaf:true,
                        iconCls: 'menu-report'
                    }]
            },{
                text:'Pelayanan Jaminan',
                id:'102020000000000',
                fungsi:'1',
                iconCls: 'menu-folder-user',
                children:[{
                        text:'Entry Pembayaran Jaminan',
                        id:'102020100000000',
                        leaf:true,
                        iconCls: 'menu-form'
                    },{
                        text:'Daftar Pembayaran Jaminan',
                        id:'102020200000000',
                        leaf:true,
                        iconCls: 'menu-form'
                    },{
                        text:'Pelaporan Jaminan',
                        id:'102020300000000',
                        leaf:true,
                        iconCls: 'menu-report'
                    }]
            }]
    },{
        text:'Akuntansi dan Keuangan',
        id:'103000000000000',
        expanded: false,
        iconCls: 'menu-coins',
        children:[{
                text:'General Ledger',
                id:'103010000000000',
                expanded: false,
                iconCls: 'menu-folder',
                children:[{
                        text:'Setup',
                        id:'103010100000000',
                        expanded: false,
                        iconCls: 'menu-data-support',
                        children:[{
                                text:'Setup Periode Transaksi',
                                id:'103010101000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Setup Akun',
                                id:'103010102000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Setup Jurnal Standar',
                                id:'103010103000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Setup Mata Uang',
                                id:'103010104000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Setup Format Laporan',
                                id:'103010105000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Setup Pointer',
                                id:'103010106000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	}]
                    },{
                        text:'Rekening Antara',
                        id:'103010200000000',
                        expanded: false,
                        iconCls: 'menu-folder',
                        children:[{
                                text:'Entry Transfer Dropping Kantor Daerah',
                                id:'103010201000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Entry Transfer Dropping Kantor Pusat',
                                id:'103010202000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	}]
                    },{
                        text:'Jurnal Lainnya',
                        id:'103010300000000',
                        expanded: false,
                        iconCls: 'menu-folder',
                        children:[{
                                text:'Entry Jurnal Lainnya',
                                id:'103010301000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Verifikasi Jurnal Lainnya',
                                id:'103010302000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	}]
                    },{
                        text:'Proses',
                        id:'103010600000000',
                        expanded: false,
                        iconCls: 'menu-folder',
                        children:[{
                                text:'Proses Akhir Periode',
                                id:'103010601000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Refresh Data Keuangan',
                                id:'103010602000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	}]
                    },{
                        text:'Pelaporan',
                        id:'103010700000000',
                        expanded: false,
                        iconCls: 'menu-report',
                        children:[{
                                text:'Listing Transaksi',
                                id:'103010701000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Laporan Memorial',
                                id:'103010702000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Laporan Buku Harian',
                                id:'103010703000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Laporan Rekap Buku Harian',
                                id:'103010704000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Laporan Buku Besar',
                                id:'103010705000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Laporan Ledger Subledger',
                                id:'103010706000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Laporan Neraca Saldo',
                                id:'103010707000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Laporan Persekot Kerja',
                                id:'103010708000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Laporan Akhir Periode',
                                id:'103010709000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Laporan MIS Akuntansi',
                                id:'103010710000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	}]
                    }]
            },{
                text:'Pengeluaran',
                id:'103020000000000',
                expanded: false,
                iconCls: 'menu-folder',
                children:[{
                		text:'Setup',
                        id:'103020100000000',
                        expanded: false,
                        iconCls: 'menu-data-support',
                        children:[{
                                text:'Setup Pegawai',
                                id:'103020101000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Setup Pegawai External',
                                id:'103020102000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Setup Rekanan',
                                id:'103020103000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Setup Pencairan Anggaran',
                                id:'103020104000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Setup Otorisasi',
                                id:'103020105000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	}]
                    },{
                        text:'Pencairan Anggaran',
                        id:'103020200000000',
                        expanded: false,
                        iconCls: 'menu-folder',
                        children:[{                    
                                text:'Entry Memo Pencairan Anggaran',
                                id:'103020201000000',
                                leaf:true,
                                iconCls: 'menu-form'
                            },{
                                text:'Approval Memo Pencairan Anggaran',
                                id:'103020202000000',
                                leaf:true,
                                iconCls: 'menu-form'
                            },{
                                text:'Koreksi Memo Pencairan Anggaran',
                                id:'103020203000000',
                                leaf:true,
                                iconCls: 'menu-form'
                            },{
                                text:'Pembatalan Memo Pencairan Anggaran',
                                id:'103020204000000',
                                leaf:true,
                                iconCls: 'menu-form'
                            }]
                    },{
                        text:'Beban Lainnya',
                        id:'103020300000000',
                        expanded: false,
                        iconCls: 'menu-folder',
                        children:[{
                                text:'Entry Beban Lainnya',
                                id:'103020301000000',
                                leaf:true,
                                iconCls: 'menu-form'
                            },{
                                text:'Approval Beban Lainnya',
                                id:'103020302000000',
                                leaf:true,
                                iconCls: 'menu-form'
                            }]
                    },{
                        text:'Pelaporan',
                        id:'103020500000000',
                        leaf:true,
                        iconCls: 'menu-report'
                    }]
            },{
                text:'Penerimaan',
                id:'103030000000000',
                expanded: false,
                iconCls: 'menu-folder',
                children:[{
                        text:'Entry Penerimaan Lainnya',
                        id:'103030100000000',
                        leaf:true,
                        iconCls: 'menu-form'
                    },{
                        text:'Approval Penerimaan Lainnya',
                        id:'103030200000000',
                        leaf:true,
                        iconCls: 'menu-form'
                    },{
                        text:'Pelaporan',
                        id:'103030300000000',
                        leaf:true,
                        iconCls: 'menu-report'
                    }]
            },{
                text:'Kas dan Bank',
                id:'103040000000000',
                expanded: false,
                iconCls: 'menu-folder',
                children:[{
                        text:'Pembayaran',
                        id:'103040100000000',
                        leaf:true,
                        iconCls: 'menu-form'
                    },{
                        text:'Penerimaan',
                        id:'103040200000000',
                        leaf:true,
                        iconCls: 'menu-form'
                    },{
                        text:'Pemindahbukuan',
                        id:'103040300000000',
                        expanded: false,
                        iconCls: 'menu-folder',
                        children:[{
                                text:'Iuran dan Fee',
                                id:'103040301000000',
                                leaf:true,
                                iconCls: 'menu-form'
                            },{
                                text:'Iuran Jakons',
                                id:'103040302000000',
                                leaf:true,
                                iconCls: 'menu-form'
                            },{
                                text:'Lainnya',
                                id:'103040303000000',
                                leaf:true,
                                iconCls: 'menu-form'
                            }]
                    },{
                        text:'Estimasi Biaya',
                        id:'103040400000000',
                        leaf:true,
                        iconCls: 'menu-form'
                    },{
                        text:'Pelaporan',
                        id:'103040500000000',
                        leaf:true,
                        iconCls: 'menu-report'
                    }]
            },{
                text:'Anggaran',
                id:'103050000000000',
                expanded: false,
                iconCls: 'menu-folder',
                children:[{
                        text:'Penyusunan Anggaran',
                        id:'103050100000000',
                        leaf:true,
                        iconCls: 'menu-form'
                    },{
                        text:'Pergeseran dan Monitoring',
                        id:'103050200000000',
                        expanded: false,
                        iconCls: 'menu-folder',
                        children:[{
                                text:'Setup Format Laporan LRA',
                                id:'103050201000000',
                                leaf:true,
                                iconCls: 'menu-form'
                            },{
                                text:'Master Anggaran',
                                id:'103050202000000',
                                leaf:true,
                                iconCls: 'menu-form'
                            },{
                                text:'Entry Pergeseran Anggaran',
                                id:'103050203000000',
                                leaf:true,
                                iconCls: 'menu-form'
                            },{
                                text:'Approval Pergeseran Anggaran',
                                id:'103050204000000',
                                leaf:true,
                                iconCls: 'menu-form'
                            },{
                                text:'Laporan Pergeseran Anggaran',
                                id:'103050205000000',
                                leaf:true,
                                iconCls: 'menu-report'
                            },{
                                text:'Laporan Realisasi Anggaran',
                                id:'103050206000000',
                                leaf:true,
                                iconCls: 'menu-report'
                            }]
                    }]
            },{
                text:'Perpajakan',
                id:'103060000000000',
                expanded: false,
                iconCls: 'menu-folder',
                children: [{
                		text:'Setup',
                        id:'103010100000000',
                        expanded: false,
                        iconCls: 'menu-data-support',
                        children:[{
                                text:'Setup Wajib Pajak',
                                id:'103060101000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Setup Kantor KPP',
                                id:'103060102000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Setup Jenis Pajak',
                                id:'103060103000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Setup MAP Code',
                                id:'103060104000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Setup Tarif Pajak',
                                id:'103060105000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Setup Objek Pajak',
                                id:'103060106000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Setup PTKP',
                                id:'103060107000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Setup Treaty',
                                id:'103060108000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Setup Jenis Faktur',
                                id:'103060109000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Setup Format Laporan Pajak',
                                id:'103060110000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	}]
                    },{
                        text:'Proses',
                        id:'103060200000000',
                        expanded: false,
                        iconCls: 'menu-folder',
                        children:[{
                                text:'Entry Setoran Pajak',
                                id:'103060201000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Approval Setoran Pajak',
                                id:'103060202000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	}]
                    },{
                        text:'Pelaporan',
                        id:'103060400000000',
                        expanded: false,
                        iconCls: 'menu-folder',
                        children:[{
                                text:'Laporan SSP',
                                id:'103060401000000',
                                leaf:true,
                                iconCls: 'menu-report'
                        	},{
                                text:'Laporan Rekonsiliasi Pajak',
                                id:'103060402000000',
                                leaf:true,
                                iconCls: 'menu-report'
                        	},{
                                text:'Laporan SPT',
                                id:'103060403000000',
                                leaf:true,
                                iconCls: 'menu-report'
                        	},{
                                text:'Laporan Format CSV',
                                id:'103060404000000',
                                leaf:true,
                                iconCls: 'menu-report'
                        	}]
                    }]
                       
            }]
    }]
}
<?PHP
} else {
?>
{ 
text: 'Pilih menu',
id:'menurole',
children: [{
        text:'Operasional dan Pelayanan',
        id:'102000000000000',
        expanded: false,
        iconCls: 'menu-folder-heart',
        children:[{
                text:'Kepesertaan',
                id:'102010000000000',
                fungsi:'1',
                expanded: false,
                iconCls: 'menu-folder-user',
                children:[{
                        text:'Entry Penerimaan Iuran Jakons',
                        id:'102010100000000',
                        fungsi:'1',
                        leaf:true,
                        iconCls: 'menu-form'
                    },{
                        text:'Adjustment Rekon',
                        id:'102010200000000',
                        fungsi:'1',
                        leaf:true,
                        iconCls: 'menu-form'
                    },{
                    	text:'Daftar Penerimaan Iuran',
                        id:'102010300000000',
                        fungsi:'1',
                        leaf:true,
                        iconCls: 'menu-report'
                    },{
                    	text:'Daftar Rekonsiliasi Iuran',
                        id:'102010400000000',
                        fungsi:'1',
                        leaf:true,
                        iconCls: 'menu-report'
                    },{
                    	text:'Daftar Piutang Iuran',
                        id:'102010500000000',
                        fungsi:'1',
                        leaf:true,
                        iconCls: 'menu-report'
                    },{
                        text:'Pelaporan',
                        id:'102010600000000',
                        fungsi:'1',
                        leaf:true,
                        iconCls: 'menu-report'
                    }]
            },{
                text:'Pelayanan Jaminan',
                id:'102020000000000',
                fungsi:'1',
                iconCls: 'menu-folder-user',
                children:[{
                        text:'Entry Pembayaran Jaminan',
                        id:'102020100000000',
                        leaf:true,
                        iconCls: 'menu-form'
                    },{
                        text:'Daftar Pembayaran Jaminan',
                        id:'102020200000000',
                        leaf:true,
                        iconCls: 'menu-form'
                    },{
                        text:'Pelaporan Jaminan',
                        id:'102020300000000',
                        leaf:true,
                        iconCls: 'menu-report'
                    }]
            }]
    },{
        text:'Akuntansi dan Keuangan',
        id:'103000000000000',
        expanded: false,
        iconCls: 'menu-coins',
        children:[{
                text:'General Ledger',
                id:'103010000000000',
                expanded: false,
                iconCls: 'menu-folder',
                children:[{
                        text:'Rekening Antara',
                        id:'103010200000000',
                        expanded: false,
                        iconCls: 'menu-folder',
                        children:[{
                                text:'Entry Transfer Dropping Kantor Daerah',
                                id:'103010201000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	}]
                    },{
                        text:'Jurnal Lainnya',
                        id:'103010300000000',
                        expanded: false,
                        iconCls: 'menu-folder',
                        children:[{
                                text:'Entry Jurnal Lainnya',
                                id:'103010301000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Verifikasi Jurnal Lainnya',
                                id:'103010302000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	}]
                    },{
                        text:'Proses',
                        id:'103010600000000',
                        expanded: false,
                        iconCls: 'menu-folder',
                        children:[{
                                text:'Refresh Data Keuangan',
                                id:'103010602000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	}]
                    },{
                        text:'Pelaporan',
                        id:'103010700000000',
                        expanded: false,
                        iconCls: 'menu-report',
                        children:[{
                                text:'Listing Transaksi',
                                id:'103010701000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Laporan Memorial',
                                id:'103010702000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Laporan Buku Harian',
                                id:'103010703000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Laporan Rekap Buku Harian',
                                id:'103010704000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	}]
                    }]
            },{
                text:'Pengeluaran',
                id:'103020000000000',
                expanded: false,
                iconCls: 'menu-folder',
                children:[{
                		text:'Setup',
                        id:'103020100000000',
                        expanded: false,
                        iconCls: 'menu-data-support',
                        children:[{
                                text:'Setup Pegawai',
                                id:'103020101000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Setup Pegawai External',
                                id:'103020102000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Setup Rekanan',
                                id:'103020103000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Setup Otorisasi',
                                id:'103020105000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	}]
                    },{
                        text:'Pencairan Anggaran',
                        id:'103020200000000',
                        expanded: false,
                        iconCls: 'menu-folder',
                        children:[{                    
                                text:'Entry Memo Pencairan Anggaran',
                                id:'103020201000000',
                                leaf:true,
                                iconCls: 'menu-form'
                            },{
                                text:'Approval Memo Pencairan Anggaran',
                                id:'103020202000000',
                                leaf:true,
                                iconCls: 'menu-form'
                            },{
                                text:'Koreksi Memo Pencairan Anggaran',
                                id:'103020203000000',
                                leaf:true,
                                iconCls: 'menu-form'
                            },{
                                text:'Pembatalan Memo Pencairan Anggaran',
                                id:'103020204000000',
                                leaf:true,
                                iconCls: 'menu-form'
                            }]
                    },{
                        text:'Beban Lainnya',
                        id:'103020300000000',
                        expanded: false,
                        iconCls: 'menu-folder',
                        children:[{
                                text:'Entry Beban Lainnya',
                                id:'103020301000000',
                                leaf:true,
                                iconCls: 'menu-form'
                            },{
                                text:'Approval Beban Lainnya',
                                id:'103020302000000',
                                leaf:true,
                                iconCls: 'menu-form'
                            }]
                    },{
                        text:'Pelaporan',
                        id:'103020500000000',
                        leaf:true,
                        iconCls: 'menu-report'
                    }]
            },{
                text:'Penerimaan Lainnya',
                id:'103030000000000',
                expanded: false,
                iconCls: 'menu-folder',
                children:[{
                        text:'Entry Penerimaan Lainnya',
                        id:'103030100000000',
                        leaf:true,
                        iconCls: 'menu-form'
                    },{
                        text:'Approval Penerimaan Lainnya',
                        id:'103030200000000',
                        leaf:true,
                        iconCls: 'menu-form'
                    },{
                        text:'Pelaporan',
                        id:'103030300000000',
                        leaf:true,
                        iconCls: 'menu-report'
                    }]
            },{
                text:'Kas dan Bank',
                id:'103040000000000',
                expanded: false,
                iconCls: 'menu-folder',
                children:[{
                        text:'Pembayaran',
                        id:'103040100000000',
                        leaf:true,
                        iconCls: 'menu-form'
                    },{
                        text:'Penerimaan',
                        id:'103040200000000',
                        leaf:true,
                        iconCls: 'menu-form'
                    },{
                        text:'Pemindahbukuan',
                        id:'103040300000000',
                        expanded: false,
                        iconCls: 'menu-folder',
                        children:[{
                                text:'Iuran dan Fee',
                                id:'103040301000000',
                                leaf:true,
                                iconCls: 'menu-form'
                            },{
                                text:'Iuran Jakons',
                                id:'103040302000000',
                                leaf:true,
                                iconCls: 'menu-form'
                            },{
                                text:'Pindah Buku Lainnya',
                                id:'103040303000000',
                                leaf:true,
                                iconCls: 'menu-form'
                            }]
                    },{
                        text:'Estimasi Biaya',
                        id:'103040400000000',
                        leaf:true,
                        iconCls: 'menu-form'
                    },{
                        text:'Pelaporan',
                        id:'103040500000000',
                        leaf:true,
                        iconCls: 'menu-report'
                    }]
            },{
                text:'Anggaran',
                id:'103050000000000',
                expanded: false,
                iconCls: 'menu-folder',
                children:[{
                        text:'Pergeseran dan Monitoring',
                        id:'103050200000000',
                        expanded: false,
                        iconCls: 'menu-folder',
                        children:[{
                                text:'Master Anggaran',
                                id:'103050202000000',
                                leaf:true,
                                iconCls: 'menu-form'
                            },{
                                text:'Entry Pergeseran Anggaran',
                                id:'103050203000000',
                                leaf:true,
                                iconCls: 'menu-form'
                            },{
                                text:'Approval Pergeseran Anggaran',
                                id:'103050204000000',
                                leaf:true,
                                iconCls: 'menu-form'
                            },{
                                text:'Laporan Pergeseran Anggaran',
                                id:'103050205000000',
                                leaf:true,
                                iconCls: 'menu-report'
                            },{
                                text:'Laporan Realisasi Anggaran',
                                id:'103050206000000',
                                leaf:true,
                                iconCls: 'menu-report'
                            }]
                    }]
            },{
                text:'Perpajakan',
                id:'103060000000000',
                expanded: false,
                iconCls: 'menu-folder',
                children: [{
                		text:'Setup',
                        id:'103010100000000',
                        expanded: false,
                        iconCls: 'menu-data-support',
                        children:[{
                                text:'Setup Wajib Pajak',
                                id:'103060101000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Setup Kantor KPP',
                                id:'103060102000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	}]
                    },{
                        text:'Setoran Pajak',
                        id:'103060200000000',
                        expanded: false,
                        iconCls: 'menu-folder',
                        children:[{
                                text:'Entry Setoran Pajak',
                                id:'103060201000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	},{
                                text:'Approval Setoran Pajak',
                                id:'103060202000000',
                                leaf:true,
                                iconCls: 'menu-form'
                        	}]
                    },{
                        text:'Pelaporan',
                        id:'103060400000000',
                        expanded: false,
                        iconCls: 'menu-folder',
                        children:[{
                                text:'Laporan SSP',
                                id:'103060401000000',
                                leaf:true,
                                iconCls: 'menu-report'
                        	},{
                                text:'Laporan Rekonsiliasi Pajak',
                                id:'103060402000000',
                                leaf:true,
                                iconCls: 'menu-report'
                        	},{
                                text:'Laporan SPT',
                                id:'103060403000000',
                                leaf:true,
                                iconCls: 'menu-report'
                        	},{
                                text:'Laporan Format CSV',
                                id:'103060404000000',
                                leaf:true,
                                iconCls: 'menu-report'
                        	}]
                    }]
                       
            }]
    }]
}
<?PHP
}
?>