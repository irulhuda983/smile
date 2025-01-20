	<!-- LOCAL JAVASCRIPTS------------------------------------------------------->		
  <script type="text/javascript" src="../../javascript/calendar.js"></script>
  <script type="text/javascript" src="../../javascript/numeral.min.js"></script>
  <script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
	
	<script language="javascript">
    function fl_js_reset_keyword2()
    {
      document.getElementById('keyword2a').value = '';
      document.getElementById('keyword2b').value = '';		
    }
		
		function fl_js_val_rg_kondisi_terakhir_induk()
		{
			var v_rg_kondisi = $("input[name='rg_kondisi_terakhir_induk']:checked").val();
				
			if (v_rg_kondisi=="B")
			{
			 	//aktifkan span pilihan ubah status ------------------------------------
				window.document.getElementById("span_perubahan_kondisi_terakhir").style.display = 'block';	  	 
			}else
			{
				window.document.getElementById("span_perubahan_kondisi_terakhir").style.display = 'none';
				$('#kode_kondisi_terakhir_induk').val('');
        $('#tgl_kondisi_terakhir_induk').val('');		
			}							 
		}
    function refreshParent() 
    {																						
      var c_sender_mid  = window.document.getElementById('sender_mid').value;
  		
  		window.location.replace('?mid='+c_sender_mid);
    }
  	
		function doSubmitKonfirmasi()
  	{
      var form 									 				 = window.document.formreg;
		 	var c_kode_klaim  								 = window.document.getElementById('kode_klaim').value;
		 	var c_no_konfirmasi_induk  				 = window.document.getElementById('no_konfirmasi_induk').value;			
			var c_kode_kondisi_terakhir_induk  = window.document.getElementById('kode_kondisi_terakhir_induk').value;
			var c_tgl_kondisi_terakhir_induk   = window.document.getElementById('tgl_kondisi_terakhir_induk').value;
			var c_sender_mid   								 = window.document.getElementById('sender_mid').value;
			var c_sender_rg_kategori   				 = window.document.getElementById('sender_rg_kategori').value;

			var c_rg_kondisi = $("input[name='rg_kondisi_terakhir_induk']:checked").val();
			if (c_rg_kondisi!="A" && c_rg_kondisi!="B")
			{
			 	 c_rg_kondisi = "X";
			}
			
      if(c_kode_klaim=="" || c_no_konfirmasi_induk==""){
        alert('Data Berkala yang Konfirmasi tidak boleh kosong...!!!');
        form.no_penetapan.focus();
			}else if (c_rg_kondisi=="X")
			{
        alert('Kondisi Terakhir Penerima Manfaat belum dipilih, harap lengkapi data input...!!!');			
			}else if ((c_rg_kondisi=="B") && (c_kode_kondisi_terakhir_induk=="" || c_tgl_kondisi_terakhir_induk==""))
			{
        alert('Untuk pilhan Ada Perubahan Status (Menikah/Usia23,dll) maka Kondisi akhir dan tgl kondisi harus diinput keduanya...!!!');
        form.kode_kondisi_terakhir_induk.focus();																																																										
      }else
  		{
				 NewWindow('../ajax/pn5045_submit.php?kode_klaim='+c_kode_klaim+'&no_konfirmasi_induk='+c_no_konfirmasi_induk+'&rg_kondisi='+c_rg_kondisi+'&kode_kondisi_terakhir_induk='+c_kode_kondisi_terakhir_induk+'&tgl_kondisi_terakhir_induk='+c_tgl_kondisi_terakhir_induk+'&sender_mid='+c_sender_mid+'&rg_kategori='+c_sender_rg_kategori+'','',300,50,'no');
			}								 
  	}
		
		function doSubmitInformasiPenerima()
  	{
      var form = window.document.formreg;
			
			if($('#alamat').val()==""){
        alert('Alamat tidak boleh kosong...!!!');
				window.document.getElementById('alamat').focus();
			}else if ($('#kode_pos').val()==""){
        alert('Kode Pos tidak boleh kosong...!!!');
        window.document.getElementById('kode_pos').focus();
			}else if ($('#npwp').val()==""){
        alert('NPWP tidak boleh kosong, isikan 0 jika memang tidak memiliki NPWP...!!!');
        window.document.getElementById('npwp').focus();
			}else if ( $('#nama_bank_penerima').val().value==""){
        alert('Bank Penerima tidak boleh kosong, Pilih bank penerima...!!!');
        window.document.getElementById('nama_bank_penerima').focus();
			}else if ($('#kode_bank_penerima').val().value==""){
        alert('Kode Bank Penerima tidak boleh kosong, Pilih ulang bank penerima...!!!');
        window.document.getElementById('nama_bank_penerima').focus();				
			}else if ($('#no_rekening_penerima').val().value==""){
        alert('No Rekening Penerima tidak boleh kosong...!!!');
        window.document.getElementById('no_rekening_penerima').focus();
			}else if ($('#nama_rekening_penerima').val().value==""){
        alert('Nama Rekening Penerima tidak boleh kosong...!!!');
        window.document.getElementById('nama_rekening_penerima').focus();
			}else if ($('#kode_bank_pembayar').val().value==""){
        alert('Bank Pembayar tidak boleh kosong...!!!');
        window.document.getElementById('kode_bank_pembayar').focus();				
			}else if ($('#status_valid_rekening_penerima').val()!="Y"){
        alert('Rekening belum valid, tidak dapat disimpan, Tickmark Valid jika memang sudah valid...!!!');
				window.document.getElementById('status_valid_rekening_penerima').focus();																																				
      }else
  		{
				 form.btn_task.value="submitInformasiPenerima";
         form.submit(); 
  		}				
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

    function fl_js_val_email(v_field_id)
    {
      var x = window.document.getElementById(v_field_id).value;
			var atpos=x.indexOf("@");
      var dotpos=x.lastIndexOf(".");
      if ((x!='') && (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length))
      {
         document.getElementById(v_field_id).value = '';				 
				 window.document.getElementById(v_field_id).focus();
				 alert("Format Email tidak valid, belum ada (@ DAN .)");         
				 return false; 	
      }
    }

    function fl_js_val_npwp(v_field_id)
    {
      var v_npwp = window.document.getElementById(v_field_id).value;
			if ((v_npwp!='') && (v_npwp!='0') && (v_npwp.length!=15))
      {
         document.getElementById(v_field_id).value = '0';				 
				 window.document.getElementById(v_field_id).focus();
				 alert("NPWP tidak valid, harus 15 karakter...!!!");         
				 return false; 	
      }else
			{
			 	 fl_js_val_numeric(v_field_id);	 
			}
    }
		
																										
  </script>	
	<!-- end LOCAL JAVASCRIPTS -------------------------------------------------->