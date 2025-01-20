<?
session_start();
include_once "../../includes/conf_global.php";
include_once "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "SMILE";
$gs_pagetitle = "DAFTAR PENERIMA BEASISWA";

$p	= $_POST['vp'];
$a	= $_POST['va'];
$b	= $_POST['vb'];
$c	= $_POST['vc'];
$d	= $_POST['vd'];
$e	= $_POST['ve'];
$f	= $_POST['vf'];
$g	= $_POST['vg'];
$h	= $_POST['vh'];
$j	= $_POST['vj'];
$k	= $_POST['vk'];
$l	= $_POST['vl'];
$m	= $_POST['vm'];
$n	= $_POST['vn'];
$q	= $_POST['vq'];
$r	= $_POST['vr'];
$s	= $_POST['vs'];
$t	= $_POST['vt'];
$kode_klaim	= $_POST['kode_klaim'];
$pilihsearch = $_POST['pilihsearch'];
$searchtxt 	 = $_POST['searchtxt'];	
	  
if ($a=="")
{
  $p	= $_GET['p'];
  $a	= $_GET['a'];
  $b	= $_GET['b'];
  $c	= $_GET['c'];
  $d	= $_GET['d'];
  $e	= $_GET['e'];
  $f	= $_GET['f'];
  $g	= $_GET['g'];
  $h	= $_GET['h'];
	$j	= $_GET['j'];
	$k	= $_GET['k'];
	$l	= $_GET['l'];
	$m	= $_GET['m'];
	$n	= $_GET['n'];
	$q	= $_GET['q'];	
	$r	= $_GET['r'];
	$s	= $_GET['s'];
	$t	= $_GET['t'];
    $kode_klaim	= $_GET['kode_klaim'];
	$pilihsearch = $_GET['pilihsearch'];
	$searchtxt 	 = $_GET['searchtxt'];	
}

$formname=(!$a) ? "adminForm" : $a;	
$fieldnameb=(!$b) ? "porto_id" : $b;	
$fieldnamec=(!$c) ? "filc" : $c;	
$fieldnamed=(!$d) ? "fild" : $d;
$fieldnamee=(!$e) ? "file" : $e;
$fieldnamef=(!$f) ? "filf" : $f;
$fieldnameg=(!$g) ? "filg" : $g;
$fieldnameh=(!$h) ? "filh" : $h;
$fieldnamej=(!$j) ? "filj" : $j;
$fieldnamek=(!$k) ? "filk" : $k;
$fieldnamel=(!$l) ? "fill" : $l;
$fieldnamem=(!$m) ? "film" : $m;
$fieldnamen=(!$n) ? "filn" : $n;
$fieldnameq=(!$q) ? "filq" : $q;	
$fieldnamer=(!$r) ? "filr" : $r;	
$fieldnames=(!$s) ? "fils" : $s;
$fieldnamet=(!$t) ? "filt" : $t;
$px=(!$p) ? "inv2301_entrydealingticket_deposito.php" : $p;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?=$pagetitle;?></title>
<meta name="Author" content="JroBalian" />
<link rel="stylesheet" type="text/css" href="<?="http://$HTTP_HOST";?>/style/style.css" />
<script type="text/javascript" language="JavaScript" src="../../javascript/iframe.js"></script>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/common.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<script type="text/javascript"></script>
</head>
<body>
<form action="<?=$PHP_SELF;?>" method="post" id="lov_inv_porto" name="lov_inv_porto">

    <table class="captionform">
    <caption>Daftar Penerima Beasiswa</caption>
      <tr><th colspan="3"></th></tr>
      <style>
        #header-caption2 {position:absolute;top:0;left:0;width:98%;height:27px;background:-webkit-linear-gradient(left,#6ba5ff,#416fd6);z-index: 300;text-align: left;}
        #header-caption2 h3 {font-size: 14px;color: #ffffff;margin: 6px 1px 1px 1px;height:23px;border-bottom:1px solid #6997ff;padding-left:1px;border-top-right-radius:1px;border-top-left-radius:1px;}
      </style>
      <tr>
				<td id="header-caption2" colspan="2"><strong><h3><?=$gs_pagetitle;?></strong>&nbsp;<strong>(ANAK DARI <?=$d;?> - NIK <?=$c;?>)</strong></h3></td>
			</tr>	
      <tr><td colspan="3"></br></br></td></tr>
  	</table>
		
    <table class="caption">
    <caption>Daftar Penerima Beasiswa</caption>
      <tr><th colspan="2"></th></tr>
      <tr> 
        <td colspan="2">			
        </td>
        <td align="right">Search By &nbsp
          <select name="pilihsearch" style="width:150px;">
            <? 
            switch($pilihsearch)
            {
							case 'sc_nik_penerima' : $sel3="selected"; break;
            }
            ?>
						<option value="sc_nik_penerima" <?=$sel3;?>>NIK Anak</option> 
          </select>
          <input type="text" name="searchtxt" value="<?=$searchtxt;?>" style="background-color: #ccffff;width:150px;">
          <input type="submit" name="cari2" value="GO">
          <input type="hidden" id="vp" name="vp" value="<?=$p;?>">
          <input type="hidden" id="va" name="va" value="<?=$a;?>">
          <input type="hidden" id="vb" name="vb" value="<?=$b;?>">
          <input type="hidden" id="vc" name="vc" value="<?=$c;?>">   	
          <input type="hidden" id="vd" name="vd" value="<?=$d;?>">
          <input type="hidden" id="ve" name="ve" value="<?=$e;?>">
          <input type="hidden" id="vf" name="vf" value="<?=$f;?>">
          <input type="hidden" id="vg" name="vg" value="<?=$g;?>">
          <input type="hidden" id="vh" name="vh" value="<?=$h;?>">	
          <input type="hidden" id="vj" name="vj" value="<?=$j;?>">
          <input type="hidden" id="vk" name="vk" value="<?=$k;?>">
          <input type="hidden" id="vl" name="vl" value="<?=$l;?>">	
          <input type="hidden" id="vm" name="vm" value="<?=$m;?>">
          <input type="hidden" id="vn" name="vn" value="<?=$n;?>">	
          <input type="hidden" id="vq" name="vq" value="<?=$q;?>">	
					<input type="hidden" id="vr" name="vr" value="<?=$r;?>">
					<input type="hidden" id="vs" name="vs" value="<?=$s;?>">
					<input type="hidden" id="vt" name="vt" value="<?=$t;?>">	
					<input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$kode_klaim;?>">	
        </td>
      </tr>
    </table>
				
		<?
		//------------------------- start list data --------------------------------
		$ln_rows_per_page = 10;
    $ls_lov_url = 'pn5061_lov_nikpenerimabeasiswa.php'; // url sama dengan nama file		
    
    //penanganan untuk filter data -------------------------------------------				
    $ls_colname  = "";
    $ls_colval 	 = "";	
    
		if ($searchtxt!="")
		{
      if ($pilihsearch=="sc_nik_penerima")
      {
        $ls_colname  = "NIK_PENERIMA";
        $ls_colval 	 = $searchtxt;
      }
		}
		
    //get data from WS -------------------------------------------------------
    global $wsIp;
    $ipDev  = $wsIp."/JSPN5041/LovNikPenerimaBeasiswa";
    $url    = $ipDev;
    $chId   = 'SMILE';
    $gs_username	= $_SESSION["USER"];
		
    // set HTTP header -------------------------------------------------------
    $headers = array(
      'Content-Type'=> 'application/json',
      'X-Forwarded-For'=> $ipfwd,
    );
    
    // set POST params -------------------------------------------------------	
    $ln_page_ke = $_GET['page'];
    if ( !isset($_GET['page']) || !preg_match('/^[0-9]+$/',$_GET['page']) || $_GET['page'] < 1 ) 
    {
     	$ln_page_ke = 1;
    }else if ($_GET['page']=="")
    {
     	$ln_page_ke = 1;
    }
    $ln_page_ke = $ln_page_ke + 0;
    
		$ls_nik_tk = $c; 
		 
		$data = array(
      'chId'=>$chId,
      'reqId'=>$gs_username,
			'PAGE'=>$ln_page_ke,
      'NROWS'=>$ln_rows_per_page,
			'NIK_TK'=>$ls_nik_tk,
      'C_COLNAME'=>$ls_colname,
      'C_COLVAL'=>$ls_colval,
      'C_COLNAME2'=>"",
      'C_COLVAL2'=>"",
			'O_COLNAME'=>"",
      'O_MODE'=>"ASC"
    );

    // Open connection -------------------------------------------------------
    $ch = curl_init();
    
    // Set the url, number of POST vars, POST data ---------------------------
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
    // Execute post ----------------------------------------------------------
    $result = curl_exec($ch);
    $resultArray = json_decode(utf8_encode($result));
		
		$total_rows  = $resultArray->TOTAL_REC;
		$total_pages = f_total_pages($total_rows, $ln_rows_per_page);
    $othervar		 = "&mid=".$mid."&searchtxt=".$searchtxt."&pilihsearch=".$pilihsearch;
    if ( !isset($_GET['page']) || !preg_match('/^[0-9]+$/',$_GET['page']) || $_GET['page'] < 1 ) {
    $_GET['page'] = 1;
    } else if ( $_GET['page'] > $total_pages ) {
    $_GET['page'] = $total_pages;
    }
    $start_row = f_page_to_row($_GET['page'], $ln_rows_per_page);
    $jmlrow		 = $ln_rows_per_page;
    ?>
    <?
    echo "<table  id=mydata cellspacing=0>";
    echo "<tr>";
    echo "<th class=awal>&nbsp;<a href=\"$PHP_SELF?f=a.kode_tk&o=$o\"><strong>NIK Anak</strong></a></th>";
		echo "<th><a href=\"$PHP_SELF?f=a.nama_tk&o=$o\"><strong>Nama Anak</strong></a></th>";    
    echo "<th><a href=\"$PHP_SELF?f=a.kode_klaim&o=$o\"><strong>Tempat & Tgl Lahir</strong></a></th>";
		echo "<th><a href=\"$PHP_SELF?f=a.kpj&o=$o\"><strong>Jenis Kelamin</strong></a></th>";
    echo "<th><a href=\"$PHP_SELF?f=a.kode_klaim&o=$o\"><strong>Nama Ortu/Wali</strong></a></th>";
    echo "</tr>";
    
		$i=0;
    $nx=1;

    // $sql_jml_max = "SELECT X.TARIF_MAKSIMUM
    //                     FROM PN.PN_TARIF_MANFAAT X
    //                 WHERE     X.KODE_TARIF =
    //                                 (SELECT CASE
    //                                         WHEN B.KODE_TIPE_KLAIM = 'JKK01'
    //                                         THEN
    //                                             'BEASISWA_JKK_KULIAH_MAXTAHUN'
    //                                         WHEN B.KODE_TIPE_KLAIM = 'JKM01'
    //                                         THEN
    //                                             'BEASISWA_JKM_KULIAH_MAXTAHUN'
    //                                         END
    //                                         KODE_TARIF
    //                                 FROM PN.PN_KLAIM B
    //                                 WHERE B.KODE_KLAIM = '$kode_klaim')
    //                         AND (SELECT (SELECT C.KEYWORD
    //                                         FROM PN.PN_KODE_SEBAB_KLAIM C
    //                                     WHERE C.KODE_SEBAB_KLAIM = B.KODE_SEBAB_KLAIM)
    //                                 FROM PN.PN_KLAIM B
    //                             WHERE B.KODE_KLAIM = '$kode_klaim') LIKE
    //                                 X.KODE_PERLINDUNGAN || '%'
    //                         AND KODE_SEGMEN = 'TKI'";
    // $DB->parse($sql_jml_max);
    // $DB->execute();
    // $row_max = $DB->nextrow();
    // $jml_max = $row_max["TARIF_MAKSIMUM"];
    // $ls_block_penerima = '';

    for($i=0;$i<ExtendedFunction::count($resultArray->DATA);$i++)
    {
        $nik_penerima = $resultArray->DATA[$i]->NIK_PENERIMA;

        // $sql_query = "SELECT COUNT (1) jml_thn_kuliah
        //             FROM pn.pn_klaim_manfaat_detil_beasis a
        //             WHERE     kode_manfaat = '2'
        //                 AND NVL (nom_manfaat, 0) <> 0
        //                 AND no_urut = (SELECT b.no_urut
        //                                     FROM PN.PN_KLAIM_MANFAAT_DETIL b
        //                                 WHERE b.beasiswa_nik_penerima = '$nik_penerima' AND rownum = 1)
        //                 AND ((jenjang = 'KULIAH' AND JENIS = 'BEASISWA') OR JENIS = 'PELATIHAN')
        //                 AND kode_klaim IN
        //                         (    SELECT kode_klaim
        //                                 FROM sijstk.pn_klaim
        //                         START WITH     kode_klaim = '$kode_klaim'
        //                                     AND NVL (status_batal, 'T') = 'T'
        //                         CONNECT BY     PRIOR kode_klaim_induk = kode_klaim
        //                                     AND NVL (status_batal, 'T') = 'T')";

        // $DB->parse($sql_query);
        // $DB->execute();
        // $row = $DB->nextrow();
        // $ls_jml_thn_kuliah =  $row["JML_THN_KULIAH"];

        // if($ls_jml_thn_kuliah >= $jml_max){
        //     $ls_block_penerima = 'color:red;';
        //     echo "<tr bgcolor=#".($nx%2 ? "f3f3f3" : "ffffff")." style = 'cursor: pointer;".$ls_block_penerima."' >";
        // } else {
            echo "<tr bgcolor=#".($nx%2 ? "f3f3f3" : "ffffff")." onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$resultArray->DATA[$i]->NIK_PENERIMA)."';".							
              "window.opener.document.getElementById('".$fieldnameb."').focus(); ".
              "window.opener.fjq_ajax_val_getprofilebynikpenerima(); ".
              "window.close();\" style = \"cursor: pointer;\" >";	
        // }			
					?>
					<td><?=$resultArray->DATA[$i]->NIK_PENERIMA;?></td>
					<td><?=$resultArray->DATA[$i]->NAMA_PENERIMA;?></td>
  				<td>
							<?
							$ls_tempat_tgllahir = $resultArray->DATA[$i]->TEMPAT_LAHIR.", ".$resultArray->DATA[$i]->TGL_LAHIR;
							?>
							<?=$ls_tempat_tgllahir;?>
					</td>
  				<td><?=$resultArray->DATA[$i]->NM_JENIS_KELAMIN;?></td>
					<td><?=$resultArray->DATA[$i]->NAMA_ORTU_WALI;?></td>																																																						
        </tr>
    <? 
    $nx++;
    }
    
    if ($i == 0) {
      echo '<tr bgcolor="'.($i%2 ? "#f3f3f3" : "#ffffff").'" style="height: 26px;">';
      echo '<td colspan="5" style="text-align:center">-- Data Tidak Ditemukan --</td>';
      echo '</tr>';
    }		 
    ?>
    </table>	
		
    <table class="paging" aria-describedby="paging">
      <tr><th></th></tr>
      <tr>	
        <td>Total Rows <strong><?=$total_rows; ?></strong> | Total Pages <strong><?=$total_pages; ?></strong></td>							
        <td height="15" align="right">
        <?$othervar = $othervar."&p=".$p."&a=".$a."&b=".$b."&c=".$c."&d=".$d."&e=".$e."&f=".$f."&g=".$g."&h=".$h."&j=".$j."&k=".$k."&l=".$l."&m=".$m."&n=".$n."&q=".$q."&r=".$r."&s=".$s."&t=".$t."&rg_kategori=".$ls_rg_kategori."";?>
        <strong>Page :</strong> <?php echo f_draw_pager($ls_lov_url, $total_pages, $_GET['page'],$othervar); ?>
        </td>			
      </tr>		
    </table>		
    <?
    //---------------------------------------- end list data -------------------------
    ?>
		
		</br>
		
    <div style="background: #F2F2F2;margin-top:2px;padding:10px 20px;border:1px solid #ececec;text-align:left;">
      <span style="background: #FF0; border: 1px solid #CCC;"><em><strong>Keterangan:</strong></em></span>
      <ul>
      <li style="margin-left:15px;">Pencarian menggunakan <span style="color:#ff0000;">NIK Peserta</span>, harap mengisikan nomor identitas LENGKAP.</li>	
      <li style="margin-left:15px;">Klik Tombol GO untuk memulai pencarian data</li>
			<li style="margin-left:15px;">Pilih salah satu apabila TK pernah bekerja di beberapa NPP.</li>
      </ul>
		</div>
				
	<div id="clear-bottom-popup"></div>
</div> 

<div id="footer-popup">
  <p class="lft"></p>
  <p class="rgt">SMILE</p>
</div>
</form>
</body>
</html>
