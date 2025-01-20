<?PHP
session_start();
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE	        = $_POST["TYPE"] . $_GET['TYPE'];
$USER         = $_SESSION["USER"];
$KODE_KANTOR  = $_SESSION['KDKANTOR']; 
$KODE    = $_POST['KODE'] . $_GET['KODE'];


if($TYPE=='query'){
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
        $condition .= " where (a.kode_perusahaan like '%{$ls_search_keyword}%' or b.nama_perusahaan like '%{$ls_search_keyword}%' or b.alamat_perusahaan like '%{$ls_search_keyword}%') ";
    } 

    $order      = $_POST["order"];
    $by = $order[0]['dir'];

    if($order[0]['column']=='0'){
        $order = " ORDER BY A.KODE_PERUSAHAAN ";
    }else if($order[0]['column']=='1'){
        $order = " ORDER BY A.KODE_PERUSAHAAN ";
    }else if($order[0]['column']=='2'){
        $order = " ORDER BY A.STATUS_AKTIF ";
    }else if($order[0]['column']=='3'){
        $order = " ORDER BY B.NAMA_PERUSAHAAN";
    }else if($order[0]['column']=='4'){
        $order = " ORDER BY ALAMAT_PERUSAHAAN ";
    }else if($order[0]['column']=='5'){
        $order = " ORDER BY A.TGL_REKAM ";
    }else if($order[0]['column']=='6'){
        $order = " ORDER BY A.PETUGAS_REKAM ";
    } else $order = "order by A.KODE_PERUSAHAAN";
    // else{
    //     $order = "ORDER BY TGL_REKAM ";
    // }
    //$order .= $by;
    // $order .= 'DESC';
    if($end==0)  $end=100;
    $sql_utama = "select rownum no_urut,A.kode_perusahaan,decode(a.status_aktif,'T',nvl(to_char(tgl_nonaktif,'dd/mm/yyyy'),' '),nvl(to_char(tgl_aktif,'dd/mm/yyyy'),' ')) tgl_aktif, status_aktif,
to_char(a.tgl_rekam,'dd/mm/yyyy') tgl_rekam,a.petugas_rekam,b.nama_perusahaan,b.alamat_perusahaan from sijstk.pn_rtw_prs_pendukung a left outer join sijstk.kn_perusahaan b
on a.kode_perusahaan=b.kode_perusahaan  {$condition}   ";
    
    $sql= "select count(1) jml from({$sql_utama})";
    $recordsTotal=0; //echo $sql;
    $DB->parse($sql);
    if($DB->execute())
      if($data = $DB->nextrow())
        $recordsTotal=$data['JML'];
        
    $sql = "select 
            NO_URUT,KODE_PERUSAHAAN,TGL_AKTIF,STATUS_AKTIF,TGL_REKAM,PETUGAS_REKAM,NAMA_PERUSAHAAN,ALAMAT_PERUSAHAAN
        from ({$sql_utama} {$order}) 
        where no_urut between ".$start." and ".$end;//echo $sql;
//where B.KODE_KANTOR in (select kode_kantor from sijstk.sc_user_fungsi where kode_user='{$_SESSION['USER']}')  {$condition}   {$order}) 
    //$queryTotal = " SELECT COUNT(1)
     //         FROM    SIJSTK.PN_RTW_AGENDA A inner join  sijstk.PN_KLAIM B
     //         ON (B.KODE_KLAIM = A.KODE_KLAIM)
     //       where B.KODE_KANTOR in (select kode_kantor from sijstk.sc_user_fungsi where kode_user='{$_SESSION['USER']}') $condition";
     // print_r($sql); echo
     // die;
    //$recordsTotal = $DB->get_data($queryTotal); 
    
    $DB->parse($sql);
    if($DB->execute()){ 
      $i = 0;
      while($data = $DB->nextrow())
      {
          //$recordsTotal++;
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

}elseif($TYPE=='getlampiran'){
    $sql = "select kode_perusahaan,NO_URUT,keterangan ,NAMA_FILE,DOC_FILE,TO_CHAR(TGL_REKAM,'DD/MM/YYYY') TGL_REKAM, PETUGAS_REKAM ,LENGTH(doc_file) UKURAN
    FROM sijstk.pn_rtw_prs_pendukung_lamp
    where KODE_PERUSAHAAN='{$KODE}'
    order by no_urut";
    $DB->parse($sql);
    $DB->execute();//echo $sql;
    while($row = $DB->nextrow())
    {
        if($key2=='ro')
        {
            echo "<tr><td><div style=\"width:110px;text-align:center;padding:0;\"><a href=\"../ajax/pn5034_download_lampiran.php?dataid={$row['KODE_PERUSAHAAN']}&no={$row['NO_URUT']}\" target=\"_doc_lampiran\"><img src=\"../../images/zoom.png\" />View</a></div></td>";
            echo "<td>{$row['NAMA_FILE']}</td><td>{$row['KETERANGAN']}</td><td align=\"right\">{$row['UKURAN']}</td><td>{$row['TGL_REKAM']}</td><td>{$row['PETUGAS_REKAM']}</td></tr>\n";
        }
        else
        {
            echo "<tr><td><div style=\"width:110px;text-align:center;padding:0;\"><a href=\"../ajax/pn5034_download_lampiran.php?dataid={$row['KODE_PERUSAHAAN']}&no={$row['NO_URUT']}\" target=\"_doc_lampiran\"><img src=\"../../images/zoom.png\" />View</a> &nbsp; | &nbsp; <a href=\"javascript:deleteLampiran('{$row['KODE_PERUSAHAAN']}','{$row['NO_URUT']}');\"><img src=\"../../images/minus.png\" />Hapus</a></div></td>";
            echo "<td>{$row['NAMA_FILE']}</td><td>{$row['KETERANGAN']}</td><td align=\"right\">{$row['UKURAN']}</td><td>{$row['TGL_REKAM']}</td><td>{$row['PETUGAS_REKAM']}</td></tr>\n";
        }
    }
}else{
    echo '{"ret":-1,"msg":"Tidak ada tipe yang dipilih"}';
}
?>