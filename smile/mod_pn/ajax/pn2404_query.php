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
if($TYPE=='queryIKS'){
    $kdkantor=$_SESSION['gs_kantor_aktif'];
    if(trim($SEARCHA)!=""){
       $kdkantor=trim($SEARCHA);
    } 
    $sql_utama="select rownum nourut, a.KODE_FASKES,NO_IKS,b.KODE_KANTOR,NAMA_FASKES, to_char(tgl_awal_iks,'DD/MM/YYYY') tgl_awal_iks, to_char(tgl_akhir_iks,'DD/MM/YYYY') tgl_akhir_iks,
to_char(a.TGL_REKAM,'DD/MM/YYYY') tgl_rekam,a.petugas_rekam,
MONTHS_BETWEEN (tgl_akhir_iks,SYSDATE)  due_date
from sijstk.tc_iks a,sijstk.tc_faskes b
where a.kode_faskes=b.kode_faskes 
and b.kode_kantor in (select x.kode_kantor from sijstk.ms_kantor x
                                where x.kode_tipe not in ('0','1')                                                                            
                                start with x.kode_kantor =  '{$kdkantor}'
                                connect by prior x.kode_kantor = x.kode_kantor_induk)
and b.kode_status<>'ST6'
and kode_status_iks=3 and kode_status_iks1=3 and MONTHS_BETWEEN(tgl_akhir_iks,sysdate) between 0 and 3
order by b.kode_kantor,due_date,a.kode_faskes desc,no_iks desc";
    $recordsTotal=0;
    $sql = "select count(*) JML from ({$sql_utama}) " ;//echo $sql;
    $DB->parse($sql);
    if($DB->execute()) 
        if($row = $DB->nextrow())
            $recordsTotal=$row['JML'];

    $order      = $_POST["order"];
    $by = $order[0]['dir'];
  
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
    $sql = "select A.* from ({$sql_utama}) A
            where NOurut between {$start} and {$end} ";
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
}else{
    echo '{"ret":-1,"msg":"Tidak ada tipe yang dipilih"}';
}
?>