<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "SIJSTK";
$gs_pagetitle = "Daftar Upah Terakhir TK";

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
$px=(!$p) ? "inv2301_entrydealingticket_deposito.php" : $p;

if($_POST['btntarik'])
{
  //tarik ulang data upah ------------------------------------------------------
  $qry = "BEGIN SIJSTK.P_PN_PN5040.X_INSERT_TKGABUNG('".$h."','$username',:p_sukses,:p_mess);END;";											 	
  $proc = $DB->parse($qry);				
  oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
  oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
  $DB->execute();				
  $ls_sukses = $p_sukses;	
  $ls_mess = $p_mess;
  
  $msg = "Ambil ulang data upah selesai, session dilanjutkan..";			
}	
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
<div id="header-popup">	
<h3><?=$gs_pagetitle;?></h3>
</div>
<div id="container-popup">
<!--[if lte IE 6]>
<div id="clearie6"></div>
<![endif]-->
    <table class="caption">
      <tr> 
        <td colspan="2">
      		&nbsp;				
        </td>
        <td align="right">Search By &nbsp
          <select name="pilihsearch">
            <? 
            switch($pilihsearch)
            {
          		case 'sc_kode' : $sel1="selected"; break; 		
            }
            ?>
        		<option value="sc_all">--ALL--</option>
        		<option value="sc_kode" <?=$sel1;?>>Tgl Bayar</option>
	   
          </select>
          <input type="text" name="searchtxt" value="<?=$searchtxt;?>" size="15" style="background-color: #ccffff;">
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
        </td>
      </tr>
    </table>
				
		<?
		//------------------------- start list data --------------------------------
  	//filter data		
 		if(isset($searchtxt))
		{
      $searchtxt    = strtoupper($searchtxt);
      if ($pilihsearch=="sc_kode")
      {
       $filtersearch = "and to_date(a.tgl_bayar,'dd/mm/yyyy') like '%".$searchtxt."%' ";
      }			                		 
      else
      {
       $filtersearch = "and (to_date(a.tgl_bayar,'dd/mm/yyyy') like '%".$searchtxt."%') ";
      }
		}		
    // query with paging								
    $rows_per_page = 12;
    $url = 'pn5041_lovtapjkk1_upahterakhir.php'; // url sama dengan nama file				
    //The unfiltered SELECT
		//Cek apakah adata data di pn_klaim_tkupah yg tgl_bayarnya sblm tgl kecelakaan
		//jika tidak ada maka cek ke PN_AGENDA_KOREKSI_KLAIM_UPAH yg status_approvalnya Y dan tidak batal, jika ada maka data upah dapat ditampilkan
		
		$sql1 = "select count(*) as v_cnt from sijstk.pn_klaim_tkupah a ".
						"where a.kode_klaim='$h' ".
						$filtersearch.
						"and ".
						"(  ".
  					"	(to_char(a.tgl_bayar,'yyyymmdd') <= to_char(to_date('$g','dd/mm/yyyy'),'yyyymmdd')) ".
  					"	or ".
  					"	(to_char(a.tgl_rekon,'yyyymmdd') <= to_char(to_date('$g','dd/mm/yyyy'),'yyyymmdd')) ".
						")";
		$DB->parse($sql1);
    $DB->execute();
    $row = $DB->nextrow();
    $ln_cnt = $row["V_CNT"];
		if ($ln_cnt==""){$ln_cnt=="0";}
		
		if ($ln_cnt=="0")
		{
		 	 //cek ke PN_AGENDA_KOREKSI_KLAIM_UPAH yg status_approvalnya Y dan tidak batal
  		$sql2 = "select count(*) as v_cnt2 from sijstk.pn_agenda_koreksi_klaim_upah a ".
  						"where a.kode_klaim='$h' ".
							"and nvl(a.status_approval,'T')='Y' ".
							"and nvl(a.status_batal,'T')='T' ";
  		$DB->parse($sql2);
      $DB->execute();
      $row = $DB->nextrow();
      $ln_cnt2 = $row["V_CNT2"];		
			if ($ln_cnt2==""){$ln_cnt2=="0";}
			
			if ($ln_cnt2=="0")
			{
			 	 //query tetap dibatasi ------------------------------------------------
        $sql = "select ".
               "     a.kode_perusahaan, (select nama_perusahaan from sijstk.kn_perusahaan where kode_perusahaan = a.kode_perusahaan) nama_perusahaan, ".
               "     (select npp from sijstk.kn_perusahaan where kode_perusahaan = a.kode_perusahaan) npp, ".
               "     a.kode_divisi, a.kode_segmen, a.kode_tk, to_char(a.blth,'dd/mm/yyyy') blth, to_char(a.blth,'mm/yyyy') blth_mm, to_char(a.tgl_bayar,'dd/mm/yyyy') tgl_bayar, ".
    					 "		 to_char(a.tgl_rekon,'dd/mm/yyyy') tgl_siap_rekon, a.nom_upah, ".
    					 "		 case when ((to_char(a.tgl_bayar,'yyyymmdd') <= to_char(to_date('$g','dd/mm/yyyy'),'yyyymmdd')) or (to_char(a.tgl_rekon,'yyyymmdd') <= to_char(to_date('$g','dd/mm/yyyy'),'yyyymmdd'))) then 'Y' else 'T' end flag_bisa_dipilih, ".
               "		 case when a.kode_segmen = 'PU' then ". //Penambahan smile berulang 29-11-2019, 28/12/2019
							 "		 (	select to_char(tgl_rekon,'dd/mm/yyyy') from sijstk.kn_iuran_perusahaan ".
							 "				where kode_perusahaan=a.kode_perusahaan ".
							 "				and kode_divisi = a.kode_divisi ".
							 "				and kode_segmen = a.kode_segmen ".
							 "				and blth=a.blth ".
							 "				and nvl(status_rekon,'T')='Y' ".
							 "				and rownum=1 ".
							 "				) ".
							 "		 else ".
							 "		 		to_char(a.tgl_rekon,'dd/mm/yyyy') ".
							 "		 end tgl_rekon ".
               "from sijstk.pn_klaim_tkupah a ".
               "where a.kode_klaim='$h' ".
    					 $filtersearch.
               "order by a.blth desc ";					 
			}else
			{
			 	//bypass tgl bayar -----------------------------------------------------
        $sql = "select ".
               "     a.kode_perusahaan, (select nama_perusahaan from sijstk.kn_perusahaan where kode_perusahaan = a.kode_perusahaan) nama_perusahaan, ".
               "     (select npp from sijstk.kn_perusahaan where kode_perusahaan = a.kode_perusahaan) npp, ".
               "     a.kode_divisi, a.kode_segmen, a.kode_tk, to_char(a.blth,'dd/mm/yyyy') blth, to_char(a.blth,'mm/yyyy') blth_mm, to_char(a.tgl_bayar,'dd/mm/yyyy') tgl_bayar, ".
    					 "		 to_char(a.tgl_rekon,'dd/mm/yyyy') tgl_rekon, a.nom_upah, ".
    					 "		 'Y' flag_bisa_dipilih ".
               "from sijstk.pn_klaim_tkupah a ".
               "where 1=1 and a.kode_klaim='$h' ".
    					 $filtersearch.
               "order by a.blth desc ";					
			}  
		}else
		{
		 	//query tetap dibatasi ------------------------------------------------
      $sql = "select ".
             "     a.kode_perusahaan, (select nama_perusahaan from sijstk.kn_perusahaan where kode_perusahaan = a.kode_perusahaan) nama_perusahaan, ".
             "     (select npp from sijstk.kn_perusahaan where kode_perusahaan = a.kode_perusahaan) npp, ".
             "     a.kode_divisi, a.kode_segmen, a.kode_tk, to_char(a.blth,'dd/mm/yyyy') blth, to_char(a.blth,'mm/yyyy') blth_mm, to_char(a.tgl_bayar,'dd/mm/yyyy') tgl_bayar, ".
  					 "		 to_char(a.tgl_rekon,'dd/mm/yyyy') tgl_siap_rekon, a.nom_upah, ".
  					 "		 case when ((to_char(a.tgl_bayar,'yyyymmdd') <= to_char(to_date('$g','dd/mm/yyyy'),'yyyymmdd')) or (to_char(a.tgl_rekon,'yyyymmdd') <= to_char(to_date('$g','dd/mm/yyyy'),'yyyymmdd'))) then 'Y' else 'T' end flag_bisa_dipilih, ".
             "		 case when a.kode_segmen = 'PU' then ". //Penambahan smile berulang 29-11-2019, 28/12/2019
             "		 (	select to_char(tgl_rekon,'dd/mm/yyyy') from sijstk.kn_iuran_perusahaan ".
             "				where kode_perusahaan=a.kode_perusahaan ".
             "				and kode_divisi = a.kode_divisi ".
             "				and kode_segmen = a.kode_segmen ".
             "				and blth=a.blth ".
             "				and nvl(status_rekon,'T')='Y' ".
             "				and rownum=1 ".
             "				) ".
             "		 else ".
             "		 		to_char(a.tgl_rekon,'dd/mm/yyyy') ".
             "		 end tgl_rekon ".
             "from sijstk.pn_klaim_tkupah a ".
             "where a.kode_klaim='$h' ".
  					 $filtersearch.
             "order by a.blth desc ";		
		}		
    //echo $sql;
    $total_rows  = f_count_rows($DB,$sql);
    $total_pages = f_total_pages($total_rows, $rows_per_page);
    $othervar		 = "&mid=".$mid."&searchtxt=".$searchtxt."&pilihsearch=".$pilihsearch;
    if ( !isset($_GET['page']) || !preg_match('/^[0-9]+$/',$_GET['page']) || $_GET['page'] < 1 ) {
    $_GET['page'] = 1;
    } else if ( $_GET['page'] > $total_pages ) {
    $_GET['page'] = $total_pages;
    }
    $start_row = f_page_to_row($_GET['page'], $rows_per_page);
    $jmlrow		 = $rows_per_page;
    ?>
    <?
    echo "<table  id=mydata cellspacing=0>";
    echo "<tr>";
    echo "<th class=awal>&nbsp;<a href=\"$PHP_SELF?f=a.kode&o=$o\"><b>NPP</b></a></th>";    
    echo "<th style=text-align:center;><a href=\"$PHP_SELF?f=a.keterangan&o=$o\"><b>Perusahaan</b></a></th>";
		echo "<th style=text-align:center;><a href=\"$PHP_SELF?f=a.keterangan&o=$o\"><b>Unit</b></a></th>";
		echo "<th style=text-align:center;><a href=\"$PHP_SELF?f=a.keterangan&o=$o\"><b>Blth</b></a></th>";
		echo "<th style=text-align:center;><a href=\"$PHP_SELF?f=a.keterangan&o=$o\"><b>Upah</b></a></th>";
		echo "<th style=text-align:center;><a href=\"$PHP_SELF?f=a.keterangan&o=$o\"><b>Tgl Siap Posting</b></a></th>";
    echo "<th style=text-align:center;><a href=\"$PHP_SELF?f=a.keterangan&o=$o\"><b>Tgl Bayar</b></a></th>";
    echo "<th style=text-align:center;><a href=\"$PHP_SELF?f=a.keterangan&o=$o\"><b>Tgl Posting</b></a></th>";
    echo "</tr>";
    
    $sql = f_query_perpage($sql, $start_row, $rows_per_page);
    $DB->parse($sql);
    $DB->execute();
    $i=0;
    $nx=1;
		$nbisapilih=0;
    while ($row = $DB->nextrow())
    {
					if ($row["FLAG_BISA_DIPILIH"] == "Y")
					{
					 	 $nbisapilih++;
					}
					
					if ($nbisapilih<=2 && $row["FLAG_BISA_DIPILIH"] == "Y")
					{
  					echo "<tr bgcolor=#".($nx%2 ? "f3f3f3" : "ffffff")." onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["NOM_UPAH"])."';".
                  "window.opener.document.".$formname.".".$fieldnamej.".value='".ExtendedFunction::ereg_replace("'","\'",$row["BLTH"])."'; ".  
          				"window.opener.document.getElementById('".$fieldnameb."').focus(); ".
    							"window.close();\" style = \"cursor: pointer;\" >";
					}else
					{
					 	echo "<tr bgcolor=#".($nx%2 ? "f3f3f3" : "ffffff")." >";
					}
					?>
  				<td style="text-align:center;"><?=$row["NPP"];?></td>
  				<td style="text-align:left;"><?=$row["NAMA_PERUSAHAAN"];?></td>
  				<td style="text-align:center;"><?=$row["KODE_DIVISI"];?></td>
					<td style="text-align:center;"><?=$row["BLTH_MM"];?></td>
					<td style="text-align:right;"><?=number_format((float)$row["NOM_UPAH"],2,".",",");?></td>
					<td style="text-align:center;"><?=$row["TGL_SIAP_REKON"];?></td>
          <td style="text-align:center;">
							<?
							if ($nx<=2 && $row["FLAG_BISA_DIPILIH"] == "T")
							{
							 	?>
								<font color="#ff0000"><?=$row["TGL_BAYAR"];?> &nbsp;(*)</font>
								<?
							}else
							{
							 	?>
								<?=$row["TGL_BAYAR"];?>
								<?	 
							}
							?>
					</td>
          
					<td style="text-align:center;">
							<?
							if ($nx<=2 && $row["FLAG_BISA_DIPILIH"] == "T")
							{
							 	?>
								<font color="#ff0000"><?=$row["TGL_REKON"];?> &nbsp;(*)</font>
								<?
							}else
							{
							 	?>
								<?=$row["TGL_REKON"];?>
								<?	 
							}
							?>
					</td>
          
					<!--					
					<td style="text-align:center;"><?=$row["TGL_BAYAR"];?></td>
					<td style="text-align:center;"><?=$row["TGL_REKON"];?></td>	
					-->																																																				
        </tr>		
    <? 
    $i++; $nx++;
    }
    
    ?>
    </table>	
    
    <table class="paging">					
      <tr>
        <td>Total Rows <b><?=$total_rows; ?></b> | Total Pages <b><?=$total_pages; ?></b></td>		
        <td height="15" align="right">
        <?$othervar = $othervar."&p=".$p."&a=".$a."&b=".$b."&c=".$c."&d=".$d."&e=".$e."&f=".$f."&g=".$g."&h=".$h."&j=".$j."&k=".$k."&l=".$l."&m=".$m."&n=".$n."&q=".$q."";?>
        <b>Page :</b> <?php echo f_draw_pager($url, $total_pages, $_GET['page'],$othervar); ?>
        </td>
      </tr>

      <tr>
				<td>
					<i>Tgl Bayar/Posting bertanda <font color="#ff0000">(*)</font> menandakan bahwa iuan dibayar/diposting setelah tgl kecelakaan <?=$g;?> sehingga tidak valid untuk digunakan sebagai dasar upah</i>
				</td>	
      </tr>			
    </table>

    <div class="clear5"></div>
    <div id="buttonbox">				
    	<input type="submit" class="btn green" name="btntarik" value="            AMBIL ULANG DATA UPAH             ">
    </div>	
				
    <?
    //---------------------------------------- end list data -------------------------
    ?>
		
		</br>
		
    <div style="background: #F2F2F2;margin-top:2px;padding:10px 20px;border:1px solid #ececec;text-align:left;">
      <span style="background: #FF0; border: 1px solid #CCC;"><i><b>Keterangan:</b></i></span>
      <li style="margin-left:15px;">Data yang ditampilkan adalah upah selama 10 bulan terakhir dengan status minimal SIAP POSTING (SIAP REKON)</li>	
      <li style="margin-left:15px;"><font color="#ff0000">Upah yang dapat dipilih sebagai dasar perhitungan adalah upah peserta yang dilaporkan kepada BPJS Ketenagakerjaan selama 2 bulan terakhir sebelum tanggal kejadian</font></li>
			<li style="margin-left:15px;">Jika upah yg dipilih adalah upah dengan status SIAP POSTING (SIAP REKON) maka selanjutnya upah TK untuk BLTH tersebut tidak dapat diubah lagi pada saat posting data upah</li>
			<li style="margin-left:15px;">Apabila data upah tidak muncul di penetapan sebelumnya maka dapat dilakukan penarikan dengan mengklik tombol <font color="#ff0000">      AMBIL ULANG DATA UPAH      </font></li>
		</div>
		
		</br>		
	<div id="clear-bottom-popup"></div>

	<?
  if (isset($msg))		
  {
    ?>
      <fieldset style="width:1000px;">
        <?=$ls_error==1 ? "<legend><font color=#ff0000>Error</font></legend>" : "<legend><font color=#007bb7>Message</font></legend>";?>
        <?=$ls_error==1 ? "<font color=#ff0000>".$msg."</font>" : "<font color=#007bb7>".$msg."</font>";?>
      </fieldset>		
    <?
  }
  ?>	
</div> 

<div id="footer-popup">
  <p class="lft"></p>
  <p class="rgt">SIJSTK</p>
</div>
</form>
</body>
</html>
