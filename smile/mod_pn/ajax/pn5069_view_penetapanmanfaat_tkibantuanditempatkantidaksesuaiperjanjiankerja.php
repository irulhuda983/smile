<?
$pagetype="report";
$gs_pagetitle = "PN5002 - RINCIAN MANFAAT";
require_once "../../includes/header_app.php";
include_once '../../includes/fungsi_rpt.php';
$DB2 = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
/*--------------------- Form History -----------------------------------------
File: pn5041_penetapanmanfaat_tkibantuanditempatkantidaksesuaiperjanjiankerja.php

Deskripsi:
-----------
File ini dipergunakan untuk rincian manfaat bantuan Ditempatkan Tidak Sesuai Perjanjuan Kerja

Author:
--------
Tim SIJSTK

Histori Perubahan:
--------------------
23/01/2019 - Tim SIJSTK
Pembuatan Form
  
-------------------- End Form History --------------------------------------*/
$ls_kode_klaim	 		 = !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ls_kode_manfaat	 	 = !isset($_GET['kode_manfaat']) ? $_POST['kode_manfaat'] : $_GET['kode_manfaat'];
$ls_form_penetapan	 = !isset($_GET['form_penetapan']) ? $_POST['form_penetapan'] : $_GET['form_penetapan'];
$ls_sender 					 = !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
$ls_sender_mid 			 = !isset($_GET['sender_mid']) ? $_POST['sender_mid'] : $_GET['sender_mid'];
$ls_task 			 			 = !isset($_GET['task']) ? $_POST['task'] : $_GET['task'];
	
if ($ls_kode_manfaat!="")
{
  $sql = "select a.nama_manfaat from sijstk.pn_kode_manfaat a ".
         "where a.kode_manfaat = '$ls_kode_manfaat'";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
  $ls_status	= $row['STATUS'];
	$ls_nama_manfaat	= $row['NAMA_MANFAAT'];
	
 	$gs_pagetitle = "PN5002 - RINCIAN MANFAAT "." - ".$ls_nama_manfaat." (KODE KLAIM ".$ls_kode_klaim.")";	 		  	 
}

if(isset($_GET['delete_manfaatrinci']))
{
  $qry = "BEGIN SIJSTK.P_PN_PN5040.X_POST_DELETE_MNF_DETIL ('".$_GET['kode_klaim']."','".$_GET['kode_manfaat']."','".$_GET['no_urut']."', '$username',:p_sukses,:p_mess);END;";												 	
  $proc = $DB->parse($qry);				
  oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
	oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
  $DB->execute();				
  $ls_mess = $p_mess;	
				
	$msg = "Data sudah terhapus..";
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
	echo "window.location.replace('?kode_klaim=$ls_kode_klaim&kode_manfaat=$ls_kode_manfaat&task=edit&form_penetapan=$ls_form_penetapan&sender=$ls_sender&msg=$msg');";
	echo "</script>";	
}

//--------------------- fungsi lokal javascript ------------------------------
?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/common.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<link href="../assets/easyui/themes/default/easyui.css" rel="stylesheet">
<script type="text/javascript"></script>

<script type="text/javascript" src="../../highcharts/js/jquery.min.js"></script>
<script src="../../highcharts/js/highcharts.js"></script>
<script language="javascript">
  function NewWindow4(mypage,myname,w,h,scroll){
    var openwin = window.parent.Ext.create('Ext.window.Window', {
      title: myname,
      collapsible: true,
      animCollapse: true,
      
      maximizable: true,
      width: w,
      height: h,
      minWidth: 450,
      minHeight: 250,
      layout: 'fit',
      html:'<iframe src="'+mypage+'"  height="100%" width="100%" frameborder="0" style="border:0; height:100%; width:100%;scrollbars=no;"></iframe>',
    });
    openwin.show();
  }	
  
  function confirmDelete(delUrl) {
  	if (confirm("Are you sure you want to delete this record")) {
  	document.location = delUrl;
  	}
  }
</script>
<?
//--------------------- end fungsi lokal javascript ----------------------------
?>

<table class="captionfoxrm" aria-describedby="captionfoxrmdesc">
  <tr><th></th></tr>
  <style>
    #header-caption2 {position:absolute;top:0;left:0;width: 98%;height: 35px;background: -webkit-linear-gradient(left,#6ba5ff,#416fd6);z-index: 300;text-align: left;}
    #header-caption2 h3 {font-size: 14px;color: #ffffff;margin: 10px 10px 10px 10px;height: 25px;border-bottom: 1px solid #6997ff;padding-left: 1px;border-top-right-radius: 1px;border-top-left-radius: 1px;}
  </style>		
  <tr><td id="header-caption2" colspan="3"><h3><?=$gs_pagetitle;?></h3></td></tr>	
  <tr><td colspan="3"></br></br></td></tr>	
</table>

<div id="formframe">
  <div id="formKiri" style="width:880px">
		<input type="hidden" name="TYPE" value="<?=$_REQUEST["task"];?>">
    <input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["dataid"];?>">
		<input type="hidden" id="activetab" name="activetab" value="<?=$ls_activetab;?>">
		<input type="hidden" id="form_penetapan" name="form_penetapan" value="<?=$ls_form_penetapan;?>">	
    <input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
    <input type="hidden" id="sender_mid" name="sender_mid" value="<?=$ls_sender_mid;?>">		
		<input type="hidden" id="task" name="task" value="<?=$ls_task;?>">
						 
    <table id="mydata2" cellspacing="0" style="text-align:center;width:85%;" aria-describedby="mydata2desc">
      <tbody>
				<tr>
        	<th scope="col" colspan="5"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.80), rgba(0,0,0,0));"/></th>	
        </tr>	

        <tr>
          <th scope="col" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="2">&nbsp;</th>
          <th scope="col" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="2">Penetapan Nilai Manfaat</th>
          <th scope="col" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="1"></th>
        </tr>	
				
        <tr>
          <th scope="col" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="2">&nbsp;</th>
          <th scope="col" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="2"><hr/></th>
          <th scope="col" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="1">&nbsp;</th>
        </tr>
										
        <tr>
          <th scope="col" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:50px;">No</th>
          <th scope="col" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:150px;">Tipe Penerima</th>
					<th scope="col" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:100px;">Biaya Disetujui</th>
          <th scope="col" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:100px;">Catatan</th>
          <th scope="col" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:100px;">Action</th>
        </tr>

        <tr>
        <th scope="col" colspan="5"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
        </tr>
             					
        <?
        if ($ls_kode_manfaat != "")
        {			
          $sql = "select 
                      a.kode_klaim, a.kode_manfaat, b.nama_manfaat, a.no_urut, a.kode_manfaat_detil, a.kategori_manfaat, 
                      a.kode_tipe_penerima, c.nama_tipe_penerima, a.kd_prg, a.nom_biaya_diajukan, a.nom_biaya_disetujui, 
                      a.nom_manfaat_utama, a.nom_manfaat_tambahan, a.nom_manfaat_gross, 
                      a.nom_pph, a.nom_pembulatan, a.nom_manfaat_netto, a.keterangan
                  from sijstk.pn_klaim_manfaat_detil a, sijstk.pn_kode_manfaat b, sijstk.pn_kode_tipe_penerima c
                  where a.kode_manfaat = b.kode_manfaat(+) 
                  and a.kode_tipe_penerima = c.kode_tipe_penerima(+)
                  and a.kode_klaim = '$ls_kode_klaim'
                  and a.kode_manfaat = '$ls_kode_manfaat'
                  order by a.no_urut ";								               
          $DB->parse($sql);
          $DB->execute();							              					
          $i=0;		
          $ln_dtl =0;
					$ln_tot_biaya_diajukan  =0;
					$ln_tot_biaya_disetujui =0;						
          while ($row = $DB->nextrow())
          {
            $ls_no_urut = $row["NO_URUT"];
            ?>
            <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>								
              <td style="text-align:center"><?=$row["NO_URUT"];?></td>
              <td style="text-align:center"><?=$row["NAMA_TIPE_PENERIMA"];?></td>  									
              <td style="text-align:right"><?=number_format((float)$row["NOM_BIAYA_DISETUJUI"],2,".",",");?></td>
							<td style="text-align:center"><?=$row["KETERANGAN"];?></td>
              <td>
							
              <a href="javascript:void(0)" onclick="fl_js_tap_mnf_rinci('<?=$ls_kode_klaim;?>','<?=$ls_kode_manfaat;?>','<?=$ls_no_urut;?>','<?=$ls_form_penetapan;?>')"> <img src="../../images/indent_right.gif" border="0" alt="Rincian Manfaat" align="absmiddle" /><span style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;">&nbsp;View </span></a>
              						
              </td>								
            </tr>
            <?							    							
            $i++;//iterasi i
						$ln_tot_biaya_diajukan  += $row["NOM_BIAYA_DIAJUKAN"];
						$ln_tot_biaya_disetujui += $row["NOM_BIAYA_DISETUJUI"];
          }	//end while
          $ln_dtl=$i;
					
          if ($i == 0) {
            echo '<tr bgcolor="'.($i%2 ? "#f3f3f3" : "#ffffff").'" style="height: 26px;">';
            echo '<td colspan="5" style="text-align:center">-- Belum Ada Rincian Manfaat --</td>';
            echo '</tr>';
          }					
        }						
        ?>									             																
      </tbody>
			
      <tr>
      	<th scope="col" colspan="5"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.20), rgba(0,0,0,0));"/></th>	
      </tr>
						
      <tr>
        <td style="text-align:right;" colspan="2"><em><strong>Total</strong></em>
          <input type="hidden" id="kounter_dtl" name="kounter_dtl" value="<?=$ln_dtl;?>">
          <input type="hidden" id="count_dtl" name="count_dtl" value="<?=$ln_countdtl;?>">
          <input type="text" name="showmessage" style="border-width: 0;text-align:right" readonly size="5">				
				</td>        									
        <td style="text-align:right"><?=number_format((float)$ln_tot_biaya_disetujui,2,".",",");?></td>				
				<td colspan="1"></td>
				<td style="text-align:center;">
				
        </td>																					
      </tr>
			
      <tr>
      	<th scope="col" colspan="5"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.20), rgba(0,0,0,0));"/></th>	
      </tr>			
			
			<tr>
				<td style="text-align:center;" colspan="5">&nbsp;				
				</td>
			</tr>				
			<tr>
				<td style="text-align:center;" colspan="5">	
  				
				</td>
			</tr>																
    </table>
            			
	</div> <!--end formKiri -->
</div> <!--end formframe -->	

<div id="clear-bottom"></div>
<script language="javascript">
	//template -------------------------------------------------------------------
	$(document).ready(function(){
		$("input[type=text]").keyup(function(){
			$(this).val( $(this).val().toUpperCase() );
		});

    $(window).bind("resize", function(){
			resize();
		});
		
		resize();
		
		let kode_klaim = $('#kode_klaim').val();
		let kode_manfaat = $('#kode_manfaat').val();
		
		loadSelectedRecord(kode_klaim, kode_manfaat, null);
	});

	function resize(){		 
		$("#div_container").width($("#div_dummy").width());
		
		$("#div_header").width($("#div_dummy").width());
		$("#div_body").width($("#div_dummy").width());
		$("#div_footer").width($("#div_dummy").width());
		
		$("#div_filter").width(0);
		$("#div_data").width(0);
		$("#div_page").width(0);

		$("#div_filter").width($("#div_dummy_data").width());
		$("#div_data").width($("#div_dummy_data").width());
		$("#div_page").width($("#div_dummy_data").width());
	}

	function showForm(mypage, myname, w, h, scroll) {
		var openwin = window.parent.Ext.create('Ext.window.Window', {
			title: myname,
			collapsible: true,
			animCollapse: true,
			maximizable: true,
			closable: true,
			width: w,
			height: h,
			minWidth: w,
			minHeight: h,
			layout: 'fit',
			modal: true,
			html: '<iframe src="' + mypage + '"  frameborder="0" style="border:0; height:100%; width:100%; overflow-x:hidden; overflow-y:hidden;" scrolling=="'+scroll+'"></iframe>',
			listeners: {
				close: function () {
					// filter();
				},
				destroy: function (wnd, eOpts) {
				}
			}
		});
		openwin.show();
		return openwin;
	}

	function getValue(val){
		return val == null ? '' : val;
	}

	function getValueNumber(val){
		return val == null ? '0' : val;
	}
				
	function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	}

	function asyncLoading() {
		preload(true);
	}

	function asyncPreload(isloading) {
		if (isloading) {
			window.asyncLoader = setInterval(asyncLoading, 100);
		} else {
			clearInterval(window.asyncLoader);
			preload(false);
		}
	}
	//end template ---------------------------------------------------------------			
</script>
<script language="javascript">
	function fl_js_tap_mnf_rinci(p_kode_klaim, p_kode_manfaat, p_no_urut, p_form_penetapan)
	{		console.log('okkkk');
		var c_mid = '<?=$mid;?>';
		showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5069_view_penetapanmanfaat_tkibantuanditempatkantidaksesuaiperjanjiankerjaentry.php?task=view&kode_klaim='+p_kode_klaim+'&kode_manfaat='+p_kode_manfaat+'&no_urut='+p_no_urut+'&form_penetapan='+p_form_penetapan+'&sender=pn5041_penetapanmanfaat_tkibantuanditempatkantidaksesuaiperjanjiankerja.php'+'','',980,550,'yes');
	}
</script>
<?
include_once "../../includes/footer_app.php";
?>

