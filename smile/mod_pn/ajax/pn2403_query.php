<?PHP
session_start();
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE	        = $_POST["TYPE"] . $_GET['TYPE'];
$SEARCHA        = isset($_POST['SEARCHA'])?strtoupper($_POST['SEARCHA']):''; //echo $SEARCHA;
$SEARCHB        = isset($_POST['SEARCHB'])?strtoupper($_POST['SEARCHB']):''; //echo $SEARCHA;
$SEARCHC        = isset($_POST['SEARCHC'])?strtoupper($_POST['SEARCHC']):''; //echo $SEARCHA;
$USER           = $_SESSION["USER"];
$KODE_KANTOR    = $_SESSION['KDKANTOR']; 

$action= isset($_GET['formregact'])?$_GET['formregact']:'';

$action= isset($_POST['formregact'])?$_POST['formregact']:$action;
$schema="sijstk";             
/*****get parameter***********/
if($action=='New' || $action=='Edit')
{
    $ls_kode_jenis     = strtoupper($_POST["kode_jenis"]);
    $ls_nama_jenis     = strtoupper($_POST["nama_jenis"]);
    $ls_status_nonaktif     = strtoupper($_POST["status_nonaktif"]);
    $ls_status_nonaktif_old     = strtoupper($_POST["status_nonaktif_old"]);
}

if($TYPE=='queryFaskes'){
    $search="where ( (A.KODE_STATUS='ST5' AND STATUS_REQUEST='B') or (A.KODE_STATUS='ST3' AND STATUS_REQUEST='N') )
                and
                (
                    (nvl(a.status_approve_request,'x')<>'Y' and '{$_SESSION['regrole']}'='6')
                    or
                    (nvl(a.status_approve_request,'x')='Y' and nvl(a.status_approve_request1,'x')<>'Y' and '{$_SESSION['regrole']}'='21')
                )
            and kode_kantor in (select x.kode_kantor from sijstk.ms_kantor x
                    where x.kode_tipe not in ('0','1')                                                                            
                    start with x.kode_kantor = '{$_SESSION['gs_kantor_aktif']}' 
                    connect by prior x.kode_kantor = x.kode_kantor_induk)
            ";
    if(trim($SEARCHB)!=""){
        if($SEARCHA!='FASKES')
            $search .= " and ALAMAT like '%{$SEARCHB}%'";
        else 
            $search .= " and NAMA_FASKES like '%{$SEARCHB}%'";
    } 
    $recordsTotal=0;
    $sql = "select count(*) JML from sijstk.TC_FASKES A  {$search} " ;//echo $sql;
    $DB->parse($sql);
    if($DB->execute()) 
        if($row = $DB->nextrow())
            $recordsTotal=$row['JML'];

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY KODE_FASKES ASC ";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY KODE_FASKES ASC ";
    }else if($order[0]['column']=='3'){
        $order = "ORDER BY NAMA_FASKES ASC ";
    }else if($order[0]['column']=='4'){
        $order = "ORDER BY ALAMAT ASC ";
    } else $order = "order by NAMA_FASKES ASC";
  
    $draw = 1;
    if(isset($_POST['draw'])){
        $draw = $_POST['draw']; 
    }else{
        $draw = 1;       
    }

    $start = $_POST['start']+1;
    $length = $_POST['length'];
    $end = ($start-1) + $length;

    if($end==0)  $end=100;
    $sql = "";
    $sql = "select rownum NO_URUT,A.* from
            (select rownum NO_URUT1 ,KODE_FASKES,NAMA_FASKES,ALAMAT,a.KODE_STATUS,KODE_TIPE,
                KODE_KANTOR,B.NAMA_STATUS,STATUS_REQUEST
                from sijstk.TC_FASKES A left outer join sijstk.tc_kode_status b on a.kode_status=b.kode_status
                {$search} 
                {$order}
            ) A
            where NO_URUT1 between {$start} and {$end} ";
            //echo $sql;
    
    $DB->parse($sql);
    if($DB->execute()){ 
        $i = 0;
        while($data = $DB->nextrow())
        {
        //$data['ACTION'] = "";
            $jsondata .= json_encode($data);
            $jsondata .= ',';
            $i++;
        }
        $jsondataStart = '{"draw":'.$draw.',"recordsTotal":'.$recordsTotal.',"recordsFiltered":'.$recordsTotal.',"data":[';
        $jsondata .= ']}';
        $jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
        echo $jsondata;
    } else {
      echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
    }

}else if($TYPE=='queryIKS'){
    $search="";
    if(trim($SEARCHA)!=""){
        $search=" where A.KODE_FASKES = '{$SEARCHA}'";
    } 
    $recordsTotal=0;
    $sql = "select count(*) JML  from sijstk.TC_IKS A {$search}"; //echo $sql;
    $DB->parse($sql);
    if($DB->execute()) 
        if($row = $DB->nextrow())
            $recordsTotal=$row['JML'];

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY KODE_FASKES ASC ";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY NO_IKS ASC ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY TGL_AWAL_IKS ASC ";
    }else if($order[0]['column']=='3'){
    } else $order = "order by NO_IKS ASC";
  
    $draw = 1;
    if(isset($_POST['draw'])){
        $draw = $_POST['draw']; 
    }else{
        $draw = 1;       
    }

    $start = $_POST['start']+1;
    $length = $_POST['length'];
    $end = ($start-1) + $length;

    if($end==0)  $end=100;
    $sql = "";
    $sql = "select * from
            (select rownum NO_URUT ,A.KODE_FASKES,A.KODE_IKS,A.NO_IKS,TO_CHAR(A.TGL_AWAL_IKS) TGL_AWAL_IKS,to_char(A.TGL_AKHIR_IKS) TGL_AKHIR_IKS,
            case
                WHEN A.KODE_STATUS_IKS='1' then 'BARU'
                WHEN A.KODE_STATUS_IKS='2' then 'MENUNGGU APPROVAL'
                WHEN A.KODE_STATUS_IKS='3' then 'DISETUJUI'
                WHEN A.KODE_STATUS_IKS='4' then 'DITOLAK'
                WHEN A.KODE_STATUS_IKS='5' then 'NON-AKTIF'
                WHEN A.KODE_STATUS_IKS='6' then 'DIBATALKAN'
            end STATUS_IKS,A.KODE_STATUS_IKS,
            A.STATUS_NA,nvl(A.ALASAN_NA,' ') ALASAN_NA,nvl(B.NO_IKS,' ') NO_ADDENDUM,
            case 
                when add_months(A.TGL_AKHIR_IKS,-3)<=to_date(sysdate) and A.TGL_AKHIR_IKS>to_date(SYSdate) and A.STATUS_NA='T' and A.KODE_STATUS_IKS='3' then 'Y' 
                else 'T'
            end CAN_ADENDUM
             from sijstk.TC_IKS A left outer join sijstk.TC_IKS B on (A.KODE_FASKES=B.KODE_FASKES and A.KODE_ADDENDUM=B.KODE_IKS)
              {$search} {$order}
            )
            where NO_URUT between {$start} and {$end} ";// echo $sql;
    
    $DB->parse($sql);
    if($DB->execute()){ 
        $i = 0;
        while($data = $DB->nextrow())
        {
        //$data['ACTION'] = "";
            $jsondata .= json_encode($data);
            $jsondata .= ',';
            $i++;
        }
        $jsondataStart = '{"draw":'.$draw.',"recordsTotal":'.$recordsTotal.',"recordsFiltered":'.$recordsTotal.',"data":[';
        $jsondata .= ']}';
        $jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
        echo $jsondata;
    } else {
      echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
    }
}else if($TYPE=='queryLamp'){
    $search="";
    if(trim($SEARCHA)!=""){
        $search=" where A.KODE_FASKES = '{$SEARCHA}'";
    } 
    if(trim($SEARCHC)!=""){
        if($SEARCHB!='FASKES')
            $search .= " and ALAMAT like '%{$SEARCHC}%'";
        else 
            $search .= " and NAMA_FASKES like '%{$SEARCHC}%'";
    } 
    $recordsTotal=0;
    $sql = "select count(*) JML  from sijstk.TC_IKS_LAMPIRAN A {$search}"; //echo $sql;
    $DB->parse($sql);
    if($DB->execute()) 
        if($row = $DB->nextrow())
            $recordsTotal=$row['JML'];

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY A.KODE_FASKES ASC ";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY A.NO_IKS ASC ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY A.NAMA_FILE ASC ";
    }else if($order[0]['column']=='3'){
        $order = "ORDER BY A.TGL_AWAL_IKS ASC ";
    }else if($order[0]['column']=='4'){
        $order = "ORDER BY A.TGL_AKHIR_IKS ASC ";
    } else $order = "order by A.NO_IKS ASC";
  
    $draw = 1;
    if(isset($_POST['draw'])){
        $draw = $_POST['draw']; 
    }else{
        $draw = 1;       
    }

    $start = $_POST['start']+1;
    $length = $_POST['length'];
    $end = ($start-1) + $length;

    if($end==0)  $end=100;
    $sql = "";
    $sql = "select * from
            (select rownum NO_URUT ,A.KODE_FASKES,A.KODE_IKS,A.NO_IKS,TO_CHAR(A.TGL_AWAL_IKS) TGL_AWAL_IKS,to_char(A.TGL_AKHIR_IKS) TGL_AKHIR_IKS,
            TO_CHAR(A.TGL_REKAM,'DD/MM/YYYY') TGL_REKAM,PETUGAS_REKAM,A.NAMA_FILE,KODE_LAMPIRAN
             from sijstk.TC_IKS_LAMPIRAN A 
              {$search} {$order}
            )
            where NO_URUT between {$start} and {$end} ";// echo $sql;
    
    $DB->parse($sql);
    if($DB->execute()){ 
        $i = 0;
        while($data = $DB->nextrow())
        {
        //$data['ACTION'] = "";
            $jsondata .= json_encode($data);
            $jsondata .= ',';
            $i++;
        }
        $jsondataStart = '{"draw":'.$draw.',"recordsTotal":'.$recordsTotal.',"recordsFiltered":'.$recordsTotal.',"data":[';
        $jsondata .= ']}';
        $jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
        echo $jsondata;
    } else {
      echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
    }
}
else{
    echo '{"ret":-1,"msg":"Tidak ada tipe yang dipilih"}';
}
?>