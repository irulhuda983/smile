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
$SEARCHD        = isset($_POST['SEARCHD'])?strtoupper($_POST['SEARCHD']):''; //echo $SEARCHA;
$USER           = $_SESSION["USER"];
$KODE_KANTOR    = $_SESSION['KDKANTOR']; 

$schema="sijstk";             

if($TYPE=='queryFaskes'){
    $search='';
    if(trim($SEARCHA)!=""){
        $search=" and kode_kantor in (
select kode_kantor 
from ms.ms_kantor 
START WITH kode_kantor = '{$SEARCHA}'
CONNECT BY PRIOR kode_kantor=kode_kantor_induk) ";
            //$search.=" AND A.KODE_KANTOR='{$SEARCHA}'";
        
    }
    if(trim($SEARCHC)!=""){
        if($SEARCHB=='NOFASKES')
            $search .= " and NO_FASKES = '{$SEARCHC}'";
        else if($SEARCHB=='KODEFASKES')
            $search .= " and KODE_FASKES = '{$SEARCHC}'";
        else if($SEARCHB=='TIPEFASKES')
            $search .= " and KODE_TIPE ='{$SEARCHC}'";
        else if($SEARCHB=='ALAMAT')
            $search .= " and ALAMAT like '%{$SEARCHC}%'";
        else 
            $search .= " and NAMA_FASKES like '%{$SEARCHC}%'";
    } 
    $recordsTotal=0;
    $sql_utama="select rownum no_urut1,kode_kantor,(select nama_kantor from ms.ms_kantor where kode_kantor=a.kode_kantor) nama_kantor,
                    nama_faskes,alamat,kode_faskes,no_faskes
                    from tc.tc_faskes a       
                    where kode_status='ST3'     {$search} ";
    $sql = "select count(*) JML from ({$sql_utama})";//echo $sql;
    $DB->parse($sql);
    if($DB->execute()) 
        if($row = $DB->nextrow())
            $recordsTotal=$row['JML'];

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    $order = "order by NAMA_FASKES ASC";
  
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
            ({$sql_utama} {$order}
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
}
?>