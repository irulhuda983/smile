
<script language="JavaScript">    
    $(document).ready(function(){

        $('#info_masa_iur_bulan_keps').hide();
        $('#profile_title').hide();
        $('#fieldset_daftar_lamaran').hide();
        $('#fieldset_daftar_konseling').hide();
        $('#fieldset_daftar_pelatihan_kerja').hide();
        $('#fieldset_daftar_wawancara').hide();
		
        if('<?=$_GET['root_sender'];?>' == 'pn5041.php' || '<?=$_GET['root_sender'];?>' == 'pn5043.php' || '<?=$_GET['root_sender'];?>' == 'pn5048.php'){
            $('#info_masa_iur_bulan_keps').show();
            setTimeout(function(){ filter(); }, 1000);
        }

		setTimeout(function(){ informasiManfaat(); }, 1000);
        				
	});

    function getValue(val){
		return val == null ? '-' : val;
	}

    function filter(val = 0){
		var pages 		= new Number($("#pages").val());
		var page 		= new Number($("#page").val());
		var page_item 	= $("#page_item").val();
        var kode_klaim  = '<?=$ls_kode_klaim?>';
	 

		if (val == 1) {
			page = (page + 1) > pages ? pages : (page + 1);
		} else if (val == 2) {
			page = pages;
		} else if (val == -1) {
			page = (page - 1) <= 0 ? 1 : (page - 1);
		} else if (val == -2){
			page = 1;
		}else if(val == 0){
			page=1;
		}

		$("#page").val(page);
		
		
		preload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5040_action_view_jkp.php?"+Math.random(),
			data: {
				types		: 'query',
				page		: page,
				page_item	: page_item,
                kode_klaim  : kode_klaim
			},
			success: function(data){
				var jdata = JSON.parse(data);	
				if (jdata.ret == 1){
					var html_data = "";
					for(var i = 0; i < jdata.data.length; i++){
						html_data += "<tr bgcolor=#"+(i%2 ? 'f3f3f3' : 'ffffff')+">";						
						html_data += '<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getValue(jdata.data[i].KPJ) + '</td>';
						html_data += '<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getValue(jdata.data[i].NPP) + '</td>';
						html_data += '<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getValue(jdata.data[i].KODE_DIVISI) + '</td>';
						html_data += '<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getValue(jdata.data[i].BLTH_IURAN) + '</td>';							
						html_data += '<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getValue(jdata.data[i].TGL_BAYAR) + '</td>';							
						html_data += '<td style="text-align:right;font: 10px Verdana, Arial, Helvetica, sans-serif;"> Rp ' + getValue(jdata.data[i].NOM_IUR_REKOMPOSISI_JKK) + '</td>';						
						html_data += '<td style="text-align:right;font: 10px Verdana, Arial, Helvetica, sans-serif;"> Rp ' + getValue(jdata.data[i].NOM_IUR_REKOMPOSISI_JKM) + '</td>';						
						html_data += '<td style="text-align:right;font: 10px Verdana, Arial, Helvetica, sans-serif;"> Rp ' + getValue(jdata.data[i].NOM_SUBSIDI_PEMERINTAH) + '</td>';						
						html_data += '<td style="text-align:right;font: 10px Verdana, Arial, Helvetica, sans-serif;"> Rp ' + getValue(jdata.data[i].NOM_IUR_TOTAL) + '</td>';				
						html_data += '</tr>';
					}

                if (html_data == "") {
						html_data += '<tr class="nohover-color">';
						html_data += '<td colspan="9" style="text-align: center;">-- Data tidak ditemukan --</td>';
						html_data += '</tr>';
					}

					$("#data_list").html(html_data);
					
					// load info halaman
					$("#pages").val(jdata.pages);
					$("#span_info_halaman").html('dari ' + jdata.pages + ' halaman');

					// load info item
					$("#span_info_item").html('Menampilkan item ke ' + jdata.start + ' sampai dengan ' + jdata.end + ' dari ' + jdata.recordsTotal + ' items');
					$("#jumlah_bulan").html(jdata.recordsTotal + ' Bulan');
					$("#hdn_total_records").val(jdata.recordsTotal);
          
				  preload(false);
				} else {
					alert(jdata.msg);
				}
				preload(false);
			},
			complete: function(){
				preload(false);
			},
			error: function(){
				alert("Terjadi kesalahan, coba beberapa saat lagi!");
				preload(false);
			}
		});
	}
  

  function informasiManfaat(){
    var kode_klaim        = '<?=$ls_kode_klaim?>';
	 
    // ======================================= informasi manfaat ===================================================
    preload(true);
    $.ajax({
     type: 'POST',
     url: "../ajax/pn5040_action_view_jkp.php?"+Math.random(),
     data: {
      types			      : 'query_info_manfaat',
      kode_profile_tk_jkp : '<?=$ls_jkpkode_profile_tk_jkp?>',
      tahap_jkp           : '<?=$ls_jkptahap_jkp?>'
    },
    success: function(data){
      var jdata = JSON.parse(data);	
      if (jdata.ret == 1){
        var html_data_info_manfaat = "";
        for(var i = 0; i < jdata.data.length; i++){
          html_data_info_manfaat += "<tr bgcolor=#"+(i%2 ? 'f3f3f3' : 'ffffff')+">";						
          html_data_info_manfaat += '<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getValue(jdata.data[i].BULAN_MANFAAT_KE) + '</td>';
          html_data_info_manfaat += '<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getBLTH(jdata.data[i].BLTH_MANFAAT) + '</td>';
          html_data_info_manfaat += '<td style="text-align:right;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getValue(currencyIDR(jdata.data[i].NOM_UPAH_PELAPORAN)) + '</td>';
          html_data_info_manfaat += '<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getBLTH(jdata.data[i].BLTH_UPAH_PELAPORAN) + '</td>';							
          html_data_info_manfaat += '<td style="text-align:right;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getValue(currencyIDR(jdata.data[i].NOM_UPAH_TERHITUNG)) + '</td>';							
          html_data_info_manfaat += '<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getPercent(jdata.data[i].TARIF_UPAH_TERHITUNG) + '</td>';						
          html_data_info_manfaat += '<td style="text-align:right;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + currencyIDR(jdata.data[i].NOM_MANFAAT) + '</td>';						
          html_data_info_manfaat += '<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getValue(jdata.data[i].STATUS_PENGAJUAN) + '</td>';					
          html_data_info_manfaat += '<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getTanggal(jdata.data[i].TGL_BAYAR) + '</td>';					
          html_data_info_manfaat += '</tr>';

        }

        if (html_data_info_manfaat == "") {
          html_data_info_manfaat += '<tr class="nohover-color">';
          html_data_info_manfaat += '<td colspan="8" style="text-align: center;">-- Data tidak ditemukan --</td>';
          html_data_info_manfaat += '</tr>';
        }

        $("#data_list_info_manfaat").html(html_data_info_manfaat);

        if('<?=$ls_jkpbulan_manfaat_ke;?>' > 1){
            $('#profile_title').show();
            $('#fieldset_daftar_lamaran').show();
            $('#fieldset_daftar_konseling').show();
            $('#fieldset_daftar_pelatihan_kerja').show();
            $('#fieldset_daftar_wawancara').show();

            setTimeout(function(){ daftarLamaran(kode_klaim); }, 1000);
            setTimeout(function(){ daftarPelatihanKerja(kode_klaim); }, 1000);
            setTimeout(function(){ daftarKonseling(kode_klaim); }, 1000);
            setTimeout(function(){ daftarWawancara(kode_klaim); }, 1000);

        }
        
       
          preload(false);
        } else {
          alert(jdata.msg);
        }
        preload(false);
      },
      complete: function(){
        preload(false);
      },
      error: function(){
        alert("Terjadi kesalahan, coba beberapa saat lagi!");
        preload(false);
      }
    });

          // ======================================= informasi manfaat ===================================================

	}

  function daftarLamaran(kode_klaim){
    preload(true);
    $.ajax({
      type: 'POST',
      url: "../ajax/pn5040_action_view_jkp.php?"+Math.random(),
      data: {
        types			  : 'query_daftar_lamaran',
        kode_klaim  : kode_klaim
      },
      success: function(data){
        var jdata = JSON.parse(data);	
        if (jdata.ret == 1){
          var html_data_daftar_lamaran = "";
          for(var i = 0; i < jdata.data.length; i++){
            html_data_daftar_lamaran += "<tr bgcolor=#"+(i%2 ? 'f3f3f3' : 'ffffff')+">";						
            html_data_daftar_lamaran += '<td style="text-align:left;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getValue(jdata.data[i].NAMA_PERUSAHAAN_LAMARAN) + '</td>';
            html_data_daftar_lamaran += '<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getTanggal(jdata.data[i].TGL_LAMARAN) + '</td>';
            html_data_daftar_lamaran += '<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getValue(jdata.data[i].POSISI_LAMARAN) + '</td>';
            html_data_daftar_lamaran += '<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getValue(jdata.data[i].STATUS_LAMARAN_KERJA) + '</td>';							
            html_data_daftar_lamaran += '<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getValue(jdata.data[i].STATUS_INTERVIEW) + '</td>';							
            html_data_daftar_lamaran += '</tr>';

          }

          if (html_data_daftar_lamaran == "") {
            html_data_daftar_lamaran += '<tr class="nohover-color">';
            html_data_daftar_lamaran += '<td colspan="5" style="text-align: center;">-- Data tidak ditemukan --</td>';
            html_data_daftar_lamaran += '</tr>';
          }

          $("#data_list_daftar_lamaran").html(html_data_daftar_lamaran);

          preload(false);
        } else {
          alert(jdata.msg);
        }
        preload(false);
      },
      complete: function(){
        preload(false);
      },
      error: function(){
        alert("Terjadi kesalahan, coba beberapa saat lagi!");
        preload(false);
      }
    });

  }

  function daftarPelatihanKerja(kode_klaim){
    preload(true);
    $.ajax({
      type: 'POST',
      url: "../ajax/pn5040_action_view_jkp.php?"+Math.random(),
      data: {
        types			  : 'query_daftar_pelatihan_kerja',
        kode_klaim  : kode_klaim
      },
      success: function(data){
        var jdata = JSON.parse(data);	
        if (jdata.ret == 1){
          var html_data_daftar_pelatihan_kerja = "";
          for(var i = 0; i < jdata.data.length; i++){
            html_data_daftar_pelatihan_kerja += "<tr bgcolor=#"+(i%2 ? 'f3f3f3' : 'ffffff')+">";			
            html_data_daftar_pelatihan_kerja += '<td style="text-align:left;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getValue(jdata.data[i].NAMA_BLK) + '</td>';
            html_data_daftar_pelatihan_kerja += '<td style="text-align:left;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getValue(jdata.data[i].JENIS_BLK) + '</td>';
            html_data_daftar_pelatihan_kerja += '<td style="text-align:left;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getValue(jdata.data[i].JENIS_PELATIHAN) + '</td>';
            html_data_daftar_pelatihan_kerja += '<td style="text-align:left;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getValue(jdata.data[i].NAMA_MATERI) + '</td>';							
            html_data_daftar_pelatihan_kerja += '<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getTanggal(jdata.data[i].TGL_AWAL_PELATIHAN) + '</td>';							
            html_data_daftar_pelatihan_kerja += '<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getTanggal(jdata.data[i].TGL_AKHIR_PELATIHAN) + '</td>';							
            html_data_daftar_pelatihan_kerja += '<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getValue(jdata.data[i].PERSENTASI_ABSENSI) + '</td>';							
            html_data_daftar_pelatihan_kerja += '<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getValue(jdata.data[i].STATUS_LULUS) + '</td>';							
            html_data_daftar_pelatihan_kerja += '</tr>';

          }

          if (html_data_daftar_pelatihan_kerja == "") {
            html_data_daftar_pelatihan_kerja += '<tr class="nohover-color">';
            html_data_daftar_pelatihan_kerja += '<td colspan="8" style="text-align: center;">-- Data tidak ditemukan --</td>';
            html_data_daftar_pelatihan_kerja += '</tr>';
          }

          $("#data_list_daftar_pelatihan_kerja").html(html_data_daftar_pelatihan_kerja);

          preload(false);
        } else {
          alert(jdata.msg);
        }
        preload(false);
      },
      complete: function(){
        preload(false);
      },
      error: function(){
        alert("Terjadi kesalahan, coba beberapa saat lagi!");
        preload(false);
      }
    });

  }

  function daftarKonseling(kode_klaim){
    preload(true);
    $.ajax({
      type: 'POST',
      url: "../ajax/pn5040_action_view_jkp.php?"+Math.random(),
      data: {
        types			  : 'query_daftar_konseling',
        kode_klaim  : kode_klaim
      },
      success: function(data){
        var jdata = JSON.parse(data);	
        if (jdata.ret == 1){
          var html_data_daftar_konseling = "";
          for(var i = 0; i < jdata.data.length; i++){
            html_data_daftar_konseling += "<tr bgcolor=#"+(i%2 ? 'f3f3f3' : 'ffffff')+">";						
            html_data_daftar_konseling += '<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getValue(jdata.data[i].TGL_KONSELING) + '</td>';
            html_data_daftar_konseling += '<td style="text-align:left;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getValue(jdata.data[i].REKOMENDASI_AKTIVITAS) + '</td>';
            html_data_daftar_konseling += '<td style="text-align:left;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getValue(jdata.data[i].NAMA_KONSELOR) + '</td>';
            html_data_daftar_konseling += '</tr>';

          }

          if (html_data_daftar_konseling == "") {
            html_data_daftar_konseling += '<tr class="nohover-color">';
            html_data_daftar_konseling += '<td colspan="3" style="text-align: center;">-- Data tidak ditemukan --</td>';
            html_data_daftar_konseling += '</tr>';
          }

          $("#data_list_daftar_konseling").html(html_data_daftar_konseling);

          preload(false);
        } else {
          alert(jdata.msg);
        }
        preload(false);
      },
      complete: function(){
        preload(false);
      },
      error: function(){
        alert("Terjadi kesalahan, coba beberapa saat lagi!");
        preload(false);
      }
    });  

  }


  function daftarWawancara(kode_klaim){
    preload(true);
    $.ajax({
      type: 'POST',
      url: "../ajax/pn5040_action_view_jkp.php?"+Math.random(),
      data: {
        types		: 'query_daftar_wawancara',
        kode_klaim  : kode_klaim
      },
      success: function(data){
        var jdata = JSON.parse(data);	
        if (jdata.ret == 1){
          var html_data_daftar_wawancara = "";
          for(var i = 0; i < jdata.data.length; i++){
            html_data_daftar_wawancara += "<tr bgcolor=#"+(i%2 ? 'f3f3f3' : 'ffffff')+">";						
            html_data_daftar_wawancara += '<td style="text-align:left;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getValue(jdata.data[i].NAMA_PERUSAHAAN) + '</td>';
            html_data_daftar_wawancara += '<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getValue(jdata.data[i].TGL_INTERVIEW) + '</td>';
            html_data_daftar_wawancara += '<td style="text-align:left;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getValue(jdata.data[i].POSISI) + '</td>';
            html_data_daftar_wawancara += '<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">' + getValue(jdata.data[i].HASIL_WAWANCARA) + '</td>';
            html_data_daftar_wawancara += '</tr>';

          }

          if (html_data_daftar_wawancara == "") {
            html_data_daftar_wawancara += '<tr class="nohover-color">';
            html_data_daftar_wawancara += '<td colspan="4" style="text-align: center;">-- Data tidak ditemukan --</td>';
            html_data_daftar_wawancara += '</tr>';
          }

          $("#data_list_daftar_wawancara").html(html_data_daftar_wawancara);

          preload(false);
        } else {
          alert(jdata.msg);
        }
        preload(false);
      },
      complete: function(){
        preload(false);
      },
      error: function(){
        alert("Terjadi kesalahan, coba beberapa saat lagi!");
        preload(false);
      }
    });  

  }

  function getBLTH(val){
    if(val == '-'){
      val = null;
    }
    
    tglNaskahFormat = new Date(val);
    var dd = String(tglNaskahFormat.getDate()).padStart(2, '0');
    var mm = String(tglNaskahFormat.getMonth() + 1).padStart(2, '0');
    var yyyy = tglNaskahFormat.getFullYear();

    tglNaskahFormat = mm + '-' + yyyy;
		return val == null ? '-' : tglNaskahFormat;
	}

  function getTanggal(val){
    if(val == '-'){
      val = null;
    }

    tglNaskahFormat = new Date(val);
    var dd = String(tglNaskahFormat.getDate()).padStart(2, '0');
    var mm = String(tglNaskahFormat.getMonth() + 1).padStart(2, '0');
    var yyyy = tglNaskahFormat.getFullYear();

    tglNaskahFormat = dd + '-' + mm + '-' + yyyy;
		return val == null ? '-' : tglNaskahFormat;
	}

  function getPercent(val){

    percent = new Number(val);
    percent = percent*100
    percent = percent + '%';
		return val == null ? '-' : percent;
	}

  function currencyIDR(amount){
		var formatter = new Intl.NumberFormat('en-US', {
			style: 'currency',
			currency: 'IDR',
		});
		var hasil = formatter.format(amount);
		var res = hasil.replace("IDR", "Rp");
		return amount == null ? '<center>-</center>' : res;
	}
</script>	

<div id="info_masa_iur_bulan_keps">
    <fieldset><legend><b><i><font color="#009999">Informasi Masa Iur dan Bulan Kepesertaan :</font></i></b></legend>
        <table width="96%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
          <thead id="tblmasaiurjkp">
            <tr>
                <th colspan="9"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
            </tr>
            <tr>
                <th colspan="5"></th>	
                <th colspan="4" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Iuran</th>
            </tr>	
            <tr>
                <th colspan="5"></th>	
                <th colspan="4"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>
            </tr>	
            <tr>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>No. Referensi</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>NPP</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Div</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>BLTH</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Tanggal Bayar</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Rekomposisi JKK</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Rekomposisi JKM</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Subsidi Pemerintah</th>
                <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Total</th>		
            </tr>
            <tr>
        	    <th colspan="9"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
            </tr>   
          </thead>  
          <tbody id="data_list"></tbody>
            <tr>
                <th colspan="9"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
            </tr>																																	 							
        </table>

        <div id="div_page" style="padding-bottom: 26px; padding-left: 10px; padding-right: 10px;">
							<div style="padding-top: 8px;">
								<div style="float: left;">
									<span style="vertical-align: middle;">Halaman</span>
									<a href="javascript:void(0)" title="First Page" style="vertical-align: middle; padding-left: 2px; padding-right: 2px;" onclick="filter(-2)"><<</a>
									<a href="javascript:void(0)" title="Previous Page" style="vertical-align: middle; padding-left: 2px; padding-right: 2px;" onclick="filter(-1)">Prev</a>
									<input type="text" value="1" id="page" name="page" style="width: 50px;  vertical-align: middle; text-align: center;" onkeypress="return isNumber(event)" onblur="filter()"/>
									<a href="javascript:void(0)" title="Next Page" style="vertical-align: middle; padding-left: 2px; padding-right: 2px;" onclick="filter(1)">Next</a>
									<a href="javascript:void(0)" title="Last Page" style="vertical-align: middle; padding-left: 2px; padding-right: 2px;" onclick="filter(2)">>></a>
									<span style="vertical-align: middle;" id="span_info_halaman">dari 1 halaman</span>
									<input type="hidden" id="pages">
								</div>
								<div style="float: right">
									<input type="text" style="visibility:hidden; width: 2px;">
									<input type="hidden" id="hdn_total_records">
									<span style="vertical-align: middle;" id="span_info_item">Menampilkan item ke 0 sampai dengan 0 dari 0 items</span>
									<select name="page_item" id="page_item" style="width: 50px;" onchange="filter()">
										<option value="10">10</option>
										<option value="15">15</option>
										<option value="20">20</option>
										<option value="25">25</option>
										<option value="50">50</option>
									</select>								
							</div>
						</div>
						
        <br><br>
        <br><br>

        <div class="form-row_kiri">
          <label style="width:230px;"><b><i>Keterangan:</i></b></label>	    				
        </div>																																																	
        <div class="clear"></div>

        <div class="form-row_kiri">
          <label style="width:230px;">Jumlah Bulan Iur JKP</label>		 	    				
          <label>: <span id="jumlah_bulan"></span></label>		 	    				
        </div>																																																	
        <div class="clear"></div>

        <div class="form-row_kiri">
          <label style="width:230px;">Terdapat Enam Bulan Upah Berturut-turut</label>		 	    				
          <label>: <?=$ls_jkpflag_enam_bulan_berturut;?></label>
     		</div>																																																	
        <div class="clear"></div>

  </fieldset>
  <br>
</div>

<div id="profile_title">
    <hr style="border:0; border-top:3px double #8c8c8c;"/>
    <b>Profile Aktivitas TK</b>
    <hr style="border:0; border-top:3px double #8c8c8c;"/>
    <br>
</div>

<div id="fieldset_daftar_lamaran">
    <fieldset style="width:98%;"><legend><b><i><font color="#009999">Daftar Lamaran</font></i></b></legend>
        <table id="tblrincian1" width="95%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
            <thead>
                <tr>							 	
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nama Perusahaan</th>
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tanggal Lamaran</th>
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Posisi</th>
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Status Lamaran</th>
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Status Interview</th>
                </tr>
                <tr>
                    <th colspan="10"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
                </tr>
            </thead>
            <tbody id="data_list_daftar_lamaran"></tbody>
            <tr><td colspan="10"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td></tr>															
        </table>
    </fieldset>
    <br> 
</div>

<div id="fieldset_daftar_wawancara">
    <fieldset style="width:98%;"><legend><b><i><font color="#009999">Daftar Wawancara</font></i></b></legend>
        <table id="tblrincian1" width="95%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
            <thead>
                <tr>							 	
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nama Perusahaan</th>
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tanggal Wawancara</th>
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Posisi</th>
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Hasil Wawancara</th>
                </tr>
                <tr>
                    <th colspan="10"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
                </tr>
            </thead>
            <tbody id="data_list_daftar_wawancara"></tbody>
            <tr><td colspan="10"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td></tr>															
        </table>
    </fieldset>
    <br> 
</div>

<div id="fieldset_daftar_pelatihan_kerja">
    <fieldset style="width:98%;"><legend><b><i><font color="#009999">Daftar Pelatihan Kerja</font></i></b></legend>
        <table id="tblrincian1" width="95%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
            <thead>
                <tr>								
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nama BLK</th>
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tipe BLK</th>
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Jenis Pelatihan</th>
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Materi</th>
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tanggal Awal</th>
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tanggal Akhir</th>
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Absensi</th>
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Status</th>
                </tr>
                <tr>
                    <th colspan="10"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
                </tr>
            </thead>
            <tbody id="data_list_daftar_pelatihan_kerja"></tbody>
            <tr><td colspan="10"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td></tr>															
        </table>        
    </fieldset>	
    <br>
</div>

<div id="fieldset_daftar_konseling">
    <fieldset style="width:98%;" ><legend><b><i><font color="#009999">Daftar Counseling</font></i></b></legend>
        <table id="tblrincian1" width="95%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
            <thead>
                <tr>					
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tanggal Counseling</th>
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Rekomendasi Aktivitas</th>
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nama Counselor</th>
                </tr>
                <tr>
                    <th colspan="10"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
                <tr>
            </thead>
            <tbody id="data_list_daftar_konseling"></tbody>
            <tr><td colspan="10"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td></tr>															
        </table>
        
    </fieldset>
    <br>	
</div>


<div id="informasi_manfaat">
    <fieldset style="width:98%;"><legend><b><i><font color="#009999">Informasi Manfaat</font></i></b></legend>
        <table width="95%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
            <thead id="tblmasaiurjkp">
                <tr>
                    <th colspan="9"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
                </tr>
                <tr>
                    <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Bulan ke-</th>
                    <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>BLTH Manfaat</th>
                    <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Upah Dilaporkan</th>
                    <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>BLTH Upah Dilaporkan</th>
                    <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Upah Terhitung</th>
                    <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>% Upah Terhitung</th>
                    <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Nilai Manfaat</th>
                    <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Status Manfaat</th>	
                    <th style='text-align:center;font: 12px Arial, Helvetica, sans-serif;'>Tanggal Bayar</th>	
                </tr>
                <tr>
                    <th colspan="9"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
                </tr>   
            </thead>  									
            <tbody id="data_list_info_manfaat"></tbody>
                <tr>
                    <th colspan="9"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
                </tr>
                <tr>																																									 							
        </table>
    </fieldset>	
    <br>
</div>