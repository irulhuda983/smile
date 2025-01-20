<?php
//Created @ 01/02/2008 to build ajax process enable
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$action= isset($_GET['f'])?$_GET['f']:'';
$key1= isset($_GET['key1'])?$_GET['key1']:'';
$key2= isset($_GET['key2'])?$_GET['key2']:'';

$action= isset($_POST['f'])?$_POST['f']:$action;
$key1= isset($_POST['key1'])?$_POST['key1']:$key1;
$key2= isset($_POST['key2'])?$_POST['key2']:$key2;
$schema='sijstk';

if($action=='getMSLookup')
{  
    $sql = "select KODE,KETERANGAN from  {$schema}.ms_lookup where tipe='{$key1}' and (aktif='Y'  or kode='{$key2}') order by seq";
    $DB->parse($sql);
    $DB->execute();//echo $sql;
    echo "<option value=\"\"></option>";
    while($row = $DB->nextrow())
        echo "<option  ". ($row["KODE"]==$key2?" selected ":"") . " value=\"{$row["KODE"]}\">{$row["KETERANGAN"]}</option>";

}

if($action=='getDiagnosa')
{  
    $sql = "select KODE_DIAGNOSA,NAMA_DIAGNOSA from {$schema}.pn_kode_diagnosa where KODE_GROUP_ICD='{$key1}' order by KODE_DIAGNOSA";
    $DB->parse($sql);
    $DB->execute();//echo $sql;
    echo "<option value=\"\"></option>";
    while($row = $DB->nextrow())
        if($row['KODE_DIAGNOSA']==$key2)
            echo "<option  value=\"{$row["KODE_DIAGNOSA"]}\" selected>{$row["NAMA_DIAGNOSA"]}</option>";
        else    
            echo "<option  value=\"{$row["KODE_DIAGNOSA"]}\">{$row["NAMA_DIAGNOSA"]}</option>";

}
if($action=='getDiagnosaDetil')
{  
    $sql = "select KODE_DIAGNOSA_DETIL,NAMA_DIAGNOSA_DETIL from {$schema}.pn_kode_diagnosa_detil where KODE_DIAGNOSA='{$key1}' order by KODE_DIAGNOSA_DETIL";
        $DB->parse($sql);
        $DB->execute();//echo $sql;
        echo "<option value=\"\"></option>";
        while($row = $DB->nextrow())
            if($row['KODE_DIAGNOSA_DETIL']==$key2)
                echo "<option  value=\"{$row["KODE_DIAGNOSA_DETIL"]}\" selected>{$row["NAMA_DIAGNOSA_DETIL"]}</option>";
            else
            echo "<option  value=\"{$row["KODE_DIAGNOSA_DETIL"]}\">{$row["NAMA_DIAGNOSA_DETIL"]}</option>";
}
if($action=='getInformasiDasarDiagnosa')
{  
    $sql = "select A.*,B.NAMA_DIAGNOSA_DETIL,C.NAMA_DIAGNOSA,D.NAMA_GROUP_ICD ,A.NO_URUT
    from {$schema}.pn_rtw_infodasar_diagnosa A left outer join
    {$schema}.PN_KODE_DIAGNOSA_DETIL B on A.KODE_DIAGNOSA_DETIL=B.KODE_DIAGNOSA_DETIL left outer join
    {$schema}.PN_KODE_DIAGNOSA C on B.KODE_DIAGNOSA=C.KODE_DIAGNOSA left outer join
    {$schema}.PN_KODE_GROUP_ICD D on C.KODE_GROUP_ICD=D.KODE_GROUP_ICD
    where A.KODE_RTW_INFODASAR='ID{$key1}' order by a.no_urut";
    $DB->parse($sql);
    $DB->execute();//echo $sql; 
    while($row = $DB->nextrow())
    {
        if($key2=='ro')
        {
            echo "<tr><td valign=\"top\" style=\"border-bottom:1px solid #e0e0e0;\">{$row['NAMA_TENAGA_MEDIS']}</td><td valign=\"top\" style=\"border-bottom:1px solid #e0e0e0;\">{$row['NAMA_GROUP_ICD']}</td><td valign=\"top\" style=\"border-bottom:1px solid #e0e0e0;\">{$row['NAMA_DIAGNOSA']}</td><td valign=\"top\" style=\"border-bottom:1px solid #e0e0e0;\">{$row['NAMA_DIAGNOSA_DETIL']}</td><td valign=\"top\" style=\"border-bottom:1px solid #e0e0e0;\">{$row['KETERANGAN']}&nbsp;</td></tr>\n";
        }
        else
        {
            echo "<tr><td valign=\"top\"style=\"border-bottom:1px solid #e0e0e0;\"><div style=\"width:110px;text-align:center;padding:0;\"><a href=\"javascript:NewWindow('pn5032_form_info_dasar_diagnosa.php?dataid={$key1}&task=EditInfo&nourut={$row['NO_URUT']}','_per_id',800,300,1);\"><img src=\"../../images/edit.gif\" /> Edit</a> &nbsp; | &nbsp; <a href=\"javascript:deleteInfoDiagnosa('{$key1}','{$row['NO_URUT']}');\"><img src=\"../../images/minus.png\" /> Hapus</a></div></td>";
            echo "<td valign=\"top\" style=\"border-bottom:1px solid #e0e0e0;\">{$row['NAMA_TENAGA_MEDIS']}</td><td valign=\"top\" style=\"border-bottom:1px solid #e0e0e0;\">{$row['NAMA_GROUP_ICD']}</td><td valign=\"top\" style=\"border-bottom:1px solid #e0e0e0;\">{$row['NAMA_DIAGNOSA']}</td><td valign=\"top\" style=\"border-bottom:1px solid #e0e0e0;\">{$row['NAMA_DIAGNOSA_DETIL']}</td><td valign=\"top\" style=\"border-bottom:1px solid #e0e0e0;\">{$row['KETERANGAN']}&nbsp;</td></tr>\n";
        }
    }
}
///////////////////////////////////////////

if($action=='getJenis')
{  
    $sql = "select KODE_JENIS,NAMA_JENIS from {$schema}.tc_kode_jenis where (status_nonaktif='T') or KODE_JENIS='{$key1}'";
    $DB->parse($sql);
    $DB->execute();//echo $sql;
    echo "<option value=\"\"></option>";
    while($row = $DB->nextrow())
        echo "<option  ". ($row["KODE_JENIS"]==$key1?" selected ":"") . " value=\"{$row["KODE_JENIS"]}\">{$row["NAMA_JENIS"]}</option>";

}

if($action=='getSubJenis')
{  
    $sql = "select KODE_JENIS_DETIL,NAMA_JENIS_DETIL from {$schema}.tc_kode_jenis_detil where (status_nonaktif='T' and KODE_JENIS='{$key1}') or KODE_JENIS_DETIL='{$key2}'";
    $DB->parse($sql);
    $DB->execute();//echo $sql;
    echo "<option value=\"\"></option>";
    while($row = $DB->nextrow())
        echo "<option  ". ($row["KODE_JENIS_DETIL"]==$key2?" selected ":"") . " value=\"{$row["KODE_JENIS_DETIL"]}\">{$row["NAMA_JENIS_DETIL"]}</option>";

}
if($action=='getPerencanaanInfo')
{  
    $sql = "select KODE_RTW_PERENCANAAN,
    NO_URUT,
    HAMBATAN,
    STRATEGI_PENYELESAIAN,
    to_char(TGL_MULAI_REHAB,'DD/MM/YYYY') TGL_MULAI_REHAB,
    to_char(TGL_SELESAI_REHAB,'DD/MM/YYYY') TGL_SELESAI_REHAB,
    ESTIMASI_BIAYA,
    KETERANGAN
    from {$schema}.pn_rtw_perencanaan_detil where KODE_RTW_PERENCANAAN='PER{$key1}'";
    $DB->parse($sql);
    $DB->execute();//echo $sql;
    while($row = $DB->nextrow())
    {
        if($key2=='ro')
        {
            echo "<tr><td>{$row['STRATEGI_PENYELESAIAN']}</td><td>{$row['HAMBATAN']}</td><td>{$row['TGL_MULAI_REHAB']}</td><td>{$row['TGL_SELESAI_REHAB']}</td><td>{$row['ESTIMASI_BIAYA']}</td><td>{$row['KETERANGAN']}</td></tr>\n";
        }else
        {
            echo "<tr><td valign=\"top\"style=\"border-bottom:1px solid #e0e0e0;\"><div style=\"width:110px;text-align:center;padding:0;\"><a href=\"javascript:NewWindow('pn5032_form_perencanaan_info.php?dataid={$key1}&task=EditInfo&parenttask={$task}&nourut={$row['NO_URUT']}','_per_info',800,300,1);\"><img src=\"../../images/edit.gif\" /> Edit</a> &nbsp; | &nbsp; <a href=\"javascript:deleteInfoPerencanaan('{$key1}','{$row['NO_URUT']}');\"><img src=\"../../images/minus.png\" /> Hapus</a></div></td>";
            echo "<td>{$row['STRATEGI_PENYELESAIAN']}</td><td>{$row['HAMBATAN']}</td><td>{$row['TGL_MULAI_REHAB']}</td><td>{$row['TGL_SELESAI_REHAB']}</td><td>{$row['ESTIMASI_BIAYA']}</td><td>{$row['KETERANGAN']}</td></tr>\n";
        }
    }
}
if($action=='getEvaluasi')
{  
    $sql = "select KODE_KUSIONER,NAMA_KUSIONER from {$schema}.PN_RTW_kode_evaluasi_detil where (status_nonaktif='T' and KODE_TIPE_EVALUASI='{$key1}') or KODE_KUSIONER='{$key2}' order by no_urut";
    $DB->parse($sql);
    $DB->execute();//echo $sql;
    echo "<option value=\"\"></option>";
    while($row = $DB->nextrow())
        echo "<option  ". ($row["KODE_KUSIONER"]==$key2?" selected ":"") . " value=\"{$row["KODE_KUSIONER"]}\">{$row["NAMA_KUSIONER"]}</option>";

}
if($action=='getLampiran')
{  
    $sql = "select KODE_RTW_KLAIM,A.NO_URUT,b.keterangan JENIS_DOK,NAMA_FILE,DOC_FILE,TO_CHAR(TGL_REKAM,'DD/MM/YYYY') TGL_REKAM, PETUGAS_REKAM ,LENGTH(doc_file) UKURAN
    FROM sijstk.PN_RTW_LAMPIRAN A LEFT OUTER JOIN
    (select KODE,KETERANGAN from sijstk.ms_lookup where tipe='RTWLAMP') B ON A.KODE_JENIS_FILE =B.KODE  
    where KODE_RTW_KLAIM='{$key1}'
    order by kode_jenis_file,no_urut";
    $DB->parse($sql);
    $DB->execute();//echo $sql;
    while($row = $DB->nextrow())
    {
        if($key2=='ro')
        {
            echo "<tr><td><div style=\"width:110px;text-align:center;padding:0;\"><a href=\"../ajax/pn5032_download_lampiran.php?dataid={$row['KODE_RTW_KLAIM']}&no={$row['NO_URUT']}\" target=\"_doc_lampiran\"><img src=\"../../images/zoom.png\" />View</a></div></td>";
            echo "<td>{$row['NAMA_FILE']}</td><td>{$row['JENIS_DOK']}</td><td align=\"right\">{$row['UKURAN']}</td><td>{$row['TGL_REKAM']}</td><td>{$row['PETUGAS_REKAM']}</td></tr>\n";
        }
        else
        {
            echo "<tr><td><div style=\"width:110px;text-align:center;padding:0;\"><a href=\"../ajax/pn5032_download_lampiran.php?dataid={$row['KODE_RTW_KLAIM']}&no={$row['NO_URUT']}\" target=\"_doc_lampiran\"><img src=\"../../images/zoom.png\" />View</a> &nbsp; | &nbsp; <a href=\"javascript:deleteLampiran('{$row['KODE_RTW_KLAIM']}','{$row['NO_URUT']}');\"><img src=\"../../images/minus.png\" />Hapus</a></div></td>";
            echo "<td>{$row['NAMA_FILE']}</td><td>{$row['JENIS_DOK']}</td><td align=\"right\">{$row['UKURAN']}</td><td>{$row['TGL_REKAM']}</td><td>{$row['PETUGAS_REKAM']}</td></tr>\n";
        }
    }
}
if($action=='getPenilaianNilai')
{  
    $sql = "select A.*,B.NILAI_MAKSIMAL from
    (
    select  kode_template_penilaian,count(A.kode_penilaian) JML,sum(A.nilai) NILAI,sum(A.nilai)/count(A.kode_penilaian) PROSEN from pn_rtw_penilaian_detil A 
     where kode_penilaian='{$key1}' and kode_template_penilaian='{$key2}' group by A.kode_template_penilaian
    ) A left outer join pn_rtw_kode_penilaian B on A.KODE_TEMPLATE_PENILAIAN=B.KODE_TEMPLATE_PENILAIAN";
    $DB->parse($sql);
    $DB->execute();//echo $sql;
    $arr_nilai=array("jml"=>"","nilai"=>"","prosen"=>"","maks"=>"");
    if($row = $DB->nextrow())
    {
        $arr_nilai=array("jml"=>$row['JML'],"nilai"=>$row['NILAI'],"prosen"=>number_format($row['PROSEN'],2)."%","maks"=>$row['NILAI_MAKSIMAL']);
    }
    echo json_encode($arr_nilai);
}
?>