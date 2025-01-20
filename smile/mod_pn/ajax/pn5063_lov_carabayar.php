<?
session_start();
$pagetype="report";
require_once "../../includes/header_app.php";
$pagetitle = "SMILE";
$gs_pagetitle = "DAFTAR CARA BAYAR";

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
<form action="<?=$PHP_SELF;?>" method="post" id="lov_inv_porto" name="lov_inv_porto">
  <div id="actmenu">
   	<h3 style="margin-top: 5px;margin-left: 10px; color:#FFFFFF"><font color="#ffff99" style="font-size:15px;"><?=$gs_pagetitle;?></font></h3> 
  </div>
		
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
							case 'sc_nama' : $sel2="selected"; break; 		
            }
            ?>
        		<option value="sc_all">--ALL--</option>
        		<option value="sc_kode" <?=$sel1;?>>Kode</option>
						<option value="sc_nama" <?=$sel2;?>>Nama</option>     
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
    $ln_rows_per_page = 10;
    $ls_lov_url = 'pn5040_lov_jeniskelamin.php'; // url sama dengan nama file		
    
    //penanganan untuk filter data -------------------------------------------				
    $ls_colname  = "";
    $ls_colval 	 = "";	
    
    if ($pilihsearch=="sc_kode")
    {
      $ls_colname  = "KODE";
      $ls_colval 	 = $searchtxt;
    }elseif ($pilihsearch=="sc_nama")
    {
      $ls_colname  = "KETERANGAN";
      $ls_colval 	 = $searchtxt;
    }
    
    //get data from WS -------------------------------------------------------
    global $wsIp;
    $ipDev  = $wsIp."/MS1001/LovMsLookup";
    $url    = $ipDev;
    $chId   = 'SMILE';
    $gs_kode_user	= $_SESSION["USER"];
		
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
      'chId'				=>$chId,
      'reqId'				=>$username,
      'TIPE'				=>"KLMCRBYR",
      'PAGE'				=>$ln_page_ke,
      'NROWS'				=>$ln_rows_per_page,
      'C_COLNAME'		=>$ls_colname,
      'C_COLVAL'		=>$ls_colval,
      'C_COLNAME2'	=>"",
      'C_COLVAL2'		=>"",
      'O_COLNAME'		=>"SEQ",
      'O_MODE'			=>"ASC"
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
    echo "<th class=awal>&nbsp;<a href=\"$PHP_SELF?f=a.kode&o=$o\"><b>Kode</b></a></th>";    
    echo "<th><a href=\"$PHP_SELF?f=a.nama&o=$o\"><b>Nama</b></a></th>";
    echo "</tr>";
    
		if ($e=="JHT_INPUT")
  	{
  	 	$ls_exec_prc = "window.opener.fl_js_jhtinput_set_span_carabayar(); "; 
  	}else if ($e=="JHM_INPUT")
  	{
  	 	$ls_exec_prc = "window.opener.fl_js_jhminput_set_span_carabayar(); "; 
  	}else if ($e=="SIAP_BAYAR_LUMPVERIF")
  	{
  	 	$ls_exec_prc = "window.opener.fl_js_lumpverif_span_carabayar_edit(); "; 
  	}
		
		if ($j=="")
		{
		 	$j = "T";//is sentralisasi 
		}
					
    $i=0;
    $nx=1;
    for($i=0;$i<ExtendedFunction::count($resultArray->DATA);$i++)
    {
      if ($j=="Y")
			{
  			//utk is_sentralisasi = Y maka pilihan SPB dihilangkan -----------------
				if ($resultArray->DATA[$i]->KODE!="S")
  			{
    			//$f=jenis_klaim
  				//$g=nom_manfaat
  				//$h=nom_max_va_debit
  				//va debit khusus utk klaim jht dan nominal manfaat <= nominal max -----
  				if (($resultArray->DATA[$i]->KODE!="V") || ($resultArray->DATA[$i]->KODE=="V" && $f=="JHT" && $g<=$h))
  				{
    				echo "<tr bgcolor=#".($nx%2 ? "f3f3f3" : "ffffff")." onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$resultArray->DATA[$i]->KODE)."';".
                "window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$resultArray->DATA[$i]->KETERANGAN)."'; ". 
                "window.opener.document.getElementById('".$fieldnameb."').focus(); ".
      					$ls_exec_prc.
                "window.close();\" style = \"cursor: pointer;\" >";?>
              <td><?=$resultArray->DATA[$i]->KODE;?></td>
              <td><?=$resultArray->DATA[$i]->KETERANGAN;?></td>																																																				
            </tr>
  				<?
  				}
          else if(($resultArray->DATA[$i]->KODE!="V") || ($resultArray->DATA[$i]->KODE=="V" && $f=="JHT" && $g=='')){
            echo "<tr bgcolor=#".($nx%2 ? "f3f3f3" : "ffffff")." onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$resultArray->DATA[$i]->KODE)."';".
                "window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$resultArray->DATA[$i]->KETERANGAN)."'; ". 
                "window.opener.document.getElementById('".$fieldnameb."').focus(); ".
      					$ls_exec_prc.
                "window.close();\" style = \"cursor: pointer;\" >";?>
              <td><?=$resultArray->DATA[$i]->KODE;?></td>
              <td><?=$resultArray->DATA[$i]->KETERANGAN;?></td>																																																				
            </tr>
            <?php
          }
  			}
			}else
			{
			 	//utk is_sentralisasi = T maka pilihan VA Debit dihilangkan ------------
				if ($resultArray->DATA[$i]->KODE!="V")
  			{
  				echo "<tr bgcolor=#".($nx%2 ? "f3f3f3" : "ffffff")." onclick=\"javascript:window.opener.document.".$formname.".".$fieldnameb.".value='".ExtendedFunction::ereg_replace("'","\'",$resultArray->DATA[$i]->KODE)."';".
              "window.opener.document.".$formname.".".$fieldnamec.".value='".ExtendedFunction::ereg_replace("'","\'",$resultArray->DATA[$i]->KETERANGAN)."'; ". 
              "window.opener.document.getElementById('".$fieldnameb."').focus(); ".
    					$ls_exec_prc.
              "window.close();\" style = \"cursor: pointer;\" >";?>
            <td><?=$resultArray->DATA[$i]->KODE;?></td>
            <td><?=$resultArray->DATA[$i]->KETERANGAN;?></td>																																																				
          </tr>
  				<?
  			}					 
			}
			?>
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
        <td>Total Rows <b><?=$total_rows; ?></b> | Total Pages <b><?=$total_pages; ?></b></td>
        <td height="15" align="right">
        <?$othervar = $othervar."&p=".$p."&a=".$a."&b=".$b."&c=".$c."&d=".$d."&e=".$e."&f=".$f."&g=".$g."&h=".$h."&j=".$j."&k=".$k."&l=".$l."&m=".$m."&n=".$n."&q=".$q."";?>
        <b>Page :</b> <?php echo f_draw_pager($ls_lov_url, $total_pages, $_GET['page'],$othervar); ?>
        </td>
      </tr>
    </table>
    <?
    //---------------------------------------- end list data -------------------------
    ?>
		
    <div id="div_footer" class="div-footer">
      <div style="background: #f2f2f2;padding:10px 20px;border:1px solid #ececec;">
        <span><b>Keterangan:</b></span>
        <li style="margin-left:15px;">Pilih <font color="#ff0000">jenis kelamin</font>.</li>
				<li style="margin-left:15px;">Untuk pencarian data menggunakan <font color="#ff0000">Nama</font> maka tambahkan % didepan ataupun dibelakang keyword.</li>
      </div>				
    </div>	
</div> 

<div id="footer-popup">
  <p class="lft"></p>
  <p class="rgt">SMILE</p>
</div>
</form>
