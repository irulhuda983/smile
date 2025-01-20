			 
			 function submitbutton(pressbutton) {
        	submitform(pressbutton);
       }

			 function submitform(pressbutton){
        	document.adminForm.task.value=pressbutton;
        	try {
        		document.adminForm.onsubmit();
        		}
        	catch(e){}
        	document.adminForm.submit();
        }
						
				function checkAll( n, fldName ) {
          if (!fldName) {
             fldName = 'cb';
          }
        	var f = document.adminForm;
        	var c = f.toggle.checked;
        	var n2 = 0;
        	for (i=0; i < n; i++) {
        		cb = eval( 'f.' + fldName + '' + i );
        		if (cb) {
        			cb.checked = c;
        			n2++;
        		}
        	}
					
					if (c) {
        		document.adminForm.boxchecked.value = n2;
        	} else {
        		document.adminForm.boxchecked.value = 0;
        	}
        }
				
				function isChecked(isitchecked){
        	if (isitchecked == true){
        		document.adminForm.boxchecked.value++;
        	}
        	else {
        		document.adminForm.boxchecked.value--;
        	}
        }
				
				function hideMainMenu()
        {
        	document.adminForm.hidemainmenu.value=1;
        }
				
				function popupViewx()
				{
          
					var choices="";
					
					var n = document.adminForm.jmlbaris.value;
					var cebox = new Array(n);
					for(var i=0; i < cebox.length; i++ ){
					   if( document.adminForm.cebox[i].checked == true){
               alert ('test = '+cebox[i]);
							 choices += document.adminForm.cebox[i].value+"\n";
						 }
          }
					
         alert(choices);


				}
				
				function popupView(pagename)
				{
				   var f = document.adminForm;
					 var xx = 0;
					 var yy = "";
           var str="";
           for (var i = 0; i < f.pilih.length;i++){					 
						 xx = window.document.getElementById('cb'+i);
						 if(xx.checked)
						 {
  						 yy = xx.value;
  						 
						 }
           }
					 //alert('test = '+pagename+'?rid='+yy); 
           NewWindow(''+pagename+'?rid='+yy+'','',900,500,1);
        }
				
				function popupPrint(pagename)
				{
           NewWindow(''+pagename+'','',900,500,1);
        }
/*
		window.oncontextmenu = function () {
		return false;
		}*/