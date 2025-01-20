<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE	    = $_POST["TYPE"];
$KEYWORD 	= $_POST["KEYWORD"];
// $KD_KANTOR 	= $_SESSION['kdkantorrole'];
$KD_KANTOR  = $_POST['KD_KTR'];
$USER 		= $_SESSION["USER"];
$TGL1 		= $_POST["TGL1"];
$TGL2 		= $_POST["TGL2"];

if($TYPE!=''){

  $draw = 1;
  if(isset($_POST['draw']))
	{
    $draw = $_POST['draw'];
  }else{
    $draw = 1;
  }

  $start  = $_POST['start']+1;
  $length = $_POST['length'];
  $end    = ($start-1) + $length;

  $search = $_POST['search'];

  $condition ="";

  $order = $_POST["order"];
  $by 	 = $order[0]['dir'];

  $sql = "";

  if($order[0]['column']=='0')
	{
      $order = "ORDER BY X.NAMA_KANTOR ";
  }else if($order[0]['column']=='1'){
      $order = "ORDER BY X.JUMLAH_LEWAT_SLA ";
  }else if($order[0]['column']=='2'){
      $order = "ORDER BY X.KODE_KANTOR ";
  }
  $order .= $by;

	//penanganan untuk filter data -----------------------------------------------
  if($TYPE != ''){
  	if (($KEYWORD != '') && ($KEYWORD != 'null')) {
  		if (preg_match("/%/i", $KEYWORD)) {
  			$condition .= ' AND X.'.$TYPE . " LIKE '".$KEYWORD."' ";
  		} else {
  			$condition .= ' AND X.'.$TYPE . " = '".$KEYWORD."' ";
  		};
  	}
	}

	//query data -----------------------------------------------------------------
	$sql = "SELECT * FROM
					(
            SELECT rownum no,a.* FROM
						(
              select
                  x.kode_kantor as kode_kantor,
                  x.nama_kantor as nama_kantor,
                  x.jml_klaim as jumlah_lewat_sla from (
                    select kode_kantor,
                    (select nama_kantor from sijstk.ms_kantor where kode_kantor = xx.kode_kantor) nama_kantor,
                    count(kode_klaim) jml_klaim
                    from(
                      select kode_klaim,kode_kantor
                      from sijstk.pn_klaim a
                      where status_lunas = 'T'
                        and status_batal = 'T'
                        and status_klaim not in ('SELESAI','BATAL')
                        and kode_kantor in (  SELECT KODE_KANTOR
                                              FROM ms.MS_KANTOR
                                              WHERE     AKTIF = 'Y'
                                                    AND STATUS_ONLINE = 'Y'
                                                    AND KODE_TIPE NOT IN ('1', '2')
                                        START WITH KODE_KANTOR = '".$KD_KANTOR."'
                                        CONNECT BY PRIOR KODE_KANTOR = KODE_KANTOR_INDUK)
                        and a.tgl_ubah >= (select trunc(add_months(sysdate,-12)) from dual) and trunc(a.tgl_ubah) <= trunc(sysdate)
                        and kode_tipe_klaim not like 'JPN%'
                      union
                        select kode_klaim,kode_kantor
                        from sijstk.pn_klaim a
                        where status_lunas = 'T'
                            and status_batal = 'T'
                            and status_klaim not in ('SELESAI','BATAL')
                            and kode_kantor in (  SELECT KODE_KANTOR
                                                  FROM ms.MS_KANTOR
                                                  WHERE     AKTIF = 'Y'
                                                        AND STATUS_ONLINE = 'Y'
                                                        AND KODE_TIPE NOT IN ('1', '2')
                                            START WITH KODE_KANTOR = '".$KD_KANTOR."'
                                            CONNECT BY PRIOR KODE_KANTOR = KODE_KANTOR_INDUK)
                            and a.tgl_ubah >= (select trunc(add_months(sysdate,-12)) from dual) and trunc(a.tgl_ubah) <= trunc(sysdate)
                            and kode_tipe_klaim like 'JPN%'
                            and not exists (select null from sijstk.pn_klaim_pembayaran_berkala where kode_klaim = a.kode_klaim)
                    )xx
                group by kode_kantor
              ) X WHERE 1=1 ".$condition." ".$order."
						) A
					) A
					WHERE 1 = 1 AND NO BETWEEN ".$start." and ".$end." ";

	$queryTotalRows = "SELECT count(1) FROM
										(
                        select rownum as no, x.kode_kantor, x.jml_klaim from (
                          select kode_kantor,
                          count(kode_klaim) jml_klaim
                          from(
                            select kode_klaim,kode_kantor
                            from sijstk.pn_klaim a
                            where status_lunas = 'T'
                              and status_batal = 'T'
                              and status_klaim not in ('SELESAI','BATAL')
                              and kode_kantor in (  SELECT KODE_KANTOR
                                                    FROM ms.MS_KANTOR
                                                    WHERE     AKTIF = 'Y'
                                                          AND STATUS_ONLINE = 'Y'
                                                          AND KODE_TIPE NOT IN ('1', '2')
                                              START WITH KODE_KANTOR = '".$KD_KANTOR."'
                                              CONNECT BY PRIOR KODE_KANTOR = KODE_KANTOR_INDUK)
                              and a.tgl_ubah >= (select trunc(add_months(sysdate,-12)) from dual) and trunc(a.tgl_ubah) <= trunc(sysdate)
                              and kode_tipe_klaim not like 'JPN%'
                            union
                              select kode_klaim,kode_kantor
                              from sijstk.pn_klaim a
                              where status_lunas = 'T'
                                  and status_batal = 'T'
                                  and status_klaim not in ('SELESAI','BATAL')
                                  and kode_kantor in (  SELECT KODE_KANTOR
                                                        FROM ms.MS_KANTOR
                                                        WHERE     AKTIF = 'Y'
                                                              AND STATUS_ONLINE = 'Y'
                                                              AND KODE_TIPE NOT IN ('1', '2')
                                                  START WITH KODE_KANTOR = '".$KD_KANTOR."'
                                                  CONNECT BY PRIOR KODE_KANTOR = KODE_KANTOR_INDUK)
                                  and a.tgl_ubah >= (select trunc(add_months(sysdate,-12)) from dual) and trunc(a.tgl_ubah) <= trunc(sysdate)
                                  and kode_tipe_klaim like 'JPN%'
                                  and not exists (select null from sijstk.pn_klaim_pembayaran_berkala where kode_klaim = a.kode_klaim)
                          )xx
                          group by kode_kantor
                      ) x WHERE 1=1 ".$condition."
										) A ";

    $recordsTotal = $DB->get_data($queryTotalRows);
    $recordsTotal = $recordsTotal;

  $DB->parse($sql);
  if($DB->execute())
	{
    $i = 0;
    while($data = $DB->nextrow())
    {
        $data['ACTION'] = '<input type="button" class="btn green" id="edit_btn" name="edit_btn" value="Detil" onClick="location.replace(\'../../mod_pn/form/pn5053_form_detil_sla.php?kdktr='.$data['KODE_KANTOR'].'&tgl1='.$TGL1.'&tgl2='.$TGL2.'\')">';
				$jsondata .= json_encode($data);
        $jsondata .= ',';
        $i++;
    }
    $jsondataStart = '{"draw":'.$draw.',"recordsTotal":'.$recordsTotal.',"recordsFiltered":'.$recordsTotal.',"data":[';
    $jsondata .= ']}';
    $jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
    echo $jsondata;
  } else
	{
     echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
  }
}
?>
