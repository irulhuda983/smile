<?php
//------------------------------------------------------------------------------
// Menu untuk display rincian penetapan manfaat
// dibuat tgl : 21/08/2020
//------------------------------------------------------------------------------
$pagetype = "form";
require_once "../../includes/header_app_nosql.php";	
include '../../includes/fungsi_newrpt.php';
//set parameter ----------------------------------------------------------------
$pagetype 			  = "form";
$gs_kodeform 	 	 	= "PN5030";
$chId 	 	 			 	= "SMILE";
$gs_pagetitle 	 	= "PENETAPAN MANFAAT";											 
$gs_kantor_aktif 	= $_SESSION['kdkantorrole'];
$gs_kode_user		 	= $_SESSION["USER"];
$gs_kode_role		 	= $_SESSION['regrole'];
$task 					 	= $_POST["task"];
$editid 				 	= $_POST['editid'];
$ls_kode_klaim	 	= !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ls_kode_manfaat	= !isset($_GET['kode_manfaat']) ? $_POST['kode_manfaat'] : $_GET['kode_manfaat'];
$ls_nik_penerima_cetak = $_POST['nik_penerima_cetak'];
$task_detil 				= $_POST['task_detil'];

if ($task_detil=="doCetakKartu")
{	 				
  $ls_user_param .= " P_NIK_PENERIMA='$ls_nik_penerima_cetak'"; 
  $ls_user_param .= " P_USER='$gs_kode_user'"; 							  	 
  $ls_nama_rpt .= "PNR900301.rdf";
  
  $ls_pdf = $ls_nama_rpt;	
  
  $tipe     = "PDF";
  $ls_modul = "pn";  
  exec_rpt_enc_new(1,$ls_modul, $ls_nama_rpt, $ls_user_param, $tipe);
  		
  echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "reloadFormUtama();";
  echo "</script>";	 	 
}
//end set parameter ------------------------------------------------------------
?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<link rel="stylesheet" type="text/css" href="../../style/jquery.dataTables.min.css">
<!-- custom css -->
<link href="../style.custom.css?a=<?=rand()?>" rel="stylesheet"/>
<link href="../../style/select2/css/select2.min.css" rel="stylesheet" />
<script type="text/javascript" src="../../style/select2/js/select2.min.js"></script>

<div class="div-action-menu">
	<div class="menu">
		<div class="item"><span id="span_page_title"><?=$gs_pagetitle;?></span></div>
		<div class="item" style="float: right; padding: 2px;">
			<font color="#ffffff"><span id="span_page_title_right"></span></font>	 				
		</div>		
	</div>
</div>

<div id="formframe" style="min-width:80%;">
	<div id="div_dummy" style="width: 100%;"></div>
	<div id="formKiri">
		<form name="formreg" id="formreg" role="form" method="post" enctype="multipart/form-data">
			<input type="hidden" id="task" name="task" value="<?=$task;?>">
			<input type="hidden" id="editid" name="editid" value="<?=$editid;?>">
			<input type="hidden" id="mid" name="mid" value="<?=$mid;?>">
			<input type="hidden" id="tipe" name="tipe" value="">
			<input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
			<input type="hidden" id="kode_manfaat" name="kode_manfaat" value="<?=$ls_kode_manfaat;?>">
			<input type="hidden" id="nik_penerima_cetak" name="nik_penerima_cetak" value="<?=$ls_nik_penerima_cetak;?>">
			<input type="hidden" id="task_detil" name="task_detil" value="<?=$task_detil;?>">
			
			<div id="div_container" class="div-container">
				<div id="div_body" class="div-body">
          <div class="div-row">
            <div class="div-col" style="width:100%; max-height: 100%;">
              <fieldset><legend>Penetapan Manfaat</legend>
                <table id="tblMnf" width="100%" class="table-data2">
                  <thead>								
                    <tr>
                      <th style="text-align:center;" colspan="2">&nbsp;</th>
                      <th style="text-align:center;" colspan="4" class="hr-single-bottom">PENETAPAN NILAI MANFAAT</th>
                      <th style="text-align:center;" colspan="2"></th>
                    </tr>					
                    <tr class="hr-double-bottom">
                      <th style="text-align:center;">No</th>
                      <th style="text-align:center;">Tipe Penerima</th>
											<th style="text-align:center;">NIK Anak</th>
                      <th style="text-align:center;">Nama Anak</th>
                      <th style="text-align:right;">Biaya Disetujui</th>
                      <th style="text-align:center;">Catatan</th>
                      <th style="text-align:center;">Action</th>										
                    </tr>		
									</thead>	
									<tbody id="data_tblMnf">		
                    <tr class="nohover-color">
                    	<td colspan="7" style="text-align: center;">-- data tidak ditemukan --</td>
                    </tr>																		             																
                  </tbody>
									<tfoot>
                    <tr>
                      <td style="text-align:right;" colspan="4"><i>Total Manfaat:</i>
                        <input type="hidden" id="mnf_kounter_dtl" name="mnf_kounter_dtl" value="<?=$ln_dtl;?>">
                        <input type="hidden" id="mnf_count_dtl" name="mnf_count_dtl" value="<?=$ln_countdtl;?>">
                        <input type="hidden" name="mnf_showmessage" style="border-width: 0;text-align:right" readonly size="5">				
              				</td>        									
											<td style="text-align:right"><span id="mnf_tot_biaya_disetujui"></span></td>			
              				<td colspan="2"></td>																				
                    </tr>
									</tfoot>																
                </table>
              </fieldset>				 
						</div>
					</div>
					
					<div id="divForm2"></div>
					<div id="divForm3"></div>
																																				
				</div>
				<!--end div_body-->
					
				<div id="div_footer" class="div-footer">
  				<span id="span_button_utama" style="display:block;">
            <div style="padding-top:6px;width:99%;">
  						<div class="div-footer-content">
  							<div style="padding-bottom: 8px;"><b>Keterangan:</b></div>
  							<li style="margin-left:15px;">Klik tombol <font color="#ff0000"> <b>X</b> </font> pada pojok kanan atas untuk menutup form dan kembali ke menu utama.</li>
  						</div>
            </div>
  				</span><!--end span_button_utama-->					
				</div>
				<!--end div_footer--> 
			</div>							
		</form>	 
	</div>	 
</div>

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
	function loadSelectedRecord(v_kode_klaim, v_kode_manfaat, fn)
	{
		if (v_kode_klaim == '' || v_kode_manfaat == '') {
			return alert('Kode Klaim atau Kode Manfaat tidak boleh kosong');
		}
		
		asyncPreload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5069_action.php?"+Math.random(),
			data: {
				tipe: 'fjq_ajax_val_getmanfaatbykodemnf',
				v_kode_klaim: v_kode_klaim,
				v_kode_manfaat:v_kode_manfaat
			},
			success: function(data){
				try {
					jdata = JSON.parse(data);
					if (jdata.ret == 0) 
					{ 
						$("#span_page_title").html('PN5030 - RINCIAN MANFAAT - BEASISWA');
						$("#span_page_title_right").html('KODE KLAIM : '+v_kode_klaim);

						var v_kode_segmen = getValue(jdata.dataKdKlaim.INFO_KLAIM.KODE_SEGMEN);
						var v_tgl_kejadian = getValue(jdata.dataKdKlaim.INFO_KLAIM.TGL_KEJADIAN);
						var v_tgl_kejadian_yyymmdd = v_tgl_kejadian.substring(6, 10)+v_tgl_kejadian.substring(3, 5)+v_tgl_kejadian.substring(0, 2);
						var v_jenis_klaim = getValue(jdata.dataKdKlaim.INFO_KLAIM.KODE_TIPE_KLAIM).substring(0, 3);
						var v_status_klaim = getValue(jdata.dataKdKlaim.INFO_KLAIM.STATUS_KLAIM);
												
            //set data manfaat -------------------------------------------------
            var html_mnf = "";	
						var v_tot_d_nom_biaya_disetujui = 0;
							
            if (jdata.data.DATA)
            { 
              for(var i = 0; i < jdata.data.DATA.length; i++)
              {
								html_mnf += '<tr>';
                html_mnf += '<td style="text-align: center;">' + getValue(jdata.data.DATA[i].NO_URUT) + '</td>';
                html_mnf += '<td style="text-align: center;">' + getValue(jdata.data.DATA[i].NAMA_TIPE_PENERIMA) + '</td>';
								html_mnf += '<td style="text-align: center;">' + getValue(jdata.data.DATA[i].BEASISWA_PENERIMA) + '</td>';
								html_mnf += '<td style="text-align: center;">' + getValue(jdata.data.DATA[i].BEASISWA_JENIS_NAMA) + '</td>';
								html_mnf += '<td style="text-align: center;">' + getValue(jdata.data.DATA[i].BEASISWA_JENJANG_PENDIDIKAN_NM) + '</td>';
								html_mnf += '<td style="text-align: right;">' + format_uang(getValueNumber(jdata.data.DATA[i].NOM_BIAYA_DISETUJUI)) + '</td>';
								html_mnf += '<td style="text-align: center;">' + getValue(jdata.data.DATA[i].KETERANGAN) + '</td>';
                
								if (getValue(jdata.data.DATA[i].KODE_SEGMEN)=="TKI" && getValue(jdata.data.DATA[i].TGL_KEJADIAN_YYYYMMDD)>="20181210")
    						{
  								html_mnf += '<td style="text-align: center;">' 
                              + '<a href="javascript:void(0)" onclick="fl_js_tap_mnf_tki_rinci(\'' 
  														+ getValue(jdata.data.DATA[i].KODE_KLAIM) + '\', \'' 
                              + getValue(jdata.data.DATA[i].KODE_MANFAAT) + '\', \''  
                              + getValue(jdata.data.DATA[i].NO_URUT) + '\')"><img src="../../images/indent_right.gif" border="0" alt="Rincian Manfaat" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> VIEW </font></a>'
															+ '<a href="javascript:void(0)" onclick="fl_js_tap_mnf_tki_histori(\'' 
  														+ getValue(jdata.data.DATA[i].KODE_KLAIM) + '\', \'' 
                              + getValue(jdata.data.DATA[i].KODE_MANFAAT) + '\', \''  
                              + getValue(jdata.data.DATA[i].BEASISWA_NIK_PENERIMA) + '\')"><img src="../../images/indent_right.gif" border="0" alt="Rincian Manfaat" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> HISTORI </font></a>' + '</td>';						
								}else if ((getValue(jdata.data.DATA[i].KODE_SEGMEN)=="PU" || getValue(jdata.data.DATA[i].KODE_SEGMEN)=="BPU" || getValue(jdata.data.DATA[i].KODE_SEGMEN)=="JAKON") && getValue(jdata.data.DATA[i].TGL_KEJADIAN_YYYYMMDD)>="20191202")
    						{
  									html_mnf += '<td style="text-align: center;">' 
                                + '<a href="javascript:void(0)" onclick="fl_js_tap_mnf_pp82_rinci(\'' 
    														+ getValue(jdata.data.DATA[i].KODE_KLAIM) + '\', \'' 
                                + getValue(jdata.data.DATA[i].KODE_MANFAAT) + '\', \''  
                                + getValue(jdata.data.DATA[i].NO_URUT) + '\')"><img src="../../images/indent_right.gif" border="0" alt="Rincian Manfaat" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> View | &nbsp;</font></a>'
  															+ '<a href="javascript:void(0)" onclick="fl_js_doCetakKartu(\'' 
                                + getValue(jdata.data.DATA[i].BEASISWA_NIK_PENERIMA) + '\')"><img src="../../images/printer.png" border="0" alt="Rincian Manfaat" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> Kartu Beasiswa </font></a>' + '</td>';														
    						}else
    						{						
  								html_mnf += '<td style="text-align: center;">' 
                              + '<a href="javascript:void(0)" onclick="fl_js_tap_mnf_rinci(\'' 
  														+ getValue(jdata.data.DATA[i].KODE_KLAIM) + '\', \'' 
                              + getValue(jdata.data.DATA[i].KODE_MANFAAT) + '\', \''  
                              + getValue(jdata.data.DATA[i].NO_URUT) + '\')"><img src="../../images/indent_right.gif" border="0" alt="Rincian Manfaat" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> VIEW </font></a>' + '</td>';
    						}	
								html_mnf += '</tr>';
                
								v_tot_d_nom_biaya_disetujui += parseFloat(getValueNumber(jdata.data.DATA[i].NOM_BIAYA_DISETUJUI),4);
              }

              if (html_mnf == "") {
                html_mnf += '<tr class="nohover-color">';
                html_mnf += '<td colspan="8" style="text-align: center;">-- data tidak ditemukan --</td>';
                html_mnf += '</tr>';
              }
              $("#data_tblMnf").html(html_mnf);
																					
							$("#mnf_tot_biaya_disetujui").html(format_uang(v_tot_d_nom_biaya_disetujui));		
            }
            //end set data manfaat ---------------------------------------------						

					//set rincian data amalgamasi dan upah jkm -------------------------
						if ((v_jenis_klaim=="JKM" || v_jenis_klaim=="JHM") && (v_kode_segmen=="PU" || v_kode_segmen=="BPU") && v_tgl_kejadian_yyymmdd>="20191202")
						{
              //set grid amalgamasi jkm ----------------------------------------
							var html_amg = "";
							html_amg += '<div class="div-row">';
              html_amg += '  <div class="div-col" style="width:100%; max-height: 100%;">';
              html_amg += '    <fieldset><legend>Amalgamasi JKM</legend>';
              html_amg += '      <table id="tblAmg" width="98%" class="table-data2">';
              html_amg += '        <thead>';												
              html_amg += '          <tr class="hr-double-bottom">';
              html_amg += '            <th style="text-align:center;">No. Referensi</th>';
              html_amg += '            <th style="text-align:center;">NIK</th>';
    					html_amg += '						<th style="text-align:left;">Nama TK</th>';
              html_amg += '            <th style="text-align:left;">Tempat Lahir</th>';
              html_amg += '            <th style="text-align:center;">Tgl Lahir</th>';
              html_amg += '            <th style="text-align:left;">Kode TK</th>';
              html_amg += '            <th style="text-align:left;">Kode TK AG</th>';											
              html_amg += '          </tr>';		
    					html_amg += '				</thead>';	
    					html_amg += '				<tbody id="data_tblAmg">';		
              html_amg += '          <tr class="nohover-color">';
              html_amg += '          	<td colspan="7" style="text-align: center;">-- data tidak ditemukan --</td>';
              html_amg += '          </tr>';																		             																
              html_amg += '        </tbody>';
    					html_amg += '				<tfoot>';
							html_amg += '          <tr>';
							html_amg += '          <td colspan="7">&nbsp;';
							html_amg += '          </td>';
							html_amg += '          </tr>';
              html_amg += '          <tr>';
              html_amg += '            <td style="text-align:left;" colspan="3"><i><b>&nbsp;</b></i>';
							html_amg += '    				</td>';							
              html_amg += '            <td style="text-align:right;" colspan="4"><i><b>&nbsp;</b></i>';
							html_amg += '    				</td>';														
              html_amg += '          </tr>';						
    					html_amg += '				</tfoot>';														
              html_amg += '      </table>';
              html_amg += '    </fieldset>';				 
    					html_amg += '	</div>';
    					html_amg += '</div>';	
							
							$("#divForm2").html(html_amg);

              var html_dtamg = "";	
              if (jdata.dataKdKlaim.INPUT_JPN_LISTAMALGAMASIJP)
              { 
                for(var i = 0; i < jdata.dataKdKlaim.INPUT_JPN_LISTAMALGAMASIJP.length; i++)
                {
  								html_dtamg += '<tr>';
                  html_dtamg += '  <td style="text-align: center;">' + getValue(jdata.dataKdKlaim.INPUT_JPN_LISTAMALGAMASIJP[i].KPJ) + '</td>';
                  html_dtamg += '	 <td style="text-align: center;">' + getValue(jdata.dataKdKlaim.INPUT_JPN_LISTAMALGAMASIJP[i].NOMOR_IDENTITAS) + '</td>';
  								html_dtamg += '	 <td style="text-align: left;">' + getValue(jdata.dataKdKlaim.INPUT_JPN_LISTAMALGAMASIJP[i].NAMA_TK) + '</td>';
  								html_dtamg += '	 <td style="text-align: left;">' + getValue(jdata.dataKdKlaim.INPUT_JPN_LISTAMALGAMASIJP[i].TEMPAT_LAHIR) + '</td>';
  								html_dtamg += '	 <td style="text-align: center;">' + getValue(jdata.dataKdKlaim.INPUT_JPN_LISTAMALGAMASIJP[i].TGL_LAHIR) + '</td>';
  								html_dtamg += '	 <td style="text-align: left;">' + getValue(jdata.dataKdKlaim.INPUT_JPN_LISTAMALGAMASIJP[i].KODE_TK) + '</td>';
  								html_dtamg += '	 <td style="text-align: left;">' + getValue(jdata.dataKdKlaim.INPUT_JPN_LISTAMALGAMASIJP[i].KODE_TK_GABUNG) + '</td>';
  								html_dtamg += '</tr>';
                }
                if (html_dtamg == "") {
                  html_dtamg += '<tr class="nohover-color">';
                  html_dtamg += '<td colspan="7" style="text-align: center;">-- data tidak ditemukan --</td>';
                  html_dtamg += '</tr>';
                }
                $("#data_tblAmg").html(html_dtamg);			
              }							
							//end set grid amalgamasi jkm ------------------------------------
							
							//set grid data upah ---------------------------------------------
							var html_upah = "";
							html_upah += '<div class="div-row">';
              html_upah += '  <div class="div-col" style="width:100%; max-height: 100%;">';
              html_upah += '    <fieldset><legend>Informasi Masa Iur sebagai Dasar Kelayakan Manfaat Beasiswa JKM</legend>';
              html_upah += '      <table id="tblUpah" width="98%" class="table-data2">';
              html_upah += '        <thead>';												
              html_upah += '          <tr class="hr-double-bottom">';
              html_upah += '            <th style="text-align:center;">No</th>';
              html_upah += '            <th style="text-align:center;">NPP</th>';
    					html_upah += '						<th style="text-align:left;">PK/BU/Wadah</th>';
              html_upah += '            <th style="text-align:center;">Unit</th>';
							html_upah += '            <th style="text-align:center;">Segmen</th>';
              html_upah += '            <th style="text-align:center;">Blth</th>';
              html_upah += '            <th style="text-align:right;">Upah</th>';
              html_upah += '            <th style="text-align:center;">Tgl Bayar</th>';
    					html_upah += '						<th style="text-align:center;">Tgl Posting</th>';
    					html_upah += '						<th style="text-align:center;width:100px;">Masa Iur Terhitung</th>';											
              html_upah += '          </tr>';		
    					html_upah += '				</thead>';	
    					html_upah += '				<tbody id="data_tblUpah">';		
              html_upah += '          <tr class="nohover-color">';
              html_upah += '          	<td colspan="10" style="text-align: center;">-- data tidak ditemukan --</td>';
              html_upah += '          </tr>';																		             																
              html_upah += '        </tbody>';
    					html_upah += '				<tfoot>';
              html_upah += '          <tr>';
              html_upah += '            <td style="text-align:right;" colspan="9"><i><b>Total Bulan Pembayaran Iuran&nbsp;</b></i>';
              html_upah += '              <input type="hidden" id="mnf_kounter_dtl" name="mnf_kounter_dtl" value="<?=$ln_dtl;?>">';
              html_upah += '              <input type="hidden" id="mnf_count_dtl" name="mnf_count_dtl" value="<?=$ln_countdtl;?>">';
              html_upah += '              <input type="hidden" name="mnf_showmessage" style="border-width: 0;text-align:right" readonly size="5">';				
              html_upah += '    				</td>';
    					html_upah += '						<td style="text-align:right"><b><span id="tot_jml_bln_pembayaran"></span></b></td>';																	
              html_upah += '          </tr>';
			  
              html_upah += '          <tr>';
              html_upah += '            <td style="text-align:right;" colspan="9"><i><b>Total Bulan Pengembalian Iuran &nbsp;</b></i>';

              html_upah += '    				</td>';
    					html_upah += '						<td style="text-align:right"><b><span id="tot_jml_bln_pengembalian"></span></b></td>';																	
              html_upah += '          </tr>';
              html_upah += '          <tr>';
              html_upah += '            <td style="text-align:right;" colspan="9"><i><b>Total Masa Iur&nbsp;</b></i>';				
              html_upah += '    				</td>';
    					html_upah += '						<td style="text-align:right"><b><span id="tot_jml_bln_masa_iur"></span></b></td>';																	
              html_upah += '          </tr>';
    					html_upah += '				</tfoot>';														
              html_upah += '      </table>';
              html_upah += '    </fieldset>';				 
    					html_upah += '	</div>';
    					html_upah += '</div>';	
							
							$("#divForm3").html(html_upah);
							
              var html_dtupah = "";	
  			//   var v_tot_d_jml_bln = 0;
			var v_tot_d_jml_bln_pembayaran = 0;	
			var v_tot_d_jml_bln_pengembalian = 0;
			var v_tot_d_jml_bln_iur = 0;
			var v_tot_d_jml_bln_pengembalian_abs = 0;	
              if (jdata.dataUpah.DATA)
              { 
                for(var i = 0; i < jdata.dataUpah.DATA.length; i++)
                {
  								html_dtupah += '<tr>';
                  html_dtupah += '  <td style="text-align: center;">' + getValue(jdata.dataUpah.DATA[i].NO) + '</td>';
                  html_dtupah += '	 <td style="text-align: center;">' + getValue(jdata.dataUpah.DATA[i].NPP) + '</td>';
  								html_dtupah += '	 <td style="text-align: left;">' + getValue(jdata.dataUpah.DATA[i].NAMA_PERUSAHAAN) + '</td>';
  								html_dtupah += '	 <td style="text-align: center;">' + getValue(jdata.dataUpah.DATA[i].KODE_DIVISI) + '</td>';
									html_dtupah += '	 <td style="text-align: center;">' + getValue(jdata.dataUpah.DATA[i].KODE_SEGMEN) + '</td>';
  								html_dtupah += '	 <td style="text-align: center;">' + getValue(jdata.dataUpah.DATA[i].BLTH_MM) + '</td>';
  								html_dtupah += '	 <td style="text-align: right;">' + format_uang(getValueNumber(jdata.dataUpah.DATA[i].NOM_UPAH)) + '</td>';
  								html_dtupah += '	 <td style="text-align: center;">' + getValue(jdata.dataUpah.DATA[i].TGL_BAYAR) + '</td>';
  								html_dtupah += '	 <td style="text-align: center;">' + getValue(jdata.dataUpah.DATA[i].TGL_REKON) + '</td>';
  								html_dtupah += '	 <td style="text-align: right;">' + getValueNumber(jdata.dataUpah.DATA[i].JML_BLN) + '</td>';
  								html_dtupah += '</tr>';
  								
  								v_tot_d_jml_bln_pembayaran += parseInt(getValueNumber(jdata.dataUpah.DATA[i].JML_BLN));
                }
  
                if (html_dtupah == "") {
                  html_dtupah += '<tr class="nohover-color">';
                  html_dtupah += '<td colspan="10" style="text-align: center;">-- data tidak ditemukan --</td>';
                  html_dtupah += '</tr>';
                }

				var v_tot_d_jml_bln_iur = v_tot_d_jml_bln_pembayaran + v_tot_d_jml_bln_pengembalian;

                $("#data_tblUpah").html(html_dtupah);
				$("#tot_jml_bln_pembayaran").html(format_nondesimal(v_tot_d_jml_bln_pembayaran));				
  				$("#tot_jml_bln_pengembalian").html(format_nondesimal(v_tot_d_jml_bln_pengembalian * -1));				
  				$("#tot_jml_bln_masa_iur").html(format_nondesimal(v_tot_d_jml_bln_iur));			
              }
              //end set grid data upah -----------------------------------------																	
						}											
						//end set rincian data amalgamasi dan upah jkm ---------------------
											
						//set button -------------------------------------------------------			
						window.document.getElementById("span_button_utama").style.display = 'block';
						//end set button ---------------------------------------------------	
						
						resize();						
					}
				} catch (e) {
					alert("Terjadi kesalahan, coba beberapa saat lagi!!!");
				}
				asyncPreload(false);
			},
			complete: function(){
				asyncPreload(false);
			},
			error: function(){
				alert("Terjadi kesalahan, coba beberapa saat lagi!!");
				asyncPreload(false);
			}
		});						
	}
	//end loadSelectedRecord -----------------------------------------------------
</script>

<script language="javascript">
	function fl_js_tap_mnf_rinci(p_kode_klaim, p_kode_manfaat, p_no_urut)
	{		
		var c_mid = '<?=$mid;?>';
		showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5069_view_penetapanmanfaat_beasiswarinci.php?kode_klaim='+p_kode_klaim+'&kode_manfaat='+p_kode_manfaat+'&no_urut='+p_no_urut+'&mid='+c_mid+'','',980,550,'yes');
	}
	
	function fl_js_tap_mnf_tki_rinci(p_kode_klaim, p_kode_manfaat, p_no_urut)
	{		
		var c_mid = '<?=$mid;?>';
		showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5069_view_penetapanmanfaat_beasiswatkirinci.php?kode_klaim='+p_kode_klaim+'&kode_manfaat='+p_kode_manfaat+'&no_urut='+p_no_urut+'&mid='+c_mid+'','',980,650,'yes');
	}
	
	function fl_js_tap_mnf_tki_histori(p_kode_klaim, p_kode_manfaat, p_beasiswa_nik_penerima)
	{		
		var c_mid = '<?=$mid;?>';
		showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5069_view_penetapanmanfaat_beasiswatkihistory.php?kode_klaim='+p_kode_klaim+'&kode_manfaat='+p_kode_manfaat+'&beasiswa_nik_penerima='+p_beasiswa_nik_penerima+'&mid='+c_mid+'','',980,550,'yes');
	}

	function fl_js_tap_mnf_pp82_rinci(v_kode_klaim, v_kode_manfaat, v_no_urut)
	{		
		var v_task = 'view';
		var c_mid = '<?=$mid;?>';
		
		showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5069_view_penetapanmanfaat_beasiswarincipp82.php?task='+v_task+'&kode_klaim='+v_kode_klaim+'&kode_manfaat='+v_kode_manfaat+'&no_urut='+v_no_urut+'&mid='+c_mid+'&form_penetapan=<?=$ls_form_penetapan;?>&sender=pn5061_penetapanmanfaat_beasiswa.php','',1250,580,'no');
	}

	function fl_js_doCetakKartu(v_nik_penerima)		
	{
    document.formreg.task_detil.value = 'doCetakKartu';
		document.formreg.nik_penerima_cetak.value = v_nik_penerima;
    try {
    		document.formreg.onsubmit();
    } catch(e){}
    document.formreg.submit();		
	}

	function reloadFormUtama()
  {
		document.formreg.task_detil.value = '';
    try {
    		document.formreg.onsubmit();
    } catch(e){}
    document.formreg.submit();	
  }							
</script>

<?php
include "../../includes/footer_app_nosql.php";
?>

