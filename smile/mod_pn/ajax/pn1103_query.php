<?PHP
session_start();
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE	        = $_POST["TYPE"] . $_GET['TYPE'];
$USER         = $_SESSION["USER"];
$KODE_KANTOR  = $_SESSION['KDKANTOR']; 

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

if($TYPE=='queryKegiatan'){
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
    $recordsTotal=0;
    $sql = "select count(*) JML from {$schema}.pn_kode_promotif_kegiatan"; //echo $sql;
    $DB->parse($sql);
    if($DB->execute()) 
        if($row = $DB->nextrow())
            $recordsTotal=$row['JML'];

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY NO_URUT ASC ";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY KODE_KEGIATAN ASC ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY NAMA_KEGIATAN ASC ";
    }else if($order[0]['column']=='3'){
    } else $order = "order by NO_URUT ASC";
  
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
    $sql = "select * from (select rownum NO_URUT1,NO_URUT ,KODE_KEGIATAN,NAMA_KEGIATAN,KETERANGAN,STATUS_NONAKTIF from {$schema}.pn_kode_promotif_kegiatan {$order})
            where NO_URUT1 between {$start} and {$end} "; //echo $sql;
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
else if($TYPE=='querySubKegiatan'){
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
    $recordsTotal=0;
    $sql = "select count(*) JML from {$schema}.pn_kode_promotif_sub"; //echo $sql;
    $DB->parse($sql);
    if($DB->execute()) 
        if($row = $DB->nextrow())
            $recordsTotal=$row['JML'];

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY KODE_SUB_KEGIATAN ASC ";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY KODE_SUB_KEGIATAN ASC ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY NAMA_SUB_KEGIATAN ASC ";
    }else if($order[0]['column']=='3'){
        $order = "ORDER BY KODE_SUB_KEGIATAN ASC ";
    } else $order = "order by KODE_SUB_KEGIATAN ASC";
  
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
    $sql = "select * from (select rownum NO_URUT1,KODE_SUB_KEGIATAN,NAMA_SUB_KEGIATAN,KETERANGAN,BENTUK_KEGIATAN,
        nvl(REGULASI,' ') REGULASI from {$schema}.pn_kode_promotif_sub {$order})
            where NO_URUT1 between {$start} and {$end} ";//echo $sql;
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
}else if($TYPE=='queryTipe'){
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
    $recordsTotal=0;
    $sql = "select count(*) JML from sijstk.tc_kode_tipe "; //echo $sql;
    $DB->parse($sql);
    if($DB->execute()) 
        if($row = $DB->nextrow())
            $recordsTotal=$row['JML'];

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY KODE_TIPE ASC ";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY KODE_TIPE ASC ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY NAMA_TIPE ASC ";
    }else if($order[0]['column']=='3'){
        $order = "ORDER BY STATUS_NONAKTIF ASC ";
    } else $order = "order by KODE_TIPE ASC";
  
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
            (select rownum NO_URUT ,KODE_TIPE,NAMA_TIPE,STATUS_NONAKTIF
             from sijstk.tc_kode_tipe {$order}
            )
            where NO_URUT between {$start} and {$end} "; //echo $sql;
    
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

}else if($TYPE=='queryKepemilikan'){
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
    $recordsTotal=0;
    $sql = "select count(*) JML from sijstk.tc_kode_kepemilikan "; //echo $sql;
    $DB->parse($sql);
    if($DB->execute()) 
        if($row = $DB->nextrow())
            $recordsTotal=$row['JML'];

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY KODE_KEPEMILIKAN ASC ";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY KODE_KEPEMILIKAN ASC ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY NAMA_KEPEMILIKAN ASC ";
    }else if($order[0]['column']=='3'){
        $order = "ORDER BY STATUS_NONAKTIF ASC ";
    } else $order = "order by KODE_KEPEMILIKAN ASC";
  
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
            (select rownum NO_URUT ,KODE_KEPEMILIKAN,NAMA_KEPEMILIKAN,STATUS_NONAKTIF
             from sijstk.tc_kode_kepemilikan {$order}
            )
            where NO_URUT between {$start} and {$end} "; //echo $sql;
    
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

}else if($TYPE=='queryPembayaran'){
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
    $recordsTotal=0;
    $sql = "select count(*) JML from sijstk.tc_kode_METODE_PEMBAYARAN"; //echo $sql;
    $DB->parse($sql);
    if($DB->execute()) 
        if($row = $DB->nextrow())
            $recordsTotal=$row['JML'];

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY KODE_METODE_PEMBAYARAN ASC ";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY KODE_METODE_PEMBAYARAN ASC ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY NAMA_METODE_PEMBAYARAN ASC ";
    }else if($order[0]['column']=='3'){
        $order = "ORDER BY STATUS_NONAKTIF ASC ";
    } else $order = "order by KODE_METODE_PEMBAYARAN ASC";
  
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
            (select rownum NO_URUT ,KODE_METODE_PEMBAYARAN,NAMA_METODE_PEMBAYARAN,STATUS_NONAKTIF
             from sijstk.tc_kode_METODE_PEMBAYARAN {$order}
            )
            where NO_URUT between {$start} and {$end} "; //echo $sql;
    
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

}else if($TYPE=='queryBank'){
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
    $recordsTotal=0;
    $sql = "select count(*) JML from sijstk.tc_kode_BANK_PEMBAYARAN"; //echo $sql;
    $DB->parse($sql);
    if($DB->execute()) 
        if($row = $DB->nextrow())
            $recordsTotal=$row['JML'];

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY KODE_BANK_PEMBAYARAN ASC ";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY KODE_BANK_PEMBAYARAN ASC ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY NAMA_BANK_PEMBAYARAN ASC ";
    }else if($order[0]['column']=='3'){
        $order = "ORDER BY STATUS_NONAKTIF ASC ";
    } else $order = "order by KODE_BANK_PEMBAYARAN ASC";
  
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
            (select rownum NO_URUT ,KODE_BANK_PEMBAYARAN,NAMA_BANK_PEMBAYARAN,STATUS_NONAKTIF
             from sijstk.tc_kode_BANK_PEMBAYARAN {$order}
            )
            where NO_URUT between {$start} and {$end} "; //echo $sql;
    
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