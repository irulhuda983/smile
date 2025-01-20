<style>
.gw_selected {background-color:#FFC0FF;}
.gw_cursor_hand tr:hover {cursor:pointer important!;}
#listdata tr:hover {background-color:#e0e0FF;cursor:pointer;}
</style>
    <div id="formframe">
      <div id="formKiri" style="width:100%"><form>	 
        <fieldset><legend><b>APPROVAL DATA IKS</b></legend>										 									 
          <div class="form-row_kanan">
            <span style="margin-right:5px;">Search by:</span>
            <select id="type" name="type" style="width:150px;" >
	    		    <option value="2">Tob be Approved</option>
                <option value="3">Approved</option>              
                <option value="4">Rejected</option>              
            </select>          							       
            <input type="button" name="btncari" class="btn green" id="btncari" value=" TAMPILKAN DATA ">
					
          </div>
          <div class="clear"></div>
          
          <div id="formsplit">
            <div class="clear"></div>
              <table class="table table-striped table-bordered row-border hover responsive-table" id="mydata" cellspacing="0" width="100%">
                <thead>
                  <tr>								
                    <th scope="col" class="align-left">Action</th> 
                    <th scope="col" class="align-left">Kantor</th> 
                    <th scope="col" class="align-left">Nama Faskes</th>
                    <th scope="col" class="align-left">No IKS</th>
                    <th scope="col" class="align-left">Masa Awal IKS</th>  
                    <th scope="col" class="align-left">Masa Akhir IKS</th>  
                    <th scope="col" class="align-left">Tipe Approval</th>
                    <th scope="col" class="align-left">Status 1</th>
                    <th scope="col" class="align-left">Keterangan 1</th>
                    <th scope="col" class="align-left">Status 2</th>
                    <th scope="col" class="align-left">Keterangan 2</th>
                    <th scope="col" class="align-left">Tgl. Submit</th>
                    <th scope="col" class="align-left">Petugas Submit</th>
                  </tr>
                </thead>
                <tbody id="listdata">
                </tbody>
              </table>
            <div class="clear"></div>
          </div>																																																					
        </fieldset>
        <br>
		    <fieldset style="background: #f2f2f2;"><legend style="background: #FF0; border: 1px solid #CCC;color:#000000;">Keterangan:</legend>
          <li>Pilih Jenis Pencarian</li>	
          <li>Klik Tombol TAMPILKAN DATA untuk memulai pencarian data</li>	
          <li>Klik pada baris data untuk memilih data yang akan di <i>edit/ view/ delete</i></li>
          <li>Klik Tombol View ntuk melihat detail data Klik salah satu data pada Tabel hasil Pencarian</li>
        </fieldset>
  		</form></div>
  	</div>
<script type="text/javascript">
$(document).ready(function(){
  loadData('','','');
  $('#btncari').click(function() {
    loadData($("#type").val(),'','');
  });
});
window.refreshData=function(){
    loadData($("#type").val(),'','');
}
function loadData(par_key1,par_key2,par_key3)
{
  window.dataid='';
  preload(true);
  window.table = $('#mydata').DataTable({
    "scrollCollapse": true,
    "paging": true,
    "searching": false ,   
    "serverSide":true,
    "destroy":true,
    //"lengthMenu": [ [10, 25, 50, 100, -1], [10, 25, 50, 100, "All"] ],
    //"pageLength": 10,
    "ajax"	: {
      "url"	: "http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn2402_query.php",
      "type": "POST",	
      "dataSrc": "records",					
      "data" : function(e) { 
        e.act = "search";
        e.key1 = par_key1;
        e.key2 = par_key2;
        e.key3 = par_key3;
      },complete : function(ajaxdata){
        preload(false);
      },error: function (){
          alert("Terjadi kesalahan pada server. Silahkan dicoba beberapa saat lagi");
          preload(false);
      }
      //,success:function(ajaxdata) {console.log(ajaxdata);}
    },
    "columns": [
      { "data": "KODE" },
      { "data": "KODE_KANTOR" },
      { "data": "NAMA_FASKES" },
      { "data": "NO_IKS" },       
      { "data": "TGL_AWAL_IKS" },
      { "data": "TGL_AKHIR_IKS" },
      { "data": "TIPE_APPROVAL" },
      { "data": "STATUS_APPROVAL" },
      { "data": "ALASAN_APPROVAL" },
      { "data": "STATUS_APPROVAL1" },
      { "data": "ALASAN_APPROVAL1" },
      { "data": "TGL_SUBMIT" },
      { "data": "PETUGAS_SUBMIT" }
    ] 
    ,
    columnDefs:[
      { 
        data:'KODE_IKS',
        targets: 0,
        searchable: false,
        orderable: false,
        render: function(data, type, full, meta){
            if(type === 'display'){  
                var s_iks = data.substring(0,5);
                var s_faskes = data.substring(6);
              if(par_key1=='2'||par_key1=='')
                data = '<div style="float:left;width:80px;" ><a href="javascript:NewWindowApproval(\'http://<?=$HTTP_HOST;?>/mod_pn/form/pn2402_form_approval.php?iks='+s_iks+'&faskes='+s_faskes+'&task=Approve\',\'_formapprovaliks\',800,400,1);" ><img src="http://<?=$HTTP_HOST;?>/images/accept.png" align="absmiddle" border="0"> Approval</a></div>';
              else
                data = '<div style="float:left;width:80px;" ><a href="javascript:NewWindowApproval(\'http://<?=$HTTP_HOST;?>/mod_pn/form/pn2402_form_approval.php?iks='+s_iks+'&faskes='+s_faskes+'&task=View\',\'_formapprovaliks\',800,400,1);" ><img src="http://<?=$HTTP_HOST;?>/images/application_get.png" align="absmiddle" border="0"> View</a></div>';
            }
            
            return data;
        }
      }
    ]
  });
  setTimeout(function(){
    $('#mydata tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
          window.table.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        var rowData =window.table.row( this ).data();
        window.dataid=rowData.KODE_IKS;         
    });
  }, 1000);
}
function NewWindowApproval(mypage,myname,w,h,scroll){
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
</script>