{ 
text: '.',
children: [{
    text:'Kepesertaan',
    expanded: false,
    iconCls: 'menu-folder-user',
    children:[{
        text:'Penerimaan Iuran',
        expanded: false,
        iconCls: 'menu-money-add',
        children:[{
            text:'Penerimaan Iuran JAKON',
            id:'penerimaan_iuran_jakon',
            leaf:true,
            iconCls: 'menu-form'
        }]
    },{
        text:'Adjustment Rekon',
        id:'adjustment',
        leaf:true,
        iconCls: 'menu-form'
    },{
        text:'Proses Akhir Bulan',
        id:'proses_akhirbulan',
        leaf:true,
        iconCls: 'menu-form'
    },{
        text:'Pelaporan Operasional',
        id:'report_operasional',
        leaf:true,
        iconCls: 'menu-report'
    }]
},{
    text:'Pelayanan',
    expanded: false,
    iconCls: 'menu-folder-heart',
    children:[{
        text:'Pembayaran Klaim',
        id:'jaminan_pembayaran_klaim',
        leaf:true,
        iconCls: 'menu-form'
    }]
},{
    text:'Keuangan',
    expanded: false,
    iconCls: 'menu-coins',
    children:[{
    	text:'Monitoring Anggaran',
        expanded: false,
        iconCls: 'menu-folder',
        children:[{
                text:'Realisasi',
                id:'realisasi_anggaran',
                leaf:true,
        iconCls: 'menu-form'
            },{
                text:'Pergeseran Anggaran',
                id:'pergeseran_anggaran',
                leaf:true,
        iconCls: 'menu-form'
        	}]
        },{
        text:'Pencairan Anggaran',
        expanded: false,
        iconCls: 'menu-folder',
        children:[{
            text:'Setup Pejabat Approval',
            id:'keu_setup_pejabatapproval',
            leaf:true,
            iconCls: 'menu-form'
          },{
            text:'Memo Pencairan',
            id:'mppa',
            leaf:true,
            iconCls: 'menu-form'
          },{
            text:'Approval MPPA',
            id:'mppa_approval',
            leaf:true,
            iconCls: 'menu-form'
          }]
    },{
    text:'Kas dan Bank',
    expanded: false,
    iconCls: 'menu-folder',
    children:[{
        text:'Pembayaran',
        id:'mppa_pembayaran',
        leaf:true,
        iconCls: 'menu-form'
        },{
        text:'Pindah dana VA',
        id:'transfer_iuran_jakon',
        leaf:true,
        iconCls: 'menu-form'
        },{
        text:'Pindah dana JAKON',
        id:'pemindahbukuan_iuran_jakon',
        leaf:true,
        iconCls: 'menu-form'
        },{
        text:'Estimasi Biaya',
        id:'estimasi_biaya',
        leaf:true,
        iconCls: 'menu-form'
        }]
    }]
},{
    text:'Akuntansi',
    expanded: false,
    iconCls: 'menu-folder-bulb',
    children:[{
        text:'Proses',
        expanded: false,
        iconCls: 'menu-folder',
        children:[{
            text:'Refresh',
            id:'proses_refresh',
            leaf:true,
        iconCls: 'menu-form'
        }]
    },{
    text:'Pelaporan',
    expanded: false,
    iconCls: 'menu-folder',
    children:[{
        text:'Laporan Transaksi',
        expanded: false,
        iconCls: 'menu-folder',
        children:[{
                text:'Listing Transaksi',
                id:'laporan_listing_transaksi',
                leaf:true,
        		iconCls: 'menu-report'
            },{
                text:'Memorial',
                id:'laporan_memorial',
                leaf:true,
        		iconCls: 'menu-report'
            },{
                text:'Buku Harian',
                id:'laporan_buku_harian',
                leaf:true,
        		iconCls: 'menu-report'
            },{
                text:'Rekap Buku Harian',
                id:'rekap_buku_harian',
                leaf:true,
        		iconCls: 'menu-report'
            },{
                text:'Buku Besar',
                id:'buku_besar',
                leaf:true,
        		iconCls: 'menu-report'
            },{
                text:'Ledger Subledger',
                id:'ledger_subledger',
                leaf:true,
        		iconCls: 'menu-report'
            },{
                text:'Neraca Saldo',
                id:'neraca_saldo',
                leaf:true,
        		iconCls: 'menu-report'
            },{
                text:'Persekot Kerja',
                id:'persekot_kerja',
                leaf:true,
        		iconCls: 'menu-report'
            }]
          },{
            text:'Laporan MIS Akuntansi',
            expanded: false,
            iconCls: 'menu-folder',
            children:[{
                    text:'MIS Akuntansi',
                    id:'laporan_mis_akuntansi',
                    leaf:true,
        			iconCls: 'menu-report'
            }]
        }]
    }]
}]
}