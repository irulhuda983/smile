<?PHP
session_start();
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE	        = $_POST["TYPE"] . $_GET['TYPE'];
$SEARCHA        = isset($_POST['SEARCHA'])?strtoupper($_POST['SEARCHA']):''; //echo $SEARCHA;
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

if($TYPE=='queryTipeKlaim'){
    
    $recordsTotal=0;
    $sql = "select count(*) JML from sijstk.PN_KODE_TIPE_KLAIM "; //echo $sql;
    $DB->parse($sql);
    if($DB->execute()) 
        if($row = $DB->nextrow())
            $recordsTotal=$row['JML'];
    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY KODE_TIPE_KLAIM ASC ";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY KODE_TIPE_KLAIM ASC ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY NAMA_TIPE_KLAIM ASC ";
    } else $order = "order by KODE_TIPE_KLAIM ASC";
  
    $draw = 1;
    if(isset($_POST['draw'])){
        $draw = $_POST['draw']; 
    }else{
        $draw = 1;       
    }

    $start = $_POST['start']+1;
    $length = $_POST['length'];
    $end = ($start-1) + $length;

    if($end==0)  $end=200;
    $sql = "";
    $sql = "select * from (select rownum NO_URUT ,KODE_TIPE_KLAIM,NAMA_TIPE_KLAIM,STATUS_NONAKTIF from sijstk.PN_KODE_TIPE_KLAIM {$order})
            where NO_URUT between {$start} and {$end} "; //echo $sql;
 
    $DB->parse($sql);
    if($DB->execute()){ 
        $i = 0;
        while($data = $DB->nextrow())
        {
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
else if($TYPE=='queryTipePenerima'){
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
    $sql = "select count(*) JML from sijstk.PN_KODE_TIPE_PENERIMA"; //echo $sql;
    $DB->parse($sql);
    if($DB->execute()) 
        if($row = $DB->nextrow())
            $recordsTotal=$row['JML'];

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY KODE_TIPE_PENERIMA ASC ";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY KODE_TIPE_PENERIMA ASC ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY NAMA_TIPE_PENERIMA ASC ";
    }else if($order[0]['column']=='3'){
        $order = "ORDER BY NAMA_TIPE_PENERIMA ASC ";
    }else if($order[0]['column']=='4'){
        $order = "ORDER BY STATUS_NONAKTIF ASC ";
    } else $order = "order by A.KODE_TIPE_PENERIMA ASC";
  
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
            (select rownum NO_URUT ,KODE_TIPE_PENERIMA,NAMA_TIPE_PENERIMA,STATUS_NONAKTIF
             from sijstk.PN_KODE_TIPE_PENERIMA  {$order}
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

}else if($TYPE=='queryGroupICD'){
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
    $sql = "select count(*) JML from sijstk.PN_KODE_GROUP_ICD "; //echo $sql;
    $DB->parse($sql);
    if($DB->execute()) 
        if($row = $DB->nextrow())
            $recordsTotal=$row['JML'];

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY KODE_GROUP_ICD ASC ";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY KODE_GROUP_ICD ASC ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY NAMA_GROUP_ICD ASC ";
    }else if($order[0]['column']=='3'){
        $order = "ORDER BY A.STATUS_NONAKTIF ASC ";
    } else $order = "order by NAMA_GROUP_ICD,KODE_GROUP_ICD ASC";
  
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
            (select rownum NO_URUT ,KODE_GROUP_ICD,NAMA_GROUP_ICD,STATUS_NONAKTIF
             from sijstk.PN_KODE_GROUP_ICD {$order}
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

}else if($TYPE=='queryLokasiKecelakaan'){
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
    $sql = "select count(*) JML from sijstk.PN_KODE_LOKASI_KECELAKAAN "; //echo $sql;
    $DB->parse($sql);
    if($DB->execute()) 
        if($row = $DB->nextrow())
            $recordsTotal=$row['JML'];

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY KODE_LOKASI_KECELAKAAN ASC ";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY KODE_LOKASI_KECELAKAAN ASC ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY NAMA_LOKASI_KECELAKAAN ASC ";
    }else if($order[0]['column']=='3'){
        $order = "ORDER BY STATUS_NONAKTIF ASC ";
    } else $order = "order by KODE_LOKASI_KECELAKAAN ASC";
  
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
            (select rownum NO_URUT ,KODE_LOKASI_KECELAKAAN,NAMA_LOKASI_KECELAKAAN,STATUS_NONAKTIF
             from sijstk.PN_KODE_LOKASI_KECELAKAAN {$order}
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

}else if($TYPE=='queryKondisiTerakhir'){
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
    $sql = "select count(*) JML from sijstk.PN_KODE_KONDISI_TERAKHIR"; //echo $sql;
    $DB->parse($sql);
    if($DB->execute()) 
        if($row = $DB->nextrow())
            $recordsTotal=$row['JML'];

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY KODE_KONDISI_TERAKHIR ASC ";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY KODE_KONDISI_TERAKHIR ASC ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY NAMA_KONDISI_TERAKHIR ASC ";
    }else if($order[0]['column']=='3'){
        $order = "ORDER BY STATUS_NONAKTIF ASC ";
    } else $order = "order by KODE_KONDISI_TERAKHIR ASC";
  
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
            (select rownum NO_URUT ,KODE_KONDISI_TERAKHIR,NAMA_KONDISI_TERAKHIR,A.STATUS_NONAKTIF,NAMA_TIPE_KLAIM
             from sijstk.pn_kode_KONDISI_TERAKHIR A left outer join PN_KODE_TIPE_KLAIM B on A.kode_tipe_klaim =b.kode_tipe_klaim {$order}
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

}else if($TYPE=='queryJenisKasus'){
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
    $sql = "select count(*) JML from sijstk.PN_KODE_JENIS_KASUS"; //echo $sql;
    $DB->parse($sql);
    if($DB->execute()) 
        if($row = $DB->nextrow())
            $recordsTotal=$row['JML'];

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY KODE_JENIS_KASUS ASC ";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY KODE_JENIS_KASUS ASC ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY NAMA_JENIS_KASUS ASC ";
    }else if($order[0]['column']=='3'){
        $order = "ORDER BY STATUS_NONAKTIF ASC ";
    } else $order = "order by KODE_JENIS_KASUS ASC";
  
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
            (select rownum NO_URUT ,KODE_JENIS_KASUS,NAMA_JENIS_KASUS,A.STATUS_NONAKTIF,NAMA_TIPE_KLAIM
            from sijstk.pn_kode_JENIS_KASUS A left outer join PN_KODE_TIPE_KLAIM B on A.kode_tipe_klaim =b.kode_tipe_klaim {$order}
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

}else if($TYPE=='queryAkibat'){
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
    $sql = "select count(*) JML from sijstk.PN_KODE_AKIBAT_DIDERITA"; //echo $sql;
    $DB->parse($sql);
    if($DB->execute()) 
        if($row = $DB->nextrow())
            $recordsTotal=$row['JML'];

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY KODE_AKIBAT_DIDERITA ASC ";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY KODE_AKIBAT_DIDERITA ASC ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY NAMA_AKIBAT_DIDERITA ASC ";
    }else if($order[0]['column']=='3'){
        $order = "ORDER BY NAMA_AKIBAT_DIDERITA ASC ";
    }else if($order[0]['column']=='4'){
        $order = "ORDER BY STATUS_NONAKTIF ASC ";
    } else $order = "order by A.KODE_AKIBAT_DIDERITA ASC";
  
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
            (select rownum NO_URUT ,KODE_AKIBAT_DIDERITA,NAMA_AKIBAT_DIDERITA,STATUS_NONAKTIF
             from sijstk.PN_KODE_AKIBAT_DIDERITA  {$order}
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
}else if($TYPE=='queryDiagnosa'){
    $search="";
    if(trim($SEARCHA)!=""){
        $search=" where KODE_DIAGNOSA like '%{$SEARCHA}%' or NAMA_DIAGNOSA like '%{$SEARCHA}%' or NAMA_GROUP_ICD like '%{$SEARCHA}%'";
    } 
    $recordsTotal=0;
    $sql = "select count(*) JML from sijstk.PN_KODE_DIAGNOSA A left outer join  sijstk.PN_KODE_GROUP_ICD B 
    on A.KODE_GROUP_ICD=B.KODE_GROUP_ICD  {$search}";//echo $sql;
    $DB->parse($sql);
    if($DB->execute()) 
        if($row = $DB->nextrow())
            $recordsTotal=$row['JML'];

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY KODE_DIAGNOSA ASC ";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY KODE_DIAGNOSA ASC ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY NAMA_DIAGNOSA ASC ";
    }else if($order[0]['column']=='3'){
        $order = "ORDER BY NAMA_GROUP_ICD ASC ";
    }else if($order[0]['column']=='4'){
        $order = "ORDER BY STATUS_NONAKTIF ASC ";
    } else $order = "order by A.KODE_GROUP_ICD,A.KODE_DIAGNOSA ASC";
  
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
            (select rownum NO_URUT ,KODE_DIAGNOSA,NAMA_DIAGNOSA,A.STATUS_NONAKTIF,NAMA_GROUP_ICD
             from sijstk.PN_KODE_DIAGNOSA A left outer join  sijstk.PN_KODE_GROUP_ICD B 
             on A.KODE_GROUP_ICD=B.KODE_GROUP_ICD {$search} {$order}
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
}else if($TYPE=='queryDiagnosaDetil'){
    $search="";
    if(trim($SEARCHA)!=""){
        $search=" where KODE_DIAGNOSA_DETIL like '%{$SEARCHA}%' or NAMA_DIAGNOSA_DETIL like '%{$SEARCHA}%' or NAMA_DIAGNOSA like '%{$SEARCHA}%' or NAMA_GROUP_ICD like '%{$SEARCHA}%'";
    } 
    $recordsTotal=0;
    $sql = "select count(*) JML  from sijstk.PN_KODE_DIAGNOSA_DETIL A left outer join  sijstk.PN_KODE_DIAGNOSA B on A.KODE_DIAGNOSA=B.KODE_DIAGNOSA
    left outer join  sijstk.PN_KODE_GROUP_ICD C on B.KODE_GROUP_ICD=c.KODE_GROUP_ICD {$search}"; //echo $sql;
    $DB->parse($sql);
    if($DB->execute()) 
        if($row = $DB->nextrow())
            $recordsTotal=$row['JML'];

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY KODE_DIAGNOSA_DETIL ASC ";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY KODE_DIAGNOSA_DETIL ASC ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY NAMA_DIAGNOSA_DETIL ASC ";
    }else if($order[0]['column']=='3'){
        $order = "ORDER BY NAMA_GROUP_ICD ASC ";
    }else if($order[0]['column']=='4'){
            $order = "ORDER BY NAMA_DIAGNOSA ASC ";
    }else if($order[0]['column']=='5'){
        $order = "ORDER BY STATUS_NONAKTIF ASC ";
    } else $order = "order by B.KODE_GROUP_ICD,B.KODE_DIAGNOSA,A.KODE_DIAGNOSA_DETIL ASC";
  
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
            (select rownum NO_URUT ,KODE_DIAGNOSA_DETIL,NAMA_DIAGNOSA_DETIL,A.STATUS_NONAKTIF,
            B.NAMA_DIAGNOSA,C.NAMA_GROUP_ICD
             from sijstk.PN_KODE_DIAGNOSA_DETIL A left outer join  sijstk.PN_KODE_DIAGNOSA B on A.KODE_DIAGNOSA=B.KODE_DIAGNOSA
             left outer join  sijstk.PN_KODE_GROUP_ICD C on B.KODE_GROUP_ICD=c.KODE_GROUP_ICD
              {$search} {$order}
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
}else if($TYPE=='queryDokumen'){
    $search="";
    if(trim($SEARCHA)!=""){
        $search=" where KODE_DOKUMEN like '%{$SEARCHA}%' or NAMA_DOKUMEN like '%{$SEARCHA}%'";
    } 
    $recordsTotal=0;
    $sql = "select count(*) JML  from sijstk.PN_KODE_DOKUMEN {$search}"; //echo $sql;
    $DB->parse($sql);
    if($DB->execute()) 
        if($row = $DB->nextrow())
            $recordsTotal=$row['JML'];

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY KODE_DOKUMEN ASC ";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY KODE_DOKUMEN ASC ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY NAMA_DOKUMEN ASC ";
    }else if($order[0]['column']==''){
        $order = "ORDER BY STATUS_NONAKTIF ASC ";
    } else $order = "order by KODE_DOKUMEN ASC";
  
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
            (select rownum NO_URUT ,KODE_DOKUMEN,NAMA_DOKUMEN,nvl(A.STATUS_NONAKTIF,'T') STATUS_NONAKTIF
             from sijstk.PN_KODE_DOKUMEN A 
              {$search} {$order}
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
}else if($TYPE=='querySebabKlaim'){
    $search="";
    if(trim($SEARCHA)!=""){
        $search=" where KODE_SEBAB_KLAIM like '%{$SEARCHA}%' or NAMA_SEBAB_KLAIM like '%{$SEARCHA}%' or NAMA_TIPE_KLAIM like '%{$SEARCHA}%'";
    } 
    $recordsTotal=0;
    $sql = "select count(*) JML  from sijstk.PN_KODE_SEBAB_KLAIM A left outer join sijstk.PN_KODE_TIPE_KLAIM B 
            on A.KODE_TIPE_KLAIM=B.KODE_TIPE_KLAIM  {$search}";// echo $sql;
    $DB->parse($sql);
    if($DB->execute()) 
        if($row = $DB->nextrow())
            $recordsTotal=$row['JML'];

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY KODE_SEBAB_KLAIM ASC ";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY KODE_SEBAB_KLAIM ASC ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY NAMA_SEBAB_KLAIM ASC ";
    }else if($order[0]['column']=='3'){
        $order = "ORDER BY NAMA_TIPE_KLAIM ASC ";
    }else if($order[0]['column']=='4'){
        $order = "ORDER BY STATUS_NONAKTIF ASC ";
    } else $order = "order by KODE_SEBAB_KLAIM ASC";
  
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
            (select rownum NO_URUT ,KODE_SEBAB_KLAIM,NAMA_SEBAB_KLAIM,NAMA_TIPE_KLAIM,nvl(A.STATUS_NONAKTIF,'T') STATUS_NONAKTIF,
                FLAG_MENINGGAL,FLAG_PARTIAL,PERSEN_PENGAMBILAN_MAKSIMUM MAKS
             from sijstk.PN_KODE_SEBAB_KLAIM A left outer join sijstk.PN_KODE_TIPE_KLAIM B on A.KODE_TIPE_KLAIM=B.KODE_TIPE_KLAIM
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
}else if($TYPE=='querySebabSegmen'){
    $search="";
    if(trim($SEARCHA)!=""){
        $search=" where NAMA_SEBAB_KLAIM like '%{$SEARCHA}%' or NAMA_SEGMEN like '%{$SEARCHA}%' or A.KETERANGAN like '%{$SEARCHA}%'";
    } 
    $recordsTotal=0;
    $sql = "select count(*) JML  from sijstk.PN_KODE_SEBAB_SEGMEN A left outer join sijstk.PN_KODE_SEBAB_KLAIM B 
            on A.KODE_SEBAB_KLAIM=B.KODE_SEBAB_KLAIM left outer join sijstk.kn_kode_segmen c 
            on a.kode_segmen=c.kode_segmen  {$search}"; //echo $sql;
    $DB->parse($sql);
    if($DB->execute()) 
        if($row = $DB->nextrow())
            $recordsTotal=$row['JML'];

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY A.KODE_SEBAB_KLAIM ASC ";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY NAMA_SEBAB_KLAIM ASC ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY NAMA_SEGMEN ASC ";
    }else if($order[0]['column']=='3'){
        $order = "ORDER BY A.KETERANGAN ASC ";
    }else if($order[0]['column']=='4'){
        $order = "ORDER BY STATUS_NONAKTIF ASC ";
    } else $order = "order by A.KODE_SEBAB_KLAIM ASC";
  
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
            (select rownum NO_URUT,A.KODE_SEGMEN,A.KODE_SEBAB_KLAIM ,NAMA_SEBAB_KLAIM,NAMA_SEGMEN,nvl(A.STATUS_NONAKTIF,'T') STATUS_NONAKTIF,
                A.KETERANGAN
                from sijstk.PN_KODE_SEBAB_SEGMEN A left outer join sijstk.PN_KODE_SEBAB_KLAIM B 
                on A.KODE_SEBAB_KLAIM=B.KODE_SEBAB_KLAIM left outer join sijstk.kn_kode_segmen c 
                on a.kode_segmen=c.kode_segmen
              {$search} {$order}
            )
            where NO_URUT between {$start} and {$end} ";
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
}else if($TYPE=='queryManfaat'){
    $search="";
    if(trim($SEARCHA)!=""){
        $search=" where KODE_MANFAAT like '%{$SEARCHA}%' or NAMA_MANFAAT like '%{$SEARCHA}%' or KATEGORI_MANFAAT like '%{$SEARCHA}%' or JENIS_MANFAAT like '%{$SEARCHA}%' or TIPE_MANFAAT like '%{$SEARCHA}%' or KETERANGAN like '%{$SEARCHA}%'";
    } 
    $recordsTotal=0;
    $sql = "select count(*) JML  from sijstk.PN_KODE_MANFAAT  {$search}"; //echo $sql;
    $DB->parse($sql);
    if($DB->execute()) 
        if($row = $DB->nextrow())
            $recordsTotal=$row['JML'];

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = "ORDER BY KODE_MANFAAT ASC ";
    }else if($order[0]['column']=='1'){
        $order = "ORDER BY NAMA_MANFAAT ASC ";
    }else if($order[0]['column']=='2'){
        $order = "ORDER BY KATEGORI_MANFAAT ASC ";
    }else if($order[0]['column']=='3'){
        $order = "ORDER BY JENIS_MANFAAT ASC ";
    }else if($order[0]['column']=='4'){
        $order = "ORDER BY TIPE_MANFAAT ASC ";
    }else if($order[0]['column']=='5'){
        $order = "ORDER BY FLAG_BERKALA ASC ";
    }else if($order[0]['column']=='6'){
        $order = "ORDER BY KETERANGAN ASC ";
    }else if($order[0]['column']=='7'){
        $order = "ORDER BY STATUS_NONAKTIF ASC ";
    } else $order = "order by KODE_MANFAAT ASC";
  
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
            (select rownum NO_URUT,KODE_MANFAAT,NAMA_MANFAAT,KATEGORI_MANFAAT,JENIS_MANFAAT,TIPE_MANFAAT,FLAG_BERKALA,KETERANGAN,nvl(A.STATUS_NONAKTIF,'T') STATUS_NONAKTIF
                from sijstk.PN_KODE_MANFAAT A
              {$search} {$order}
            )
            where NO_URUT between {$start} and {$end} ";
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
else{
    echo '{"ret":-1,"msg":"Tidak ada tipe yang dipilih"}';
}
?>