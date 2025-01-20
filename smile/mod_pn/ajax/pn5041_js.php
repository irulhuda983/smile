<?
//--------------------- fungsi lokal javascript ------------------------------
?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/common.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<link href="../assets/easyui/themes/default/easyui.css" rel="stylesheet">
<script type="text/javascript"></script>

<script type="text/javascript" src="../../highcharts/js/jquery.min.js"></script>
<script src="../../highcharts/js/highcharts.js"></script>

<link rel="stylesheet" type="text/css" href="../../style/tabs/css/screen.css" media="screen" />
<script type="text/javascript" src="../../style/tabs/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript">
  $(document).ready(function() {
    let activetab = '<?=$ls_activetab?>';
    if (document.getElementById('t'+activetab)== undefined) {
      activetab = 1;
    }

  	$('ul#nav li a').removeClass('active'); 																			//menghilangkan class active (yang tampil)			
    // $('#t'+<?=$ls_activetab;?>).addClass("active"); 															// menambahkan class active pada link yang diklik
    $('#t'+activetab).addClass("active"); 															// menambahkan class active pada link yang diklik
    $('.tab_konten').hide(); 																											// menutup semua konten tab					
    // $('#tab'+<?=$ls_activetab;?>).fadeIn('slow'); 																//tab pertama ditampilkan								 
    $('#tab'+activetab).fadeIn('slow'); 																//tab pertama ditampilkan								 
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
	
  function doSubmit() {
  	window.open('../ajax/pn5999_otorisasi_submitpenetapan.php?sender=<?=$ls_current_form;?>','approve','width=400,height=100,top=100,left=100,scrollbars=yes')
  }
	
  function doSubmitSetuju() {
  	window.open('../ajax/pn5999_otorisasi_submitpenetapan.php?sender=<?=$ls_current_form;?>','approve','width=400,height=100,top=100,left=100,scrollbars=yes')
  }
	
  function doSubmitPenetapanTanpaOtentikasi() {
    var form = document.adminForm;
		
    form.btn_task.value="submit_penetapan_tanpa_otentikasi";
    form.submit();
  }	

  function doSubmitAjuJKK1() {
    var form = document.adminForm;
    if(form.jkk1_kode_jenis_kasus.value=="KS01" && form.tapjkk1_kode_akibat_diderita.value==""){
    	alert('Untuk jenis kasus Kecelakaan Kerja maka Akibat yang Diderita harus diisi, lengkapi data input...!!!');
    	form.tapjkk1_kode_akibat_diderita.focus();  
    }else if(form.tapjkk1_kode_tempat_perawatan.value=="TR01" && form.tapjkk1_kode_ppk.value==""){
    	alert('Untuk Tempat Perawatan Faskes maka nama Faskes harus diisi, lengkapi data input...!!!');
    	form.tapjkk1_kode_ppk.focus(); 
    }else if(form.tapjkk1_kode_tempat_perawatan.value!="TR01" && form.tapjkk1_nama_faskes_reimburse.value==""){
      alert('Untuk Tempat Perawatan selain Faskes maka nama RS/Puskesmas/Klini Reimburse harus diisi, lengkapi data input...!!!');
      form.tapjkk1_nama_faskes_reimburse.focus(); 
		}else if(form.kode_segmen.value!="TKI" && form.tapjkk1_kode_tipe_upah.value==""){
      alert('Tipe Upah tidak boleh kosong, lengkapi data input...!!!');
      form.tapjkk1_kode_tipe_upah.focus();								 
    }// penambahan mandatory nilai upah untuk menghindari upah JKK 0, 06042022
    else if(form.kode_segmen.value!="TKI" && form.tapjkk1_nom_upah_terakhir.value=="0.00"){
        alert('Nilai Upah tidak boleh 0, lengkapi data input...!!!');
        form.tapjkk1_nom_upah_terakhir.focus();
    }//Penambahan mandatory, jika jenis kasusnya adalah kecelakaan kerja KS01
    else if(form.jkk1_kode_jenis_kasus.value=="KS01" && form.tapjkk1_kode_tindakan_bahaya.value ==""){
      alert('Kode Tindakan Bahaya tidak boleh kosong, lengkapi data input...!!!');
      form.tapjkk1_kode_tindakan_bahaya.focus();                 
    }else if(form.jkk1_kode_jenis_kasus.value=="KS01" && form.tapjkk1_kode_kondisi_bahaya.value ==""){
      alert('Kondisi Bahaya tidak boleh kosong, lengkapi data input...!!!');
      form.tapjkk1_kode_kondisi_bahaya.focus();                 
    }else if(form.jkk1_kode_jenis_kasus.value=="KS01" && form.tapjkk1_kode_corak.value ==""){
      alert('Kode Corak tidak boleh kosong, lengkapi data input...!!!');
      form.tapjkk1_kode_corak.focus();                 
    }else if(form.jkk1_kode_jenis_kasus.value=="KS01" && form.tapjkk1_kode_sumber_cedera.value ==""){
      alert('Kode Sumber Cedera tidak boleh kosong, lengkapi data input...!!!');
      form.tapjkk1_kode_sumber_cedera.focus();                 
    }else if(form.jkk1_kode_jenis_kasus.value=="KS01" && form.tapjkk1_kode_bagian_sakit.value ==""){
      alert('Kode Bagian Sakit tidak boleh kosong, lengkapi data input...!!!');
      form.tapjkk1_kode_bagian_sakit.focus();
    }//Penambahan mandatory, jika jenis kasusnya adalah kecelakaan kerja KS02
    else if(form.jkk1_kode_jenis_kasus.value=="KS02" && form.tapjkk1_kode_lama_bekerja.value ==""){
      alert('Lama Bekerja tidak boleh kosong, lengkapi data input...!!!');
      form.tapjkk1_kode_lama_bekerja.focus();
    }else if(form.jkk1_kode_jenis_kasus.value=="KS02" && form.tapjkk1_kode_penyakit_timbul.value ==""){
      alert('Penyakit yang Timbul tidak boleh kosong, lengkapi data input...!!!');
      form.tapjkk1_kode_penyakit_timbul.focus();		                 
    }else
    {
      form.btn_task.value="submit_ajujkk1_tanpa_otentikasi";
      form.submit();
    }
  }	
			
  function fl_js_simpandataAjuJKK1()
  {
      var form = document.adminForm;
      if(form.jkk1_kode_jenis_kasus.value=="KS01" && form.tapjkk1_kode_akibat_diderita.value==""){
        alert('Untuk jenis kasus Kecelakaan Kerja maka Akibat yang Diderita harus diisi, lengkapi data input...!!!');
        form.tapjkk1_kode_akibat_diderita.focus();  
			}else if(form.tapjkk1_kode_tempat_perawatan.value=="TR01" && form.tapjkk1_kode_ppk.value==""){
        alert('Untuk Tempat Perawatan Faskes maka nama Faskes harus diisi, lengkapi data input...!!!');
        form.tapjkk1_kode_ppk.focus(); 
			}else if(form.tapjkk1_kode_tempat_perawatan.value!="TR01" && form.tapjkk1_nama_faskes_reimburse.value==""){
        alert('Untuk Tempat Perawatan selain Faskes maka nama RS/Puskesmas/Klini Reimburse harus diisi, lengkapi data input...!!!');
        form.tapjkk1_nama_faskes_reimburse.focus(); 
			}else if(form.kode_segmen.value!="TKI" && form.tapjkk1_kode_tipe_upah.value==""){
        alert('Tipe Upah tidak boleh kosong, lengkapi data input...!!!');
        form.tapjkk1_kode_tipe_upah.focus();								 
      }// penambahan mandatory nilai upah untuk menghindari upah JKK 0, 06042022
      else if(form.kode_segmen.value!="TKI" && form.tapjkk1_nom_upah_terakhir.value=="0.00"){
        alert('Nilai Upah tidak boleh 0, lengkapi data input...!!!');
        form.tapjkk1_nom_upah_terakhir.focus();
      }//Penambahan mandatory, jika jenis kasusnya adalah kecelakaan kerja KS01
      else if(form.jkk1_kode_jenis_kasus.value=="KS01" && form.tapjkk1_kode_tindakan_bahaya.value ==""){
        alert('Kode Tindakan Bahaya tidak boleh kosong, lengkapi data input...!!!');
        form.tapjkk1_kode_tindakan_bahaya.focus();                 
      }else if(form.jkk1_kode_jenis_kasus.value=="KS01" && form.tapjkk1_kode_kondisi_bahaya.value ==""){
        alert('Kondisi Bahaya tidak boleh kosong, lengkapi data input...!!!');
        form.tapjkk1_kode_kondisi_bahaya.focus();                 
      }else if(form.jkk1_kode_jenis_kasus.value=="KS01" && form.tapjkk1_kode_corak.value ==""){
        alert('Kode Corak tidak boleh kosong, lengkapi data input...!!!');
        form.tapjkk1_kode_corak.focus();                 
      }else if(form.jkk1_kode_jenis_kasus.value=="KS01" && form.tapjkk1_kode_sumber_cedera.value ==""){
        alert('Kode Sumber Cedera tidak boleh kosong, lengkapi data input...!!!');
        form.tapjkk1_kode_sumber_cedera.focus();                 
      }else if(form.jkk1_kode_jenis_kasus.value=="KS01" && form.tapjkk1_kode_bagian_sakit.value ==""){
        alert('Kode Bagian Sakit tidak boleh kosong, lengkapi data input...!!!');
        form.tapjkk1_kode_bagian_sakit.focus();
			}//Penambahan mandatory, jika jenis kasusnya adalah kecelakaan kerja KS02
			else if(form.jkk1_kode_jenis_kasus.value=="KS02" && form.tapjkk1_kode_lama_bekerja.value ==""){
        alert('Lama Bekerja tidak boleh kosong, lengkapi data input...!!!');
        form.tapjkk1_kode_lama_bekerja.focus();
			}else if(form.jkk1_kode_jenis_kasus.value=="KS02" && form.tapjkk1_kode_penyakit_timbul.value ==""){
        alert('Penyakit yang Timbul tidak boleh kosong, lengkapi data input...!!!');
        form.tapjkk1_kode_penyakit_timbul.focus();		                 
      }else
      {
       form.btn_task.value="simpan_data_ajujkk1";
       form.submit();
      }		 				 
  }							
</script>

<script type="text/javascript">
function refreshRootForm() 
{																						
  <?php	
  if($ls_root_form!=''){			
  	echo "window.location.replace('../form/$ls_root_form?mid=$ls_mid&rg_kategori=$ls_rg_kategori');";		
  }
  ?>	
}
</script>

<?
//--------------------- end fungsi lokal javascript ----------------------------
?>