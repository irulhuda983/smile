<?PHP
session_start(); 
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE                                   = $_POST['formregact'];
$noform                                   = $_POST['noform'];

$ls_kode_rtw        					= $_POST["kode_rtw"];
$ls_kode_template       				= $_POST["kode_template"];
$ls_kode_no = substr($ls_kode_template,-2);
$ls_data_penilaian      = $_POST['data_penilaian'];
//print_r($_FILES);
//print_r($_FILES);  echo $_FILES['lamp_file']['name']['a'];
//VIEW -------------------------------------------------------------------------

if ($TYPE=="Assessment")
{
    
    $sql = 	"select count(*) JML from sijstk.pn_rtw_penilaian where kode_penilaian='NIL{$ls_kode_rtw}{$ls_kode_no}'";
    $DB->parse($sql);
    $DB->execute();
    $ls_ada=0;
    if($row = $DB->nextrow())
        $ls_ada=$row['JML'];
    if($ls_ada>0)
    {
        $sql = 	"delete from sijstk.pn_rtw_penilaian_detil_tmp where user_id='{$_SESSION['USER']}'";
        $DB->parse($sql);
        $DB->execute();
        $sql='';
        foreach($ls_data_penilaian as $xitem)
            $sql .= " into sijstk.pn_rtw_penilaian_detil_tmp(USER_ID,
            KODE_PENILAIAN,
            KODE_TEMPLATE_PENILAIAN,
            KODE_ASSESMENT,
            KODE_NILAI,
            KETERANGAN) values('{$_SESSION['USER']}','NIL{$ls_kode_rtw}{$ls_kode_no}','{$ls_kode_template}','{$xitem['kode_asessment']}','{$xitem['kode_nilai']}','{$xitem['keterangan']}')";
        $sql = "insert all {$sql} select * from dual";
        $DB->parse($sql); //echo $sql;
        if(!$DB->execute())
            echo "Update data asesment penilaian gagal!";
        else
        {
            $sql = "begin sijstk.p_pn_rtw.X_PENILAIAN_DETIL_SAVE('{$_SESSION["USER"]}',:P_OUT);end;";
            $proc = $DB->parse($sql); //echo $sql;
            oci_bind_by_name($proc,":P_OUT",$p_query,10);
            
            if(!$DB->execute()){   
                echo "Update data asesment penilaian gagal1!";
            }
        }
    }else 
    {
        echo "Lakukan penyimpanan data informasi penilaiannya terlebih dahulu!";
    }
    /*
    $sql = 	"select KODE from sijstk.ms_lookup where tipe='RTWLAMP' order by seq";
    $DB->parse($sql);
    $DB->execute();
    $ls_ada = 0;
    $ls_array_file=array();
    while($row = $DB->nextrow())
        $ls_array_file[]=$row['KODE'];
    foreach($ls_array_file as $xitem)
    {
        if($_FILES['lamp_file']['tmp_name'][$xitem]!='')
        {
            $DOC_FILE = file_get_contents($_FILES['lamp_file']['tmp_name'][$xitem]);
            $ls_ket = $_POST['lamp_ket_'.$xitem];
            if ($DOC_FILE) { 
                $sql = 	"select count(kode_RTW_KLAIM) as JML from sijstk.pn_RTW_lampiran where kode_rtw_klaim='{$ls_kode_rtw}'";
                $DB->parse($sql);
                $DB->execute();
                $ls_ada = 0;
                if($row = $DB->nextrow())
                    $ls_ada=$row['JML'];

                $sql = "INSERT INTO sijstk.PN_RTW_LAMPIRAN (
                        KODE_RTW_KLAIM,
                        KODE_KLAIM,
                        NO_URUT,
                        KODE_JENIS_FILE,
                        NAMA_FILE,
                            DOC_FILE,
                            KETERANGAN,
                        TGL_REKAM,PETUGAS_REKAM)
                        VALUES(
                        '{$ls_kode_rtw}',
                        (select KODE_KLAIM from sijstk.PN_RTW_KLAIM where kode_RTW_KLAIM='{$ls_kode_rtw}'),
                        '{$ls_ada}',
                        '{$xitem}',
                        '{$_FILES['lamp_file']['name'][$xitem]}',
                        EMPTY_BLOB(),
                        '{$ls_ket}',sysdate,
                        '{$_SESSION['USER']}'
                        ) RETURNING DOC_FILE  INTO :LOB_A"; //echo $sql;
                if(!$DB->insertBlob($sql, 'Insert Lampiran RTW', ':LOB_A', $DOC_FILE))
                            echo "Gagal upload data dokumen";
               
            }
        }
    }
    */
    
}
else if ($TYPE=="New" || $TYPE=="Edit")
{		
    //Cek kode klaim sudah di agenda rtwkan

    $sql = 	"select max(no_urut) as JML from sijstk.pn_RTW_kecacatan where kode_rtw_klaim='{$ls_kode_rtw}'";
    $DB->parse($sql);
    $DB->execute();
    $ls_ada = 0;
    if($row = $DB->nextrow())
        $ls_ada=$row['JML']+1;
    else
        $ls_ada=1;
    
    if($ls_ada<=0)
    {
    $sql = 	"insert into sijstk.pn_rtw_kecacatan(
            KODE_RTW_KLAIM,
            KODE_KLAIM,
            NO_URUT,
            KODE_STATUS_KECACATAN,
            STATUS_KECACATAN_LAINNYA,
            FLG_BAHAYA,
            FLG_TOLERANSI,
            FLG_KELAYAKAN,
            FLG_PEKERJAANSEMULA,
            FLG_EFEKTIVITAS,
            FLG_KONDISIMEDIS,
            FLG_RESIKO,
            FLG_FISIKMENTAL,
            FLG_LAINNYA,
            KETERANGAN_FLG_LAINNYA,
            REF_TK,
            REF_PERUSAHAAN,
            KETERANGAN,
            TGL_REKAM,
            PETUGAS_REKAM)
        values(
            '{$ls_kode_rtw}',
            (select KODE_KLAIM from sijstk.PN_RTW_KLAIM where kode_RTW_KLAIM='{$ls_kode_rtw}'),
            '{$ls_ada}',
            '{$ls_impairement}',
            '{$ls_impairement_lain}',
            '{$ls_endanger}',
            '{$ls_toleransi}',
            '{$ls_kelaikan}',
            '{$ls_startingwork}',
            '{$ls_startingwork_drop}',
            '{$ls_work_medic_problem}',
            '{$ls_work_comunity}',
            '{$ls_nowork_temp}',
            '{$ls_work_other}',
            '{$ls_work_other_i}',
            '{$ls_ref_kerja_i}',
            '{$ls_ref_prs_i}',
            '{$ls_keterangan}',
            sysdate,
            '{$_SESSION['USER']}')";
    }else{
        $sql = 	"update sijstk.pn_rtw_kecacatan set            
            KODE_STATUS_KECACATAN='{$ls_impairement}',
            STATUS_KECACATAN_LAINNYA='{$ls_impairement_lain}',
            FLG_BAHAYA='{$ls_endanger}',
            FLG_TOLERANSI='{$ls_toleransi}',
            FLG_KELAYAKAN='{$ls_kelaikan}',
            FLG_PEKERJAANSEMULA='{$ls_startingwork}',
            FLG_EFEKTIVITAS='{$ls_startingwork_drop}',
            FLG_KONDISIMEDIS='{$ls_work_medic_problem}',
            FLG_RESIKO='{$ls_work_comunity}',
            FLG_FISIKMENTAL='{$ls_nowork_temp}',
            FLG_LAINNYA='{$ls_work_other}',
            KETERANGAN_FLG_LAINNYA='{$ls_work_other_i}',
            REF_TK='{$ls_ref_kerja_i}',
            REF_PERUSAHAAN='{$ls_ref_prs_i}',
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


?>
