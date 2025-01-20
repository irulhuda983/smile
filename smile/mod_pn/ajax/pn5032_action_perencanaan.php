<?PHP
session_start();
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE                                   = $_POST['formregact'];
$noform                                 = $_POST['noform'];

$ls_kode_rtw 					        = $_POST["kode_rtw"];
$ls_no_rekam					        = $_POST["p_no_rekam_medis"];
$ls_tgl1    					        = $_POST["p_tgl1"];
$ls_pernyataan_tk				        = $_POST["p_pernyataan_tk"];
$ls_diagnosa					        = strtoupper($_POST["p_diagnosa"]);
$ls_nm_dokter					        = strtoupper($_POST["p_nm_dokter"]);
$ls_tujuan					            = strtoupper($_POST["p_tujuan"]);
$ls_keputusan					        = strtoupper($_POST["p_keputusan"]);
$ls_keterangan				            = strtoupper($_POST["p_keterangan"]);
$kode_del = $_POST['f'];
$key1 = $_POST['key1'];
$key2 = $_POST['key2'];
//VIEW -------------------------------------------------------------------------
if ($TYPE=="View" && $ls_kode_rtw_agenda != '')
{
  //query data --------------------------------------------------------		
}
else if ($TYPE=="New" || $TYPE=="Edit")
{		

    $sql = 	"select count(kode_RTW_KLAIM) as JML from sijstk.pn_RTW_perencanaan where kode_rtw_klaim='{$ls_kode_rtw}'";
    $DB->parse($sql);
    $DB->execute();
    $ls_ada = 0;
    if($row = $DB->nextrow())
        $ls_ada=$row['JML'];
    
    if($ls_ada<=0)
    {
    $sql = 	"insert into sijstk.pn_rtw_perencanaan(
            KODE_RTW_KLAIM,
            KODE_KLAIM,
            KODE_RTW_PERENCANAAN,
            NO_REKAM_MEDIS,
            TGL_PEMERIKSAAN1,
            STATUS_PERNYATAAN_TK,
            DIAGNOSA,
            NAMA_MEDIS,
            TUJUAN,
            STATUS_KEPUTUSAN,
            KETERANGAN,
            TGL_REKAM,
            PETUGAS_REKAM)
        values(
            '{$ls_kode_rtw}',
            (select KODE_KLAIM from sijstk.PN_RTW_KLAIM where kode_RTW_KLAIM='{$ls_kode_rtw}'),
            'PER{$ls_kode_rtw}',
            '{$ls_no_rekam}',
            to_date('{$ls_tgl1}','dd/mm/yyyy'),
            '{$ls_pernyataan_tk}',
            '{$ls_diagnosa}',
            '{$ls_nm_dokter}',
            '{$ls_tujuan}',
            '{$ls_keputusan}',
            '{$ls_keterangan}',
            sysdate,
            '{$_SESSION['USER']}')";
    }else{
        $sql = 	"update sijstk.pn_rtw_perencanaan set            
            NO_REKAM_MEDIS='{$ls_no_rekam}',
            TGL_PEMERIKSAAN1=to_date('{$ls_tgl1}','dd/mm/yyyy'),
            STATUS_PERNYATAAN_TK='{$ls_pernyataan_tk}',
            NAMA_MEDIS='{$ls_nm_dokter}',
            DIAGNOSA='{$ls_diagnosa}',
            TUJUAN='{$ls_tujuan}',
            STATUS_KEPUTUSAN='{$ls_keputusan}',
            KETERANGAN='{$ls_keterangan}',
            TGL_UBAH=sysdate,
            PETUGAS_UBAH='{$_SESSION['USER']}'
            where KODE_RTW_KLAIM='{$ls_kode_rtw}'";
    }         
    $DB->parse($sql);//echo $sql;
    if($DB->execute())
    {
         echo '{"ret":0,"msg":"Sukses, Data agenda berhasil disimpan, session dilanjutkan..","DATAID":"'.$ls_kode_rtw.'"}';		
    }
    else 
        echo '{"ret":-1,"msg":"Proses gagal, data gagal ditambahkan...!!!"}';
}
else if($TYPE=="NewInfo")
{
    $ls_kode_rtw    = $_POST['dataid'];
    $sql = 	"select max(NO_URUT) as JML from sijstk.pn_RTW_perencanaan_detil";
    $DB->parse($sql);
    $DB->execute();
    $jumlah = 0;
    if($row = $DB->nextrow())
        $jumlah=$row['JML']+1;
    else
        $jumlah=1;

     
    $ls_hambatan		= strtoupper($_POST["hambatan"]);
    $ls_strategi		= strtoupper($_POST["strategi"]);
    $ls_mulai   		= $_POST["tgl_mulai"];
    $ls_selesai 		= $_POST["tgl_selesai"];
    $ls_biaya 		    = $_POST["biaya"];
    //$ls_status		    = $_POST["status"];
    $ls_keterangan		= strtoupper($_POST["keterangan"]);
    //Cek kode klaim sudah di agenda rtwkan
    $sql = 	"insert into sijstk.pn_rtw_perencanaan_detil(
        KODE_RTW_PERENCANAAN,
        NO_URUT,
        HAMBATAN,
        STRATEGI_PENYELESAIAN,
        TGL_MULAI_REHAB,
        TGL_SELESAI_REHAB,
        ESTIMASI_BIAYA,
        KETERANGAN,
        TGL_REKAM,
        PETUGAS_REKAM)
    values(
        'PER{$ls_kode_rtw}',
        '{$jumlah}',
        '{$ls_hambatan}',
        '{$ls_strategi}',
        to_date('{$ls_mulai}','dd/mm/yyyy'),
        to_date('{$ls_selesai}','dd/mm/yyyy'),
        '{$ls_biaya}',
        '{$ls_keterangan}',
        sysdate,
        '{$_SESSION['USER']}')";
    $DB->parse($sql);//echo $sql;
    if(!$DB->execute()) 
        echo "Proses gagal, data gagal ditambahkan...!!!";
}else if($TYPE=="EditInfo")
{
    $ls_kode_rtw    = $_POST['dataid'];
    $ls_hambatan		= strtoupper($_POST["hambatan"]);
    $ls_strategi		= strtoupper($_POST["strategi"]);
    $ls_mulai   		= $_POST["tgl_mulai"];
    $ls_selesai 		= $_POST["tgl_selesai"];
    $ls_biaya 		    = $_POST["biaya"];
    $ls_no_urut 		    = $_POST["nourut"];
    //$ls_status		    = $_POST["status"];
    $ls_keterangan		= strtoupper($_POST["keterangan"]);
    //Cek kode klaim sudah di agenda rtwkan
    $sql = 	"update sijstk.pn_rtw_perencanaan_detil set
        HAMBATAN='{$ls_hambatan}',
        STRATEGI_PENYELESAIAN='{$ls_strategi}',
        TGL_MULAI_REHAB=to_date('{$ls_mulai}','dd/mm/yyyy'),
        TGL_SELESAI_REHAB=to_date('{$ls_selesai}','dd/mm/yyyy'),
        ESTIMASI_BIAYA='{$ls_biaya}',
        KETERANGAN='{$ls_keterangan}',
        TGL_UBAH=sysdate,
        PETUGAS_UBAH='{$_SESSION['USER']}'
    where KODE_RTW_PERENCANAAN='PER{$ls_kode_rtw}'   and NO_URUT='{$ls_no_urut}'";
    $DB->parse($sql);//echo $sql;
    if(!$DB->execute()) 
        echo "Proses gagal, data gagal ditambahkan...!!!";
}
else if($kode_del=="delPerencanaan")
{
    
    //Cek kode klaim sudah di agenda rtwkan
    $sql = 	"delete from sijstk.pn_rtw_perencanaan_detil
    where KODE_RTW_PERENCANAAN='PER{$_POST['key1']}'   and NO_URUT='{$_POST['key2']}'";
    $DB->parse($sql);//echo $sql;
    if(!$DB->execute()) 
        echo "Proses gagal, data gagal ditambahkan...!!!";
}

?>
