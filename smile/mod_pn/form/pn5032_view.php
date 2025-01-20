<style>
.gw_selected {background-color:#FFC0FF;}
.gw_cursor_hand tr:hover {cursor:pointer important!;}
#listdata tr:hover {background-color:#e0e0FF;cursor:pointer;}
</style>
    <div id="formframe">
      <div id="formKiri" style="width:1000px"><form id="formreg" name="formreg">	 
      <input type="hidden" id="formregact" name="formregact" value="delegate" />
        <fieldset><legend><b>Implementasi JKK RTW</b></legend>								 									 
          <div class="form-row_kanan">
            <span style="margin-right:2px;">Search by:</span>
            <select id="pfilter" name="pfilter" >
              <option value="AGENDADIPROSES">Agenda atau Dalam Proses Implementasi</option>    
              <option value="AGENDA">Agenda</option>    
              <option value="DIPROSES">Dalam Diproses Implementasi</option>
              <option value="SELESAI">Selesai</option>
              <option value="PUTUS">Putus</option>
              <option value="BATAL">Batal</option>
              <option value="SEMUA">Semua Status</option>               
            </select>
            <select id="sfilter" name="sfilter" >
              <option value="KODE_RTW_KLAIM">No Agenda</option>
              <option value="NPP">NPP</option>
              <option value="NOMOR_IDENTITAS">NIK</option>
              <option value="NAMA_TK">Nama TK</option>
              <option value="KETERANGAN">Keterangan</option>              
            </select>
            <input  type="text" name="keyword" id="keyword" style="width:150px;" placeholder="Keyword">            							       
            <input type="button" name="btncari" class="btn green" id="btncari" value=" TAMPILKAN DATA ">
					
          </div>
          <div class="clear"></div>
          
          <div id="formsplit">
            <div class="clear"></div>
              <table class="table table-striped table-bordered row-border hover responsive-table" id="mydata" cellspacing="0" width="100%">
                <thead>
                </thead>
                <tbody id="listdata">
                </tbody>
              </table>
            <div class="clear"></div>
          </div>																																																					
        </fieldset>
        <?php if($_SESSION['regrole']=='1' || $_SESSION['regrole']=='6') {  ?>
        <fieldset style="background: #F2F2F2;" id="fieldsetdelegasi"><legend style="background: #FF0; border: 1px solid #CCC;color:#000000;" >Pendelegasian RTW:</legend>
          Kantor tujuan : 
          <input type="text" id="kode_dst_kantor" name="kode_dst_kantor" maxlength="40"  value="" style="width:40px;background-color:#ffff99;" readonly /><input type="text" id="nama_dst_kantor" name="nama_dst_kantor"  value="" style="width:200px;background-color:#e9e9e9;" readonly/>
          <a href="#" onclick="NewWindow('../ajax/pn5031_lov_dstkantor.php?fname=formreg&ffocus=kode_dst_kantor&f1=kode_dst_kantor&f2=nama_dst_kantor','_lov_kantordst',800,600,1)" >   
                <img src="../../images/help.png" alt="Cari Kantor Tujuan" border="0" align="absmiddle" ></a> <br /> <br />
                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; <input type="button" name="btndelegasi" class="btn green" id="btndelegasi" value=" DELEGASIKAN ">
        </fieldset>
        <?php }?>
        <br>
		    <fieldset style="background: #F2F2F2;"><legend style="background: #FF0; border: 1px solid #CCC;color:#000000;">Keterangan:</legend>
          <li>Pilih Jenis Pencarian</li>	
          <li>Minimal Agenda II JKK, Mengikuti program RTW dan masih dalam pengobatan</li>
          <li>Klik Tombol TAMPILKAN DATA untuk memulai pencarian data</li>	
          <li>Klik Tombol View ntuk melihat detail data Klik salah satu data pada Tabel hasil Pencarian</li>
        </fieldset>
  		</form></div>
  	</div>
<script type="text/javascript">
$(document).ready(function(){
 //lov_subData('getStatus','getStatus','T',0);
  //loadData($('#fsstatus').val(),$('#type').val(),$('#keyword').val());
  loadData();
  //$('#mydata tbody').on('click', 'tr', function () {
        //var a= confirm(this.find());
  //});
  $('#btncari').click(function() {
    loadData();
  });
  <?php if($_SESSION['regrole']=='1' || $_SESSION['regrole']=='6') {  ?>
  $('#btndelegasi').click(function() {
    delegasikan();
  });
  <?php }?>
});
var dataid=0;
var datastatus='PROSES';
function set_dataid(p_id,p_status)
{
  dataid=p_id;
  datastatus=p_status;
}
function loadData()
{
  <?php if($_SESSION['regrole']=='1' || $_SESSION['regrole']=='6') {  ?>
  if($('#pfilter').val()=='AGENDADIPROSES'||$('#pfilter').val()=='AGENDA'||$('#pfilter').val()=='DIPROSES')
    $("#fieldsetdelegasi").show();
  else
    $("#fieldsetdelegasi").hide();
  <?php }?>
  preload(true);
  window.table = $('#mydata').DataTable({
    "scrollCollapse"  : true,
    "paging"          : true,
    'sPaginationType' : 'full_numbers',
    scrollY           : true,
    scrollX           : true,
    "processing"      : true,
    "serverSide"      : true,
    "search"          : {
          "regex": true
    },
    select            : true,
    "searching"       : false,
    "destroy"         : true,
        "ajax"        : {
          "url"  : "../ajax/pn5032_query.php",
          "type" : "POST",
          "dataSrc": "data",					
          "data" : function(e) { 
            e.TYPE           = 'query';
            e.search_pfilter = $('#pfilter').val();
            e.search_sfilter = $('#sfilter').val();
            e.keyword        = $('#keyword').val();            
          },complete : function(data){
            preload(false);
            console.log(data);
          },error: function (data){
              console.log(data);
              alertError("Terjadi kesalahan pada server. Silahkan dicoba beberapa saat lagi");
              preload(false);
            }
        },
        "columns": [
          { "title"     : "#",
            "data"      : "NO_URUT",
            "width"     : "1%",
            "className" : "dt-body-center dt-head-center",
            "render"    : function(d,t,r){
                return '<input type="checkbox" name="kd[]" value="'+r['KODE_RTW_KLAIM']+'" onClick="set_dataid(\''+r['KODE_RTW_KLAIM']+'\',\''+r['STATUS_RTW_KLAIM']+'\')">';
            }
          },
          { "title"     : "No Agenda",
            "data"      : "KODE_RTW_KLAIM",
            "width"     : "10%",
            "className" : "dt-body-center dt-head-center"  },
          { "title"     : "Kode Klaim",
            "data"      : "KODE_KLAIM",
            "width"     : "10%",
            "className" : "dt-body-center dt-head-center"  },
          { "title"     : "Tanggal",
            "data"      : "TGL_REKAM",
            "width"     : "10%",
            "className" : "dt-body-left dt-head-center"  },
            { "title"     : "STATUS",
            "data"      : "NAMA_STATUS",
            "width"     : "10%",
            "className" : "dt-body-center dt-head-center"  },
          { "title"     : "Nama TK",
            "data"      : "NAMA_TK",
            "width"     : "10%",
            "className" : "dt-body-center dt-head-center"  },
          { "title"     : "Nama Perusahaan",
            "data"      : "NAMA_PERUSAHAAN",
            "width"     : "10%",
            "className" : "dt-body-center dt-head-center"  },
          { "title"     : "Tujuan",
            "data"      : "NAMA_KANTOR",
            "width"     : "10%",
            "className" : "dt-body-center dt-head-center"  },
          { "title"     : "SUBMIT STATUS",
            "data"      : "STATUS",
            "width"     : "10%",
            "className" : "dt-body-center dt-head-center"  }
          
        ]
      });
}	
function lov_subData(par_callFunc,par_ajaxFunc,par_ajaxKey1,par_ajaxKey2)
{
    var ajax_Key1 = (par_ajaxKey1 == undefined) ? "":par_ajaxKey1;
    var ajax_Key2 = (par_ajaxKey2 == undefined) ? "":par_ajaxKey2;
    $.ajax({
        type: 'GET',
        url: "http://<?=$HTTP_HOST;?>/mod_tc/ajax/tc5001_lov.php?"+Math.random(),
        data: {f:par_ajaxFunc,key1:ajax_Key1,key2:ajax_Key2},
        success: function(ajaxdata){
            if(par_callFunc=='getStatus') getStatus(ajaxdata);								
        }
    });
}
<?php if($_SESSION['regrole']=='1' || $_SESSION['regrole']=='6') {  ?>
function check_checkedid()
{ 
  var ilength=0;
  var checks = document.getElementsByName("kd[]");
  for (var i=0; i < checks.length; i++) {
    if(checks[i].checked == true)
      ilength++;
  }
  return ilength;
}
function delegasikan()
{
  var irow=check_checkedid();
  if($("#kode_dst_kantor").val()=="")
    alert('Silahkan isikan kantor tujuan!');
  else if(irow==0)
    alert('Silahkan untuk memilih agenda RTW yang akan didelegasikan');
  else if(confirm('Yakin mendelegasikan '+irow+' agenda RTW?'))
  {
    preload(true);
    $.ajax({
        type: 'POST',
        url: "../ajax/pn5032_action.php?"+Math.random(),
        data: $("#formreg").serialize(),
        success: function(ajaxdata){ 
            preload(false); 
            console.log(ajaxdata);	
            jdata = JSON.parse(ajaxdata);
            if(jdata.ret == '0')
            {						 		 						 						 
                window.parent.Ext.notify.msg('Pendelegasian RTW berhasil, session dilanjutkan...','');
                loadData();
                $("#kode_dst_kantor").val('');
                $("#nama_dst_kantor").val('');
            }else 
            {
               alert('Pendelegasian RTW gagal, ulangi beberapa saat lagi!');
            }
        }
    });
  }
}
<?php }?>
</script>