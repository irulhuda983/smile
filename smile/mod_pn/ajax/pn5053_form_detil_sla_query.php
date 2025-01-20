<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE				= $_POST["TYPE"];
$KEYWORD 		= $_POST["KEYWORD"];
$KD_KANTOR 	= $_SESSION['kdkantorrole'];
$USER 			= $_SESSION["USER"];
$TGL1 			= $_POST["TGL1"];
$TGL2 			= $_POST["TGL2"];
$KD_KANTOR  = $_POST["KDKTR"];

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
      $order = "ORDER BY AA.JUMLAH_HARI_SLA ";
  }else if($order[0]['column']=='1'){
      $order = "ORDER BY AA.KODE_KANTOR ";
  }else if($order[0]['column']=='2'){
      $order = "ORDER BY AA.NAMA_KANTOR ";
  }
  $order .= $by;

	//penanganan untuk filter data -----------------------------------------------
  if($TYPE != ''){
  	if (($KEYWORD != '') && ($KEYWORD != 'null')) {
  		if (preg_match("/%/i", $KEYWORD)) {
  			$condition .= ' AND AA.'.$TYPE . " LIKE '".$KEYWORD."' ";
  		} else {
  			$condition .= ' AND AA.'.$TYPE . " = '".$KEYWORD."' ";
  		};
  	}
	}

	//query data -----------------------------------------------------------------
	$sql = "SELECT
    *
FROM
    (
        SELECT
            ROWNUM no,
            aa.*
        FROM
            (
                SELECT
                    kode_klaim,
                    kpj,
                    nama_tk,
                    to_char(tgl_awal_sla, 'dd-mm-yyyy') tgl_awal_sla,
                    to_char(tanggal_proses_terakhir, 'dd-mm-yyyy') tanggal_proses_terakhir,
                    CASE
                        WHEN kode_tipe_klaim LIKE 'JHT%' THEN
                            'JHT'
                        WHEN kode_tipe_klaim LIKE 'JKK%' THEN
                            'JKK'
                        WHEN kode_tipe_klaim LIKE 'JKM%' THEN
                            'JKM'
                        WHEN kode_tipe_klaim LIKE 'JPN%' THEN
                            'JPN'
                        WHEN kode_tipe_klaim LIKE 'JHM%' THEN
                            'JHM'
                        WHEN kode_tipe_klaim LIKE 'JKP%' THEN
                            'JKP'
                    END                              AS kode_tipe_klaim,
                    jumlah_hari_sla,
                    CASE
                        WHEN kode_tipe_klaim LIKE 'JHT%' THEN
                            5
                        WHEN kode_tipe_klaim LIKE 'JKK%' THEN
                            7
                        WHEN kode_tipe_klaim LIKE 'JKM%' THEN
                            3
                        WHEN kode_tipe_klaim LIKE 'JPN%' THEN
                            15
                        WHEN kode_tipe_klaim LIKE 'JHM%' THEN
                            3
                        WHEN kode_tipe_klaim LIKE 'JKP%' THEN
                            3
                    END                              AS target_hari,
                    bulan_manfaat_ke,
                    status_klaim,
                    nvl(petugas_ubah, petugas_rekam) AS petugas_akhir
                FROM
                    (
                        SELECT
                            kode_klaim,
                            kpj,
                            nama_tk,
                            kode_tipe_klaim,
                            CASE
                                WHEN a.kode_tipe_klaim NOT LIKE 'JKK%'
                                     AND a.kode_klaim_induk IS NULL THEN
                                         CASE
                                            WHEN a.kode_tipe_klaim LIKE 'JKP%' THEN
                                             (
                                                SELECT 
                                                b.tgl_rekam
                                                FROM siapkerja.sk_klaim_pengajuan@to_ec b
                                                where b.kode_klaim = a.kode_klaim
                                             )
                                            ELSE
                                                tgl_submit_agenda
                                         END 
                                WHEN a.kode_tipe_klaim LIKE 'JKK%'
                                     AND a.kode_klaim_induk IS NULL THEN
                                    tgl_submit_agenda2
                                WHEN a.kode_klaim_induk IS NOT NULL THEN
                                    tgl_submit_penetapan
                            END                      AS tgl_awal_sla,
                            nvl(tgl_ubah, tgl_rekam) tanggal_proses_terakhir,
                            status_klaim,
                            petugas_ubah,
                            petugas_rekam,
                            CASE
                                WHEN a.kode_tipe_klaim LIKE 'JKP%' THEN
                                    (
                                        SELECT
                                            to_char(jkp.bulan_manfaat_ke)
                                        FROM
                                            pn.pn_klaim_manfaat_detil_phkjkp jkp
                                        WHERE
                                            jkp.kode_klaim = a.kode_klaim
                                    )
                                ELSE
                                    '-'
                            END                      AS bulan_manfaat_ke,
                            CASE
                                WHEN a.kode_tipe_klaim LIKE 'JHT%'
                                     AND a.kode_klaim_induk IS NULL THEN
                                    (
                                        CASE
                                            WHEN tgl_submit_agenda IS NULL THEN
                                                NULL
                                            ELSE
                                                ( 5 - ( lk.f_gl_getjml_harikerja(tgl_submit_agenda,
                                                                                 trunc(sysdate)) - 1 ) )
                                        END
                                    )
                                WHEN a.kode_tipe_klaim LIKE 'JHT%'
                                     AND a.kode_klaim_induk IS NOT NULL THEN
                                    (
                                        CASE
                                            WHEN tgl_submit_penetapan IS NULL THEN
                                                NULL
                                            ELSE
                                                ( 5 - ( lk.f_gl_getjml_harikerja(tgl_submit_penetapan,
                                                                                 trunc(sysdate)) - 1 ) )
                                        END
                                    )
                                WHEN a.kode_tipe_klaim LIKE 'JKK%'
                                     AND a.kode_klaim_induk IS NULL THEN
                                    (
                                        CASE
                                            WHEN tgl_submit_agenda2 IS NULL THEN
                                                NULL
                                            ELSE
                                                ( 7 - ( lk.f_gl_getjml_harikerja(tgl_submit_agenda2,
                                                                                 trunc(sysdate)) - 1 ) )
                                        END
                                    )
                                WHEN a.kode_tipe_klaim LIKE 'JKK%'
                                     AND a.kode_klaim_induk IS NOT NULL THEN
                                    (
                                        CASE
                                            WHEN tgl_submit_penetapan IS NULL THEN
                                                NULL
                                            ELSE
                                                ( 7 - ( lk.f_gl_getjml_harikerja(tgl_submit_penetapan,
                                                                                 trunc(sysdate)) - 1 ) )
                                        END
                                    )
                                WHEN ( a.kode_tipe_klaim LIKE 'JKM%'
                                       OR a.kode_tipe_klaim LIKE 'JHM%' )
                                     AND a.kode_klaim_induk IS NULL THEN
                                    (
                                        CASE
                                            WHEN tgl_submit_agenda IS NULL THEN
                                                NULL
                                            ELSE
                                                ( 3 - ( lk.f_gl_getjml_harikerja(tgl_submit_agenda,
                                                                                 trunc(sysdate)) - 1 ) )
                                        END
                                    )
                                WHEN ( a.kode_tipe_klaim LIKE 'JKM%'
                                       OR a.kode_tipe_klaim LIKE 'JHM%' )
                                     AND a.kode_klaim_induk IS NOT NULL THEN
                                    (
                                        CASE
                                            WHEN tgl_submit_penetapan IS NULL THEN
                                                NULL
                                            ELSE
                                                ( 3 - ( lk.f_gl_getjml_harikerja(tgl_submit_penetapan,
                                                                                 trunc(sysdate)) - 1 ) )
                                        END
                                    )
                                WHEN ( a.kode_tipe_klaim LIKE 'JKP%' )
                                     AND a.kode_klaim_induk IS NULL THEN
                                    (
                                        CASE
                                            WHEN tgl_submit_agenda IS NULL THEN
                                                NULL
                                            ELSE
                                                ( 3 - ( lk.f_gl_getjml_harikerja(tgl_submit_agenda,
                                                                                 trunc(sysdate)) - 1 ) )
                                        END
                                    )
                                WHEN ( a.kode_tipe_klaim LIKE 'JKP%' )
                                     AND a.kode_klaim_induk IS NOT NULL THEN
                                    (
                                        CASE
                                            WHEN 
                                            (
                                                SELECT 
                                                b.tgl_rekam
                                                FROM siapkerja.sk_klaim_pengajuan@to_ec b
                                                where b.kode_klaim = a.kode_klaim
                                            ) IS NULL THEN
                                                NULL
                                            ELSE
                                                ( 3 - ( lk.f_gl_getjml_harikerja
                                                                                (
                                                                                    (
                                                                                        SELECT 
                                                                                        b.tgl_rekam
                                                                                        FROM siapkerja.sk_klaim_pengajuan@to_ec b
                                                                                        where b.kode_klaim = a.kode_klaim
                                                                                    ),
                                                                                 trunc(sysdate)
                                                                                 ) - 1 ) )
                                        END
                                    )
                            END                      AS jumlah_hari_sla
                        FROM
                            sijstk.pn_klaim a
                        WHERE
                                status_lunas = 'T'
                            AND status_batal = 'T'
                            AND status_klaim NOT IN ( 'SELESAI', 'BATAL' )
                            AND kode_kantor = '$KD_KANTOR'
                            AND a.tgl_ubah >= (
                                SELECT
                                    trunc(add_months(sysdate, - 12))
                                FROM
                                    dual
                            )
                            AND trunc(a.tgl_ubah) <= trunc(sysdate)
                            AND kode_tipe_klaim NOT LIKE 'JPN%'
                        UNION
                        SELECT
                            kode_klaim,
                            kpj,
                            nama_tk,
                            kode_tipe_klaim,
                            CASE
                                WHEN a.kode_klaim_induk IS NULL THEN
                                    tgl_submit_agenda
                                WHEN a.kode_klaim_induk IS NOT NULL THEN
                                    tgl_submit_penetapan
                            END                      AS tgl_awal_sla,
                            nvl(tgl_ubah, tgl_rekam) tanggal_proses_terakhir,
                            status_klaim,
                            petugas_ubah,
                            petugas_rekam,
                            '-'                      bulan_manfaat_ke,
                            (
                                CASE
                                    WHEN a.kode_klaim_induk IS NULL THEN
                                        (
                                            CASE
                                                WHEN tgl_submit_agenda IS NULL THEN
                                                    NULL
                                                ELSE
                                                    ( 15 - ( lk.f_gl_getjml_harikerja(tgl_submit_agenda,
                                                                                      trunc(sysdate)) - 1 ) )
                                            END
                                        )
                                    WHEN a.kode_klaim_induk IS NOT NULL THEN
                                        (
                                            CASE
                                                WHEN tgl_submit_penetapan IS NULL THEN
                                                    NULL
                                                ELSE
                                                    ( 15 - ( lk.f_gl_getjml_harikerja(tgl_submit_penetapan,
                                                                                      trunc(sysdate)) - 1 ) )
                                            END
                                        )
                                END
                            )                        AS jumlah_hari_sla
                        FROM
                            sijstk.pn_klaim a
                        WHERE
                                status_lunas = 'T'
                            AND status_batal = 'T'
                            AND status_klaim NOT IN ( 'SELESAI', 'BATAL' )
                            AND kode_kantor = '$KD_KANTOR'
                            AND a.tgl_ubah >= (
                                SELECT
                                    trunc(add_months(sysdate, - 12))
                                FROM
                                    dual
                            )
                            AND trunc(a.tgl_ubah) <= trunc(sysdate)
                            AND kode_tipe_klaim LIKE 'JPN%'
                            AND NOT EXISTS (
                                SELECT
                                    NULL
                                FROM
                                    sijstk.pn_klaim_pembayaran_berkala
                                WHERE
                                    kode_klaim = a.kode_klaim
                            )
                    )
                ORDER BY
                    jumlah_hari_sla ASC
            ) aa
        WHERE
            1 = 1 $condition
        ORDER BY
            no
    ) aaa
WHERE
        1 = 1
    AND no BETWEEN $start AND $end";
	// 16-05-2024 Penyesuaian query manajemen rilis (query lama dihapus)
	// 01-04-2024 penyesuaian query untuk CR Monitoring Report SLA Program JKP


	$queryTotalRows = "SELECT count(1) FROM (
                                        select kode_klaim, kpj, nama_tk
                                            from (
                                                select 
                                                    kode_klaim, kpj, nama_tk
                                                    from sijstk.pn_klaim a
                                                    where status_lunas = 'T'
                                                        and status_batal = 'T'
                                                        and status_klaim not in ('SELESAI','BATAL')
                                                        and kode_kantor = '".$KD_KANTOR."'
                                                        and a.tgl_ubah >= (select trunc(add_months(sysdate,-12)) from dual) and trunc(a.tgl_ubah) <= trunc(sysdate)
                                                        and kode_tipe_klaim not like 'JPN%'
                                                    union
                                                    select 
                                                    kode_klaim, kpj, nama_tk
                                                    from sijstk.pn_klaim a
                                                    where status_lunas = 'T'
                                                        and status_batal = 'T'
                                                        and status_klaim not in ('SELESAI','BATAL')
                                                        and kode_kantor = '".$KD_KANTOR."'
                                                        and a.tgl_ubah >= (select trunc(add_months(sysdate,-12)) from dual) and trunc(a.tgl_ubah) <= trunc(sysdate)
                                                        and kode_tipe_klaim like 'JPN%'
                                                        and not exists (select null from sijstk.pn_klaim_pembayaran_berkala where kode_klaim = a.kode_klaim)
                                                )
						) AA WHERE 1=1 ".$condition;
	$recordsTotal = $DB->get_data($queryTotalRows);

  $DB->parse($sql);
  if($DB->execute())
	{
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
  } else
	{
     echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
  }
}
?>
