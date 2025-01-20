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
			document.getElementById('keyword2d').value = '';	
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
