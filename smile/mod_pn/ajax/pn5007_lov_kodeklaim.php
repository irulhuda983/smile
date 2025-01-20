<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "SIJSTK";
$gs_pagetitle = "Daftar Penetapan Klaim";

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
  <style>
    #header-caption2 {position:absolute;top:0;left:0;width:98%;height:27px;background:-webkit-linear-gradient(left,#6ba5ff,#416fd6);z-index: 300;text-align: left;}
    #header-caption2 h3 {font-size: 14px;color: #ffffff;margin: 6px 1px 1px 1px;height:23px;border-bottom:1px solid #6997ff;padding-left:1px;border-top-right-radius:1px;border-top-left-radius:1px;}
  </style>
  <tr><td id="header-caption2" colspan="3"><h3><?=$gs_pagetitle;?></h3></td></tr>	
  <tr><td colspan="3"></br></br></td></tr>	
</table>

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
          		case 'sc_kpj' : $sel1="selected"; break;
							case 'sc_nik' : $sel2="selected"; break; 
							//case 'sc_no_penetapan' : $sel3="selected"; break; 	
							case 'sc_kode_klaim' : $sel4="selected"; break; 
							case 'sc_namatk' : $sel5="selected"; break; 	
            }
            ?>
        		<option value="sc_kpj" <?=$sel1;?>>No. Referensi</option>
						<option value="sc_nik" <?=$sel2;?>>NIK</option> 
						<option value="sc_namatk" <?=$sel5;?>>Nama TK</option> 
						<!--<option value="sc_no_penetapan" <?=$sel3;?>>No Penetapan</option>-->
						<option value="sc_kode_klaim" <?=$sel4;?>>Kode Klaim Awal</option>   
          </select>
          <input type="text" name="searchtxt" value="<?=$searchtxt;?>" size="30" style="background-color: #ccffff;">
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
 		if(isset($pilihsearch) && $searchtxt!="")
		{
      $searchtxt    = strtoupper($searchtxt);
      if ($pilihsearch=="sc_kpj")
      {
       $filtersearch = " and tt.kpj = '".$searchtxt."' ";
      }
      elseif ($pilihsearch=="sc_nik")
      {
       $filtersearch = " and tt.nomor_identitas = '".$searchtxt."' ";
      }		
      elseif ($pilihsearch=="sc_no_penetapan")
      {
       $filtersearch = " and tt.no_penetapan like '%".$searchtxt."%' ";
      }		
      elseif ($pilihsearch=="sc_kode_klaim")
      {
       $filtersearch = " and tt.kode_klaim_pertama like '%".$searchtxt."%' ";
      }elseif ($pilihsearch=="sc_namatk")
      {
       	$filtersearch = " and tt.nama_tk like '".$searchtxt."%' ";
			}
														                		 		
      // query with paging								
      $rows_per_page = 15;
      $url = 'pn5007_lov_kodeklaim.php'; // url sama dengan nama file				
      //The unfiltered SELECT
      $sql = "select
                kode_klaim, kode_kantor, kode_segmen, kode_perusahaan, kode_divisi, kode_tk, nama_tk,kpj,nomor_identitas,
                kode_tipe_klaim, kode_sebab_klaim, ket_jenis_klaim, tgl_penetapan, no_penetapan, ket_nama_tk, kode_klaim_pertama					
					 	  from
							(
  					 	 	select 
          		 	 		a.kode_klaim, a.kode_kantor, a.kode_segmen, a.kode_perusahaan, a.kode_divisi, a.kode_tk, a.nama_tk,a.kpj,a.nomor_identitas,
                    a.kode_tipe_klaim, a.kode_sebab_klaim, 
    								(select nama_tipe_klaim from pn.pn_kode_tipe_klaim where kode_tipe_klaim = a.kode_tipe_klaim)||' | '||(select keyword||' - '||nama_sebab_klaim from pn.pn_kode_sebab_klaim where kode_sebab_klaim = a.kode_sebab_klaim) ket_jenis_klaim,
                    a.tgl_penetapan, nvl(a.no_penetapan,a.kode_klaim) no_penetapan,
    								a.nama_tk||' (No.Referensi: '||a.kpj||' | NIK: '||a.nomor_identitas||' | NPP: '||(select npp from sijstk.kn_perusahaan where kode_perusahaan = a.kode_perusahaan)||' - '||(select nama_perusahaan from sijstk.kn_perusahaan where kode_perusahaan = a.kode_perusahaan)||')' ket_nama_tk,
                    (
                        select kode_klaim from pn.pn_klaim where kode_klaim_induk is null
                        start with kode_klaim = a.kode_klaim and nvl(status_batal,'T')='T' 
                        connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')='T' 
                    ) kode_klaim_pertama, to_char(a.tgl_kejadian,'dd/mm/yyyy') tgl_kejadian                
								from sijstk.pn_klaim a
                where nvl(status_lunas,'T')='Y'
                and nvl(status_batal,'T')='T'
                and kode_tipe_klaim = 'JHT01'
                and kode_sebab_klaim <> 'SKJ01'
								and a.kode_klaim_anak is null
								and a.status_klaim not in ('BATAL','DIBATALKAN')
  							UNION ALL
                select 
                    a.kode_klaim, a.kode_kantor, a.kode_segmen, a.kode_perusahaan, a.kode_divisi, a.kode_tk, a.nama_tk,a.kpj,a.nomor_identitas,
                    a.kode_tipe_klaim, a.kode_sebab_klaim, 
                    (select nama_tipe_klaim from pn.pn_kode_tipe_klaim where kode_tipe_klaim = a.kode_tipe_klaim) ket_jenis_klaim,
                    a.tgl_penetapan, nvl(a.no_penetapan,a.kode_klaim) no_penetapan,
                    a.nama_tk||' (No.Referensi: '||a.kpj||' | NIK: '||a.nomor_identitas||' | NPP: '||(select npp from sijstk.kn_perusahaan where kode_perusahaan = a.kode_perusahaan)||' - '||(select nama_perusahaan from sijstk.kn_perusahaan where kode_perusahaan = a.kode_perusahaan)||')' ket_nama_tk,
                    (
                        select kode_klaim from pn.pn_klaim where kode_klaim_induk is null
                        start with kode_klaim = a.kode_klaim and nvl(status_batal,'T')='T' 
                        connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')='T' 
                    ) kode_klaim_pertama, to_char(a.tgl_kejadian,'dd/mm/yyyy') tgl_kejadian										
                from sijstk.pn_klaim a
                where nvl(status_submit_pengajuan,'T')='Y'
                and nvl(status_batal,'T')='T'
                and kode_tipe_klaim = 'JKK01'
								and a.kode_klaim_anak is null
								and a.status_klaim not in ('BATAL','DIBATALKAN')
							)tt where 1 = 1							
      			 	$filtersearch";
             	//order BY a.kode_klaim ";
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
      echo "<th class=awal>&nbsp;<a href=\"$PHP_SELF?f=a.kode_klaim&o=$o\"><b>Kode Klaim Terakhir</b></a></th>";    
      echo "<th><a href=\"$PHP_SELF?f=a.no_penetapan&o=$o\"><b>No Penetapan</b></a></th>";
  		echo "<th><a href=\"$PHP_SELF?f=a.kpj&o=$o\"><b>No Referensi</b></a></th>";
  		echo "<th><a href=\"$PHP_SELF?f=a.nomor_identitas&o=$o\"><b>NIK</b></a></th>";
  		echo "<th><a href=\"$PHP_SELF?f=a.nama_tk&o=$o\"><b>Nama</b></a></th>";
  		echo "<th><a href=\"$PHP_SELF?f=a.kode_sebab_klaim&o=$o\"><b>Jenis Klaim</b></a></th>";
			echo "<th><a href=\"$PHP_SELF?f=a.tgl_kejadian&o=$o\"><b>Tgl Kejadian</b></a></th>";
      echo "</tr>";
      
      $sql = f_query_perpage($sql, $start_row, $rows_per_page);
      $DB->parse($sql);
      $DB->execute();
      $i=0;
      $nx=1;
      while ($row = $DB->nextrow())
      {
      echo "<tr bgcolor=#".($nx%2 ? "f3f3f3" : "ffffff").">";
      ?>
        <td>
          <?="<a href=\"#\" onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KLAIM"])."';".
  				"window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$row["NO_PENETAPAN"])."'; ". 
  				"window.opener.document.".$formname.".".$fieldnamed.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KET_NAMA_TK"])."'; ".
  				"window.opener.document.".$formname.".".$fieldnamee.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KET_JENIS_KLAIM"])."'; ".
  				"window.opener.document.".$formname.".".$fieldnamef.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_TIPE_KLAIM"])."'; ".      											
          "window.opener.document.getElementById('".$fieldnamec."').focus(); ".
          "window.close();\" >".$row["KODE_KLAIM"]."</a>";?></td>
        <td>
          <?="<a href=\"#\" onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KLAIM"])."';".
  				"window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$row["NO_PENETAPAN"])."'; ". 
  				"window.opener.document.".$formname.".".$fieldnamed.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KET_NAMA_TK"])."'; ".
  				"window.opener.document.".$formname.".".$fieldnamee.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KET_JENIS_KLAIM"])."'; ".
  				"window.opener.document.".$formname.".".$fieldnamef.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_TIPE_KLAIM"])."'; ".      											
          "window.opener.document.getElementById('".$fieldnamec."').focus(); ".
          "window.close();\" >".$row["NO_PENETAPAN"]."</a>";?></td>  
        <td>
          <?="<a href=\"#\" onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KLAIM"])."';".
  				"window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$row["NO_PENETAPAN"])."'; ". 
  				"window.opener.document.".$formname.".".$fieldnamed.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KET_NAMA_TK"])."'; ".
  				"window.opener.document.".$formname.".".$fieldnamee.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KET_JENIS_KLAIM"])."'; ".
  				"window.opener.document.".$formname.".".$fieldnamef.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_TIPE_KLAIM"])."'; ".      											
          "window.opener.document.getElementById('".$fieldnamec."').focus(); ".
          "window.close();\" >".$row["KPJ"]."</a>";?></td>
        <td>
          <?="<a href=\"#\" onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KLAIM"])."';".
  				"window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$row["NO_PENETAPAN"])."'; ". 
  				"window.opener.document.".$formname.".".$fieldnamed.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KET_NAMA_TK"])."'; ".
  				"window.opener.document.".$formname.".".$fieldnamee.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KET_JENIS_KLAIM"])."'; ".
  				"window.opener.document.".$formname.".".$fieldnamef.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_TIPE_KLAIM"])."'; ".      											
          "window.opener.document.getElementById('".$fieldnamec."').focus(); ".
          "window.close();\" >".$row["NOMOR_IDENTITAS"]."</a>";?></td>
        <td>
          <?="<a href=\"#\" onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KLAIM"])."';".
  				"window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$row["NO_PENETAPAN"])."'; ". 
  				"window.opener.document.".$formname.".".$fieldnamed.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KET_NAMA_TK"])."'; ".
  				"window.opener.document.".$formname.".".$fieldnamee.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KET_JENIS_KLAIM"])."'; ".
  				"window.opener.document.".$formname.".".$fieldnamef.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_TIPE_KLAIM"])."'; ".      											
          "window.opener.document.getElementById('".$fieldnamec."').focus(); ".
          "window.close();\" >".$row["NAMA_TK"]."</a>";?></td>
        <td>
          <?="<a href=\"#\" onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KLAIM"])."';".
  				"window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$row["NO_PENETAPAN"])."'; ". 
  				"window.opener.document.".$formname.".".$fieldnamed.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KET_NAMA_TK"])."'; ".
  				"window.opener.document.".$formname.".".$fieldnamee.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KET_JENIS_KLAIM"])."'; ".
  				"window.opener.document.".$formname.".".$fieldnamef.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_TIPE_KLAIM"])."'; ".      											
          "window.opener.document.getElementById('".$fieldnamec."').focus(); ".
          "window.close();\" >".$row["KET_JENIS_KLAIM"]."</a>";?></td>	
        <td>
          <?="<a href=\"#\" onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_KLAIM"])."';".
  				"window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$row["NO_PENETAPAN"])."'; ". 
  				"window.opener.document.".$formname.".".$fieldnamed.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KET_NAMA_TK"])."'; ".
  				"window.opener.document.".$formname.".".$fieldnamee.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KET_JENIS_KLAIM"])."'; ".
  				"window.opener.document.".$formname.".".$fieldnamef.".value='".ExtendedFunction::ereg_replace("'","\'",$row["KODE_TIPE_KLAIM"])."'; ".      											
          "window.opener.document.getElementById('".$fieldnamec."').focus(); ".
          "window.close();\" >".$row["TGL_KEJADIAN"]."</a>";?></td>																	  				   																																																								
        </tr>
      <? 
      $i++; $nx++;
      }
      
      if ($i == 0) {
        echo '<tr bgcolor="'.($i%2 ? "#f3f3f3" : "#ffffff").'" style="height: 26px;">';
        echo '<td colspan="10" style="text-align:center">-- Data Tidak Ditemukan --</td>';
        echo '</tr>';
      }			
      
			echo "</table>";
		}
		?>
		    
    <table class="paging">
      <tr>
        <td>Total Rows <b><?=$total_rows; ?></b> | Total Pages <b><?=$total_pages; ?></b></td>
        <td height="15" align="right">
        <?$othervar = $othervar."&p=".$p."&a=".$a."&b=".$b."&c=".$c."&d=".$d."&e=".$e."&f=".$f."&g=".$g."&h=".$h."&j=".$j."&k=".$k."&l=".$l."&m=".$m."&n=".$n."&q=".$q."";?>
        <b>Page :</b> <?php echo f_draw_pager($url, $total_pages, $_GET['page'],$othervar); ?>
        </td>
      </tr>
    </table>
    <?
    //---------------------------------------- end list data -------------------------
    ?>
		
		</br>
		
    <div style="background: #F2F2F2;margin-top:2px;padding:10px 20px;border:1px solid #ececec;text-align:left;">
      <span style="background: #FF0; border: 1px solid #CCC;"><i><b>Keterangan:</b></i></span>
      <li style="margin-left:15px;">Pilih Jenis Pencarian</li>	
      <li style="margin-left:15px;">Untuk pencarian menggunakan No.Identitas isikan nomor identitas LENGKAP. </li>
			<li style="margin-left:15px;">Untuk pencarian menggunakan No.Referensi isikan nomor referensi LENGKAP</li>
			<li style="margin-left:15px;">Untuk pencarian menggunakan Nama TK, Nama dapat diakhiri % untuk keyword sebagian. <font color="#ff0000"> Pencarian dg NAMA TK memerlukan waktu yang lama, harap tunggu sampai proses benar-benar selesai..!!!</font></li>
			<li style="margin-left:15px;">Klik Tombol GO untuk memulai pencarian data</li>
		</div>
		
		</br>
				
	<div id="clear-bottom-popup"></div>
</div> 

<div id="footer-popup">
  <p class="lft"></p>
  <p class="rgt">SIJSTK</p>
</div>
</form>
</body>
</html>
