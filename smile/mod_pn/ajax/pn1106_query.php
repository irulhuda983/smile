<?PHP
session_start();
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE	            = $_POST["TYPE"] . $_GET['TYPE'];
$USER             = $_SESSION["USER"];
$KODE_KANTOR      = $_SESSION['KDKANTOR']; 
$gs_kantor_aktif  = $_SESSION['gs_kantor_aktif'];
$schema           = "pn";  

if($TYPE=='query'){
  $order = $_POST["order"];
  $by  = $order[0]['dir'];
  
  if($order[0]['column']=='2'){
    $orderQry = "ORDER BY KODE_BHP $by ";
  }else if($order[0]['column']=='3'){
    $orderQry = "ORDER BY NAMA_BHP $by ";
  }else $orderQry = "order by KODE_BHP $by ";

  $searchVal = $search["value"];
  if ($searchVal != "") {
    $searchQry = " and ( ";
    $searchQry .= " KODE_BHP like '%$searchVal%'";
    $searchQry .= " or NAMA_BHP like '%$searchVal%'";
    $searchQry .= ")";
  }
  
  $draw = 1;
  if(isset($_POST['draw'])){
    $draw = $_POST['draw']; 
  }else{
    $draw = 1;     
  }

  $start  = $_POST['start']+1;
  $length = $_POST['length'];
  $end    = ($start-1) + $length;

  if($end==0)  $end=100;
  $sql = "";
  $sql = "
  select  * 
  from    (
    select  rownum no_urut, 
            a.kode_bhp,
            nvl(a.nama_bhp, '-') nama_bhp,
            nvl(a.alamat_bhp, '-') alamat_bhp,
            nvl(a.nama_pimpinan, '-') nama_pimpinan,
            nvl(a.nama_penerima_bhp, '-') nama_penerima_bhp,
            nvl(a.bank_penerima_bhp, '-') bank_penerima_bhp,
            nvl(a.no_rekening_penerima_bhp, '-') no_rekening_penerima_bhp,
            nvl(a.nama_rekening_penerima_bhp, '-') nama_rekening_penerima_bhp,
            nvl(a.telepon_area, '-') telepon_area,
            nvl(a.telepon, '-') telepon,
            nvl(a.fax_area, '-') fax_area,
            nvl(a.fax, '-') fax,
            nvl(a.email, '-') email,
            nvl(a.nama_kontak, '-') nama_kontak,
            nvl(a.telepon_area_kontak, '-') telepon_area_kontak,
            nvl(a.telepon_kontak, '-') telepon_kontak,
            nvl(a.handphone_kontak, '-') handphone_kontak
    from    pn.pn_kode_bhp a
    where   rownum < {$end} $searchQry )
  where   no_urut between {$start} and {$end} {$orderQry}"; 

  $recordsTotal=0;
  $DB->parse($sql);
  if($DB->execute()){ 
    $i = 0;
    while($data = $DB->nextrow())
    {
      $data["NO_URUT"] = $start + $i;
      $recordsTotal++;
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
}else if($TYPE=='getKantor'){
  $p_kode_kantor = strtoupper($_POST["kode_kantor"]);

  $sql_kode_tipe = "SELECT KODE_TIPE, KODE_KANTOR_INDUK
                      FROM MS.MS_KANTOR
                    WHERE KODE_KANTOR = :p_group_konsol";
  $proc = $DB->parse($sql_kode_tipe);
  oci_bind_by_name($proc, ":p_group_konsol", $gs_kantor_aktif, 10);
  $DB->execute();
  $data_tipe = $DB->nextrow();
  $kode_tipe_kantor   = $data_tipe['KODE_TIPE'];
  $kode_kantor_induk  = $data_tipe['KODE_KANTOR_INDUK'];
  
  if($kode_kantor_induk == 'P' || $kode_kantor_induk == ''){

    $sql = "
    select  kode_kantor,
            nama_kantor
    from    kn.ms_kantor
    where   kode_kantor = :p_kode_kantor"; 
    $proc = $DB->parse($sql);
    oci_bind_by_name($proc, ":p_kode_kantor", $p_kode_kantor, 10);
    
    if($DB->execute()){ 
      if ($data = $DB->nextrow()) {
        $jsondata["ret"] = "0";
        $jsondata["msg"] = "Sukses";
        $jsondata["kodeKantor"] = $data["KODE_KANTOR"];
        $jsondata["namaKantor"] = $data["NAMA_KANTOR"];
      } else {
        $jsondata["ret"] = "-1";
        $jsondata["msg"] = "Data kantor tidak ditemukan";
      }
      echo json_encode($jsondata);
    } 
    
  } else {
    if($kode_tipe_kantor == '1'){

      $sql = "SELECT A.*
              FROM (    SELECT kode_kantor, nama_kantor
                          FROM ms.ms_kantor
                        WHERE     aktif = 'Y'
                              AND status_online = 'Y'
                              AND kode_tipe NOT IN ('1', '2')
                    START WITH kode_kantor = :p_group_konsol
                    CONNECT BY PRIOR kode_kantor = kode_kantor_induk) A
            WHERE A.KODE_KANTOR = :p_kode_kantor"; 
      $proc = $DB->parse($sql);
      oci_bind_by_name($proc, ":p_group_konsol", $gs_kantor_aktif, 10);
      oci_bind_by_name($proc, ":p_kode_kantor", $p_kode_kantor, 10);
      if($DB->execute()){ 
        if ($data = $DB->nextrow()) {
          $jsondata["ret"] = "0";
          $jsondata["msg"] = "Sukses";
          $jsondata["kodeKantor"] = $data["KODE_KANTOR"];
          $jsondata["namaKantor"] = $data["NAMA_KANTOR"];
        } else {
          $jsondata["ret"] = "-1";
          $jsondata["msg"] = "Data kantor tidak ditemukan, input kantor cabang sesuai dengan wilayah. ";
        }
        echo json_encode($jsondata);
      } else {
        echo '{"ret":-1,"msg":"Gagal mendapatkan data kantor!"}';
      }

    } else if($kode_tipe_kantor == '2'){

      $sql = "SELECT A.*
                FROM (    SELECT kode_kantor, nama_kantor
                            FROM ms.ms_kantor
                          WHERE     aktif = 'Y'
                                AND status_online = 'Y'
                                AND kode_tipe NOT IN ('1', '2')
                      START WITH kode_kantor = :p_group_konsol
                      CONNECT BY PRIOR kode_kantor = kode_kantor_induk) A
              WHERE A.KODE_KANTOR = :p_kode_kantor"; 
      $proc = $DB->parse($sql);
      oci_bind_by_name($proc, ":p_kode_kantor", $p_kode_kantor, 10);
      oci_bind_by_name($proc, ":p_group_konsol", $kode_kantor_induk, 10);
      if($DB->execute()){ 
        if ($data = $DB->nextrow()) {
          $jsondata["ret"] = "0";
          $jsondata["msg"] = "Sukses";
          $jsondata["kodeKantor"] = $data["KODE_KANTOR"];
          $jsondata["namaKantor"] = $data["NAMA_KANTOR"];
        } else {
          $jsondata["ret"] = "-1";
          $jsondata["msg"] = "Data kantor tidak ditemukan, input kantor cabang sesuai dengan wilayah. ";
        }
        echo json_encode($jsondata);
      } else {
        echo '{"ret":-1,"msg":"Gagal mendapatkan data kantor!"}';
      }

    } else {
      echo '{"ret":-1,"msg":"Gagal mendapatkan data kantor!"}';
    }
  }
}else if($TYPE=='getListKantorBhp'){
  $p_kode_bhp = strtoupper($_POST["kode_bhp"]);

  $sql_kode_tipe = "SELECT KODE_KANTOR_INDUK, KODE_TIPE
                      FROM MS.MS_KANTOR
                    WHERE KODE_KANTOR = :p_group_konsol";
  $proc = $DB->parse($sql_kode_tipe);
  oci_bind_by_name($proc, ":p_group_konsol", $gs_kantor_aktif, 10);
  $DB->execute();
  $data_tipe          = $DB->nextrow();
  $kode_kantor_induk  = $data_tipe['KODE_KANTOR_INDUK'];
  $kode_tipe_kantor   = $data_tipe['KODE_TIPE'];
  
  $data_kantor_btn    = array();

  if($kode_kantor_induk == 'P' || $kode_kantor_induk == ''){

    $sql = "SELECT A.*
              FROM (    SELECT kode_kantor
                          FROM ms.ms_kantor
                        WHERE     aktif = 'Y'
                              AND status_online = 'Y'
                              AND kode_tipe NOT IN ('1', '2')
                    START WITH kode_kantor = '0'
                    CONNECT BY PRIOR kode_kantor = kode_kantor_induk) A"; 
    $proc = $DB->parse($sql);
    
    if($DB->execute()){ 
      while ($row = $DB->nextrow()) {
        array_push($data_kantor_btn, $row['KODE_KANTOR']);
      }
    } 
    
  } else {
    if($kode_tipe_kantor == '1'){

      $sql = "SELECT A.*
              FROM (    SELECT kode_kantor
                          FROM ms.ms_kantor
                        WHERE     aktif = 'Y'
                              AND status_online = 'Y'
                              AND kode_tipe NOT IN ('1', '2')
                    START WITH kode_kantor = :p_group_konsol
                    CONNECT BY PRIOR kode_kantor = kode_kantor_induk) A"; 
      $proc = $DB->parse($sql);
      oci_bind_by_name($proc, ":p_group_konsol", $gs_kantor_aktif, 10);
      if($DB->execute()){ 
        while ($row = $DB->nextrow()) {
          array_push($data_kantor_btn, $row['KODE_KANTOR']);
        }
      } 
      // var_dump($sql);die();
    } else if($kode_tipe_kantor == '2'){

      $sql = "SELECT A.*
                FROM (    SELECT kode_kantor
                            FROM ms.ms_kantor
                          WHERE     aktif = 'Y'
                                AND status_online = 'Y'
                                AND kode_tipe NOT IN ('1', '2')
                      START WITH kode_kantor = :p_group_konsol
                      CONNECT BY PRIOR kode_kantor = kode_kantor_induk) A"; 
      $proc = $DB->parse($sql);
      oci_bind_by_name($proc, ":p_group_konsol", $kode_kantor_induk, 10);
      if($DB->execute()){ 
        while ($row = $DB->nextrow()) {
          array_push($data_kantor_btn, $row['KODE_KANTOR']);
        }
      } 
    } 
  }
  
  $sql = "
  select  kode_bhp,
          kode_kantor,
          (select nama_kantor from kn.ms_kantor b where a.kode_kantor = b.kode_kantor) nama_kantor,
          tgl_rekam
  from    pn.pn_kode_bhp_kantor a
  where   kode_bhp = :p_kode_bhp
  order by tgl_rekam desc, kode_bhp asc, kode_kantor asc"; 
  $proc = $DB->parse($sql);
  oci_bind_by_name($proc, ":p_kode_bhp", $p_kode_bhp, 10);
  if($DB->execute()){ 
    $data = array();
    while ($row = $DB->nextrow()) {
      array_push($data, $row);
    }
    $jsondata["ret"]          = "0";
    $jsondata["msg"]          = "Sukses";
    $jsondata["data"]         = $data;
    $jsondata["tipe_kantor"]  = $kode_kantor_induk;
    $jsondata["data_kantor"]  = $data_kantor_btn;
    echo json_encode($jsondata);
  } else {
    echo '{"ret":-1,"msg":"Gagal mendapatkan data kantor!"}';
  }
}
else{
  echo '{"ret":-1,"msg":"Tidak ada tipe yang dipilih"}';
}
?>