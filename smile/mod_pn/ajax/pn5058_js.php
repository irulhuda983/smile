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

    function confirmDelete(delUrl) {
    	if (confirm("Are you sure you want to delete this record")) {
    		 window.document.location = delUrl;
    	}
    }
				
	  function fl_js_reset_keyword2()
    {
      document.getElementById('keyword2a').value = '';
      document.getElementById('keyword2b').value = '';
			document.getElementById('keyword2c').value = '';	
    }

    function fl_js_input_kantor() 
    { 
			//reset input data -------------------------------------------------------
      window.document.getElementById("kode_tk").value = "";
      window.document.getElementById("nama_tk").value = "";
      window.document.getElementById("nomor_identitas").value = "";
      window.document.getElementById("jenis_identitas").value = "";
      window.document.getElementById("kpj").value = "";			 
      window.document.getElementById("npp").value = "";
      window.document.getElementById("nama_perusahaan").value = "";
      window.document.getElementById("kode_perusahaan").value = "";
      window.document.getElementById("kode_divisi").value = "";
      window.document.getElementById("nama_divisi").value = "";
      window.document.getElementById("kode_kantor_tk").value = "";
      window.document.getElementById("kode_proyek").value = "";
      window.document.getElementById("no_proyek").value = "";
      window.document.getElementById("nama_proyek").value = "";	
			
			window.document.getElementById("kode_tipe_klaim").value = "";
			window.document.getElementById("kode_sebab_klaim").value = "";
			window.document.getElementById("tgl_kejadian").value = "";	
			window.document.getElementById("status_klaim").value = "";	 			 	
    }	

    function fl_js_input_kode_segmen() 
    { 
      var v_kode_segmen = window.document.getElementById('kode_segmen_list').value;
			window.document.getElementById("kode_segmen").value = v_kode_segmen;
			
			if (v_kode_segmen =="TKI")
			{
				window.document.getElementById("span_status_kepesertaan").style.display = 'block';
				//window.document.getElementById("span_kode_perlindungan").style.display = 'block';
				window.document.getElementById("span_masa_perlindungan").style.display = 'block';	
				window.document.getElementById("span_negara_penempatan").style.display = 'block';		
			}else
      {
				window.document.getElementById("span_status_kepesertaan").style.display = 'none';
				//window.document.getElementById("span_kode_perlindungan").style.display = 'none';
				window.document.getElementById("span_masa_perlindungan").style.display = 'none';
				window.document.getElementById("span_negara_penempatan").style.display = 'none';   
      }
	
			fl_js_input_kpj();
    }
									
    function fl_js_input_kpj() 
    { 
			var v_kode_pointer_asal = window.document.getElementById('kode_pointer_asal').value;
			var v_kode_segmen = window.document.getElementById('kode_segmen').value;
				
			if (v_kode_segmen =="JAKON")
  		{
        window.document.getElementById("span_proyek").style.display = 'block';
        window.document.getElementById("span_kpj").style.display = 'none';
        window.document.getElementById("span_kegiatan_tambahan").style.display = 'none';
        //window.document.getElementById("kode_tk").value = "";
        //window.document.getElementById("nama_tk").value = "";
        //window.document.getElementById("nomor_identitas").value = "";
        //window.document.getElementById("jenis_identitas").value = "";
        window.document.getElementById("kpj").value = "";			 
        //window.document.getElementById("npp").value = "";
        //window.document.getElementById("nama_perusahaan").value = "";
        //window.document.getElementById("kode_perusahaan").value = "";
        //window.document.getElementById("kode_divisi").value = "";
        //window.document.getElementById("nama_divisi").value = "";
        window.document.getElementById("kode_kantor_tk").value = "";			 
  		}else
  		{
				 if (v_kode_pointer_asal !="") //data bersumber dari modul lain
				 {
					 window.document.getElementById("span_proyek").style.display = 'none';
    			 window.document.getElementById("span_kpj").style.display = 'none';
					 window.document.getElementById("span_kegiatan_tambahan").style.display = 'block';	
			 	 }else
				 {
            window.document.getElementById("span_proyek").style.display = 'none';
            window.document.getElementById("span_kpj").style.display = 'block';
            window.document.getElementById("span_kegiatan_tambahan").style.display = 'none';
            window.document.getElementById("kode_proyek").value = "";
            window.document.getElementById("no_proyek").value = "";
            window.document.getElementById("nama_proyek").value = "";						 				 		 
				 }			 		 
  		} 	
    }	

    function fl_js_tgl_kejadian() 
    {     			
			// var v_jenis_klaim = window.document.getElementById('jenis_klaim').value;
			// var v_flag_meninggal = window.document.getElementById('flag_meninggal').value;
						
			// //tgl kejadian hanya bisa diisi hanya utk klaim JKK/Meninggal ------------
			// if (v_jenis_klaim == "JKK" || v_jenis_klaim == "JKM" || v_jenis_klaim == "JHM" || v_flag_meninggal == "Y")
      // {
      //   window.document.getElementById("span_tgl_kejadian").style.display = 'block';
			// }else
			// {
			//  	window.document.getElementById("span_tgl_kejadian").style.display = 'none';	 
			// }	
			
			// //utk klaim jkk/jkm tampilkan masa perlindungan --------------------------
			// //if (v_jenis_klaim == "JKK" || v_jenis_klaim == "JKM")
			// //{
			// //	window.document.getElementById("span_status_kepesertaan").style.display = 'block';
			// //	window.document.getElementById("span_kode_perlindungan").style.display = 'block';
			// //	window.document.getElementById("span_masa_perlindungan").style.display = 'block';		
			// //}else
      // //{
			// //	window.document.getElementById("span_status_kepesertaan").style.display = 'none';
			// //	window.document.getElementById("span_kode_perlindungan").style.display = 'none';
			// //	window.document.getElementById("span_masa_perlindungan").style.display = 'none';   
      // //}	
    }

		function fl_js_cek_required_new()
		{
			var v_err_mess, v_count_err;
					v_err_mess  = '';
					v_count_err = '0';
					
      if ($("#alamat_tk").val() == '')
			{
			 	v_err_mess = 'Data alamat tk harus diisi';
				v_count_err = '1';					
			}
      if ($("#kpj").val() == '')
			{
			 	v_err_mess = 'SIlahkan Pilih Nomor KPJ terlebih dahulu';
				v_count_err = '1';					
			}
			else if ($('#nomor_telepon_tk').val().length < '8')
			{							
			 	v_err_mess = 'No telepon minimal 8 angka';
				v_count_err = '1';	
			}
      else if ($('#keterangan_pernyataan').val()== '')
			{							
			 	v_err_mess = 'Keterangan Harus diisi';
				v_count_err = '1';	
			}
			window.document.getElementById("errmess_empty_required").value = v_err_mess; 		
			window.document.getElementById("count_empty_required").value = v_count_err;		 
		}

		function fl_js_cek_required_edit()
		{
			var v_err_mess, v_count_err;
					v_err_mess  = '';
					v_count_err = '0';
					
			if ($("#alamat_tk").val() == '')
			{
			 	v_err_mess = 'Data alamat tk harus diisi';
				v_count_err = '1';					
			}
			else if ($('#nomor_telepon_tk').val().length < '8')
			{							
			 	v_err_mess = 'No telepon minimal 8 angka';
				v_count_err = '1';	
			}
      else if ($('#keterangan_pernyataan').val()== '')
			{							
			 	v_err_mess = 'Keterangan Harus diisi';
				v_count_err = '1';	
			}
			
			window.document.getElementById("errmess_empty_required").value = v_err_mess; 		
			window.document.getElementById("count_empty_required").value = v_count_err;				 
		}
								
		function fl_js_cek_kelayakan()
		{
      var form = document.formreg;
      if(form.kode_kantor.value==""){
        alert('Kantor Pelayanan kosong, harap lengkapi data input...!!!');
        form.kode_kantor.focus();
      }else if(form.kode_tipe_klaim.value==""){
        alert('Tipe Klaim kosong, harap lengkapi data input...!!!');
        form.kode_tipe_klaim.focus();
      }else if(form.kode_sebab_klaim.value==""){
        alert('Sebab Klaim kosong, harap lengkapi data input...!!!');
        form.kode_sebab_klaim.focus(); 
      }else if(form.tgl_lapor.value==""){
        alert('Tgl Lapor kosong, harap lengkapi data input...!!!');
        form.tgl_lapor.focus(); 	
      }
	  /*else if(form.tgl_kejadian.value==""){
        alert('Tgl Kejadian kosong, harap lengkapi data input...!!!');
        form.tgl_kejadian.focus();							     
      }*/
	  else
      {
       form.btn_task.value="cek_kelayakan";
       form.submit();
      }		 				 
		}									
				
		function doSubmitTanpaOtentikasi() {
      var form = document.formreg;
      if(form.kode_kantor.value==""){
        alert('Kantor Pelayanan kosong, harap lengkapi data input...!!!');
        form.kode_kantor.focus();
      }else if(form.kode_tipe_klaim.value==""){
        alert('Tipe Klaim kosong, harap lengkapi data input...!!!');
        form.kode_tipe_klaim.focus();
      }else if(form.kode_sebab_klaim.value==""){
        alert('Sebab Klaim kosong, harap lengkapi data input...!!!');
        form.kode_sebab_klaim.focus();
      }else if(form.tgl_lapor.value==""){
        alert('Tgl Lapor kosong, harap lengkapi data input...!!!');
        form.tgl_lapor.focus(); 	
      }else if(form.tgl_kejadian.value==""){
        alert('Tgl Kejadian kosong, harap lengkapi data input...!!!');
        form.tgl_kejadian.focus();
      }else if(form.status_klaim.value=="AGENDA_TAHAP_II" && form.jkk2_kode_kondisi_terakhir.value==""){
        alert('Kondisi Terakhir TK kosong, harap lengkapi data input...!!!');
        form.jkk2_kode_kondisi_terakhir.focus();											      
      }else
      {
       form.btn_task.value="submit_data_tanpa_otentikasi";
       form.submit();
      }
    }				
  </script>		

	<?
	// -- tab --------------------------------------------------------------------
  if($_REQUEST["task"] == "Edit" || $_REQUEST["task"] == "View" || $_REQUEST["task"] == "New")
  {
	?>
    <link rel="stylesheet" type="text/css" href="../../style/tabs/css/screen.css" media="screen" />
    <script type="text/javascript" src="../../style/tabs/js/jquery-1.4.2.min.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
      $('ul#nav li a').removeClass('active'); 					//menghilangkan class active (yang tampil)			
      $('#t'+<?=$ls_activetab;?>).addClass("active"); 	// menambahkan class active pada link yang diklik
      $('.tab_konten').hide(); 													// menutup semua konten tab					
      $('#tab'+<?=$ls_activetab;?>).fadeIn('slow'); 		//tab pertama ditampilkan								 
      
      // jika link tab di klik
      $('ul#nav li a').click(function() 
      { 					 																																				
        $('ul#nav li a').removeClass('active'); 				//menghilangkan class active (yang tampil)			
        $(this).addClass("active"); 										// menambahkan class active pada link yang diklik
        $('.tab_konten').hide(); 												// menutup semua konten tab
        var aktif = $(this).attr('href'); 							// mencari mana tab yang harus ditampilkan
        var aktif_idx = aktif.substr(4,5);
        document.getElementById('activetab').value = aktif_idx;
        //alert(aktif_idx);
        $(aktif).fadeIn('slow'); 												// tab yang dipilih, ditampilkan
        return false;
      });		
    });
    </script>		
	<?
	}
	// -- end tab ----------------------------------------------------------------
	?>		
	<!-- end LOCAL JAVASCRIPTS -------------------------------------------------->
	
	<!-- VALIDASI AJAX ---------------------------------------------------------->
  <script type="text/javascript" src="../../javascript/validator.js"></script>
  <script type="text/javascript" src="../../javascript/ajax.js"></script>
  
  <script type="text/javascript">
  //Create validator object
  var validator = new formValidator();
  var ajax = new sack();
  //ambil nilai previous, dibandingkan dg nilai current, apabila berbeda maka ajax akan dijalankan
  var curr_kpj =<?php echo ($ls_kpj=='') ? 'false' : "'".$ls_kpj."'"; ?>;
	var curr_kode_tk =<?php echo ($ls_kode_tk=='') ? 'false' : "'".$ls_kode_tk."'"; ?>;
	var curr_kode_tipe_klaim =<?php echo ($ls_kode_tipe_klaim=='') ? 'false' : "'".$ls_kode_tipe_klaim."'"; ?>;
  var curr_kode_sebab_klaim =<?php echo ($ls_kode_sebab_klaim=='') ? 'false' : "'".$ls_kode_sebab_klaim."'"; ?>;
	var curr_tgl_kejadian =<?php echo ($ld_tgl_kejadian=='') ? 'false' : "'".$ld_tgl_kejadian."'"; ?>;
	var curr_tgl_lapor =<?php echo ($ld_tgl_lapor=='') ? 'false' : "'".$ld_tgl_lapor."'"; ?>;
	
  //validasi kpj ---------------------------------------------------------------
  function f_ajax_val_kpj()
  {
    var c_kpj 						= window.document.getElementById('kpj').value;
    var c_kode_segmen 		= window.document.getElementById('kode_segmen').value;
		var c_kode_tk 				= window.document.getElementById('kode_tk').value;

    var c_kode_tipe_klaim = window.document.getElementById('kode_tipe_klaim').value;
		var c_kode_sebab_klaim = window.document.getElementById('kode_sebab_klaim').value;
    var c_tgl_kejadian 		= window.document.getElementById('tgl_kejadian').value;
		var c_kode_perusahaan = window.document.getElementById('kode_perusahaan').value;
		var c_kode_divisi 		= window.document.getElementById('kode_divisi').value;
		var c_kode_klaim 			= window.document.getElementById('kode_klaim').value;

    //if ((c_kpj != curr_kpj) || (c_kode_tk != curr_kode_tk)) {
		if ((c_kpj != curr_kpj)) {
			ajax.requestFile = '../ajax/pn5058_validasi.php?getClientId=f_ajax_val_kpj&c_kpj='+c_kpj+'&c_kode_segmen='+c_kode_segmen+'&c_kode_tk='+c_kode_tk+'&c_kode_tipe_klaim='+c_kode_tipe_klaim+'&c_kode_sebab_klaim='+c_kode_sebab_klaim+'&c_tgl_kejadian='+c_tgl_kejadian+'&c_kode_perusahaan='+c_kode_perusahaan+'&c_kode_divisi='+c_kode_divisi+'&c_kode_klaim='+c_kode_klaim;
      ajax.onCompletion = showClientData; // Specify function that will be executed after file has been found	
      ajax.runAJAX();	// Execute AJAX function
      curr_kpj = c_kpj;
			curr_kode_tk = c_kode_tk;
    }		
  }  

  function fl_js_get_lov_by_kpj()
  {		 					
		var c_kode_segmen = window.document.getElementById('kode_segmen').value;
		var c_kode_tipe_klaim = window.document.getElementById('kode_tipe_klaim').value;
		var c_tgl_kejadian = window.document.getElementById('tgl_kejadian').value;
		
		NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5058_lov_kpj.php?p=pn5058.php&a=formreg&b=kode_tk&c=kpj&d=nama_tk&e=kode_perusahaan&f=nama_perusahaan&g=kode_divisi&h=nama_divisi&j='+c_kode_segmen+'&k=npp&l=nomor_identitas&m=jenis_identitas&n=kode_kantor_tk&q='+c_kode_tipe_klaim+'&r='+c_tgl_kejadian+'','',1000,500,1);		 
  }
	
  function fl_js_get_lov_by_kpj2()
  {		 			
		var c_kode_segmen = window.document.getElementById('kode_segmen').value;
		var c_kpj 				= window.document.getElementById('kpj').value;
		var c_kode_tipe_klaim = window.document.getElementById('kode_tipe_klaim').value;
		var c_tgl_kejadian = window.document.getElementById('tgl_kejadian').value;
		
		NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5058_lov_kpj.php?p=pn5058.php&a=formreg&b=kode_tk&c=kpj&d=nama_tk&e=kode_perusahaan&f=nama_perusahaan&g=kode_divisi&h=nama_divisi&j='+c_kode_segmen+'&k=npp&l=nomor_identitas&m=jenis_identitas&n=kode_kantor_tk&q='+c_kode_tipe_klaim+'&r='+c_tgl_kejadian+'&pilihsearch=sc_kpj&searchtxt='+c_kpj+'','',1000,500,1);		 
  }
	
  function fl_js_get_lov_by_kpj2care()
  {		 			
		var c_kode_segmen = window.document.getElementById('kode_segmen').value;
		var c_kpj 				= window.document.getElementById('kpj').value;
		NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5058_lov_kpj2care.php?p=pn5058.php&a=formreg&b=kode_tk&c=kpj&d=nama_tk&e=kode_perusahaan&f=nama_perusahaan&g=kode_divisi&h=nama_divisi&j='+c_kode_segmen+'&k=npp&l=nomor_identitas&m=jenis_identitas&n=kode_kantor_tk&q='+c_kpj+'','',1000,500,1);		 
  }
																
  //validasi tipe klaim --------------------------------------------------------
  function f_ajax_val_kode_tipe_klaim()
  {
    var c_kode_tipe_klaim = window.document.getElementById('kode_tipe_klaim').value;
    var c_kode_segmen = window.document.getElementById('kode_segmen').value;
    var c_kode_sebab_klaim = window.document.getElementById('kode_sebab_klaim').value;
		var c_kode_tk = window.document.getElementById('kode_tk').value;
		var c_kode_klaim = window.document.getElementById('kode_klaim').value;
		var c_tgl_kejadian = window.document.getElementById('tgl_kejadian').value;
		
    if (c_kode_tipe_klaim != curr_kode_tipe_klaim) {
      ajax.requestFile = '../ajax/pn5058_validasi.php?getClientId=f_ajax_val_kode_tipe_klaim&c_kode_tipe_klaim='+c_kode_tipe_klaim+'&c_kode_segmen='+c_kode_segmen+'&c_kode_sebab_klaim='+c_kode_sebab_klaim+'&c_kode_tk='+c_kode_tk+'&c_kode_klaim='+c_kode_klaim+'&c_tgl_kejadian='+c_tgl_kejadian;
      ajax.onCompletion = showClientData; // Specify function that will be executed after file has been found	
      ajax.runAJAX();	// Execute AJAX function
      curr_kode_tipe_klaim = c_kode_tipe_klaim;
    }		
  }             

  function fl_js_get_lov_by_kode_tipe_klaim()
  {		 			
		NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5058_lov_tipeklaim.php?p=pn5058.php&a=formreg&b=kode_tipe_klaim&c=nama_tipe_klaim','',800,500,1);		 
  }
		
  //validasi sebab klaim --------------------------------------------------------
  function f_ajax_val_kode_sebab_klaim()
  {
    var c_kode_tipe_klaim = window.document.getElementById('kode_tipe_klaim').value;
    var c_kode_segmen = window.document.getElementById('kode_segmen').value;
    var c_kode_sebab_klaim = window.document.getElementById('kode_sebab_klaim').value;
		var c_kode_tk = window.document.getElementById('kode_tk').value;
		var c_kode_klaim = window.document.getElementById('kode_klaim').value;
		var c_tgl_kejadian = window.document.getElementById('tgl_kejadian').value;
		
    if (c_kode_sebab_klaim != curr_kode_sebab_klaim) {
      ajax.requestFile = '../ajax/pn5058_validasi.php?getClientId=f_ajax_val_kode_sebab_klaim&c_kode_tipe_klaim='+c_kode_tipe_klaim+'&c_kode_segmen='+c_kode_segmen+'&c_tgl_kejadian='+c_tgl_kejadian+'&c_kode_sebab_klaim='+c_kode_sebab_klaim+'&c_kode_tk='+c_kode_tk+'&c_kode_klaim='+c_kode_klaim;
      ajax.onCompletion = showClientData; // Specify function that will be executed after file has been found	
      ajax.runAJAX();	// Execute AJAX function
      curr_kode_sebab_klaim = c_kode_sebab_klaim;
    }		
  } 																

  function fl_js_get_lov_by_kode_sebab_klaim()
  {		 			
		var c_kode_tipe_klaim = window.document.getElementById('kode_tipe_klaim').value;
    var c_kode_segmen = window.document.getElementById('kode_segmen').value;
			
		NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5058_lov_sebabklaim.php?p=pn5058.php&a=formreg&b=kode_sebab_klaim&c=nama_sebab_klaim&d='+c_kode_tipe_klaim+'&e='+c_kode_segmen+'','',800,500,1);		 
  }

	//validasi tgl kejadian jkk dan jkm TKI --------------------------------------
  function f_ajax_val_tgl_kejadian()
  {
    var c_kode_perusahaan = window.document.getElementById('kode_perusahaan').value;
		var c_kode_segmen = window.document.getElementById('kode_segmen').value;
		var c_kode_divisi = window.document.getElementById('kode_divisi').value;
		var c_kode_tk = window.document.getElementById('kode_tk').value;
		var c_tgl_kejadian = window.document.getElementById('tgl_kejadian').value;
		var c_jenis_klaim = window.document.getElementById('jenis_klaim').value;
		var c_tgl_lapor = window.document.getElementById('tgl_lapor').value;
		var c_tgl_klaim = window.document.getElementById('tgl_klaim').value;
		var c_kode_tipe_klaim = window.document.getElementById('kode_tipe_klaim').value;
    var c_kode_klaim = window.document.getElementById('kode_klaim').value;
		var c_kode_sebab_klaim = window.document.getElementById('kode_sebab_klaim').value;
		
    if ((c_tgl_kejadian != curr_tgl_kejadian) && (c_tgl_kejadian!='')) {
      ajax.requestFile = '../ajax/pn5058_validasi.php?getClientId=f_ajax_val_tgl_kejadian&c_kode_perusahaan='+c_kode_perusahaan+'&c_kode_segmen='+c_kode_segmen+'&c_kode_sebab_klaim='+c_kode_sebab_klaim+'&c_kode_divisi='+c_kode_divisi+'&c_kode_tk='+c_kode_tk+'&c_tgl_kejadian='+c_tgl_kejadian+'&c_jenis_klaim='+c_jenis_klaim+'&c_tgl_lapor='+c_tgl_lapor+'&c_tgl_klaim='+c_tgl_klaim+'&c_kode_tipe_klaim='+c_kode_tipe_klaim+'&c_kode_klaim='+c_kode_klaim;
      ajax.onCompletion = showClientData; // Specify function that will be executed after file has been found	
      ajax.runAJAX();	// Execute AJAX function
      curr_tgl_kejadian = c_tgl_kejadian;
    }		
  }

	//validasi tgl lapor ---------------------------------------------------------
  function f_ajax_val_tgl_lapor()
  {
    var c_tgl_lapor = window.document.getElementById('tgl_lapor').value;
		var c_tgl_kejadian = window.document.getElementById('tgl_kejadian').value;
		var c_tgl_klaim = window.document.getElementById('tgl_klaim').value;
		var c_kode_tk = window.document.getElementById('kode_tk').value;
		var c_kode_tipe_klaim = window.document.getElementById('kode_tipe_klaim').value;
		
    if ((c_tgl_lapor != curr_tgl_lapor) && (c_tgl_lapor!='')) {
      ajax.requestFile = '../ajax/pn5058_validasi.php?getClientId=f_ajax_val_tgl_lapor&c_tgl_lapor='+c_tgl_lapor+'&c_tgl_kejadian='+c_tgl_kejadian+'&c_tgl_klaim='+c_tgl_klaim+'&c_kode_tk='+c_kode_tk+'&c_kode_tipe_klaim='+c_kode_tipe_klaim;
      ajax.onCompletion = showClientData; // Specify function that will be executed after file has been found	
      ajax.runAJAX();	// Execute AJAX function
      curr_tgl_lapor = c_tgl_lapor;
    }		
  }
			
	//validasi tgl kejadian jkk dan jkm TKI --------------------------------------
  function f_ajax_val_tglkejadian()
  {
    var c_kode_perusahaan = window.document.getElementById('kode_perusahaan').value;
		var c_kode_segmen = window.document.getElementById('kode_segmen').value;
		var c_kode_divisi = window.document.getElementById('kode_divisi').value;
		var c_kode_tk = window.document.getElementById('kode_tk').value;
		var c_tgl_kejadian = window.document.getElementById('tgl_kejadian').value;
		var c_jenis_klaim = window.document.getElementById('jenis_klaim').value;
		var c_tgl_lapor = window.document.getElementById('tgl_lapor').value;
		var c_tgl_klaim = window.document.getElementById('tgl_klaim').value;
		
    ajax.requestFile = '../ajax/pn5058_validasi.php?getClientId=f_ajax_val_tgl_kejadian&c_kode_perusahaan='+c_kode_perusahaan+'&c_kode_segmen='+c_kode_segmen+'&c_kode_divisi='+c_kode_divisi+'&c_kode_tk='+c_kode_tk+'&c_tgl_kejadian='+c_tgl_kejadian+'&c_jenis_klaim='+c_jenis_klaim+'&c_tgl_lapor='+c_tgl_lapor+'&c_tgl_klaim='+c_tgl_klaim;
    ajax.onCompletion = showClientData; // Specify function that will be executed after file has been found	
    ajax.runAJAX();	// Execute AJAX function
    curr_tgl_kejadian = c_tgl_kejadian;
  }
	
	//validasi kode_hubungan pada saat input ahli waris klaim jht ----------------
	function f_ajax_jhtinput_val_kode_hubungan()		
	{
	 	var c_jhtinput_kode_tipe_penerima = window.document.getElementById('jhtinput_kode_tipe_penerima').value;
		var c_jhtinput_kode_hubungan = window.document.getElementById('jhtinput_kode_hubungan').value;
		var c_kode_tk = window.document.getElementById('kode_tk').value;
		
    if ((c_jhtinput_kode_tipe_penerima == "AW") && (c_jhtinput_kode_hubungan != curr_jhtinput_kode_hubungan)) {
      ajax.requestFile = '../ajax/pn5058_validasi.php?getClientId=f_ajax_jhtinput_val_kode_hubungan&c_kode_tk='+c_kode_tk+'&c_jhtinput_kode_hubungan='+c_jhtinput_kode_hubungan;
      ajax.onCompletion = showClientData; // Specify function that will be executed after file has been found	
      ajax.runAJAX();	// Execute AJAX function
      curr_jhtinput_kode_hubungan = c_jhtinput_kode_hubungan;
    }			 
	}
	
  function showTableData()
  {
    var formObj = document.formreg;
    if (ajax.xmlhttp.readyState == 4) {
    	window.document.getElementById("tblrincian1").innerHTML = ajax.response;
    } else {
    	alert("Something strange occured!");
    }		
  }		
  
  function showClientData()
  {
    var formObj = document.formreg;
    eval(ajax.response);
    var st_errorval1 = window.document.getElementById("st_errval1").value;
    var st_errorval2 = window.document.getElementById("st_errval2").value;
    var st_errorval3 = window.document.getElementById("st_errval3").value;
		var st_errorval4 = window.document.getElementById("st_errval4").value;
		var st_errorval6 = window.document.getElementById("st_errval6").value;
		var st_errorval7 = window.document.getElementById("st_errval7").value;
				
    //tampilan error jika tipe klaim tidak valid --------------  					
    if (st_errorval1 == 1)
    {  
      window.document.getElementById("dispError1").innerHTML = "(* Tipe Klaim tidak valid ..!!!";
      window.document.getElementById("dispError1").style.display = 'block';
      window.document.getElementById('kode_tipe_klaim').focus();
    }else if (st_errorval1 == 2)
    {  
      window.document.getElementById("dispError1").innerHTML = "(* Klaim JHT tidak dapat dilakukan, TK tidak mengikuti program JHT ..!!!";
      window.document.getElementById("dispError1").style.display = 'block';
      window.document.getElementById('kode_tipe_klaim').focus();
    }else if (st_errorval1 == 3)
    {  
      window.document.getElementById("dispError1").innerHTML = "(* Klaim JHT/JKM tidak dapat dilakukan, TK tidak mengikuti program JHT ..!!!";
      window.document.getElementById("dispError1").style.display = 'block';
      window.document.getElementById('kode_tipe_klaim').focus();					
    }else if (st_errorval1 == 4)
    {  
      window.document.getElementById("dispError1").innerHTML = "(* Klaim JHT/JKM untuk TK tersebut sudah pernah dientry ..!!!";
      window.document.getElementById("dispError1").style.display = 'block';
      window.document.getElementById('kode_tipe_klaim').focus();	
    }else if (st_errorval1 == 5)
    {  
      window.document.getElementById("dispError1").innerHTML = "(* Klaim JHT/JKM tidak dapat dilakukan, Klaim JHT sudah pernah dientry, Silahkan entry klaim JKM ..!!!";
      window.document.getElementById("dispError1").style.display = 'block';
      window.document.getElementById('kode_tipe_klaim').focus();				
    }else if (st_errorval1 == 6)
    {  
      window.document.getElementById("dispError1").innerHTML = "(* Klaim JHT/JKM tidak dapat dilakukan, Klaim JKM sudah pernah dientry, Silahkan entry klaim JHT ..!!!";
      window.document.getElementById("dispError1").style.display = 'block';
      window.document.getElementById('kode_tipe_klaim').focus();				
    }else if (st_errorval1 == 7)
    {  
      window.document.getElementById("dispError1").innerHTML = "(* Klaim JP tidak dapat dilakukan, TK tidak mengikuti program JP ..!!!";
      window.document.getElementById("dispError1").style.display = 'block';
      window.document.getElementById('kode_tipe_klaim').focus();
    }else if (st_errorval1 == 8)
    {  
      window.document.getElementById("dispError1").innerHTML = "(* Klaim JPN untuk TK tersebut sudah pernah dientry ..!!!";
      window.document.getElementById("dispError1").style.display = 'block';
      window.document.getElementById('kode_tipe_klaim').focus();			
    }else if (st_errorval1 == 9)
    {  
      window.document.getElementById("dispError1").innerHTML = "(* Klaim JKK dengan tgl kecelakaan yang sama untuk TK tersebut sudah pernah dientry ..!!!";
      window.document.getElementById("dispError1").style.display = 'block';
      window.document.getElementById('kode_tipe_klaim').focus();
    }else if (st_errorval1 == 10)
    {  
      window.document.getElementById("dispError1").innerHTML = "(* Klaim JKM untuk TK tersebut sudah pernah dientry ..!!!";
      window.document.getElementById("dispError1").style.display = 'block';
      window.document.getElementById('kode_tipe_klaim').focus();													
    }else{
    	window.document.getElementById("dispError1").style.display = 'none';
    }
		
    //tampilan error jika sebab klaim tidak valid --------------  					
    if (st_errorval2 == 1)
    {  
      window.document.getElementById("dispError2").innerHTML = "(* Sebab Klaim tidak valid ..!!!";
      window.document.getElementById("dispError2").style.display = 'block';
      window.document.getElementById('kode_sebab_klaim').focus();
    }else if (st_errorval2 == 2)
    {  
      window.document.getElementById("dispError2").innerHTML = "(* Klaim Sebagian JHT untuk TK tersebut sudah pernah dientry ..!!!";
      window.document.getElementById("dispError2").style.display = 'block';
      window.document.getElementById('kode_sebab_klaim').focus();
    }else if (st_errorval2 == 3)
    {  
      window.document.getElementById("dispError2").innerHTML = "(* Klaim Penuh JHT untuk TK tersebut sudah pernah dientry ..!!!";
      window.document.getElementById("dispError2").style.display = 'block';
      window.document.getElementById('kode_sebab_klaim').focus();								
    }else{
    	window.document.getElementById("dispError2").style.display = 'none';
    }
    //tampilan error jika kpj tidak valid --------------  					
    if (st_errorval3 == 1)
    {  
      window.document.getElementById("dispError3").innerHTML = "(* No. Referensi tidak valid ..!!!";
      window.document.getElementById("dispError3").style.display = 'block';
      window.document.getElementById('kpj').focus();
    }else if (st_errorval3 == 2)
    {  
      window.document.getElementById("dispError3").innerHTML = "(* Tenaga kerja dengan No. Referensi yang sama bekerja di lebih dari satu perusahaan, harap lakukan penggabungan setelah data disimpan..!!!";
      window.document.getElementById("dispError3").style.display = 'block';
      window.document.getElementById('kpj').focus();
    }else{
    	window.document.getElementById("dispError3").style.display = 'none';
    }
    //tampilan error jika tgl kejadian diluar masa kepesertaan -----------------  					
    if (st_errorval4 == 1)
    {  
      window.document.getElementById("dispError4").innerHTML = "(* Agenda Klaim tidak dapat dilanjutkan, tanggal kejadian diluar masa perlindungan ..!!!";
      window.document.getElementById("dispError4").style.display = 'block';
      window.document.getElementById('tgl_kejadian').focus();
    }else{
    	window.document.getElementById("dispError4").style.display = 'none';
    }
    //tampilan error jika tgl lapor < tgl kejadian -----------------  					
    if (st_errorval6 == 1)
    {  
      window.document.getElementById("dispError6").innerHTML = "(* Tgl Lapor tidak valid, Tgl Lapor < Tgl Kejadian ..!!!";
      window.document.getElementById("dispError6").style.display = 'block';
      window.document.getElementById('tgl_lapor').focus();
    }else if (st_errorval6 == 2) //tgl lapor > tgl klaim
    {  
      window.document.getElementById("dispError6").innerHTML = "(* Tgl Lapor tidak valid, Tgl Lapor > Tgl Klaim ..!!!";
      window.document.getElementById("dispError6").style.display = 'block';
      window.document.getElementById('tgl_lapor').focus();		
    }else if (st_errorval6 == 3) //tgl lapor < blth kepesertaan tk
    {  
      window.document.getElementById("dispError6").innerHTML = "(* Tgl Lapor tidak valid, Tgl Lapor < BLTH Kepesertaan TK ..!!!";
      window.document.getElementById("dispError6").style.display = 'block';
      window.document.getElementById('tgl_lapor').focus();					
    }else{
    	window.document.getElementById("dispError6").style.display = 'none';
    }
    //tampilan error jika tgl kejadian > tgl lapor -----------------  					
    if (st_errorval7 == 1)
    {  
      window.document.getElementById("dispError7").innerHTML = "(* Tgl Kejadian tidak valid, Tgl Kejadian > Tgl Lapor ..!!!";
      window.document.getElementById("dispError7").style.display = 'block';
      window.document.getElementById('tgl_kejadian').focus();
    }else if (st_errorval7 == 2) //tgl kejadian > tgl klaim
    {  
      window.document.getElementById("dispError7").innerHTML = "(* Tgl Kejadian tidak valid, Tgl Kejadian > Tgl Klaim ..!!!";
      window.document.getElementById("dispError7").style.display = 'block';
      window.document.getElementById('tgl_kejadian').focus();					
    }else{
    	window.document.getElementById("dispError7").style.display = 'none';
    }									 			 																																						 						 																														
  }
  </script>
	<!-- end VALIDASI AJAX ------------------------------------------------------>		