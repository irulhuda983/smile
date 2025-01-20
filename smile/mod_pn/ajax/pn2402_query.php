<?php 
session_start();
//Created @ 01/02/2008 to build ajax process enable
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$action= isset($_REQUEST['act'])?$_REQUEST['act']:'';
$key1= isset($_REQUEST['key1'])?$_REQUEST['key1']:'';
$key2= isset($_REQUEST['key2'])?$_REQUEST['key2']:'';
$key3= isset($_REQUEST['key3'])?$_REQUEST['key3']:'';

$action= isset($_GET['act'])?$_GET['act']:'';
$key1= isset($_GET['key1'])?$_GET['key1']:'';
$key2= isset($_GET['key2'])?$_GET['key2']:'';
$key3= isset($_GET['key3'])?$_GET['key3']:'';

$action= isset($_POST['act'])?$_POST['act']:$action;
$key1= isset($_POST['key1'])?$_POST['key1']:$key1;
$key2= isset($_POST['key2'])?$_POST['key2']:$key2;
$key3= isset($_POST['key3'])?$_POST['key3']:$key3;
$schema="tc";
//Kantor -------------------------------------------------------------------
    $ls_kode_kantor = isset($ls_kode_kantor) ? $ls_kode_kantor : $gs_kantor_aktif; 
    if($ls_kode_kantor=="")
    {
        $ls_kode_kantor =  $gs_kantor_aktif;
    }   
    //Sumber Data : sesuai kantor login ----------------------------------------
    $sql = "select kode_tipe from sijstk.ms_kantor where kode_kantor = '$ls_kode_kantor' ";
    $DB->parse($sql);
    $DB->execute();
    $row = $DB->nextrow();
    $ls_tipe_kantor = $row["KODE_TIPE"];
    
      if ($ls_tipe_kantor=="0")
    {
        $ls_kode_sumber_data = "1";
    }else if ($ls_tipe_kantor=="1")
    {
        $ls_kode_sumber_data = "2";
    }
    else if ($ls_tipe_kantor=="3" || $ls_tipe_kantor=="4" || $ls_tipe_kantor=="5")
    {
        $ls_kode_sumber_data = "3";    
    }
    
      if ($ls_kode_sumber_data !="")
    {
      $sql = "select nama_sumber_data from sijstk.kn_kode_sumber_data ".
                 "where kode_sumber_data = '$ls_kode_sumber_data' ";
      $DB->parse($sql);
      $DB->execute();
      $row = $DB->nextrow();
      $ls_nama_sumber_data = $row["NAMA_SUMBER_DATA"];
    }   
//print_r($_SESSION);
if($action=='search')
{                    
    $start = $_REQUEST['start']+1;
    $length = $_REQUEST['length'];
    $end = ($start-1) + $length;

    if($end==0)  $end=100;
    
    $data=array("draw"=> 1, "recordsTotal"=> 0, "recordsFiltered"=> 0, "records"=>array());
    $key1=($key1=="")?"2":$key1;     
    $sql='';
    $sql_utama = "SELECT rownum nourut, A.KODE_IKS,B.KODE_KANTOR,B.NAMA_FASKES,A.NO_IKS, TO_CHAR(TGL_AWAL_IKS,'DD/MM/YYYY') TGL_AWAL_IKS, TO_CHAR(TGL_AKHIR_IKS,'DD/MM/YYYY') TGL_AKHIR_IKS,
            case 
                when A.KODE_ADDENDUM<>'' then 'Dokumen Adendum'
                else 'Dokumen Baru'
            end TIPE_APPROVAL ,A.KODE_IKS || '_' || A.KODE_FASKES  KODE  ,
            TO_CHAR(A.TGL_SUBMIT,'DD/MM/YYYY') TGL_SUBMIT,A.PETUGAS_SUBMIT ,nvl(TO_CHAR(TGL_APPROVAL,'DD/MM/YYYY'),' ') TGL_APPROVAL,
            case
                WHEN A.KODE_STATUS_IKS='3' then 'DISETUJUI'  
                WHEN A.KODE_STATUS_IKS='4' then 'DITOLAK' 
                else 'SUBMIT'
            end STATUS_APPROVAL, 
            nvl(ALASAN_APPROVAL , ' ') ALASAN_APPROVAL,
            case
                WHEN A.KODE_STATUS_IKS1='3' then 'DISETUJUI'  
                WHEN A.KODE_STATUS_IKS1='4' then 'DITOLAK' 
                WHEN A.KODE_STATUS_IKS='3' then 'SUBMIT' 
                else ' '
            end STATUS_APPROVAL1, 
            nvl(ALASAN_APPROVAL1,' ') ALASAN_APPROVAL1
            FROM {$schema}.TC_IKS A,{$schema}.TC_FASKES   B
            WHERE A.KODE_FASKES=B.KODE_FASKES and 
            (
                '{$key1}' in('3','4')
                or
                b.kode_kantor in (select x.kode_kantor from sijstk.ms_kantor x
                                where x.kode_tipe not in ('0','1')                                                                            
                                start with x.kode_kantor = '{$_SESSION['gs_kantor_aktif']}' 
                                connect by prior x.kode_kantor = x.kode_kantor_induk)
            )
            and 
            (
                (
                    '{$_SESSION['regrole']}'='6' and '{$key1}'='2' and
                    (
                        (A.KODE_STATUS_IKS='2' AND nvl((select count(1) from sijstk.tc_iks_lampiran where kode_faskes=a.kode_faskes and  kode_iks=a.kode_iks),0)>0 )
                        or
                        (A.KODE_STATUS_IKS='3' AND A.KODE_STATUS_IKS1='4')
                    )
                )
                or
                ( '{$_SESSION['regrole']}'='6' and '{$key1}' in('3','4') and A.KODE_STATUS_IKS = '{$key1}' and a.petugas_approval='{$_SESSION['USER']}' )
                or
                ('{$_SESSION['regrole']}'='21' and '{$key1}'='2' and A.KODE_STATUS_IKS='3' and (A.KODE_STATUS_IKS1='2' or nvl(A.KODE_STATUS_IKS1,'x')='x'))  
                or              
                ('{$_SESSION['regrole']}'='21' and '{$key1}'in('3','4') and A.KODE_STATUS_IKS1 = '{$key1}'  and a.petugas_approval1='{$_SESSION['USER']}') 
            )
            order by TIPE_APPROVAL, B.NAMA_FASKES";    
    
                      //echo $sql_utama;
    if(isset($_POST['draw'])){
        $draw = $_POST['draw']; 
    }else{
        $draw = 1;       
    }
    $recordsTotal=0;
    $sql = "select count(*) JML from ({$sql_utama})";//echo $sql;
    $DB->parse($sql);
    if($DB->execute()) 
        if($row = $DB->nextrow())
            $recordsTotal=$row['JML'];
    $sql = "select A.* from
            ({$sql_utama}
            ) A
            where  NOURUT between {$start} and {$end} "; //echo $sql;
    $DB->parse($sql);
    if($DB->execute()){	
        while($row = $DB->nextrow())
        { 
            $data['records'][]=array('KODE'=>$row['KODE'],'KODE_IKS'=>$row['KODE_IKS'],'KODE_KANTOR'=>$row['KODE_KANTOR'],'NAMA_FASKES'=>$row['NAMA_FASKES'],'NO_IKS'=>$row['NO_IKS'],'TGL_AWAL_IKS'=>$row['TGL_AWAL_IKS'],
                            'TGL_AKHIR_IKS'=>$row['TGL_AKHIR_IKS'],'TIPE_APPROVAL'=>$row['TIPE_APPROVAL'],'TGL_SUBMIT'=>$row['TGL_SUBMIT']
                            ,'PETUGAS_SUBMIT'=>$row['PETUGAS_SUBMIT'],'TGL_APPROVAL'=>$row['TGL_APPROVAL'],"STATUS_APPROVAL"=>$row['STATUS_APPROVAL'],"ALASAN_APPROVAL"=>$row['ALASAN_APPROVAL'],"STATUS_APPROVAL1"=>$row['STATUS_APPROVAL1'],"ALASAN_APPROVAL1"=>$row['ALASAN_APPROVAL1']);
        }
        $data['draw']=$draw;
        $data['recordsTotal']=$recordsTotal;
        $data['recordsFiltered']=$recordsTotal;
        //$datarow=json_encode($data);
        //echo str_replace("},{","],[",str_replace("}]","]]",str_replace("[{","[[",$datarow)));
        echo json_encode($data);
    }
    else    echo 'Failed';//echo $sql;      
} else if($action=='searchIKS')
{  
     $data=array("draw"=> 1, "recordsTotal"=> 0, "recordsFiltered"=> 0, "records"=>array());
    $sql = "SELECT KODE_FASKES, KODE_IKS, KODE_TIPE, NO_IKS, TO_CHAR(TGL_AWAL_IKS,'DD/MM/YYYY') TGL_AWAL_IKS, TO_CHAR(TGL_AKHIR_IKS,'DD/MM/YYYY') TGL_AKHIR_IKS, TO_CHAR(TGL_APPROVAL,'DD/MM/YYYY') TGL_APPROVAL,  ALASAN_APPROVAL,
       KODE_STATUS_IKS,TO_CHAR(TGL_NA,'DD/MM/YYYY') TGL_NA,ALASAN_NA, KODE_ADDENDUM, TO_CHAR(TGL_REKAM,'DD/MM/YYYY') TGL_REKAM, PETUGAS_REKAM,
       CASE 
        WHEN KODE_STATUS_IKS='1' then 'BARU'
        WHEN KODE_STATUS_IKS='2' then 'MENUNGGU APPROVAL'
        WHEN KODE_STATUS_IKS='3' then 'DISETUJUI'
        WHEN KODE_STATUS_IKS='4' then 'DITOLAK'
        WHEN KODE_STATUS_IKS='5' then 'NON-AKTIF'
        WHEN KODE_STATUS_IKS='6' then 'DIBATALKAN'
       END NAMA_STATUS
       FROM {$schema}.TC_IKS A 
       WHERE KODE_FASKES='{$key1}' order by TGL_AKHIR_IKS"; 
    $DB->parse($sql); //echo $sql;     //echo "---{$gs_kantor_aktif}--";
    if($DB->execute()){   
        while($row = $DB->nextrow())
        { 
            $data['recordsTotal']++;
            $data['recordsFiltered']++;
            $data['records'][]=array('KODE_IKS'=>$row['KODE_IKS'],'KODE_FASKES'=>$row['KODE_FASKES'],'NO_IKS'=>$row['NO_IKS'],'TGL_AWAL_AKHIR'=>$row['TGL_AWAL_AKHIR'],
                            'TGL_AKHIR_IKS'=>$row['TGL_AKHIR_IKS'],'NAMA_STATUS'=>$row['NAMA_STATUS'],'TGL_APPROVAL'=>$row['TGL_APPROVAL'],'TGL_NONAKTIF'=>$row['TGL_NONAKTIF'],
                            'KODE_ADENDUM'=>$row['KODE_ADENDUM'],'TGL_REKAM'=>$row['TGL_REKAM'],'PETUGAS_REKAM'=>$row['PETUGAS_REKAM']);
        }
        //$datarow=json_encode($data);
        //echo str_replace("},{","],[",str_replace("}]","]]",str_replace("[{","[[",$datarow)));
        echo json_encode($data);
    }
    else    echo 'Failed';
}

?>