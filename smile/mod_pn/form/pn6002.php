<?php
//$pagetype = "report";
$gs_pagetitle = "PN6002 - Tindak Lanjut Koreksi Data Pelayanan";

require_once "../../includes/header_app_nosql.php";
//require_once "../../includes/header_app.php";	
require_once '../../includes/conf_global.php';
?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
<script language="javascript">

function NewWindow(mypage,myname,w,h,scroll){
  var winl = (screen.width-w)/2;
  var wint = (screen.height-h)/2;
  var settings  ='height='+h+',';
	  settings +='width='+w+',';
	  settings +='top='+wint+',';
	  settings +='left='+winl+',';
	  settings +='scrollbars='+scroll+',';
	  settings +='resizable=1';
	  settings +='location=0';
	  settings +='menubar=0';
  win=window.open(mypage,myname,settings);
  if(parseInt(navigator.appVersion) >= 4){win.window.focus();}
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

</script>

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

.dataTables_length {
	margin-bottom: 10px;	
}
.dataTables_wrapper{
	position: relative;
	clear: both;
    zoom: 1;
    background: #ebebeb;
    padding-top: 10px;
    padding-bottom: 5px;
    border: 1px solid #dddddd;
}
#mylistdata_wrapper thead tr th {
	padding-top: 2px;
    padding-bottom: 2px;
}

#tindak_lanjut td {
    font-size: 10px;
    /*text-align: center;*/
    border-right: 0px solid rgb(221, 221, 221);
    border-bottom: 1px solid rgb(221, 221, 221);
	padding-top: 2px;
    padding-bottom: 2px;
}

#tindak_lanjut {
	/*text-align: center;*/
}

</style>
<div id="actmenu">
		<h3 style="margin-top: 7px;margin-left: 10px; color:#FFFFFF"><?=$gs_pagetitle;?></h3>			
</div>
<div id="formframe" style="width: 1200px">
	<div id="dispError1" style="color:red;line-height: 19px;text-align: center;margin-top: 5px;display: none;"></div>
    <div id="formKiri" style="width: 1000px">
        <form name="formreg" id="formreg" role="form" method="post" enctype="multipart/form-data">
        <fieldset><legend>DAFTAR AGENDA KOREKSI DATA</legend>
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
            Tgl Agenda &nbsp;
            <input type="text" id="tglawaldisplay" name="tglawaldisplay" value="<?=$ld_tglawaldisplay;?>" size="10" onblur="convert_date(tglawaldisplay)" >  
            <input id="btn_tgl" type="image" align="top" onclick="return showCalendar('tglawaldisplay', 'dd-mm-y');" src="../../images/calendar.gif" />&nbsp; s/d &nbsp;
            <input type="text" id="tglakhirdisplay" name="tglakhirdisplay" value="<?=$ld_tglakhirdisplay;?>" size="10" onblur="convert_date(tglakhirdisplay)" >
            <input id="btn_tgl2" type="image" align="top" onclick="return showCalendar('tglakhirdisplay', 'dd-mm-y');" src="../../images/calendar.gif" />&nbsp;&nbsp;                      
        </div>                    
        <div class="form-row_kanan">
            <span style="margin-right:5px;">Search by:</span>
            <select id="search_pilihan" name="search_pilihan" style="width:150px;" >
            	<option value="A.KODE_AGENDA">No Agenda</option>
				<option value="NAMA_JENIS_AGENDA_DETIL">Perihal</option>
				<option value="KODE_KLAIM">Kode Klaim</option>
              	<option value="KPJ">No. Peserta</option>  

            </select>
            <input  type="text" name="keyword" id="keyword" style="width:150px;" placeholder="Keyword">            						       
            <input type="button" name="btncari" class="btn green" id="btncari" value=" TAMPILKAN DATA ">
		</div>
        <div class="clear"></div>
            <table class="table responsive-table" id="tindak_lanjut" cellspacing="0" width="1200px">
            <thead>
	            <!-- HEADER DATA TABLE -->
            </thead>
            <tbody id="listdata">
            	<!-- BODY DATA TABLE -->
            </tbody>
            </table>
        <div class="clear"></div>
        <div class="clear"></div>
        </fieldset>
        <?PHP if(isset($_REQUEST['kd_agenda'])){
       		//include('../ajax/kn5041_agenda_view.php');  
    	}
        ?>
        </form>
        <br>
        <fieldset style="background: #F2F2F2; width: 1200px"><legend style="background: #FF0; border: 1px solid #CCC;">KETERANGAN</legend>
        	<li>Klik tindak lanjut untuk proses agenda permintaan pelayanan</li>
		</fieldset>
        <br>
         </div>   
        
		<?php
		if (isset($msg))		
		{
		?>
		<fieldset>
			<?=$ls_error==1 ? "<legend><font color=#ff0000>Error</font></legend>" : "<legend><font color=#007bb7>Message</font></legend>";?>
			<?=$ls_error==1 ? "<font color=#ff0000>".$msg."</font>" : "<font color=#007bb7>".$msg."</font>";?>
		</fieldset>		
		<?php
		}
		?>
								
	</div>
</div>	
<script type="text/javascript">
	$(document).ready(function(){
		preload(true);
		window.role = "<?=$_SESSION['regrole']?>";
		//console.log(window.role);
		<?if($_REQUEST["path"] == ''){?>
		loadTable();
		<? } ?>
		
		$("#show").hide();
		$('#btnprint').hide();
		setTimeout(function(){
			$('#loading').hide();
			$('#loading-mask').hide();
		}, 250);
		$('#close,#batal').click(function() {
			$("#showoff").hide(function(){
				$("#show").hide();
			});		
		});
		$('#carinpp').focus();
		$('input[type=text]').keyup(function () {
			this.value = this.value.toUpperCase();
		});

		$('#btnreload').click(function() {
            location.reload();
        });
		
		$("#btncari").click(function() {
			preload(true);
			loadTable();
        });
		
		
		$("#btnsubmit").click(function() {
			preload(true);
			var confirmation = confirm("Apakah anda yakin untuk melakukan proses submit?");
			if(confirmation==true){
				$.ajax({
				    url: 'http://<?=$HTTP_HOST;?>/mod_keps/ajax/keps_inventaris_piutang_action.php?'+Math.random(),
				    type:'POST',
				    data: { type:'submit'}, 
				    success:function(data) {
				      console.log(data);
				      preload(false);
				    }, error: function(errorThrown) { 
				      console.log(errorThrown);
				    }  
				});
			}else{
				alert("Proses submit dibatalkan");
			}
            

		});
	});
	
	function loadTable(){
			// console.log("npp:"+npp+"pemb:"+pembina+"blth:"+blth);
			// alert('TABLE');
        	window.table_agenda_pp = $('#tindak_lanjut').DataTable({
	            "scrollCollapse"	: true,
				"paging"			: true,
				'sPaginationType'	: 'full_numbers',
				scrollY				: true,
		        scrollX				: true,
		  		"processing"		: true,
				"serverSide"		: true,
				"search"			: {
				    "regex": true
				},
				select				: true,
				"searching"			: true,
				"destroy"			: true,
		        "ajax"				: {
		        	"url"  : "http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6002_action.php",
		        	"type" : "POST",
		        	"data" : function(e) { 
		        		e.type    		 	= 'query';
		        		e.search_pilihan 	= $('#search_pilihan').val();
                		e.keyword        	= $('#keyword').val();
		                e.tgl_awal_display  = $('#tglawaldisplay').val();
		                e.tgl_akhir_display = $('#tglakhirdisplay').val();          		
		        	},complete : function(data){
		        		preload(false);
		        		console.log(data);
		        	},error: function (){
		            	alertError("Terjadi kesalahan pada server. Silahkan dicoba beberapa saat lagi");
		            	preload(false);
		            }
		        },
		        "columns": [
		            { "title"		: "No",
		              "data"		: "NO_URUT",
		              "width" 		: "1%",
		              "className"	: "dt-body-center dt-head-center"  },
		        	{ "title" 		: "Action",
		        	  "data" 		: "URL_PATH",
		        	  "width" 		: "8%",
		              "className" 	: "dt-body-center dt-head-center",
		              "render"		: function(d,t,r){
		              				 var nama_link = '';
		              				 var gb_icon = ''
		              				 if(r['STATUS_AGENDA'] =='TERBUKA'){
		              				 	nama_link = 'Tindak Lanjut';
		              				 	var gb_icon = '<img src="../../images/user_go.png" border="0" alt="Tindak Lanjut Agenda" align="absmiddle">';
		              				 	// return '<a href="#" onclick="window.location.replace(\'?kd_agenda='+r['KODE_AGENDA']+'&path='+d+'\')">&nbsp;<img src="../../images/user_go.png" border="0" alt="Tindak Lanjut Agenda" align="absmiddle"><u><font color="#4682B4">'+nama_link+'</font></u></a>';
		              				 }else{
		              				 	nama_link= 'Detail';
		              				 	var gb_icon = '<img src="../../images/application_view_columns.png" border="0" alt="Detail Agenda" align="absmiddle">';
		              				 	// return '<a href="#" onClick="detil_agenda(\''+r['KODE_AGENDA']+'\',\''+d+'\',\''+r['KODE_JENIS_AGENDA_DETIL']+'\',\''+r['STATUS_AGENDA']+'\')"> &nbsp;&nbsp;<img src="../../images/application_view_columns.png" border="0" alt="Detail Agenda" align="absmiddle"><u><font color="#008000">'+nama_link+'</font></u> </a>';
		              				 }

									if(r['CEK_TIDAK_BISA_BAYAR']=="2"){

										 if(r['FLAG_KCP']=="T" && r['SESSION_ROLE']=="15" && r['STATUS_AGENDA'] =='TERBUKA'){
											return '<a href="#" onClick="checkApproval();"> &nbsp;&nbsp;'+gb_icon+'<u><font color="#008000">'+nama_link+'</font></u> </a>'; 
										}else{
											return '<a href="#" onClick="detil_agenda(\''+r['KODE_AGENDA']+'\',\''+d+'\',\''+r['KODE_JENIS_AGENDA_DETIL']+'\',\''+r['STATUS_AGENDA']+'\')"> &nbsp;&nbsp;'+gb_icon+'<u><font color="#008000">'+nama_link+'</font></u> </a>'; 
										}  

									}else{
										if(r['STATUS_AGENDA'] =='TERBUKA' && r['CEK_TIDAK_BISA_BAYAR']=="0" && r['SESSION_ROLE']=="15"){
											return '<a href="#" onClick="checkTidakBisaBayar();"> &nbsp;&nbsp;'+gb_icon+'<u><font color="#008000">'+nama_link+'</font></u> </a>';
										}else{
											return '<a href="#" onClick="detil_agenda(\''+r['KODE_AGENDA']+'\',\''+d+'\',\''+r['KODE_JENIS_AGENDA_DETIL']+'\',\''+r['STATUS_AGENDA']+'\')"> &nbsp;&nbsp;'+gb_icon+'<u><font color="#008000">'+nama_link+'</font></u> </a>'; 	
										} 
									}   

									
		              				// CEK TIDAK BISA BAYAR => PERIHAL PP0207 PP0208 PP0209 APPROVAL 2X KABID KAKACAB
									 

		              				// return '<a href="#" onclick="window.location.replace(\'?kd_agenda='+r['KODE_AGENDA']+'&path='+d+'\')">&nbsp;<img src="../../images/user_go.png" border="0" alt="Tindak Lanjut Agenda" align="absmiddle"><u><font color="#009999">'+nama_link+'</font></u></a>';


		              } 
		          	},
		            { "title"		: "No Agenda",
		              "data"		: "KODE_AGENDA",
		              "width" 		: "6%",
		              "className"	: "dt-body-center dt-head-center"  },
		            // { "title"		: "Jenis Agenda",
		            //   "data"		: "NAMA_JENIS_AGENDA",
		            //   "width" 		: "15%",
		            //   "className"	: "dt-body-center dt-head-center"  },
		            { "title" 		: "Perihal",
		              "data"		: "NAMA_JENIS_AGENDA_DETIL",
		              "width" 		: "15%",
		              "className" 	: "dt-body-left dt-head-center"  },
		            { "title"		: "Kode Klaim",
		              "data"		: "KODE_KLAIM",
		              "width" 		: "8%",
		              "className"	: "dt-body-center dt-head-center"  },
		            { "title"		: "KPJ",
		              "data"		: "KPJ",
		              "width" 		: "8%",
		              "className"	: "dt-body-center dt-head-center"  },
		            { "title"		: "Nama Peserta",
		              "data"		: "NAMA_TK",
		              "width" 		: "13%",
		              "className"	: "dt-body-center dt-head-center"  },
		            { "title" 		: "Tanggal Agenda",
		              "data"		: "TGL_AGENDA",
		              "width" 		: "5%",
		              "className" 	: "dt-body-center dt-head-center"  },
		            { "title"		: "Status Agenda",
		              "data" 		: "DETIL_STATUS",
		              "width" 		: "7%",
		              "className" 	: "dt-body-center dt-head-center"
		          }
		        ]
	        });
	        // window.table_piutang.columns.adjust().draw();
    }

     function detil_agenda(kd_agenda,path,kd_perihal,st_agenda){
        window.location.replace('pn6001.php?task=Submit&kd_perihal_detil='+kd_perihal+'&path='+path+'&kd_agenda='+kd_agenda+'&st_agenda='+st_agenda+'&form=tdl');
        // window.location.replace('../ajax/kn5041_form_tindaklanjut.php?task=Edit&kd_perihal='+kd_perihal+'&path='+path+'&kd_agenda='+kd_agenda);
    }

	function checkApproval() {
 		 alert("Approval belum dilakukan oleh Pejabat sebelumnya");	
	}

	function checkTidakBisaBayar() {
 		 alert("Approval hanya dilakukan oleh Kepala Bidang Pelayanan");	
	}

    function stopRKey(evt) {
      var evt = (evt) ? evt : ((event) ? event : null);
      var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
      if ((evt.keyCode == 13) && (node.type=="text"))  {return false;}
    }
    document.onkeypress = stopRKey;

</script>
<?php
include "../../includes/footer_app_nosql.php";
?>