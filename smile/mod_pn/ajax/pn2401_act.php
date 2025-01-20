<?php 
session_start();
//Created @ 01/02/2008 to build ajax process enable
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$action= isset($_GET['formregact'])?$_GET['formregact']:'';
$key1= isset($_GET['key1'])?$_GET['key1']:'';
$key1= isset($_GET['key1'])?$_GET['key1']:'';

$action= isset($_POST['formregact'])?$_POST['formregact']:$action;
$key1= isset($_POST['key1'])?$_POST['key1']:$key1;
$key2= isset($_POST['key1'])?$_POST['key1']:$key2;
$schema="sijstk";
/*****get parameter***********/
if($action=='New' || $action=='Edit')
{
    $ls_daftar          = strtoupper($_POST["daftar"]);
    $ls_nama_faskes     = strtoupper($_POST["nama_faskes"]);
    $ls_kode_faskes     = strtoupper($_POST["kode_faskes"]);
    $ls_kode_tipe       = strtoupper($_POST["kode_tipe"]);
    $ls_kode_kantor     = strtoupper($_POST["kode_kantor"]);
    $ls_kode_pembina    = $_SESSION["USER"];
    $ls_nama_pic        = strtoupper($_POST["nama_pic"]);
    $ls_handphone_pic   = strtoupper($_POST["handphone_pic"]);
    $ls_alamat          = strtoupper($_POST["alamat"]);
    $ls_rt              = strtoupper($_POST["rt"]);
    $ls_rw              = strtoupper($_POST["rw"]);
    $ls_kode_pos        = strtoupper($_POST["kode_pos"]);
    $ls_kode_kelurahan  = strtoupper($_POST["kode_kelurahan"]);
    $ls_kode_kecamatan  = strtoupper($_POST["kode_kecamatan"]);
    $ls_kode_kabupaten  = strtoupper($_POST["kode_kabupaten"]);
    $ls_kode_provinsi   = strtoupper($_POST["kode_provinsi"]);
    $ls_no_ijin_praktek = strtoupper($_POST["no_ijin_praktek"]);
    $ls_npwp            = strtoupper($_POST["npwp"]);
    $ls_max_tertanggung = strtoupper($_POST["max_tertanggung"]);
    $ls_kode_jenis      = strtoupper($_POST["kode_jenis"]);
    $ls_kode_jenis_detil = strtoupper($_POST["kode_jenis_detil"]);
    $ls_umum       = isset($_POST["umum"])?strtoupper($_POST["umum"]):"T";
    $ls_salin      = isset($_POST["salin"])?strtoupper($_POST["salin"]):"T";
    $ls_gigi       = isset($_POST["gigi"])?strtoupper($_POST["gigi"]):"T";
    $ls_regweb     = isset($_POST["regweb"])?strtoupper($_POST["regweb"]):"T";
    $ls_keterangan = strtoupper($_POST["keterangan"]);
    //$ls_tgl_aktif = trim($_POST["tgl_aktif"])==""?"12/31/2000":trim($_POST["tgl_aktif"]);
    //$ls_tgl_non_aktif = trim($_POST["tgl_non_aktif"])==""?"12/31/2000":trim($_POST["tgl_non_aktif"]);

    $ls_nama_pemilik = strtoupper($_POST["nama_pemilik"]);
    $ls_alamat_pemilik = strtoupper($_POST["alamat_pemilik"]);
    $ls_rt_pemilik              = strtoupper($_POST["rt_pemilik"]);
    $ls_rw_pemilik              = strtoupper($_POST["rw_pemilik"]);
    $ls_kode_pos_pemilik        = strtoupper($_POST["kode_pos_pemilik"]);
    $ls_kode_kelurahan_pemilik  = strtoupper($_POST["kode_kelurahan_pemilik"]);
    $ls_kode_kecamatan_pemilik  = strtoupper($_POST["kode_kecamatan_pemilik"]);
    $ls_kode_kabupaten_pemilik  = strtoupper($_POST["kode_kabupaten_pemilik"]);
    $ls_telepon_area_pemilik    = strtoupper($_POST["telp_area_pemilik"]);
    $ls_telepon_pemilik    = strtoupper($_POST["telp_pemilik"]);
    $ls_telepon_ext_pemilik    = strtoupper($_POST["telp_ext_pemilik"]);
    $ls_fax_area_pemilik    = strtoupper($_POST["fax_area_pemilik"]);
    $ls_fax_pemilik    = strtoupper($_POST["fax_pemilik"]);
    $ls_email_pemilik    = strtoupper($_POST["email_pemilik"]);
    $ls_kode_kepemilikan    = strtoupper($_POST["kode_kepemilikan"]);
    //$ls_kode_status     = strtoupper($_POST["kode_status"]);

    $ls_kode_metode_pembayaran   = strtoupper($_POST["paymethod"]);
    $ls_bankcode   = strtoupper($_POST["bankcode"]);
    $ls_norek   = strtoupper($_POST["norek"]);
    $ls_namarek   = strtoupper($_POST["namarek"]);

    $ls_telepon_area_pic    = strtoupper($_POST["telp_area_pic"]);
    $ls_telepon_pic         = strtoupper($_POST["telp_pic"]);
    $ls_telepon_ext_pic     = strtoupper($_POST["telp_ext_pic"]);
}
if($action=='New')
{  
    $ls_kode_new_faskes="";
    $sql1 = "select '{$ls_kode_tipe}{$ls_kode_kantor}'||to_char(sysdate,'YYMMDD')|| lpad(tc.seq_tc_kode_faskes.nextval,3,'0') NEW_KODE from dual ";
    $DB->parse($sql1);
    $DB->execute();
    if($row = $DB->nextrow())
        $ls_kode_new_faskes = $row["NEW_KODE"];
    
    if($ls_daftar=='Y')
        $sql = "insert into {$schema}.tc_faskes(KODE_FASKES, KODE_TIPE, KODE_KANTOR, KODE_PEMBINA, NAMA_FASKES, ALAMAT, RT, RW,KODE_KELURAHAN,
        KODE_KECAMATAN, KODE_KABUPATEN, KODE_POS, NAMA_PIC, HANDPHONE_PIC,  KODE_STATUS, KODE_JENIS, KODE_JENIS_DETIL, NO_IJIN_PRAKTEK, NPWP,
        MAX_TERTANGGUNG, FLAG_UMUM,FLAG_GIGI, FLAG_SALIN, FLAG_REG_WEBSITE, KETERANGAN,  NAMA_PEMILIK,  ALAMAT_PEMILIK,  RT_PEMILIK,  RW_PEMILIK,
        KODE_KELURAHAN_PEMILIK, KODE_KECAMATAN_PEMILIK, KODE_KABUPATEN_PEMILIK,  KODE_POS_PEMILIK, TELEPON_AREA_PEMILIK, TELEPON_PEMILIK, TELEPON_EXT_PEMILIK,
        FAX_AREA_PEMILIK, FAX_PEMILIK, EMAIL_PEMILIK, KODE_KEPEMILIKAN, KODE_METODE_PEMBAYARAN,  KODE_BANK_PEMBAYARAN, NAMA_REKENING_PEMBAYARAN,
        NO_REKENING_PEMBAYARAN, TGL_REKAM,PETUGAS_REKAM,TGL_SUBMIT,PETUGAS_SUBMIT,TELEPON_AREA_PIC,TELEPON_PIC,TELEPON_EXT_PIC)
        values('{$ls_kode_new_faskes}','{$ls_kode_tipe}','{$ls_kode_kantor}','{$ls_kode_pembina}'
            ,'{$ls_nama_faskes}','{$ls_alamat}','{$ls_rt}','{$ls_rw}','{$ls_kode_kelurahan}','{$ls_kode_kecamatan}','{$ls_kode_kabupaten}',
            '{$ls_kode_pos}','{$ls_nama_pic}','{$ls_handphone_pic}','ST2','{$ls_kode_jenis}','{$ls_kode_jenis_detil}','{$ls_no_ijin_praktek}',
            '{$ls_npwp}','{$ls_max_tertanggung}','{$ls_umum}','{$ls_gigi}','{$ls_salin}','{$ls_regweb}','{$ls_keterangan}','{$ls_nama_pemilik}'
            ,'{$ls_alamat_pemilik}','{$ls_rt_pemilik}','{$ls_rw_pemilik}','{$ls_kode_kelurahan_pemilik}','{$ls_kode_kecamatan_pemilik}'
            ,'{$ls_kode_kabupaten_pemilik}','{$ls_kode_pos_pemilik}','{$ls_telepon_area_pemilik}','{$ls_telepon_pemilik}','{$ls_telepon_ext_pemilik}'
            ,'{$ls_fax_area_pemilik}','{$ls_fax_pemilik}','{$ls_email_pemilik}','{$ls_kode_kepemilikan}','{$ls_kode_metode_pembayaran}'
            ,'{$ls_bankcode}','{$ls_namarek}','{$ls_norek}',sysdate,'{$_SESSION["USER"]}',sysdate,'{$_SESSION["USER"]}','{$ls_telepon_area_pic}','{$ls_telepon_pic}','{$ls_telepon_ext_pic}')";
    else
        $sql = "insert into {$schema}.tc_faskes(KODE_FASKES, KODE_TIPE, KODE_KANTOR, KODE_PEMBINA, NAMA_FASKES, ALAMAT, RT, RW,KODE_KELURAHAN,
        KODE_KECAMATAN, KODE_KABUPATEN, KODE_POS, NAMA_PIC, HANDPHONE_PIC,  KODE_STATUS, KODE_JENIS, KODE_JENIS_DETIL, NO_IJIN_PRAKTEK, NPWP,
        MAX_TERTANGGUNG, FLAG_UMUM,FLAG_GIGI, FLAG_SALIN, FLAG_REG_WEBSITE, KETERANGAN,  NAMA_PEMILIK,  ALAMAT_PEMILIK,  RT_PEMILIK,  RW_PEMILIK,
        KODE_KELURAHAN_PEMILIK, KODE_KECAMATAN_PEMILIK, KODE_KABUPATEN_PEMILIK,  KODE_POS_PEMILIK, TELEPON_AREA_PEMILIK, TELEPON_PEMILIK, TELEPON_EXT_PEMILIK,
        FAX_AREA_PEMILIK, FAX_PEMILIK, EMAIL_PEMILIK, KODE_KEPEMILIKAN, KODE_METODE_PEMBAYARAN,  KODE_BANK_PEMBAYARAN, NAMA_REKENING_PEMBAYARAN,
        NO_REKENING_PEMBAYARAN, TGL_REKAM,PETUGAS_REKAM,TELEPON_AREA_PIC,TELEPON_PIC,TELEPON_EXT_PIC)
        values('{$ls_kode_new_faskes}','{$ls_kode_tipe}','{$ls_kode_kantor}','{$ls_kode_pembina}'
            ,'{$ls_nama_faskes}','{$ls_alamat}','{$ls_rt}','{$ls_rw}','{$ls_kode_kelurahan}','{$ls_kode_kecamatan}','{$ls_kode_kabupaten}',
            '{$ls_kode_pos}','{$ls_nama_pic}','{$ls_handphone_pic}','ST1','{$ls_kode_jenis}','{$ls_kode_jenis_detil}','{$ls_no_ijin_praktek}',
            '{$ls_npwp}','{$ls_max_tertanggung}','{$ls_umum}','{$ls_gigi}','{$ls_salin}','{$ls_regweb}','{$ls_keterangan}','{$ls_nama_pemilik}'
            ,'{$ls_alamat_pemilik}','{$ls_rt_pemilik}','{$ls_rw_pemilik}','{$ls_kode_kelurahan_pemilik}','{$ls_kode_kecamatan_pemilik}'
            ,'{$ls_kode_kabupaten_pemilik}','{$ls_kode_pos_pemilik}','{$ls_telepon_area_pemilik}','{$ls_telepon_pemilik}','{$ls_telepon_ext_pemilik}'
            ,'{$ls_fax_area_pemilik}','{$ls_fax_pemilik}','{$ls_email_pemilik}','{$ls_kode_kepemilikan}','{$ls_kode_metode_pembayaran}'
            ,'{$ls_bankcode}','{$ls_namarek}','{$ls_norek}',sysdate,'{$_SESSION["USER"]}','{$ls_telepon_area_pic}','{$ls_telepon_pic}','{$ls_telepon_ext_pic}')";
    $DB->parse($sql); 
    if(!$DB->execute()){
        echo '{"ret":-1,"msg":"Gagal penyimpanan data, ulangi lagi proses save!"}';
        //echo $sql;
    } else echo '{"ret":0,"msg":"'.$ls_kode_new_faskes.'"}';
}else if ($action=='Edit')
{
    $sql = "update {$schema}.tc_faskes set KODE_TIPE='{$ls_kode_tipe}', KODE_KANTOR='{$ls_kode_kantor}', KODE_PEMBINA='{$ls_kode_pembina}', 
    NAMA_FASKES='{$ls_nama_faskes}', ALAMAT='{$ls_alamat}', RT='{$ls_rt}', RW='{$ls_rw}',KODE_KELURAHAN='{$ls_kode_kelurahan}',
    KODE_KECAMATAN='{$ls_kode_kecamatan}', KODE_KABUPATEN='{$ls_kode_kabupaten}', KODE_POS='{$ls_kode_pos}', NAMA_PIC='{$ls_nama_pic}', 
    HANDPHONE_PIC='{$ls_handphone_pic}',  KODE_JENIS='{$ls_kode_jenis}', KODE_JENIS_DETIL='{$ls_kode_jenis_detil}', NO_IJIN_PRAKTEK='{$ls_no_ijin_praktek}', NPWP='{$ls_npwp}',
    MAX_TERTANGGUNG='{$ls_max_tertanggung}', FLAG_UMUM='{$ls_umum}',FLAG_GIGI='{$ls_gigi}', FLAG_SALIN='{$ls_salin}', FLAG_REG_WEBSITE='{$ls_regweb}', 
    KETERANGAN='{$ls_keterangan}',  NAMA_PEMILIK='{$ls_nama_pemilik}',  ALAMAT_PEMILIK='{$ls_alamat_pemilik}',  RT_PEMILIK='{$ls_rt_pemilik}',  RW_PEMILIK='{$ls_rw_pemilik}',
    KODE_KELURAHAN_PEMILIK='{$ls_kode_kelurahan_pemilik}', KODE_KECAMATAN_PEMILIK='{$ls_kode_kecamatan_pemilik}', KODE_KABUPATEN_PEMILIK='{$ls_kode_kabupaten_pemilik}',  
    KODE_POS_PEMILIK='{$ls_kode_pos_pemilik}', TELEPON_AREA_PEMILIK='{$ls_telepon_area_pemilik}', TELEPON_PEMILIK='{$ls_telepon_pemilik}', TELEPON_EXT_PEMILIK='{$ls_telepon_ext_pemilik}',
    FAX_AREA_PEMILIK='{$ls_fax_area_pemilik}', FAX_PEMILIK='{$ls_fax_pemilik}', EMAIL_PEMILIK='{$ls_email_pemilik}', KODE_KEPEMILIKAN='{$ls_kode_kepemilikan}', 
    KODE_METODE_PEMBAYARAN='{$ls_kode_metode_pembayaran}',  KODE_BANK_PEMBAYARAN='{$ls_bankcode}', NAMA_REKENING_PEMBAYARAN='{$ls_namarek}',
    NO_REKENING_PEMBAYARAN='{$ls_norek}', TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}',
    TELEPON_AREA_PIC='{$ls_telepon_area_pic}',TELEPON_PIC='{$ls_telepon_pic}',TELEPON_EXT_PIC='{$ls_telepon_ext_pic}'
    where KODE_FASKES='{$ls_kode_faskes}'";
    $DB->parse($sql); 
    if(!$DB->execute()){   
        echo '{"ret":-1,"msg":"Gagal penyimpanan data, ulangi lagi proses save!"}';
        //echo $sql;
    } else echo '{"ret":0,"msg":"'.$ls_kode_new_faskes.'"}';
        //echo $sql;
    
}else if ($action=='submit')
{
    $ls_kode_faskes     = strtoupper($_POST["kode_faskes"]);
    $sql = "update {$schema}.tc_faskes set KODE_STATUS='ST2', TGL_SUBMIT=sysdate,PETUGAS_SUBMIT='{$_SESSION["USER"]}'
    where KODE_FASKES='{$ls_kode_faskes}' AND KODE_STATUS='ST1'";
    $DB->parse($sql); 
    if(!$DB->execute()){   
        echo "Gagal pendaftaran data Faskes/BLK, ulangi lagi proses save!";
    }else
    {
        echo "Sukses";
    }
}else if ($action=='saveIKS')
{
    $ls_kode_faskes     = strtoupper($_POST["kode_faskes"]); 
    $ls_kode_iks        = strtoupper($_POST["kode_iks"]); 
    $ls_kode_iks        = trim($ls_kode_iks=='')?'NONE':$ls_kode_iks;
    $ls_no_iks          = strtoupper($_POST["no_iks"]);
    $ls_tgl_awal        = strtoupper($_POST["tgl_awal"]);
    $ls_tgl_akhir       = strtoupper($_POST["tgl_akhir"]);
    $ls_tipe            = ''; 
    $ls_kode_status_iks = (isset($_POST["kode_status"])) ? $_POST["kode_status"]:'1';
    $ls_kode_status_iks = ($ls_kode_status_iks=='')?'1':$ls_kode_status_iks;                                  
    
    $sql = "begin {$schema}.p_tc_faskes.X_IKS_SAVE('{$ls_kode_faskes}','{$ls_kode_iks}','{$ls_no_iks}',to_date('{$ls_tgl_awal}','dd/mm/yyyy'),to_date('{$ls_tgl_akhir}','dd/mm/yyyy'),'{$_SESSION["USER"]}','{$ls_kode_status_iks}',:P_OUT);end;";
    
    $proc = $DB->parse($sql); 
    oci_bind_by_name($proc,":P_OUT",$p_query,10);
   // echo $sql;
    if(!$DB->execute()){   
        echo "Gagal pendaftaran data IKS, ulangi lagi proses save!";
    }
}else if ($action=='deleteIKS')
{
    $ls_kode_faskes     = strtoupper($_POST["kode_faskes"]); 
    $ls_kode_iks        = strtoupper($_POST["kode_iks"]); 
    $ls_kode_iks        = trim($ls_kode_iks=='')?'NONE':$ls_kode_iks;
    $ls_no_iks          = strtoupper($_POST["no_iks"]);
    $ls_tgl_awal        = strtoupper($_POST["tgl_awal"]);
    $ls_tgl_akhir       = strtoupper($_POST["tgl_akhir"]);
    $ls_tipe            = ''; 
    $ls_kode_status_iks = (isset($_POST["kode_status"])) ? $_POST["kode_status"]:'1';
    $ls_kode_status_iks = ($ls_kode_status_iks=='')?'1':$ls_kode_status_iks;                                  
    
    $sql = "delete from {$schema}.tc_iks where KODE_FASKES='{$ls_kode_faskes}' and KODE_IKS='{$ls_kode_iks}'";
    
    $proc = $DB->parse($sql); 
   // echo $sql;
    if(!$DB->execute()){   
        echo "Gagal pendaftaran data IKS, ulangi lagi proses save!";
    }
}else if ($action=='submitIKS')
{
    $ls_kode_faskes     = strtoupper($_POST["kode_faskes"]); 
    $ls_kode_iks     = strtoupper($_POST["kode_iks"]); 
    $ls_no_iks          = strtoupper($_POST["no_iks"]);
    $ls_tgl_awal        = strtoupper($_POST["tgl_awal"]);
    $ls_tgl_akhir       = strtoupper($_POST["tgl_akhir"]);
    
    $sql = "begin {$schema}.X_IKS_SUBMIT('{$ls_kode_faskes}','{$ls_kode_iks}','{$ls_no_iks}',to_date('{$ls_tgl_awal}','dd/mm/yyyy'),to_date('{$ls_tgl_akhir}','dd/mm/yyyy'),'{$_SESSION["USER"]}',:P_OUT);end;";
    $proc = $DB->parse($sql); //echo $sql; 
    oci_bind_by_name($proc,":P_OUT",$p_query,10);
    //echo $sql;  
    if(!$DB->execute()){   
        echo "Gagal submit data IKS, ulangi lagi proses save!";
    }
}else if ($action=='adendumIKS')
{
    $ls_kode_faskes     = strtoupper($_POST["kode_faskes"]); 
    $ls_kode_iks     = strtoupper($_POST["kode_iks"]); 
    $ls_tgl_awal        = strtoupper($_POST["tgl_awal"]);
    $ls_tgl_akhir       = strtoupper($_POST["tgl_akhir"]);
    $sql = "begin {$schema}.p_tc_faskes.X_IKS_ADENDUM('{$ls_kode_faskes}','{$ls_kode_iks}',to_date('{$ls_tgl_awal}','dd/mm/yyyy'),to_date('{$ls_tgl_akhir}','dd/mm/yyyy'),'{$_SESSION["USER"]}',:P_OUT);end;";
    $proc = $DB->parse($sql);  echo $sql;
    oci_bind_by_name($proc,":P_OUT",$p_query,10);
    
    if(!$DB->execute()){   
        echo "Gagal pendaftaran adendum IKS, ulangi lagi proses save!";
    }
}          
         

?>