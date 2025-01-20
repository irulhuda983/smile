<?php
require_once "../../includes/header_app_nosql.php";	
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";

$pagetype = "form";

$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$USER = $_SESSION["USER"];
$KD_KANTOR 	= $_SESSION['kdkantorrole'];
$KODE_ROLE 	= $_SESSION['regrole'];

$kd_agenda = $_GET['kd_agenda'];
$kode_aktivitas = $_GET['kode_aktivitas'];
$max_no_urut_aktivitas = $_GET['max_no_urut_aktivitas'];
$aksi = $_GET['aksi'];
$no_urut = $_GET['no_urut'];


if ($kd_agenda != "" && $kode_aktivitas!="") {
	$sql="select (select z.keterangan
				from sijstk.ms_lookup z
			  where     tipe = 'KLMAKTLJKM'
					and nvl (aktif, 'T') = 'Y'
					and z.kode = c.kode_aktivitas)
				nama_aktivitas,
			(select z.nama_kelurahan
				from sijstk.ms_kelurahan z
			  where z.kode_kelurahan = c.kode_kelurahan)
				nama_kelurahan,
			(select z.nama_kecamatan
				from sijstk.ms_kecamatan z
			  where z.kode_kecamatan = c.kode_kecamatan)
				nama_kecamatan,
			(select z.nama_kabupaten
				from sijstk.ms_kabupaten z
			  where z.kode_kabupaten = c.kode_kabupaten)
				nama_kabupaten,
			(select z.nama_propinsi
				from sijstk.ms_propinsi z
			  where z.kode_propinsi = c.kode_provinsi)
				nama_provinsi,
			c.keterangan
				as keterangan_aktivitas,
			TO_CHAR(c.tgl_aktivitas,'DD/MM/RRRR') tgl_aktivitas_display,  
			c.*
		  from pn.pn_agenda_koreksi_klaim_jkmakt c
		  where kode_agenda = '$kd_agenda' and kode_aktivitas='$kode_aktivitas' and no_urut='$no_urut'"; 


		  $DB->parse($sql);
		  $DB->execute();
		  $row = $DB->nextrow();	
		  
		  $ls_tgl_aktivitas        = $row["TGL_AKTIVITAS_DISPLAY"];
		  $ls_kode_aktivitas       = $row["KODE_AKTIVITAS"];
		  $ls_nama_aktivitas       = $row["NAMA_AKTIVITAS"];
		  $ls_nama_sumber          = $row["NAMA_SUMBER"];
		  $ls_profesi_sumber       = $row["PROFESI_SUMBER"];
		  $ls_alamat               = $row["ALAMAT"];
		  $ls_kode_pos             = $row["KODE_POS"];
		  $ls_kode_kelurahan             = $row["KODE_KELURAHAN"];
		  $ls_nama_kelurahan             = $row["NAMA_KELURAHAN"];
		  $ls_kode_kecamatan             = $row["KODE_KECAMATAN"];
		  $ls_nama_kecamatan             = $row["NAMA_KECAMATAN"];
		  $ls_kode_kabupaten             = $row["KODE_KABUPATEN"];
		  $ls_nama_kabupaten             = $row["NAMA_KABUPATEN"];
		  $ls_kode_propinsi              = $row["KODE_PROVINSI"];
		  $ls_nama_propinsi              = $row["NAMA_PROVINSI"];
		  $ls_no_hp                      = $row["HANDPHONE"];
		  $ls_email		                 = $row["EMAIL"];
		  $ls_keterangan_aktivitas       = $row["KETERANGAN_AKTIVITAS"];
	  }

							    

?>
<script type="text/javascript" src="../../javascript/pdfutils/browser-image-compression.js"></script>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">  
<script language="javascript">
	$(document).ready(function(){

		$("input[type=text]").keyup(function(){
			$(this).val( $(this).val().toUpperCase() );
		});

		$("textarea").keyup(function(){
			$(this).val( $(this).val().toUpperCase() );
		});

  		$(window).bind("resize", function(){
			resize();
		});
		resize();

		$(".digit").keypress(function (e) {
                       //if the letter is not digit then display error and don't type anything
                       if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                          //display error message
                         // $("#errmsg").html("Digits Only").show().fadeOut("slow");
                                 return false;
                      }
           });

		   $("#nama_aktivitas").blur(function(){
			var kode_aktivitas  = $("#kode_aktivitas").val();
			if(kode_aktivitas=="AKTV03"){
				$('#alamat_lengkap').hide();
			}else{
				$('#alamat_lengkap').show();	
			}
			});

			 $("#tgl_aktivitas").blur(function(){
			var tglaktivitas  = $("#tgl_aktivitas").val();
			var datePartsawal = tglaktivitas.split("/");	
			var dateObjectawal = new Date(+datePartsawal[2], datePartsawal[1] - 1, +datePartsawal[0]);
			var ToDate = new Date(); 
			if(dateObjectawal > ToDate){
				$("#tgl_aktivitas").val('');
			alert("Tanggal Aktivitas tidak boleh lebih dari hari ini");
			}
			});

			var kode_aktivitas  = $("#kode_aktivitas").val();
			if(kode_aktivitas=="AKTV03"){
				$('#alamat_lengkap').hide();
			}else{
				$('#alamat_lengkap').show();	
			}

			let aksi = "<?php echo $aksi; ?>";
			if(aksi=="view_aktivitas"){
				$('#btnsimpan').hide();
				$("#tgl_aktivitas").prop('readonly',true);
                $("#tgl_aktivitas").css("background-color", "#F5F5F5");
				$("#nama_aktivitas").prop('readonly',true);
                $("#nama_aktivitas").css("background-color", "#F5F5F5");
				$("#narasumber").prop('readonly',true);
                $("#narasumber").css("background-color", "#F5F5F5");
				$("#profesi").prop('readonly',true);
                $("#profesi").css("background-color", "#F5F5F5");
				$("#alamat").prop('readonly',true);
                $("#alamat").css("background-color", "#F5F5F5");
				$("#nama_kelurahan").prop('readonly',true);
                $("#nama_kelurahan").css("background-color", "#F5F5F5");
				$("#nama_kecamatan").prop('readonly',true);
                $("#nama_kecamatan").css("background-color", "#F5F5F5");
				$("#nama_kabupaten").prop('readonly',true);
                $("#nama_kabupaten").css("background-color", "#F5F5F5");
				$("#nama_propinsi").prop('readonly',true);
                $("#nama_propinsi").css("background-color", "#F5F5F5");
				$("#kode_pos").prop('readonly',true);
                $("#kode_pos").css("background-color", "#F5F5F5");
				$("#no_hp").prop('readonly',true);
                $("#no_hp").css("background-color", "#F5F5F5");
				$("#email").prop('readonly',true);
                $("#email").css("background-color", "#F5F5F5");
				$("#keterangan_aktivitas").prop('readonly',true);
                $("#keterangan_aktivitas").css("background-color", "#F5F5F5");

				$('#lov_aktivitas').hide();
				$('#btn_tgl_aktivitas').hide();

			}

			let no_urut =  "<?php echo $no_urut; ?>";
			if(no_urut=="1" || no_urut=="2" || no_urut=="3"){
				$('#lov_aktivitas').hide();
			}else{
				$('#lov_aktivitas').show();
			}

	});


	function showFormReload(mypage, myname, w, h, scroll) {
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
			html: '<iframe src="' + mypage + '"  frameborder="0" style="border:0; height:100%; width:100%; overflow-y:hidden; overflow-x:hidden; overflow:hidden;" scrolling="no"></iframe>',
			listeners: {
				close: function () {
					reLoad();
				},
					destroy: function (wnd, eOpts) {
				}
			}
		});
		openwin.show();
		return openwin;
	}

	function reLoad(){
		location.reload();
	}

	function fl_js_val_numeric(v_field_id)
  {
    var c_val = window.document.getElementById(v_field_id).value;
    var number=/^[0-9]+$/;
    
    if ((c_val!='') && (!c_val.match(number)))
    {
      document.getElementById(v_field_id).value = '';	
      window.document.getElementById(v_field_id).focus();
      alert("Harus berisikan angka, tidak boleh alphabet atau karakter lainnya...! "); 			 
      return false; 				 
    }		
  }


	function resize(){
		$("#div_container").width($("#div_dummy").width());
		
		$("#div_header").width($("#div_dummy").width());
		$("#div_body").width($("#div_dummy").width());
		$("#div_footer").width($("#div_dummy").width());
		
		$("#div_filter").width(0);
		$("#div_data").width(0);
		$("#div_page").width(0);
		$("#div_footer").width(0);

		$("#div_filter").width($("#div_dummy_data").width());
		$("#div_data").width($("#div_dummy_data").width());
		$("#div_page").width($("#div_dummy_data").width());
		$("#div_footer").width($("#div_dummy_data").width());

		$("#div_container").css('max-height', $(window).height());
	}
	
	function confirmation(title, msg, fnYes, fnNo) {
		window.parent.Ext.Msg.show({
			title: title,
			msg: msg,
			buttons: window.parent.Ext.Msg.YESNO,
			icon: window.parent.Ext.Msg.QUESTION,
			fn: function(btn) {
				if (btn === 'yes') {
					fnYes();
				} else {
					fnNo();
				}
			}
		});
	}


	function close() {
		var win = window.parent.parent.Ext.WindowManager.getActive();
    		if (win) {
     	 win.close();
    	}
	}

  	function simpan() {
		

	var form = $('#formreg');
     var formdata = false;
     if (window.FormData){
        formdata = new FormData(form[0]);
    }

	let tgl_aktivitas = $('#tgl_aktivitas').val();
	let kode_aktivitas = $('#kode_aktivitas').val();
	let nama_aktivitas = $('#nama_aktivitas').val();
	let narasumber = $('#narasumber').val();
	let profesi = $('#profesi').val();
	let alamat = $('#alamat').val();
	let kode_pos = $('#kode_pos').val();
	let kode_kelurahan = $('#kode_kelurahan').val();
	let kode_kecamatan = $('#kode_kecamatan').val();
	let kode_kabupaten = $('#kode_kabupaten').val();
	let kode_propinsi  = $('#kode_propinsi').val();
	let no_hp = $('#no_hp').val();
	let email = $('#email').val();
	let keterangan_aktivitas = $('#keterangan_aktivitas').val();
	
	let aksi = "<?php echo $aksi; ?>";
	let kd_agenda = "<?php echo $kd_agenda; ?>";

	let no_urut_max = "<?php echo $max_no_urut_aktivitas; ?>";

	console.log(no_urut_max);


	let no_urut="";
	let konfirmasi="";

	if(aksi=='tambah_aktivitas'){
		no_urut = "<?php echo $max_no_urut_aktivitas; ?>";
		konfirmasi = "Apakah Anda yakin untuk menambahkan data ini?";
	}else{
		no_urut = "<?php echo $no_urut; ?>";
		konfirmasi = "Apakah Anda yakin untuk mengedit data ini?";
	}

		confirmation("Konfirmasi", konfirmasi,
			function () {

				
			  	if(tgl_aktivitas==""){
					return alert('Tanggal Aktivitas tidak boleh kosong');
				}

				if(kode_aktivitas==""){
					return alert('Aktivitas tidak boleh kosong');
				}

				if(narasumber==""){
					return alert('Narasumber tidak boleh kosong');
				}

				if(profesi==""){
					return alert('Profesi tidak boleh kosong');
				}

				if(alamat=="" && kode_aktivitas!="AKTV03"){
					return alert('Alamat tidak boleh kosong');
				}

				if(kode_pos=="" && kode_aktivitas!="AKTV03"){
					return alert('Kode Pos tidak boleh kosong');
				}

				if(kode_kelurahan=="" && kode_aktivitas!="AKTV03"){
					return alert('Kelurahan tidak boleh kosong');
				}

				if(kode_kecamatan=="" && kode_aktivitas!="AKTV03"){
					return alert('Kecamatan tidak boleh kosong');
				}

				if(kode_kabupaten=="" && kode_aktivitas!="AKTV03"){
					return alert('Kabupaten tidak boleh kosong');
				}

				if(no_hp=="" && kode_aktivitas!="AKTV03"){
					return alert('No Telepon tidak boleh kosong');
				}

				if(keterangan_aktivitas=="" && kode_aktivitas!="AKTV03"){
					return alert('Keterangan tidak boleh kosong');
				}

				preload(true);
				$.ajax({
					type: 'POST',
					url: "../ajax/pn6001_jkm_konfirmasi_klaim_keps_aktif_action.php?"+Math.random(),
					data : {
						tgl_aktivitas : tgl_aktivitas,
						kode_aktivitas : kode_aktivitas,
						nama_aktivitas : nama_aktivitas,
						narasumber : narasumber,
						profesi : profesi,
						alamat : alamat,
						kode_pos : kode_pos,
						kode_kelurahan : kode_kelurahan,
						kode_kecamatan : kode_kecamatan,
						kode_kabupaten : kode_kabupaten,
						kode_propinsi  : kode_propinsi,
						no_hp : no_hp,
						keterangan_aktivitas : keterangan_aktivitas,
						aksi : aksi,
						kd_agenda : kd_agenda,
						no_urut : no_urut,
						email : email
					},
					success: function(data){
						jdata = JSON.parse(data);
						if (jdata.ret == 0){
							
							close();
							preload(false);
							
						} else {
							
							alert("Terjadi kesalahan, pastikan data yang di input lengkap dan benar.");
						}
						
					},
					complete: function(){
						
						preload(false);
					},
					error: function(){
						alert("Terjadi kesalahan, coba beberapa saat lagi!");
						
						preload(false);
					}
				});
			}, 
		function (){});
	}
	

	
	
	 


	

</script>
<style>
	.div-container{
		min-width: 700px;
		width: 100%;
	}
	.div-header{
		min-width: 700px;
	}
	.div-body{
		overflow-x: auto; 
		overflow-y: auto; 
		white-space: nowrap;
	}
	.div-data{
		overflow-x: auto; 
		overflow-y: auto; 
		white-space: nowrap;
	}
	.div-footer{
		padding-top: 10px;
		border-bottom: 1px solid #eeeeee;
	}
	.hr-double{
		border-top:3px double #8c8c8c;
		border-bottom:3px double #8c8c8c;
	}
  .hr-double-top{
    border-top:3px double #8c8c8c;
	}
  .hr-double-bottom{
  	border-bottom:3px double #8c8c8c;
	}
	.hr-double-left{
    border-left:3px double #8c8c8c;
	}
  .hr-double-right{
    border-right:3px double #8c8c8c;
	}
	.table-data{
		width: 100%;
		border-collapse: collapse;
		border-color: #c0c0c0;
		background-color: #ffffff;
	}
	.table-data th{
		padding: 10px 6px 10px 6px;
		font-weight: bold;
		text-align: left;
	}
	.table-data td{
		padding: 4px 6px 4px 6px;
		text-align: left;
		border-bottom: 1px solid #c0c0c0;
	}
	.table-data tr:last-child td{
		border-bottom:3px double #8c8c8c;
	}
	.table-data tbody tr:hover{
		cursor: pointer;
		background-color:#f5f5f5;
	}
  .nohover-color:hover {
		cursor: pointer!important;
    background-color:#FFFFFF!important;
	}
	.value-modified{
    background-color: #b4eeb4!important;
  }
</style>
<div id="formframe2">
	<div id="div_dummy" style="width: 100%;"></div>
	<div id="formKiri">
		<form name="formreg" id="formreg" role="form" method="post" enctype="multipart/form-data">
			<div id="div_container" class="div-container">
				<div id="div_header" class="div-header">
				</div>
				<div id="div_body" class="div-body">
					<div id="div_dummy_data" style="width: 100%;"></div>
					<div id="div_filter">
						<div style="padding-top: 0px;">
						<table border='0' width="100%">
								<tr>
									<td width="100%" valign="top">
										<div class="l_frm" ><label for="tgl_aktivitas">Tanggal Aktivitas:<span style="color:#ff0000;">&nbsp;*</span></label></div>
										<div class="r_frm">
											<input type="text" id="tgl_aktivitas" name="tgl_aktivitas" value="<?=$ls_tgl_aktivitas?>" required style="width:150px;background-color:#ffff99;" readonly="readonly">
											<input id="btn_tgl_aktivitas" type="image" align="top" onclick="return showCalendar('tgl_aktivitas', 'dd-mm-y');" src="../../images/calendar.gif"> 
										</div>
										<div class="l_frm" ><label for="aktivitas">Aktivitas <span style="color:#ff0000;">&nbsp;*</span> :</label></div>
										<div class="r_frm">
											<input type="hidden" id="kode_aktivitas" name="kode_aktivitas" value="<?=$ls_kode_aktivitas;?>" style="width:265px;background-color:#ffff99; text-align: ;"readonly />
											<input type="text" id="nama_aktivitas" name="nama_aktivitas" value="<?=$ls_nama_aktivitas;?>" style="width:265px;background-color:#ffff99; text-align: ;"readonly />
											<a href="#" id="lov_aktivitas" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_jkm_klaim_keps_aktif_lov_aktivitas.php?p=pn6001.php&a=formreg&b=nama_aktivitas&c=kode_aktivitas','',1000,500,1)" tabindex="8">                            
											<img src="../../images/help.png" alt="Cari Aktivitas" border="0" align="absmiddle"></a>   
										</div>
										<div class="l_frm" ><label for="narasumber">Narasumber:<span style="color:#ff0000;">&nbsp;*</span></label></div>
										<div class="r_frm">
											<input type="text" id="narasumber" name="narasumber" value="<?=$ls_nama_sumber?>" required style="width:265px;background-color:#ffff99;"> 
										</div>
										<div class="l_frm" ><label for="profesi">Profesi/ Jabatan:<span style="color:#ff0000;">&nbsp;*</span></label></div>
										<div class="r_frm">
											<input type="text" id="profesi" name="profesi" value="<?=$ls_profesi_sumber?>" required style="width:265px;background-color:#ffff99;"> 
										</div>
										<div id="alamat_lengkap">
										<div class="l_frm" ><label for="alamat">Alamat:<span style="color:#ff0000;width: 300px">&nbsp;*</span></label></div>   
										<div class="r_frm">
										<textarea id="alamat" maxlength="300" name="alamat" style="background-color:#ffff99;width:265px;" rows="2" <?=$i_readonly;?>><?=$ls_alamat;?></textarea> 
										</div>
										<div class="l_frm" ><label for="lab_kode_kelurahan">Kelurahan:<span style="color:#ff0000;width: 300px">&nbsp;*</span></label></div>
										<div class="r_frm">
											<input type= "hidden" id = "kode_kelurahan" name = "kode_kelurahan" value="<?=$ls_kode_kelurahan;?>" style="width: 100px;background-color:#ffff99;"required readonly>
											<input type= "text" id = "nama_kelurahan" name = "nama_kelurahan" value="<?=$ls_nama_kelurahan;?>" style="width: 200px;background-color:#ffff99;" required readonly>
											<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_lov_pos.php?p=tc5001.php&a=formreg&b=kode_kelurahan&c=nama_kelurahan&d=kode_kecamatan&e=nama_kecamatan&f=kode_kabupaten&g=nama_kabupaten&h=kode_propinsi&j=nama_propinsi&k=kode_pos','_faskeslovprop',800,500,1)" tabindex="8">							
											<img src="../../images/help.png" alt="Cari Kabupaten" border="0" align="absmiddle"></a>	     
										</div>
										<div class="l_frm" ><label for="lab_kode_kecamatan">Kecamatan:<span style="color:#ff0000;width: 300px">&nbsp;*</span></label></div>
										<div class="r_frm">
											<input type= "hidden" id = "kode_kecamatan" name = "kode_kecamatan" value="<?=$ls_kode_kecamatan;?>" style="width: 100px;background-color:#ffff99;" required readonly>
											<input type= "text" id = "nama_kecamatan" name = "nama_kecamatan" value="<?=$ls_nama_kecamatan;?>" style="width: 200px;background-color:#ffff99;" required readonly>   
										</div>
										<div class="l_frm" ><label for="lab_kode_kabupaten">Kabupaten:<span style="color:#ff0000;width: 300px">&nbsp;*</span></label></div>
										<div class="r_frm">
											<input type= "hidden" id = "kode_kabupaten" name = "kode_kabupaten" value="<?=$ls_kode_kabupaten;?>" style="width: 100px;background-color:#ffff99;" required readonly>
											<input type= "text" id = "nama_kabupaten" name = "nama_kabupaten" value="<?=$ls_nama_kabupaten;?>" style="width: 200px;background-color:#ffff99;" required readonly>
											  
										</div>
										<div class="l_frm" ><label for="lab_kode_propinsi">Propinsi:<span style="color:#ff0000;width: 300px">&nbsp;*</span></label></div>
										<div class="r_frm">
											<input type= "hidden" id = "kode_propinsi" name = "kode_propinsi" value="<?=$ls_kode_propinsi;?>" style="width: 100px;background-color:#ffff99;" required readonly>
											<input type= "text" id = "nama_propinsi" name = "nama_propinsi" value="<?=$ls_nama_propinsi;?>" style="width: 200px;background-color:#ffff99;" required readonly> 
										</div>
										<div class="l_frm" ><label for="lab_kode_pos">Kode Pos:<span style="color:#ff0000;width: 300px">&nbsp;*</span></label></div>
										<div class="r_frm">
											<input type= "text" id = "kode_pos" name = "kode_pos" value="<?=$ls_kode_pos;?>" style="width: 100px;background-color:#ffff99;" required readonly>
										</div>
										</div>
										<div class="l_frm" ><label for="lab_no_hp">Nomor Handphone:<span style="color:#ff0000;width: 300px">&nbsp;*</span></label></div>
										<div class="r_frm">
											<input type= "text" id = "no_hp" name = "no_hp"  value="<?=$ls_no_hp;?>" maxlength="20" style="width: 200px;background-color:#ffff99;" onblur="fl_js_val_numeric('no_hp');"> 
										</div>
										<div class="l_frm" ><label for="lab_email">Email:</label></div>
										<div class="r_frm">
											<input type= "text" id = "email" name = "email"  value="<?=$ls_email;?>" style="width: 200px;background-color:#ffff99;"> 
										</div>
										<div class="l_frm" ><label for="keterangan">Keterangan:<span style="color:#ff0000;width: 300px">&nbsp;*</span></label></div>   
										<div class="r_frm">
										<textarea id="keterangan_aktivitas" maxlength="300" name="keterangan_aktivitas" style="background-color:#ffff99;width:265px;" rows="2" <?=$i_readonly;?>><?=$ls_keterangan_aktivitas;?></textarea> 
										</div>
										<div class="l_frm" ><label for="keterangan">&nbsp;</label></div>   
										<div class="r_frm">
  										<br>
										<input type="button" name="btnsimpan" style="width: 100px" id="btnsimpan" value="SIMPAN" onclick="simpan()" class="btn green"/>
										</div>
									</td>
									<td>
									</td>
								</tr>
							</table>
						</div>
					</div>
				</div>
			</div>		
		</form>
	</div>
</div>
<script type="text/javascript">
</script>
<?php
include "../../includes/footer_app_nosql.php";
?>