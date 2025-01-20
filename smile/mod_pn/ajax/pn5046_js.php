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
		
		function doGenPenetapanUlang()
		{
      var form = document.formreg;
      if(form.no_penetapan_induk.value==""){
        alert('No Penetapan yang akan ditetapkan ulang kosong, harap lengkapi data input...!!!');
        form.kode_kantor.focus();						     
      }else
      {
       form.btn_task.value="Gen_Penetapan_Ulang";
       form.submit();
      }		 				 
		}									

     function doSubmitPenetapanTanpaOtentikasi() {
      var form = document.formreg;
  		console.log("masuk");
      console.log('<?=$ls_kode_klaim;?>');

      form.btn_task.value="submit_penetapan_tanpa_otentikasi";
       form.submit();
      console.log('<?=$ls_kode_klaim;?>');


    }

    function doHitungManfaatJht() {
      var form = document.formreg;
      if(form.no_penetapan_induk.value==""){
        alert('No Penetapan yang akan ditetapkan ulang kosong, harap lengkapi data input...!!!');
        form.kode_kantor.focus();     
      }else
      {
        form.btn_task.value="hitung_manfaatJht";
        form.submit();
      }
    }					
  </script>			