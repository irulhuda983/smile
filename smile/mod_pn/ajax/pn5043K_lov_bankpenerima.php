<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "SMILE";
$gs_pagetitle = "Daftar Bank Tujuan (Antar Bank)";
$gs_username	= $_SESSION["USER"];

$pilihsearch	= !isset($_POST['pilihsearch']) ? $_GET['pilihsearch'] : $_POST['pilihsearch'];
$searchtxt		= !isset($_POST['searchtxt']) ? $_GET['searchtxt'] : $_POST['searchtxt'];

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
$u	= $_POST['vu'];
$v	= $_POST['vv'];
$w	= $_POST['vw'];
$x	= $_POST['vx'];
$y	= $_POST['vy'];
$z	= $_POST['vz'];
//$pilihsearch = $_POST['pilihsearch'];
//$searchtxt 	 = $_POST['searchtxt'];

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
	$u	= $_GET['u'];
	$v	= $_GET['v'];
	$w	= $_GET['w'];
	$x	= $_GET['x'];
	$y	= $_GET['y'];
	$z	= $_GET['z'];
	//$pilihsearch = $_GET['pilihsearch'];
	//$searchtxt 	 = $_GET['searchtxt'];
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
$fieldnameu=(!$u) ? "filu" : $u;
$fieldnamev=(!$v) ? "filv" : $v;
$fieldnamew=(!$w) ? "filw" : $w;
$fieldnamex=(!$x) ? "filx" : $x;
$fieldnamey=(!$y) ? "fily" : $y;
$fieldnamez=(!$z) ? "filz" : $z;
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
          		case 'sc_nama_bank' : $sel1="selected"; break;
            }
            ?>
        		<option value="sc_nama_bank" <?=$sel1;?>>Nama Bank</option>
          </select>
          <input type="text" name="searchtxt" value="<?=$searchtxt;?>" size="25" style="background-color: #ccffff;">
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
					<input type="hidden" id="vu" name="vu" value="<?=$u;?>">
					<input type="hidden" id="vv" name="vv" value="<?=$v;?>">
					<input type="hidden" id="vw" name="vw" value="<?=$w;?>">
					<input type="hidden" id="vx" name="vx" value="<?=$x;?>">
					<input type="hidden" id="vy" name="vy" value="<?=$y;?>">
					<input type="hidden" id="vz" name="vz" value="<?=$z;?>">
        </td>
      </tr>
    </table>

		<?
		//------------------------- start list data --------------------------------
  	//filter data

			$filtersearch  = "";
   		if(isset($searchtxt) && $searchtxt!="" && $pilihsearch!="")
  		{
        $searchtxt    = strtoupper($searchtxt);
        if ($pilihsearch=="sc_nama_bank")
        {
  			 	 $filtersearch  = $searchtxt;
        }
        else
        {
         	 $filtersearch  = "";
        }
  		}

      // query with paging
      $rows_per_page = 300;
      $lov_url = 'pn5004k_lov_bankpenerima.php'; // url sama dengan nama file

			//get data from WS -------------------------------------------------------
      global $wsIp;
      $ipDev  = $wsIp."/JSOPG/GetListAntarBank";
      $wsUrl    = $ipDev;
      $chId   = 'CORE';

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

      $data = array(
        'chId'=>$chId,
        'reqId'=>$gs_username,
				'BANK_ASAL'=>"BNI",
				'BANK_TUJUAN'=>$filtersearch,
       	'TIPE_TRF'=>"ATB"
      );

      // Open connection -------------------------------------------------------
      $ch = curl_init();

      // Set the url, number of POST vars, POST data ---------------------------
      curl_setopt($ch, CURLOPT_URL, $wsUrl);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

      // Execute post ----------------------------------------------------------
      $result = curl_exec($ch);
      $resultArray = json_decode(utf8_encode($result));// mesti make a sure utf8 contentnya

      // echo('xxxxx');
		  // var_dump($resultArray->data[0]); die();

      $total_rows  = $resultArray->numrows;
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
      echo "<th class=awal>&nbsp;<a href=\"$PHP_SELF?f=a.nama_bank&o=$o\"><b>Nama Bank</b></a></th>";
      echo "<th class=awal>&nbsp;<a href=\"$PHP_SELF?f=a.kode_bank&o=$o\"><b>Kode Bank</b></a></th>";
      echo "<th class=awal>&nbsp;<a href=\"$PHP_SELF?f=a.kode_bank_bi&o=$o\"><b>Kode BI</b></a></th>";
      echo "</tr>";

      $nx=1;
			for($i=0;$i<$resultArray->numrows;$i++)
			{
  		  echo "<tr bgcolor=#".($nx%2 ? "f3f3f3" : "ffffff")." onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$resultArray->data[$i]->NAMA_BANK)."';".
                "window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$resultArray->data[$i]->KODE_BANK)."'; ".
                "window.opener.document.".$formname.".".$fieldnamed.".value='".ExtendedFunction::ereg_replace("'","\'",$resultArray->data[$i]->KODE_BANK_BI)."'; ".
								// "window.opener.fjq_ajax_val_getlist_bank_asal(); ".
                "window.opener.document.getElementById('".$fieldnameb."').focus(); ".
								"window.close();\" style = \"cursor: pointer;\" >";?>
          <td><?=$resultArray->data[$i]->NAMA_BANK;?></td>
					<td><?=$resultArray->data[$i]->KODE_BANK;?></td>
					<td><?=$resultArray->data[$i]->KODE_BANK_BI;?></td>
        </tr>
      	<?
      	$nx++;
      }

			if ($i == 0) {
        echo '<tr bgcolor="'.($i%2 ? "#f3f3f3" : "#ffffff").'" style="height: 26px;">';
        echo '<td colspan="3" style="text-align:center">-- Data Tidak Ditemukan --</td>';
        echo '</tr>';
      }

    ?>

    </table>


    <table class="paging">
      <tr>
        <td>Total Rows <b><?=$total_rows; ?>
				</b> | Total Pages <b><?=$total_pages; ?></b></td>
        <td height="15" align="right">
        <?$othervar = $othervar."&p=".$p."&a=".$a."&b=".$b."&c=".$c."&d=".$d."&e=".$e."&f=".$f."&g=".$g."&h=".$h."&j=".$j."&k=".$k."&l=".$l."&m=".$m."&n=".$n."&q=".$q."&r=".$r."&s=".$s."&t=".$t."&u=".$u."&v=".$v."&w=".$w."&x=".$x."&y=".$y."&z=".$z."";?>
        <b>Page :</b> <?php echo f_draw_pager($lov_url, $total_pages, $_GET['page'],$othervar); ?>
        </td>
      </tr>
    </table>

    <?
    //---------------------------------------- end list data -------------------------
    ?>

	<div id="clear-bottom-popup"></div>
</div>
<!--
<div id="footer-popup">
  <p class="lft"></p>
  <p class="rgt">SMILE</p>
</div>
-->
</form>
</body>
</html>
