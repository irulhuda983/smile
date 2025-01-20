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
	
  function doSubmitTanpaOtentikasi() {
    var form = document.adminForm;
		
    form.btn_task.value="submit_approval_tanpa_otentikasi";
    form.submit();
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