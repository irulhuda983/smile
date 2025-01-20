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
    $ls_search_pfilter = $_POST['search_pfilter'];
    $ls_search_sfilter = $_POST['search_sfilter'];
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
    if($ls_search_pfilter!=""){
        if($ls_search_pfilter=='AGENDADIPROSES')
            $condition .= " AND (STATUS='AGENDA' or STATUS_RTW_KLAIM='SR1')  ";
        else if($ls_search_pfilter=='AGENDA')
            $condition .= " AND (STATUS='AGENDA')  ";
        else if($ls_search_pfilter=='DIPROSES')
            $condition .= " AND STATUS_RTW_KLAIM='SR1' ";
        else if($ls_search_pfilter=='SELESAI')
            $condition .= " AND STATUS_RTW_KLAIM='SR2' ";
        else if($ls_search_pfilter=='PUTUS')
            $condition .= " AND STATUS_RTW_KLAIM='SR3' ";
        else if($ls_search_pfilter=='BATAL')
            $condition .= " AND STATUS_RTW_KLAIM='SR4' ";
        else if($ls_search_pfilter=='SEMUA')
            $condition .= " ";
        else 
            $condition .= " AND STATUS_RTW_KLAIM = 'SR1'";
    } else
        $condition .= " AND STATUS_RTW_KLAIM = 'SR1' ";
    
    if($ls_search_keyword!=""){
        if($ls_search_sfilter=='KETERANGAN')
            $condition .= " AND A.".strtoupper($ls_search_sfilter)." like '%".strtoupper($ls_search_keyword)."%'";
        else
            $condition .= " AND ".strtoupper($ls_search_sfilter)." like '%".strtoupper($ls_search_keyword)."%'";
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
        $order = "ORDER BY STATUS_KLAIM ";
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
            NAMA_PERUSAHAAN,NAMA_KANTOR,
            case 
                when STATUS_RTW_KLAIM='SR1' then 'SEDANG DIPROSES'
                when STATUS_RTW_KLAIM='SR2' then 'SELESAI'
                when STATUS_RTW_KLAIM='SR3' then 'PUTUS'
                when STATUS_RTW_KLAIM='SR4' then 'BATAL'
                else 'AGENDA'
            end NAMA_STATUS
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
    $recordsTotal=0;// echo $sql;
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

}
elseif($TYPE=='querymonitoring'){
    $draw = 1;
    if(isset($_POST['draw'])){
        $draw = $_POST['draw']; 
    }else{
        $draw = 1;       
    }
    
    $ls_kode_rtw = $_POST['kode_rtw'];

    $start = $_POST['start']+1;
    $length = $_POST['length'];
    $end = ($start-1) + $length;
    $order='';

    if($end==0)  $end=100;
    $sql = "select  NO_URUT,to_char(TGL_KUNJUNGAN,'DD/MM/YYYY') TGL_KUNJUNGAN,KODE_RTW_KLAIM,
                DIS_FISIK_FUNGSI,
                DIS_FISIK_ANATOMIS,
                GANGGUAN_PSIKIS,
                TIN_MEDIS_OPERATIF,
                TIN_MEDIS_NONOPERATIF,
                REHAB_MEDIS,
                REHAB_VOCATIONAL,
                REHAB_MENTALPSIKIS,
                DIAGNOSA_AWAL,
                DIAGNOSA_AKHIR,
                TENAGA_MEDIS_FASKES,
                KETERANGAN
        from (SELECT ROWNUM as NO_URUT,TGL_KUNJUNGAN,KODE_RTW_KLAIM,
                nvl(DIS_FISIK_FUNGSI,' ')DIS_FISIK_FUNGSI,
                nvl(DIS_FISIK_ANATOMIS,' ') DIS_FISIK_ANATOMIS,
                nvl(GANGGUAN_PSIKIS,' ') GANGGUAN_PSIKIS,
                nvl(TIN_MEDIS_OPERATIF,' ')TIN_MEDIS_OPERATIF,
                nvl(TIN_MEDIS_NONOPERATIF,' ')TIN_MEDIS_NONOPERATIF,
                nvl(REHAB_MEDIS,' ') REHAB_MEDIS,
                nvl(REHAB_VOCATIONAL,' ') REHAB_VOCATIONAL,
                nvl(REHAB_MENTALPSIKIS,' ') REHAB_MENTALPSIKIS,
                nvl(DIAGNOSA_AWAL,' ')DIAGNOSA_AWAL,
                nvl(DIAGNOSA_AKHIR,' ') DIAGNOSA_AKHIR,
                nvl(TENAGA_MEDIS_FASKES,' ')TENAGA_MEDIS_FASKES,
                nvl(KETERANGAN,' ') KETERANGAN
            FROM sijstk.PN_RTW_MONITORING  
            where kode_rtw_klaim='{$ls_kode_rtw}'
             {$order}) 
         where no_urut between ".$start." and ".$end;

    $recordsTotal=0; //echo $sql;
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
}elseif($TYPE=='queryevaluasi'){
    $draw = 1;
    if(isset($_POST['draw'])){
        $draw = $_POST['draw']; 
    }else{
        $draw = 1;       
    }
    
    $start = $_POST['start']+1;
    $length = $_POST['length'];
    $end = ($start-1) + $length;
    $order='';

    if($end==0)  $end=100;
    $sql = "select  NO_URUT,NO_URUT1,to_char(TGL_DIPERBAHARUI,'DD/MM/YYYY') TGL_DIPERBAHARUI,
            KODE_RTW_KLAIM,NO_URUT,KODE_TIPE_EVALUASI,KODE_KUSIONER,KETERANGAN_JAWABAN,
            NAMA_TIPE_EVALUASI,NAMA_KUSIONER
            from (SELECT ROWNUM as NO_URUT1,TGL_DIPERBAHARUI,
            a.KODE_RTW_KLAIM,a.NO_URUT,A.KODE_TIPE_EVALUASI,A.KODE_KUSIONER,KETERANGAN_JAWABAN,
            b.NAMA_TIPE_EVALUASI,c.NAMA_KUSIONER
            from pn_rtw_evaluasi a left outer join PN_RTW_KODE_EVALUASI B on a.KODE_TIPE_EVALUASI=b.KODE_TIPE_EVALUASI
            left outer join PN_RTW_KODE_EVALUASI_DETIL c on a.KODE_KUSIONER=c.KODE_KUSIONER
            {$order}) 
         where no_urut1 between ".$start." and ".$end;

    $recordsTotal=0; //echo $sql;
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
}elseif($TYPE=='queryelampiran'){
    $draw = 1;
    if(isset($_POST['draw'])){
        $draw = $_POST['draw']; 
    }else{
        $draw = 1;       
    }
    
    $start = $_POST['start']+1;
    $length = $_POST['length'];
    $end = ($start-1) + $length;
    $order='';

    if($end==0)  $end=100;
    $sql = "SELECT  A.KODE_RTW_KLAIM,A.NO_URUT,b.keterangan JENIS_DOK,NAMA_FILE,DOC_FILE,TO_CHAR(TGL_REKAM,'DD/MM/YYYY') TGL_REKAM, PETUGAS_REKAM 
    FROM PN_RTW_LAMPIRAN A LEFT OUTER JOIN
    (select KODE,KETERANGAN from sijstk.ms_lookup where tipe='RTWLAMP') B ON A.KODE_JENIS_FILE =B.KODE  
            {$order}) 
         where no_urut1 between ".$start." and ".$end;

    $recordsTotal=0; //echo $sql;
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