<?PHP
session_start();
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);


$TYPE               = $_POST['formregact'];

$ls_kode            = $_POST["kode_perusahaan"];
$ls_status            = $_POST["status_aktif"];
$ls_tgl             = $_POST["tgl_status"];   
$ls_no      = $_POST['nourut']; 

if ($TYPE=="SavePrs")
{    
    $jml=0;
    $sql =     "select count(*) JML from sijstk.pn_rtw_prs_pendukung where kode_perusahaan='{$ls_kode}'"; //echo $sql;
    $DB->parse($sql);
    if($DB->execute())
        if($row=$DB->nextrow())
            $jml=$row['JML'];
    $sql='';
    if($jml>0)
        if($ls_status=='Y')
            $sql="update sijstk.pn_rtw_prs_pendukung set status_aktif='Y',tgl_aktif=to_date('{$ls_tgl}','dd/mm/yyyy'),tgl_ubah=sysdate,petugas_ubah='{$_SESSION['USER']}'
                    where kode_perusahaan='{$ls_kode}'";
        else
            $sql="update sijstk.pn_rtw_prs_pendukung set status_aktif='T',tgl_nonaktif=to_date('{$ls_tgl}','dd/mm/yyyy'),tgl_ubah=sysdate,petugas_ubah='{$_SESSION['USER']}'
                    where kode_perusahaan='{$ls_kode}'";
    else
        if($ls_status=='Y')
            $sql="insert into sijstk.pn_rtw_prs_pendukung(kode_perusahaan,status_aktif,tgl_aktif,tgl_rekam,petugas_rekam) values
                ('{$ls_kode}','Y',to_date('{$ls_tgl}','dd/mm/yyyy'),sysdate,'{$_SESSION['USER']}')";
        else
            $sql="insert into sijstk.pn_rtw_prs_pendukung(kode_perusahaan,status_aktif,tgl_nonaktif,tgl_rekam,petugas_rekam) values
                ('{$ls_kode}','T',to_date('{$ls_tgl}','dd/mm/yyyy'),sysdate,'{$_SESSION['USER']}')";
    //echo $sql;
    $DB->parse($sql);
    if($DB->execute())
        echo '{"ret":0,"msg":"Sukses menyimpan data!"}';    
    else
        echo '{"ret":1,"msg":"Gagal menyimpan data!"}';
}
else if ($TYPE=="uploaddoc")
{ 
    if($_FILES['lamp_file']['tmp_name']!='')
    {
        $DOC_FILE = file_get_contents($_FILES['lamp_file']['tmp_name']);
        $ls_kode = $_POST['lamp_kode_perusahaan'];
        $ls_ket = $_POST['lamp_ket'];
        if ($DOC_FILE) { 
            $sql =     "select max(no_urut) as JML from sijstk.pn_rtw_prs_pendukung_lamp where kode_perusahaan='{$ls_kode}'";
            $DB->parse($sql);
            $DB->execute();
            $ls_ada = 0;
            if($row = $DB->nextrow())
                $ls_ada=$row['JML']+1;
            else
                $ls_ada=1;

            $sql = "INSERT INTO sijstk.pn_rtw_prs_pendukung_lamp (
                    kode_perusahaan,
                    NO_URUT,
                    NAMA_FILE,
                        DOC_FILE,
                        KETERANGAN,
                    TGL_REKAM,PETUGAS_REKAM)
                    VALUES(
                    '{$ls_kode}',
                    '{$ls_ada}',
                    '{$_FILES['lamp_file']['name']}',
                    EMPTY_BLOB(),
                    '{$ls_ket}',sysdate,
                    '{$_SESSION['USER']}'
                    ) RETURNING DOC_FILE  INTO :LOB_A"; //echo $sql;
            if(!$DB->insertBlob($sql, 'Insert Lampiran RTW Perusahaan Pendukung', ':LOB_A', $DOC_FILE))
                echo "Gagal upload data dokumen";
        }
    }
}
else if ($TYPE=="deletelampiran")
{    
    $sql="delete from sijstk.pn_rtw_prs_pendukung_lamp where kode_perusahaan='{$ls_kode}' and no_urut='{$ls_no}'";
    //echo $sql;
    $DB->parse($sql);
    if($DB->execute())
        echo '{"ret":0,"msg":"Sukses menghapus data!"}';    
    else
        echo '{"ret":1,"msg":"Gagal menghapus data!"}';
}

?>
