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
    $ls_search_pilihan = $_POST['search_pilihan'];
    $ls_search_keyword = $_POST['keyword'];

    $draw = 1;
    if(isset($_POST['draw'])){
        $draw = $_POST['draw']; 
    }else{
        $draw = 1;       
    }

    $start = $_POST['start']+1;
    $length = $_POST['length'];
    $end = ($start-1) + $length;

    $condition ="";
    //add query condition for keyword
    if($ls_search_keyword!=""){
        if($ls_search_pilihan=='KETERANGAN')
            $condition .= " AND A.".strtoupper($ls_search_pilihan)." like '%".strtoupper($ls_search_keyword)."%'";
        else
            $condition .= " AND ".strtoupper($ls_search_pilihan)." like '%".strtoupper($ls_search_keyword)."%'";
    } 

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY A.KODE_RTW_KLAIM DESC ";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY A.KODE_KLAIM DESC ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY TGL_REKAM DESC ";
    }else if($order[0]['column']=='3'){
        $order = "ORDER BY STATUS_RTW_KLAIM ";
    }else if($order[0]['column']=='4'){
        $order = "ORDER BY NAMA_TK ";
    }else if($order[0]['column']=='5'){
        $order = "ORDER BY NAMA_PERUSAHAAN ";
    }else if($order[0]['column']=='6'){
        $order = "ORDER BY NAMA_KANTOR ";
    } else $order = "order by kode_RTW_KLAIM desc";
    // else{
    //     $order = "ORDER BY TGL_REKAM ";
    // }
    //$order .= $by;
    // $order .= 'DESC';
    if($end==0)  $end=100;
    $sql = "";
    $sql = "select 
                NO_URUT,KODE_RTW_KLAIM,
                KODE_KLAIM,TGL_REKAM,
                STATUS_RTW_KLAIM, NAMA_TK,STATUS,
                NAMA_PERUSAHAAN,NAMA_KANTOR
             from (SELECT ROWNUM as NO_URUT,KODE_RTW_KLAIM,
                        A.KODE_KLAIM,
                        A.KODE_KANTOR_RTW,
                        to_char(A.TGL_REKAM,'DD/MM/YYYY') TGL_REKAM,
                        STATUS_RTW_KLAIM,A.STATUS,
                        NAMA_TK, C.NAMA_KANTOR,D.NAMA_PERUSAHAAN
                    FROM sijstk.PN_KLAIM B INNER JOIN sijstk.PN_RTW_KLAIM A ON (B.KODE_KLAIM = A.KODE_KLAIM)
                    left outer join sijstk.ms_kantor C on a.KODE_KANTOR_RTW=c.KODE_KANTOR left outer join
                    sijstk.kn_perusahaan D on B.KODE_PERUSAHAAN=D.KODE_PERUSAHAAN
                    where A.KODE_KANTOR_RTW='{$_SESSION['gs_kantor_aktif']}'  {$condition}   {$order}) 
            where no_urut between ".$start." and ".$end;//echo $sql;
//where B.KODE_KANTOR in (select kode_kantor from sijstk.sc_user_fungsi where kode_user='{$_SESSION['USER']}')  {$condition}   {$order}) 
    //$queryTotal = " SELECT COUNT(1)
     //         FROM    SIJSTK.PN_RTW_AGENDA A inner join  sijstk.PN_KLAIM B
     //         ON (B.KODE_KLAIM = A.KODE_KLAIM)
     //       where B.KODE_KANTOR in (select kode_kantor from sijstk.sc_user_fungsi where kode_user='{$_SESSION['USER']}') $condition";
     // print_r($sql); echo
     // die;
    //$recordsTotal = $DB->get_data($queryTotal);
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