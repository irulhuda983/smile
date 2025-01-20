<?php
$pagetype = "form";
$gs_pagetitle = "PN5011 - REALISASI KEGIATAN PROMOTIF/PREVENTIF";
require_once "../../includes/header_app_nosql.php";	
$mid = $_REQUEST["mid"];
/* ============================================================================
Ket : Form ini digunakan untuk perekaman data realisasi promotif/preventif
Hist: - 10/08/2017 : Pembuatan Form (Tim SIJSTK)								 						 
-----------------------------------------------------------------------------*/
?>
<form name="formreg" id="formreg" role="form" method="post">
	<!-- LOCAL JAVASCRIPTS------------------------------------------------------->		
  <script type="text/javascript" src="../../javascript/calendar.js"></script>
  <script type="text/javascript" src="../../javascript/numeral.min.js"></script>
  <script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
	
	<script language="javascript">    
    function NewWindow4(mypage,myname,w,h,scroll){
    		var openwin = window.parent.Ext.create('Ext.window.Window', {
    		title: myname,
    		collapsible: true,
    		animCollapse: true,
    
    		maximizable: true,
    		width: w,
    		height: h,
    		minWidth: 600,
    		minHeight: 400,
    		layout: 'fit',
    		html:'<iframe src="'+mypage+'"  height="100%" width="100%" frameborder="0" style="border:0; height:100%; width:100%;scrollbars=no;"></iframe>',
    		dockedItems: [{
      			xtype: 'toolbar',
      			dock: 'bottom',
      			ui: 'footer',
      			items: [
      				{ 
      					xtype: 'button',
      					text: 'Tutup',
      					handler : function(){
      						openwin.close();
      					}
      				}
      			]
      		}]
    	});
    	openwin.show();
    }
		
    function fl_js_reset_keyword2()
    {
      document.getElementById('keyword2a').value = '';		
    }				
  </script>

  <script language="javascript">
    function bkwindow(mypage,myname,w,h,scroll){
      var winl = (screen.width-w)/2;
      var wint = (screen.height-h)/2;
      var settings  ='height='+h+',';
      settings +='width='+w+',';
      settings +='top='+wint+',';
      settings +='left='+winl+',';
      settings +='scrollbars='+scroll+',';
      settings +='resizable=1';
      win=window.open(mypage,myname,settings);
      if(parseFloat(navigator.appVersion) >= 4){win.window.focus();}
    }				
  </script>
	
  <script language="javascript">
		function doSubmitTanpaOtentikasi() {
      var form = document.formreg;
			if(form.kode_kantor.value==""){
        alert('Kantor Pelayanan kosong, harap lengkapi data input...!!!');
        form.kode_kantor.focus();
      }else if(form.kode_kegiatan.value==""){
        alert('Kegiatan, harap lengkapi data input...!!!');
        form.kode_kegiatan.focus();
      }else if(form.kategori_pelaksana.value==""){
        alert('Pelaksana Kegiatan kosong, harap lengkapi data input...!!!');
        form.kategori_pelaksana.focus();
      }else if(form.nama_pelaksana.value==""){
        alert('Nama Pelaksana kosong, harap lengkapi data input...!!!');
        form.nama_pelaksana.focus();				
      }else if(form.tgl_kegiatan.value==""){
        alert('Tgl Kegiatan kosong, harap lengkapi data input...!!!');
        form.tgl_kegiatan.focus(); 	
      }else if(form.kode_sub_kegiatan.value==""){
        alert('Tema Kegiatan kosong, harap lengkapi data input...!!!');
        form.kode_sub_kegiatan.focus();		
      }else if(form.nama_detil_kegiatan.value==""){
        alert('Detil Kegiatan kosong, harap lengkapi data input...!!!');
        form.nama_detil_kegiatan.focus();	
      }else if(form.kode_segmen.value==""){
        alert('Segmentasi Kepesertaan kosong, harap lengkapi data input...!!!');
        form.kode_segmen.focus();													      
      }else
      {
       form.btn_task.value="submit_data_tanpa_otentikasi";
       form.submit();			 
      }
    }								
  </script>
  <script language="javascript">
    function fl_js_lov_promotif(v_kategori_pelaksana) 
    { 
    	var form = document.formreg;
			var	v_kategori_pelaksana = window.document.getElementById('kategori_pelaksana').value;
			var	v_status_klaim 			 = window.document.getElementById('status_klaim').value;
			
			if (v_kategori_pelaksana =="PR") //perusahaan --------------------------
      {
        window.document.getElementById("span_lov_promotif").style.display = 'block';
				window.document.getElementById('nama_pelaksana').readOnly = true;
  			window.document.getElementById('nama_pelaksana').style.backgroundColor='#F2F2F2';		
      }else //pihak ketiga ---------------
      {
        window.document.getElementById("span_lov_promotif").style.display = 'none';
        window.document.getElementById("kode_promotif").value = "";
				if (v_status_klaim!=="" && v_status_klaim!=="AGENDA")
				{
  				window.document.getElementById('nama_pelaksana').readOnly = true;
    			window.document.getElementById('nama_pelaksana').style.backgroundColor='#F2F2F2';				
				}else
				{ 
  				window.document.getElementById('nama_pelaksana').readOnly = false;
    			window.document.getElementById('nama_pelaksana').style.backgroundColor='#ffff99';	
				}			
      } 	
    }
	</script>							
	<!-- end LOCAL JAVASCRIPTS -------------------------------------------------->
	
	<!-- LOCAL CSS -------------------------------------------------------------->
  <link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
  <link rel="stylesheet" type="text/css" href="../../style/jquery.dataTables.min.css">
  <style>
  .errorField{
  	border: solid #fe8951 1px !important;
      background: rgba(254, 145, 81, 0.24);
  }
  .dataValid{
    background: #09b546;
    padding: 2px;
    color: #FFF;
    border-radius: 5px;
  }
  input.file{
  	box-shadow:0 0 !important;
  	border:0 !important; 
  }
  input[disabled].file{
    background:#FFF !important;
  }
  input.file::-webkit-file-upload-button {
    background: -webkit-linear-gradient(#5DBBF6, #2788E0);
    border: 1px solid #5492D6;
    border-radius:2px;
    color:#FFF;
    cursor:pointer;
  }
  input[disabled].file::-webkit-file-upload-button {
    background: -webkit-linear-gradient(#C0C0C0, #9A9A9A);
    border: 1px solid #ABABAB;
    cursor:no-drop;
  }
  input.file::-webkit-file-upload-button:hover {
    background: linear-gradient(#157fcc, #2a6d9e);
  }
  input[disabled].file::-webkit-file-upload-button:hover {
    background: -webkit-linear-gradient(#C0C0C0, #9A9A9A);
  }
	
  #mydata_grid th {
		font: 12px Verdana, Arial, Helvetica, sans-serif;					 
    border-right: 1px solid silver;
    border-bottom: 0.5pt solid silver !important;
    border-top: 0.5pt solid silver !important;
    text-align: center !important;
  }	
  #mydata_grid td {
		font: 10px Verdana, Arial, Helvetica, sans-serif;
    border-right: 0px solid rgb(221, 221, 221);
    border-bottom: 1px solid rgb(221, 221, 221);
    padding-top: 2px;
    padding-bottom: 2px;
  }
	  
  </style>
	<!-- end LOCAL CSS ---------------------------------------------------------->
	
	<!-- LOCAL GET/POST PARAMETER ----------------------------------------------->
  <?
  $ls_dataid  					 = $_REQUEST["dataid"];
  $ls_kode_realisasi		 = !isset($_GET['kode_realisasi']) ? $_POST['kode_realisasi'] : $_GET['kode_realisasi'];
	$ls_kode_klaim		 		 = !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
	$ls_status_klaim		 	 = !isset($_GET['status_klaim']) ? $_POST['status_klaim'] : $_GET['status_klaim'];
	$btn_task 						 = !isset($_GET['btn_task']) ? $_POST['btn_task'] : $_GET['btn_task'];
	
	$ls_activetab  				 = !isset($_GET['activetab']) ? $_POST['activetab'] : $_GET['activetab'];  
	if ($ls_activetab=="")
  {
   $ls_activetab = "1";
  }

	if($btn_task=="submit_data_tanpa_otentikasi")
  {
    //cek apakah kelengkapan administrasi yg mandatory sudah diserahkan semua --
		if ($ls_status_klaim=="AGENDA")
		{	
    		$sql = "select count(*) as v_jml from sijstk.pn_klaim_dokumen ". 
               "where kode_klaim='$ls_kode_klaim' ".
               "and nvl(syarat_tahap_ke,1) = 1 ".
               "and nvl(flag_mandatory,'T')='Y' ".
               "and nvl(status_diserahkan,'T')='T' ";
				$DB->parse($sql);
        $DB->execute();
        $row = $DB->nextrow();
        $ln_jml_belum_diserahkan = $row["V_JML"];		
		}		
		
		//submit data klaim --------------------------------------------------------
		if ($ln_jml_belum_diserahkan > "0")
		{
  		$msg = "Submit Data belum dapat dilakukan, dokumen persyaratan mandatory belum dilengkapi...!!!";			 	
      echo "<script language=\"JavaScript\" type=\"text/javascript\">";
      echo "window.location.replace('?&task=Edit&mid=$mid&kode_realisasi=$ls_kode_realisasi&kode_klaim=$ls_kode_klaim&dataid=$ls_kode_realisasi&msg=$msg&ls_error=1');";
			echo "alert('$msg')";
      echo "</script>";		
		}else
		{
  		if ($ls_status_klaim=="AGENDA")
  		{
    			$qry = "update sijstk.pn_klaim ".
                  "set status_submit_agenda     = 'Y', ".
                  "    tgl_submit_agenda        = sysdate, ".
                  "    petugas_submit_agenda    = '$username', ".
                  "    status_submit_pengajuan  = 'Y', ".
                  "    tgl_submit_pengajuan  	 	= sysdate, ".
                  "    petugas_submit_pengajuan = '$username', ".
                  "    status_submit_agenda2 		= 'Y', ".
                  "    tgl_submit_agenda2    		= sysdate, ".
                  "    petugas_submit_agenda2 	= '$username', ".
									"		 status_klaim					 		= 'PENETAPAN', ".
                  "    tgl_ubah           	 		= sysdate, ".
                  "    petugas_ubah       	 		= '$username' ".
                  "where kode_klaim = '$ls_kode_klaim' ";
          $DB->parse($qry);
          $DB->execute();	
  				$ls_ket_submit = "SUBMIT AGENDA KLAIM";
  		}
  		
  		if ($ls_ket_submit!="")
  		{
    		//generate aktivitas klaim -----------------------------------------------
    		$sql = "select nvl(max(no_urut),0)+1 as v_nourut from sijstk.pn_klaim_aktivitas ".
               "where kode_klaim = '$ls_kode_klaim' ";
        $DB->parse($sql);
        $DB->execute();
        $row = $DB->nextrow();
        $ln_no_urut = $row["V_NOURUT"];	
    		
    		$sql = "insert into sijstk.pn_klaim_aktivitas ( ".
               "	kode_klaim, no_urut, kode_aktivitas, tgl_mulai, tgl_akhir, status_aktivitas, keterangan, tgl_rekam, petugas_rekam) ". 
               "values ( ".
               "	'$ls_kode_klaim', '$ln_no_urut', 'SUBMIT', sysdate, sysdate, 'TERBUKA', '$ls_ket_submit', sysdate, '$username' ".  
               ") ";
        $DB->parse($sql);
        $DB->execute();
    
    		$sql = "update sijstk.pn_klaim_aktivitas a set status_aktivitas = 'SELESAI',tgl_akhir = sysdate,tgl_ubah = sysdate,petugas_ubah='$username' ".
               "where kode_klaim = '$ls_kode_klaim' ".
               "and no_urut in ".
               "( ".
               "     select max(no_urut) from sijstk.pn_klaim_aktivitas ".
               "     where kode_klaim = a.kode_klaim ".
               "     and no_urut < '$ln_no_urut' ".  
               "     ) ";
        $DB->parse($sql);
        $DB->execute();
    		//end generate aktivitas klaim -------------------------------------------
  		}
  				
  		$msg = "Submit Data berhasil dilakukan, session dilanjutkan..."; 	
      echo "<script language=\"JavaScript\" type=\"text/javascript\">";
      echo "window.location.replace('?&task=Edit&mid=$mid&kode_realisasi=$ls_kode_realisasi&kode_klaim=$ls_kode_klaim&dataid=$ls_kode_realisasi&msg=$msg');";
      echo "</script>";
		}													
	}		
			
  if(isset($_POST["butentry"]))
  {
    echo "<script language=\"JavaScript\" type=\"text/javascript\">";
    echo "window.location.replace('?task=New&mid=$mid');";
    echo "</script>";		
  }

	if($_REQUEST["task"] == "New")
  {
    /*--------------------------------------------------------------------*/
    /* PF A.0.1 Create Initial Value 
    ----------------------------------------------------------------------*/
    //Kantor -------------------------------------------------------------
    $ls_kode_kantor = isset($ls_kode_kantor) ? $ls_kode_kantor : $gs_kantor_aktif;
    if($ls_kode_kantor=="")
    {
     	$ls_kode_kantor =  $gs_kantor_aktif;
    }	
    //Tgl Agenda : sysdate
    $sql = "select to_char(sysdate,'dd/mm/yyyy') as tgl from dual ";
    $DB->parse($sql);
    $DB->execute();
    $row = $DB->nextrow();
    $ld_tgl_realisasi	 = $row["TGL"];
		$ls_kode_segmen = "PU";
		$ls_status_klaim ="AGENDA";			
  }else
  {
    //query data -----------------------------------------------------------
    $sql = "select 
              a.kode_realisasi, a.kode_kantor, to_char(a.tgl_realisasi,'dd/mm/yyyy') tgl_realisasi, 
              a.kategori_pelaksana, a.nama_pelaksana, a.alamat_pelaksana, a.npwp_pelaksana, 
              a.email_pelaksana, a.pic_pelaksana, a.no_telp_pic_pelaksana, 
              a.kode_kegiatan, a.kode_sub_kegiatan, a.nama_sub_kegiatan, a.nama_detil_kegiatan, 
              a.nom_diajukan, a.nom_disetujui, a.keterangan, 
              a.kode_promotif, (select npp from sijstk.pn_promotif where kode_promotif = a.kode_promotif) npp,
							a.kode_segmen,  
              a.status_batal, a.tgl_batal, a.petugas_batal, 
              a.tgl_rekam, a.petugas_rekam,
							to_char(a.tgl_kegiatan,'dd/mm/yyyy') tgl_kegiatan, a.bentuk_kegiatan,a.kode_jasa, a.no_faktur,
							(select nama_komponen from sijstk.vw_pn_promotif_komponen where bentuk_kegiatan = a.bentuk_kegiatan and kode_komponen = a.kode_jasa) nama_jasa,
							a.kode_komponen_biaya, a.kode_klaim,
							(select status_klaim from sijstk.pn_klaim where kode_klaim = a.kode_klaim) status_klaim,
							(select count(*) from sijstk.pn_promotif_realisasi_detil where kode_realisasi = a.kode_realisasi and nvl(nom_diajukan,0)<>0) cnt_detil
            from sijstk.pn_promotif_realisasi a
            where kode_realisasi = '".$_GET['dataid']."' ";
    //echo $sql;
    $DB->parse($sql);
    $DB->execute();
    $row = $DB->nextrow();
    $ls_kode_realisasi 				= $row['KODE_REALISASI'];		
		$ls_kode_kantor 					= $row['KODE_KANTOR'];
		$ls_kode_segmen 					= $row['KODE_SEGMEN'];		
    $ld_tgl_realisasi					= $row['TGL_REALISASI'];
		$ld_tgl_kegiatan					= $row['TGL_KEGIATAN'];
    $ls_kategori_pelaksana		= $row['KATEGORI_PELAKSANA'];
		$ls_kode_promotif 				= $row['KODE_PROMOTIF'];
    $ls_npp										= $row['NPP'];
		$ls_nama_pelaksana				= $row['NAMA_PELAKSANA'];
    $ls_alamat_pelaksana 			= $row['ALAMAT_PELAKSANA'];
    $ls_npwp_pelaksana				= $row['NPWP_PELAKSANA'];
		$ls_email_pelaksana				= $row['EMAIL_PELAKSANA'];
    $ls_pic_pelaksana					= $row['PIC_PELAKSANA'];
    $ls_no_telp_pic_pelaksana	= $row['NO_TELP_PIC_PELAKSANA'];
    $ls_kode_kegiatan					= $row['KODE_KEGIATAN'];
		$ls_kode_sub_kegiatan			= $row['KODE_SUB_KEGIATAN'];
		$ls_nama_sub_kegiatan			= $row['NAMA_SUB_KEGIATAN'];
    $ls_nama_detil_kegiatan		= $row['NAMA_DETIL_KEGIATAN'];
    $ln_nom_diajukan  				= $row['NOM_DIAJUKAN'];
    $ln_nom_disetujui					= $row['NOM_DISETUJUI'];
    $ls_keterangan						= $row['KETERANGAN'];
    $ls_kode_klaim						= $row['KODE_KLAIM'];
		$ls_status_klaim					= $row['STATUS_KLAIM'];
    $ld_tgl_rekam 						= $row['TGL_REKAM'];
    $ls_petugas_rekam 				= $row['PETUGAS_REKAM'];
		$ls_bentuk_kegiatan 			= $row['BENTUK_KEGIATAN'];
		$ls_kode_jasa							= $row['KODE_JASA'];
		$ls_nama_jasa							= $row['NAMA_JASA']; 
		$ln_cnt_detil							= $row['CNT_DETIL']; 
		$ls_no_faktur 		 				= $row['NO_FAKTUR']; 
  }			
  ?>
	<!-- end LOCAL GET/POST PARAMETER ------------------------------------------->
	
	<!-- VALIDASI AJAX ---------------------------------------------------------->
	<!-- end VALIDASI AJAX ------------------------------------------------------>	
		
	<!-- ACTION BUTTON ---------------------------------------------------------->
  <div id="actmenu">
  	<div id="actbutton">
  		<div style="float:left;">
  		<?PHP
  		if(isset($_REQUEST["task"]))
  		{
  		 	?>
  			<?PHP
  			if($_REQUEST["task"] == "Edit" || $_REQUEST["task"] == "New")
  			{
  			 	?>
          <div style="float:left;"><div class="icon">
          	<a id="btn_save" href="javascript:void(0)"><img src="http://<?=$HTTP_HOST;?>/images/save-as.gif" align="absmiddle" border="0"> Save</a>
          </div></div>
          <?PHP
        }; 
        ?>        
        <div style="float:left;"><div class="icon">
        	<a id="btn_close" href="http://<?=$HTTP_HOST;?>/mod_pn/form/pn5011.php?mid=<?=$mid;?>"><img src="http://<?=$HTTP_HOST;?>/images/file_cancel.gif" align="absmiddle" border="0"> Close</a> 
        </div></div>
        <?PHP
      } 
  		else 
  		{
        ?>
        <div class="icon">
        	<a href="javascript:void(0)" id="btn_view">
        	<img src="http://<?=$HTTP_HOST;?>/images/application_get.png" align="absmiddle" border="0"> View</a></div></div>
        <div style="float:left;"><div class="icon">
        	<a id="btn_edit" href="javascript:void(0)" ><img src="http://<?=$HTTP_HOST;?>/images/app_form_edit.png" align="absmiddle" border="0"> Edit</a></div></div>
        <div style="float:left;"><div class="icon">
        	<a id="btn_delete" href="javascript:void(0)"><img src="http://<?=$HTTP_HOST;?>/images/app_form_delete.png" align="absmiddle" border="0"> Delete</a></div></div>
        <div style="float:left;">
        <div class="icon"><a id="btn_new" href="javascript:void(0)">
        	<img src="http://<?=$HTTP_HOST;?>/images/app_form_add.png" align="absmiddle" border="0"> New</a>
  			</div></div>
        <?PHP
  		}
  		?>
  		</div>	
  	</div>
  </div>	
	<!-- end ACTION BUTTON ------------------------------------------------------>
	
	<!-- REQUEST TASK (GRID, NEW, EDIT, VIEW) ----------------------------------->
  <?PHP
  if(isset($_REQUEST["task"]))
  {
    if($_REQUEST["task"] == "Edit" || $_REQUEST["task"] == "View" || $_REQUEST["task"] == "New")
    {
		?>
      <link rel="stylesheet" type="text/css" href="../../style/tabs/css/screen.css" media="screen" />
      <script type="text/javascript" src="../../style/tabs/js/jquery-1.4.2.min.js"></script>
      <script type="text/javascript">
      $(document).ready(function() {
        $('ul#nav li a').removeClass('active'); 																			//menghilangkan class active (yang tampil)			
        $('#t'+<?=$ls_activetab;?>).addClass("active"); 															// menambahkan class active pada link yang diklik
        $('.tab_konten').hide(); 																											// menutup semua konten tab					
        $('#tab'+<?=$ls_activetab;?>).fadeIn('slow'); 																//tab pertama ditampilkan								 
        //$('#tab1').fadeIn('slow'); 																									//tab pertama ditampilkan
        
        // jika link tab di klik
        $('ul#nav li a').click(function() 
        { 					 																																				
          $('ul#nav li a').removeClass('active'); 																		//menghilangkan class active (yang tampil)			
          $(this).addClass("active"); 																								// menambahkan class active pada link yang diklik
          $('.tab_konten').hide(); 																										// menutup semua konten tab
          var aktif = $(this).attr('href'); 																					// mencari mana tab yang harus ditampilkan
          var aktif_idx = aktif.substr(4,5);
          document.getElementById('activetab').value = aktif_idx;
          //alert(aktif_idx);
          $(aktif).fadeIn('slow'); 																										// tab yang dipilih, ditampilkan
          return false;
        });		
      });
      </script>	
			
			<div id="formframe">
				<div id="formKiri" style="width:900px">
          <input type="hidden" name="TYPE" value="<?=$_REQUEST["task"];?>">
          <input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["dataid"];?>">
          <input type="hidden" id="activetab" name="activetab" value="<?=$ls_activetab;?>">	 
          <div id="dispError1" style="color:red;line-height: 19px;text-align: left;margin-top: 5px;display: none;"></div>
          <input type="hidden" id="st_errval1" name="st_errval1">					
          <span id="dispError2" style="color:red;line-height: 19px;text-align: left;margin-top: 5px;display: none;"></span>
          <input type="hidden" id="st_errval2" name="st_errval2">
          <span id="dispError3" style="color:red;line-height: 19px;text-align: left;margin-top: 5px;display: none;"></span>
          <input type="hidden" id="st_errval3" name="st_errval3">
          <span id="dispError4" style="color:red;line-height: 19px;text-align: left;margin-top: 5px;display: none;"></span>
          <input type="hidden" id="st_errval4" name="st_errval4">
          <span id="dispError5" style="color:red;line-height: 19px;text-align: left;margin-top: 5px;display: none;"></span>
          <input type="hidden" id="st_errval5" name="st_errval5">
          <span id="dispError6" style="color:red;line-height: 19px;text-align: left;margin-top: 5px;display: none;"></span>
          <input type="hidden" id="st_errval6" name="st_errval6">
          <span id="dispError7" style="color:red;line-height: 19px;text-align: left;margin-top: 5px;display: none;"></span>
          <input type="hidden" id="st_errval7" name="st_errval7">
          <span id="dispError8" style="color:red;line-height: 19px;text-align: left;margin-top: 5px;display: none;"></span>
          <input type="hidden" id="st_errval8" name="st_errval8">
          <span id="dispError9" style="color:red;line-height: 19px;text-align: left;margin-top: 5px;display: none;"></span>
          <input type="hidden" id="st_errval9" name="st_errval9">
          <span id="dispError10" style="color:red;line-height: 19px;text-align: left;margin-top: 5px;display: none;"></span>
          <input type="hidden" id="st_errval10" name="st_errval10">
					<input type="hidden" id="btn_task" name="btn_task" value="">
					<input type="hidden" name="trigersubmit" value="0">												
					
					<ul id="nav">
						<li><a href="#tab1" id="t1" class="active">Informasi Kegiatan</a></li>						
  					<?php
    				if(!empty($_GET['dataid']))
    				{
    				?>	
							<li><a href="#tab2" id="t2">Rincian Biaya</a></li> 
							<?php
							if ($ln_cnt_detil > "0")
							{
							?>	
							<li><a href="#tab3" id="t3">Penggantian Biaya</a></li>
							<li><a href="#tab4" id="t4">Kelengkapan Administrasi</a></li>
							<?
							}
							?>																						
						<?
						}
						?>						
					</ul>
					
					<div id="konten">
						<div style="display: none;" id="tab1" class="tab_konten">
              <div id="formKiri" style="width:820px;">
								<fieldset><legend>Entry Informasi Kegiatan <b><?=$ls_status_batal=="Y" ? "<font color=#ff0000> (* Data Klaim Sudah Dibatalkan *)</font>" : "";?> </b></legend>
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">Kantor &nbsp; *</label>
                    <select size="1" id="kode_kantor" name="kode_kantor" value="<?=$ls_kode_kantor;?>" tabindex="1" class="select_format" <?=($ls_status_klaim !="AGENDA")? " style=\"width:300px;background-color:#F5F5F5\"" : " style=\"width:300px;background-color:#ffff99\"";?>>
                      <option value="">-- Pilih --</option>
                      <?
											if ($ls_status_klaim !="AGENDA")
											{
                        $sql = "select kode_kantor, nama_kantor from sijstk.ms_kantor where kode_kantor = '$ls_kode_kantor' ";
                      }else
											{
                        $sql = "select kode_kantor, nama_kantor from sijstk.ms_kantor ".
                               "where kode_tipe not in ('0','1') ".							     									 	 
                               "start with kode_kantor = '$gs_kantor_aktif' ".
                               "connect by prior kode_kantor = kode_kantor_induk";											
											}
											$DB->parse($sql);
                      $DB->execute();
                      while($row = $DB->nextrow())
                      {
                      echo "<option ";
                      if ($row["KODE_KANTOR"]==$ls_kode_kantor && strlen($ls_kode_kantor)==strlen($row["KODE_KANTOR"])){ echo " selected"; }
                      echo " value=\"".$row["KODE_KANTOR"]."\">".$row["KODE_KANTOR"]." - ".$row["NAMA_KANTOR"]."</option>";
                      }
                      ?>
                    </select>																				 								
                  </div>				
                  <div class="form-row_kanan">
                  <label style = "text-align:right;">Kode Realisasi</label>					
                  	<input type="text" id="kode_realisasi" name="kode_realisasi" value="<?=$ls_kode_realisasi;?>" size="21" maxlength="20" readonly class="disabled">																																	 								
                  </div>				
                  <div class="clear"></div>	

           				<div class="form-row_kiri">
                  <label style = "text-align:right;">Kegiatan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; *</label>
                    <select size="1" id="kode_kegiatan" name="kode_kegiatan" value="<?=$ls_kode_kegiatan;?>" tabindex="2" class="select_format" <?=($ls_status_klaim !="AGENDA")? " style=\"width:300px;background-color:#F5F5F5\"" : " style=\"width:300px;background-color:#ffff99\"";?>>
                      <option value="">-- Pilih --</option>
                      <?
											if ($ls_status_klaim !="AGENDA")
											{
                        $sql = "select kode_kegiatan, nama_kegiatan from sijstk.pn_kode_promotif_kegiatan where kode_kegiatan = '$ls_kode_kegiatan'";
                      }else
											{
                        $sql = "select kode_kegiatan, nama_kegiatan from sijstk.pn_kode_promotif_kegiatan where nvl(status_nonaktif,'T')='T' order by no_urut";											
											}											                       
                      $DB->parse($sql);
                      $DB->execute();
                      while($row = $DB->nextrow())
                      {
                      echo "<option ";
                      if ($row["KODE_KEGIATAN"]==$ls_kode_kegiatan && strlen($ls_kode_kegiatan)==strlen($row["KODE_KEGIATAN"])){ echo " selected"; }
                      echo " value=\"".$row["KODE_KEGIATAN"]."\">".$row["NAMA_KEGIATAN"]."</option>";
                      }
                      ?>
                    </select>																				 								
                  </div>
                  <div class="form-row_kanan">
                  <label style = "text-align:right;">Segmentasi Keps *</label>
                    <select size="1" id="kode_segmen" name="kode_segmen" value="<?=$ls_kode_segmen;?>" tabindex="21" class="select_format" onchange="fl_js_kode_segmen(this.value);" <?=($ls_status_klaim !="AGENDA")? " style=\"width:165px;background-color:#F5F5F5\"" : " style=\"width:165px;background-color:#ffff99\"";?>>
                      <option value="">-- Pilih --</option>
                      <?
											if ($ls_status_klaim !="AGENDA")
											{
                        $sql = "select kode_segmen, nama_segmen from sijstk.kn_kode_segmen where kode_segmen = '$ls_kode_segmen'";
                      }else
											{
                        $sql = "select kode_segmen, nama_segmen from sijstk.kn_kode_segmen order by no_urut";											
											}											                       
                      $DB->parse($sql);
                      $DB->execute();
                      while($row = $DB->nextrow())
                      {
                      echo "<option ";
                      if ($row["KODE_SEGMEN"]==$ls_kode_segmen && strlen($ls_kode_segmen)==strlen($row["KODE_SEGMEN"])){ echo " selected"; }
                      echo " value=\"".$row["KODE_SEGMEN"]."\">".$row["NAMA_SEGMEN"]."</option>";
                      }
                      ?>
                    </select>																				 								
                  </div>																																							
      						<div class="clear"></div>

                  <div class="form-row_kiri">
                  <label style = "text-align:right;">Pelaksana &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; *</label>
                    <select size="1" id="kategori_pelaksana" name="kategori_pelaksana" value="<?=$ls_kategori_pelaksana;?>" tabindex="3" class="select_format" onchange="fl_js_lov_promotif(this.value);" <?=($ls_status_klaim !="AGENDA")? " style=\"width:280px;background-color:#F5F5F5\"" : " style=\"width:280px;background-color:#ffff99\"";?>>
                      <option value="">-- Pilih --</option>
                      <?
											if ($ls_status_klaim !="AGENDA")
											{
                        $sql = "select kode_tipe_penerima,nama_tipe_penerima from sijstk.pn_kode_tipe_penerima where kode_tipe_penerima='$ls_kategori_pelaksana'";
                      }else
											{
                        $sql = "select kode_tipe_penerima,nama_tipe_penerima from sijstk.pn_kode_tipe_penerima where kode_tipe_penerima in ('PR','TG') and nvl(status_nonaktif,'T')='T' order by no_urut";											
											}											                       
                      $DB->parse($sql);
                      $DB->execute();
                      while($row = $DB->nextrow())
                      {
                      echo "<option ";
                      if ($row["KODE_TIPE_PENERIMA"]==$ls_kategori_pelaksana && strlen($ls_kategori_pelaksana)==strlen($row["KODE_TIPE_PENERIMA"])){ echo " selected"; }
                      echo " value=\"".$row["KODE_TIPE_PENERIMA"]."\">".$row["NAMA_TIPE_PENERIMA"]."</option>";
                      }
                      ?>
                    </select>																				 								
                  </div>
                  <div class="form-row_kanan">
                  <label  style = "text-align:right;">Tgl Klaim</label>			
                  	<input type="text" id="tgl_realisasi" name="tgl_realisasi" value="<?=$ld_tgl_realisasi;?>" size="21" maxlength="10" readonly class="disabled">
                  </div>																			
      						<div class="clear"></div>	
									
									<span id="span_lov_promotif" style="display:none;">
                  <div class="form-row_kiri">
                  <label  style = "text-align:right;">NPP - Kode Promotif *</label>			
                  		<input type="text" id="npp" name="npp" value="<?=$ls_npp;?>" size="10" readonly <?=($ls_status_klaim !="AGENDA")? " style=\"background-color:#F5F5F5\"" : " style=\"background-color:#ffff99\"";?>>
											<input type="text" id="kode_promotif" name="kode_promotif" value="<?=$ls_kode_promotif;?>" size="20" readonly class="disabled">
											<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5011_lov_kodepromotif.php?p=pn5011.php&a=formreg&b=kode_promotif&c=nama_pelaksana&d=npp&e=alamat_pelaksana&f='+formreg.kode_kegiatan.value+'','',800,500,1)">
  										<img src="../../images/help.png" alt="Cari NPP" border="0" align="absmiddle"></a>										
									</div>																					
      						<div class="clear"></div>
									</span>
																		
                  <div class="form-row_kiri">
                  <label  style = "text-align:right;">Nama Pelaksana &nbsp;&nbsp;&nbsp;*</label>			
                  	<input type="text" id="nama_pelaksana" name="nama_pelaksana" value="<?=$ls_nama_pelaksana;?>" tabindex="4" size="35" maxlength="100" <?=($ls_status_klaim !="AGENDA")? " style=\"background-color:#F5F5F5\"" : " style=\"background-color:#ffff99\"";?>>
									</div>																					
      						<div class="clear"></div>																

                  <div class="form-row_kiri">
                  <label  style = "text-align:right;">NPWP Pelaksana &nbsp;*</label>			
										<input type="text" id="npwp_pelaksana" name="npwp_pelaksana" value="<?=$ls_npwp_pelaksana;?>" tabindex="5" size="35" maxlength="15" <?=($ls_status_klaim !="AGENDA")? " style=\"background-color:#F5F5F5\"" : " style=\"background-color:#ffff99\"";?>>
                  </div>														
      						<div class="clear"></div>
																		
                  <div class="form-row_kiri">
                  <label  style = "text-align:right;">Alamat Pelaksana &nbsp;</label>			
										<textarea cols="250" rows="1" id="alamat_pelaksana" name="alamat_pelaksana" tabindex="5" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" <?=($ls_status_klaim !="AGENDA")? " readonly class=disabled style=\"width:255px;background-color:#F5F5F5\"" : " style=\"width:255px;\"";?>><?=$ls_alamat_pelaksana;?></textarea>
                  </div>														
      						<div class="clear"></div>
																		
									</br>

                  <div class="form-row_kiri">
                  <label style = "text-align:right;">Tgl Kegiatan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*</label>
                    <input type="text" id="tgl_kegiatan" name="tgl_kegiatan" value="<?=$ld_tgl_kegiatan;?>" size="33" maxlength="10" tabindex="6" onblur="convert_date(tgl_kegiatan);" <?=($ls_status_klaim !="AGENDA")? " readonly class=disabled" : " style=\"background-color:#ffff99\"";?>> 								
										<input id="btn_tgl_kegiatan" type="image" align="top" onclick="return showCalendar('tgl_kegiatan', 'dd-mm-y');" src="../../images/calendar.gif" />							
                  </div>
                  <div class="form-row_kanan">
                  <label  style = "text-align:right;">Biaya Diajukan</label>			
                  	<input type="text" id="nom_diajukan" name="nom_diajukan" value="<?=number_format((float)$ln_nom_diajukan,2,".",",");?>" size="21" readonly class="disabled">
                  </div>																																				
									<div class="clear"></div>
																		
                  <div class="form-row_kiri">
                  <label  style = "text-align:right;">Tema Kegiatan &nbsp;&nbsp;*</label>
                    <input type="text" id="kode_sub_kegiatan" name="kode_sub_kegiatan" value="<?=$ls_kode_sub_kegiatan;?>" tabindex="7" size="6" maxlength="10" <?=($ls_status_klaim !="AGENDA")? " readonly class=disabled" : " style=\"background-color:#ffff99\"";?>>
                    <input type="text" id="nama_sub_kegiatan" name="nama_sub_kegiatan" value="<?=$ls_nama_sub_kegiatan;?>" size="24" readonly class="disabled">
										<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5011_lov_subkegiatan.php?p=pn5011.php&a=formreg&b=kode_sub_kegiatan&c=nama_sub_kegiatan&d='+formreg.kode_kegiatan.value+'&e='+formreg.tgl_kegiatan.value+'&f=bentuk_kegiatan','',800,500,1)">							      																	
                    <img src="../../images/help.png" alt="Cari Bank Penempatan" border="0" align="absmiddle"></a>			
                  </div>
                  <div class="form-row_kanan">
                  <label  style = "text-align:right;">Biaya Disetujui</label>			
                  	<input type="text" id="nom_disetujui" name="nom_disetujui" value="<?=number_format((float)$ln_nom_disetujui,2,".",",");?>" size="21" readonly class="disabled">
                  </div>																																																												
                  <div class="clear"></div>
																											
                  <div class="form-row_kiri">
                  <label  style = "text-align:right;">Detil Kegiatan *</label>			
                  	<input type="text" id="nama_detil_kegiatan" name="nama_detil_kegiatan" value="<?=$ls_nama_detil_kegiatan;?>" tabindex="8" size="35" maxlength="300" <?=($ls_status_klaim !="AGENDA")? " style=\"background-color:#F5F5F5\"" : " style=\"background-color:#ffff99\"";?>>
                  </div>
                  <div class="form-row_kanan">
                  <label  style = "text-align:right;">Kode Klaim</label>			
                  	<input type="text" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>" size="21" readonly class="disabled">
										<input type="hidden" id="status_klaim" name="status_klaim" value="<?=$ls_status_klaim;?>">
                  </div>																								
      						<div class="clear"></div>																		

                  <div class="form-row_kiri">
                  <label  style = "text-align:right;">Bentuk Kegiatan</label>			
                  	<input type="text" id="bentuk_kegiatan" name="bentuk_kegiatan" value="<?=$ls_bentuk_kegiatan;?>" size="6" readonly class="disabled">
										<input type="hidden" id="kode_jasa" name="kode_jasa" value="<?=$ls_kode_jasa;?>" size="6" readonly class="disabled">
                  	<input type="text" id="nama_jasa" name="nama_jasa" value="<?=$ls_nama_jasa;?>" size="24" tabindex="9" readonly <?=($ls_status_klaim !="AGENDA")? " style=\"background-color:#F5F5F5\"" : " style=\"background-color:#ffff99\"";?>>
										<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5011_lov_komponenbiaya.php?p=pn5011.php&a=formreg&b=kode_jasa&c=nama_jasa&d='+formreg.bentuk_kegiatan.value+'','',800,500,1)">							      																	
                    <img src="../../images/help.png" alt="Cari Bank Penempatan" border="0" align="absmiddle"></a>
									</div>										
									<div class="clear"></div>
																																																											 
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">Keterangan &nbsp;</label>
                  	<textarea cols="250" rows="1" id="keterangan" name="keterangan" tabindex="10" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" <?=($ls_status_klaim !="AGENDA")? " readonly class=disabled style=\"width:270px;background-color:#F5F5F5\"" : " style=\"width:270px;\"";?>><?=$ls_keterangan;?></textarea>   					
                  </div>																																																																						
                  <div class="clear"></div>	

                  <?
                  echo "<script type=\"text/javascript\">fl_js_lov_promotif('$ls_kategori_pelaksana');</script>";
                  ?>
																																																																																																		
								</fieldset>		
																				 
								<?php
                if ($ls_kode_realisasi!="")
								{
								?>
  								<div id="buttonbox" style="width:930px">
  									<input type="submit" class="btn green" id="butentry" name="butentry" value="     ENTRY REALISASI KEGIATAN    " />							
    								<?	
											if ($ls_status_klaim =="AGENDA")
											{
											  ?>											 
                        <input type="button" class="btn green" id="butsubmit" name="butsubmit" onclick="if(confirm('Apakah anda yakin akan mensubmit data untuk dilakukan penetapan klaim..?')) doSubmitTanpaOtentikasi();" value="         SUBMIT DATA UNTUK DILAKUKAN PENETAPAN KLAIM        " />
                      	<?
											}
										?>												                 
  								</div>	<!-- end div buttonbox -->
								<?
								}
                ?>															
							</div> <!-- end div id="formKiri" -->
              <div id="formKanan">
								<?php 					
                	if ($ls_kode_realisasi!="")
        					{
        					?>	
                		<fieldset>   				              									
              					<br />
												<input type="button" class="rightbutton btn green" id="btncetak" name="btncetak" value=" CETAK " onClick="NewWindow4('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5011_cetak.php?&kode_realisasi=<?=$ls_kode_realisasi;?>&kode_klaim=<?=$ls_kode_klaim;?>&jenis_klaim=JKK&mid=<?=$mid;?>&kategori_pelaksana=<?=$ls_kategori_pelaksana;?>','Cetak Agenda Klaim',600,550,'no')"><br />
      									<input type="button" class="rightbutton btn green" id="btnaktivitas" name="btnaktivitas" value=" AKTIVITAS " onClick="NewWindow4('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5001_daftaraktivitas.php?&kode_klaim=<?=$ls_kode_klaim;?>&mid=<?=$mid;?>','Daftar Aktivitas Klaim',800,550,'no')"><br />
  											<br />
  											<br />
  											<br />
  											<br />
            						<br />
  											<br />
            						<br />
            						<br />
            						<br />
            						<br />
            						<br />
            						<br />																	    									      						          						
      									<br />	
												<br />
												<br />
												<br />																	    									      						          						
      									<br />																									
                		</fieldset>
                  <?php
                  }
                  ?>																		
              	</div>	<!-- end div id="formKanan" -->																					
						</div><!-- end div id="tab1"-->	

						<div style="display: none;" id="tab2" class="tab_konten">
    					<?
							include "../ajax/pn5011_tabrincianbiaya.php";								
							?>																	
						</div><!-- end div id="tab2"-->	

						<div style="display: none;" id="tab3" class="tab_konten">
    					<?	 
    					include "../ajax/pn5011_tabpenggantianbiaya.php";							 
    					?>													 
						</div><!-- end div id="tab3"-->	

						<div style="display: none;" id="tab4" class="tab_konten">
    					<?	 
    					include "../ajax/pn5001_tabadministrasi.php";		 
    					?>													 
						</div><!-- end div id="tab4"-->
																														 
					</div><!-- end div id="konten"--> 							
          <?
          if (isset($msg))		
          {
          ?>
            <fieldset style="width:930px;">
            <?=$ls_error==1 ? "<legend><font color=#ff0000>Error</font></legend>" : "<legend><font color=#007bb7>Message</font></legend>";?>
            <?=$ls_error==1 ? "<font color=#ff0000>".$msg."</font>" : "<font color=#007bb7>".$msg."</font>";?>
            </fieldset>		
          <?
          }
          ?>			
												 
				</div><!-- end div id="formKiri"-->                 						
			</div><!-- end div id="formframe"-->						
		<?
		}
	}else
	{
  ?>	
    <table class="captionentry">
    <tr> 
    <td align="left"><b><?=$gs_pagetitle;?></b> </td>						 
    </tr>
    </table>
			 
  	<div id="formframe">
      <div id="formKiri" style="width:1000px">	 
        <fieldset><legend><b>DATA REALISASI KEGIATAN PROMOTIF/PREVENTIF</b></legend>
  				<div class="form-row_kiri">
  					<?PHP	 
          	if ($ld_tglawaldisplay=="" && $ld_tglakhirdisplay=="")//tampilkan dari 1 hari sebelumnya
          	{
          		$sql2 = "select to_char(sysdate-1,'dd/mm/yyyy') tglawal, to_char(sysdate,'dd/mm/yyyy') tglakhir from dual";		
          		$DB->parse($sql2);
          		$DB->execute();
          		$row = $DB->nextrow();
          		$ld_tglawaldisplay  = $row["TGLAWAL"];						
          		$ld_tglakhirdisplay = $row["TGLAKHIR"];						
          	}
  					?>				
    				Tgl Realisasi &nbsp;
    				<input type="text" id="tglawaldisplay" name="tglawaldisplay" value="<?=$ld_tglawaldisplay;?>" size="9" onblur="convert_date(tglawaldisplay)" >  
    				<input id="btn_tgl" type="image" align="top" onclick="return showCalendar('tglawaldisplay', 'dd-mm-y');" src="../../images/calendar.gif" />&nbsp; s/d &nbsp;
    				<input type="text" id="tglakhirdisplay" name="tglakhirdisplay" value="<?=$ld_tglakhirdisplay;?>" size="9" onblur="convert_date(tglakhirdisplay)" >
    				<input id="btn_tgl2" type="image" align="top" onclick="return showCalendar('tglakhirdisplay', 'dd-mm-y');" src="../../images/calendar.gif" />&nbsp;&nbsp;											 
  				</div>														 									 
          <div class="form-row_kanan">
            <span style="margin-right:5px;">Search by:</span>
            <select id="type" name="type" style="width:120px;" >
							<option value="NAMA_PELAKSANA">Nama Pelaksana</option>              
            </select>
            <input  type="text" name="keyword" id="keyword" style="width:120px;" placeholder="Keyword">            
            <select id="type2" name="type2" onclick="fl_js_reset_keyword2();">
              <option value="">Keyword Lain</option>
              <option value="">----------------</option>
              <option value="KATEGORI_PELAKSANA">Kategori Pelaksana</option>                       
            </select>

            <span id="KATEGORI_PELAKSANA" hidden="">
              <select size="1" id="keyword2a" name="keyword2a" value="" class="select_format" style="width:80px;">
                <option value="">-- Pilih --</option>
                <? 
                $sql = "select kode, keterangan from sijstk.ms_lookup where tipe='PROPKATPEL' order by seq";
                $DB->parse($sql);
                $DB->execute();
                while($row = $DB->nextrow())
                {
                echo "<option ";
                if ($row["KODE"]==$ls_list_kategori_pelaksana && strlen($ls_list_kategori_pelaksana)==strlen($row["KODE"])){ echo " selected"; }
                echo " value=\"".$row["KODE"]."\">".$row["KODE"]."-".$row["KETERANGAN"]."</option>";
                }
                ?>
              </select>							
            </span>	                        				                      							       
            <input type="button" name="btncari" class="btn green" id="btncari" value=" TAMPILKAN DATA ">						
          </div>
          <div class="clear"></div>
          
          <div id="formsplit">
            <div class="clear"></div>
      			<table class="table table-striped table-bordered row-border hover" id="mydata_grid" cellspacing="0" width="100%">
      				<thead>
      					<tr style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">
                  <th scope="col" style="width:2%;">Action</th>
                  <th scope="col" style="width:10%;">Kode Realisasi</th>
                  <th scope="col" style="width:6%;">Tgl</th>
                  <th scope="col" style="width:25%;">Nama</th>
									<th scope="col" style="width:6%;">Kegiatan</th>
									<th scope="col">Detil Kegiatan</th>
									<th scope="col" style="width:5%;">Ktr</th> 
									<th scope="col" style="width:5%;">Status</th>                              
      					</tr>
      				</thead>
      			</table>								
            <div class="clear"></div>
          <div class="clear"></div>																																																					
        </fieldset>
				
        <br>
        
				<fieldset style="background: #FF0;"><legend style="background: #FF0; border: 1px solid #CCC;">Keterangan:</legend>
          <li>Pilih Jenis Pencarian</li>	
          <li>Input Kata Kunci (Keyword) dengan menambahkan % untuk keyword sebagian</li>	
          <li>Klik Tombol TAMPILKAN DATA untuk memulai pencarian data</li>	
          <li>Untuk melihat detail data Klik salah satu data pada Tabel hasil Pencarian</li>
        </fieldset>
  		</div>
  	</div>	
  	<?PHP		
	}
  ?>	
	<!-- end REQUEST TASK (GRID, NEW, EDIT, VIEW) ------------------------------->
	
	<script type="text/javascript">
		$(document).ready(function()
		{
      $('#keyword').focus();            
			$('input').keyup(function(){
       	this.value = this.value.toUpperCase();
      });      
			$('#type').change(function(e){
       	$('#keyword').focus();
      });      
			$('#type2').change(function(e){
       	$('#KATEGORI_PELAKSANA').hide();
      	$('#'+$('#type2').val()).show();
      	$('#keyword2').val('');
      	$('#keyword2').focus();
    	});
			
			var v_dataid2 = '';

      <?PHP
			//------------------- TASK -----------------------------------------------
      if(isset($_REQUEST["task"]))
  		{
			 	?>													
        window.dataid = '<?=$_REQUEST["dataid"];?>';
        v_dataid2 		= '<?=$_REQUEST["dataid2"];?>';
				
				<?PHP
  			//NEW ------------------------------------------------------------------
        if($_REQUEST["task"] == "New")
  			{
         	?>
          $('#btn_save').click(function() 
  				{
							if (($('#kode_kantor').val() == ''))
							{							
							 	alert('Kantor kosong, harap lengkapi data input..!!!');																																																														
							}else if (($('#st_errval1').val() == '1') || ($('#st_errval2').val() == '1') || ($('#st_errval3').val() == '1') || ($('#st_errval4').val() == '1'))
							{
							 	alert('Data input tidak valid, harap perbaiki data ..!!!');																																											
							}else
							{ 
								preload(true);
              	$.ajax(
      					{
              		type: 'POST',
              		url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5011_action.php?'+Math.random(),
              		data: $('#formreg').serialize(),
              		success: function(data)
      						{
                		preload(false);
                		console.log($('#formreg').serialize());	
                		console.log(data);
                		jdata = JSON.parse(data);									
                		if(jdata.ret == '0')
        						{						 		 						 						 
  									  window.parent.Ext.notify.msg('Penyimpanan data berhasil, session dilanjutkan...', jdata.msg);
                			window.location='pn5011.php?task=Edit&dataid='+jdata.DATAID+'&kode_realisasi='+jdata.DATAID+'&mid=<?=$mid;?>';
                		}else 
        						{
                		 	alert(jdata.msg);
                		}
              		}
              	});
            	}																		        
          });		
        	<?PHP
        };				
  			//end NEW ------------------------------------------------
  			?>	
				
        <?PHP
  			//EDIT ---------------------------------------------------
        if($_REQUEST["task"] == "Edit")
  			{
        ?>
          setTimeout( function(){
          	preload(true);
          }, 100); 				
          $.ajax({
          	type: 'POST',
          	url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5011_action.php?'+Math.random(),
          	data: { TYPE:'VIEW', DATAID:window.dataid, DATAID2:v_dataid2},
          	success: function(data) {
          		setTimeout( function() {
          			preload(false);
          		}, 100); 
          		console.log("{ TYPE:'VIEW', DATAID:"+window.dataid+", DATAID2:"+v_dataid2+"}");	
          		console.log(data);        		
          		jdata = JSON.parse(data);
              if(jdata.ret == '0')
  						{
								$('#DATAID').val(jdata.data[0].KODE_KLAIM);													
              }
          	}
          });
          $('#btn_save').click(function() 
  				{							
							if (($('#kode_kantor').val() == ''))
							{							
							 	alert('Kantor kosong, harap lengkapi data input..!!!');													
							}else if (($('#status_klaim').val() != 'AGENDA'))
							{							
							 	alert('Data sudah disubmit ke tahap selanjutnya, tidak dapat diupdate..!!!');													
							}else if (($('#st_errval1').val() == '1') || ($('#st_errval2').val() == '1') || ($('#st_errval3').val() == '1') || ($('#st_errval4').val() == '1'))
							{
							 	alert('Data input tidak valid, harap perbaiki data ..!!!');																																											
							}else
							{ 
    							preload(true);
                  $.ajax({
                    type: 'POST',
                    url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5011_action.php?'+Math.random(),
                    data: $('#formreg').serialize(),
                    success: function(data) {
                      preload(false);
                      console.log($('#formreg').serialize());	
                      console.log(data);
                      jdata = JSON.parse(data);
                      if(jdata.ret == '0'){
                      	window.parent.Ext.notify.msg('Berhasil', jdata.msg);
  											window.location='pn5011.php?task=Edit&dataid='+jdata.DATAID+'&kode_realisasi='+jdata.DATAID+'&mid=<?=$mid;?>';
                      } else {
                      	alert(jdata.msg);
                      }
                  	}
              		}); 
            	}																		        
          });          				
        	<?PHP
        };
  			//end EDIT ---------------------------------------------------
        ?>

  			<?PHP
        //------------------- VIEW -----------------------------------		
  			if($_REQUEST["task"] == "View")
  			{
        ?>
          setTimeout( function() {
          	preload(true);
          }, 100);
          $.ajax({
            type: 'POST',
            url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5011_action.php?'+Math.random(),
            data: { TYPE:'VIEW', DATAID:window.dataid, DATAID2:v_dataid2},
            success: function(data) {
              setTimeout( function() {
              	preload(false);
              }, 100); 
              console.log("{ TYPE:'VIEW', DATAID:"+window.dataid+", DATAID2:"+v_dataid2+"}");	
              console.log(data);
              jdata = JSON.parse(data);
              if(jdata.ret == '0'){
                $('#DATAID').val(jdata.data[0].KODE_REALISASI);
								$('#KODE_REALISASI').val(jdata.data[0].KODE_REALISASI);
              }
            }
          });
          <?PHP
        };
        ?>													
			<?PHP						
      };
			//------------------- end TASK -------------------------------------------
			?>						

      window.dataid = '';
      $('#keyword').focus();      
      
			$('input').keyup(function() {
      	this.value = this.value.toUpperCase();
      });			
      
			$('textarea').keyup(function() {
      	this.value = this.value.toUpperCase();
      });
			      
      $('#type').change(function(e) {
      	$('#keyword').focus();
      });
      
			$("#menutable").hide();
      
      $('#approve').attr('disabled','disabled');
      $(".menutable").html($('#menutable').html());
      $(".menutable").hide();
									
  		loadData();
			
      $('#btn_view').click(function() {
        if(window.dataid != ''){
        window.location='pn5011.php?task=View&dataid='+window.dataid+'&mid=<?=$mid;?>';
        } else {
        alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
        }      
      });
			
      $('#btn_edit').click(function() {													
        if(window.dataid != ''){
        	window.location='pn5011.php?task=Edit&kode_realisasi='+window.dataid+'&dataid='+window.dataid+'&mid=<?=$mid;?>';
        } else {
        	alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
        } 								
      });
			
      $('#btn_delete').click(function() {
        if(window.dataid != ''){
          var r = confirm("Apakah anda yakin ?");
          if (r == true) 
					{
            $.ajax(
						{
              type: 'POST',
              url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5011_action.php?'+Math.random(),
              data: { TYPE:'DEL', DATAID:window.dataid},
              success: function(data) {
              	window.selected.slideUp(function(){						
              		$(this).remove();					
              	});
              	window.parent.Ext.notify.msg('Berhasil', "Data Berhasil dihapus!");
								loadData();
              }
            });          
          }
        }else{
        	alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
        }
      });
			
      $('#btn_new').click(function() {
      	window.location='pn5011.php?task=New&dataid='+window.dataid+'&mid=<?=$mid;?>';
      });
      
      $("#btncari").click(function() {
  			loadData();
    	});
		
  		function loadData()
  		{
  			 window.table = $('#mydata_grid').DataTable({
    			"scrollCollapse"	: true,
    			"paging"			: true,
    			'sPaginationType'	: 'full_numbers',
    			scrollY				: "300px",
    	    scrollX				: true,
    	  	"processing"		: true,
    			"serverSide"		: true,
    			"search"			: {
    			    "regex": true
    			},
    			select			: true,
    			"searching"	: false,
    			"destroy"		: true,
    	        "ajax"				: {
    	        	"url"	: "http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5011_query.php",
    	        	"type": "POST",							
    	        	"data" : function(e) { 
    	        		e.TYPE = $('#type').val();
    	        		e.KEYWORD = $('#keyword').val();
    	        		e.TYPE2 = $('#type2').val();
    	        		e.KEYWORD2A = $('#keyword2a').val();
    	        		e.TGLAWALDISPLAY = $('#tglawaldisplay').val();
    	        		e.TGLAKHIRDISPLAY = $('#tglakhirdisplay').val();
    	        	},complete : function(){
    	        		//$('#fieldset1').css("display","");
    	        		//$('#fieldset2').css("display","none");
    	        		preload(false);
    	        	},error: function (){
    	            	alert("Terjadi kesalahan pada server. Silahkan dicoba beberapa saat lagi");
    	            }
    	        },
    	        "columns": [
    	        	{ "data": "ACTION" },
    	          { "data": "KODE_REALISASI" },
    	          { "data": "TGL_REALISASI" },
    	          { "data": "NAMA_PELAKSANA" },
								{ "data": "KODE_KEGIATAN" },    	          
    	          { "data": "NAMA_DETIL_KEGIATAN" },
								{ "data": "KODE_KANTOR" },
								{ "data": "STATUS_KLAIM" },						
    	        ],
    	        'aoColumnDefs': [
    				{"className": "dt-center", "targets": [0,1,2,6,7]}
    			]
    	        
    	    });//end window.table

    			window.table.on('draw.dt',function(){
            $('input[type="checkbox"]').change(function() {
      				if(this.checked) {
                window.dataid= $(this).attr('KODE');
                v_dataid2 = $(this).attr('KODE2');
                window.selected = $(this).closest('tr');
                console.log(v_dataid2);
              }
        		});			
    			});
					//end window.table.on						  		
  		}//end function load data
						
    });	
		<!--end $(document).ready(function() ------------------------------------ ->
	</script>			
						
</form>	
<?php
include "../../includes/footer_app_nosql.php";
?>

