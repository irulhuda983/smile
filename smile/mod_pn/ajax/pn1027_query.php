<?PHP
session_start();
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE	        = $_POST["TYPE"] . $_GET['TYPE'];
$USER         = $_SESSION["USER"];
$KODE_KANTOR  = $_SESSION['KDKANTOR']; 

if($TYPE=='query'){
    /*$ls_search_pilihan = $_POST['search_pilihan'];
    $ls_search_keyword = $_POST['keyword'];

    $condition ="";
    //add query condition for keyword
    if($ls_search_keyword!=""){
        if($ls_search_pilihan=='KETERANGAN')
            $condition .= " AND A.".strtoupper($ls_search_pilihan)." like '%".strtoupper($ls_search_keyword)."%'";
        else
            $condition .= " AND ".strtoupper($ls_search_pilihan)." like '%".strtoupper($ls_search_keyword)."%'";
    } 
    */

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY A.KODE_JENIS ASC ";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY A.KODE_JENIS ASC ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY NAMA_JENIS ASC ";
    }else if($order[0]['column']=='3'){
    } else $order = "order by NAMA_JENIS ASC";
  
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
    $sql = "select rownum ,KODE_JENIS,NAMA_JENIS from sijstk.tc_kode_jenis 
            where rownum between {$start} and {$end} {$order}"; //echo $sql;
    $recordsTotal=0;
    $DB->parse($sql);
    if($DB->execute()){ 
      $i = 0;
      while($data = $DB->nextrow())
      {
          $recordsTotal++;
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