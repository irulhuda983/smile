<?php 

/*****LOAD DATA *************************************/
/*
$ls_no_rekam					        = '';
$ls_tgl1    					        = '';
$ls_pernyataan_tk				        = '';
$ls_diagnosa					        = '';
$ls_nm_dokter					        = '';
$ls_tujuan					            = '';
$ls_keputusan					        = '';
$ls_keterangan				            = '';

if($task=="View" || $task=="Edit")
{
    $sql = 	"select * from sijstk.pn_rtw_perencanaan where kode_rtw_klaim='{$ls_kode_rtw}'";
    $DB->parse($sql);// echo $sql;
    $DB->execute();
    if($row = $DB->nextrow())
    {
        $ls_no_rekam					        = $row['NO_REKAM_MEDIS'];
        $ls_tgl1    					        = $row['TGL_PEMERIKSAAN1'];
        $ls_pernyataan_tk				        = $row['STATUS_PERNYATAAN_TK'];
        $ls_diagnosa					        = $row['DIAGNOSA'];
        $ls_nm_dokter					        = $row['NAMA_MEDIS'];
        $ls_tujuan					            = $row['TUJUAN'];
        $ls_keputusan					        = $row['STATUS_KEPUTUSAN'];
        $ls_keterangan				            = $row['KETERANGAN'];
    }
}  //   echo $sql; */
/*****end LOAD DATA*********************************/ 
?>								 					
<?php if(!$ro_data && $global_readonly==''){?>
    <div class="form-row_kanan" align="right">           							       
      <input type="button" name="btncari" class="btn green" id="eval_btncari" value="Tambah">
    </div>
    <div class="clear"></div>
<?php }?>
    <div id="formsplit">
      <div class="clear"></div>
        <table class="table table-striped table-bordered row-border hover responsive-table" id="mydata_evaluasi" cellspacing="0" width="100%">
          <thead>
          </thead>
          <tbody id="listdata_evaluasi">
          </tbody>
        </table>
      <div class="clear"></div>
    </div>
<?php /*****LOCAL JAVASCRIPT INFORMASI DASAR*************************************/?>
<script type="text/javascript">
var rtw_evaluation='';
function loadDataEvaluation()
{
  preload(true);
  window.table = $('#mydata_evaluasi').DataTable({
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
            e.TYPE           = 'queryevaluasi';
            e.search_pfilter = ''; 
            e.search_sfilter = '';
            e.keyword        = '';            
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
          { "title"     : "No",
            "data"      : "NO_URUT1",
            "className" : "dt-body-center dt-head-center"   },
            <?php if(!$ro_data && $global_readonly==''){?>
          { "title"     : "#",
            "data"      : "KODE_RTW_KLAIM",
            "width"     : "1%",
            "className" : "dt-body-center dt-head-center",
            "render"    : function(d,t,r){
                return "<div style=\"width:110px;\"><a href=\"javascript:NewWindow('pn5032_form_evaluasi_input.php?dataid="+r['KODE_RTW_KLAIM']+"&task=Edit&parenttask=<?=$task;?>&nourut="+r['NO_URUT']+"','_per_info',900,300,1);\"><img src=\"../../images/edit.gif\" /> Edit</a> &nbsp; | &nbsp; <a href=\"javascript:deleteEvaluasi('"+r['KODE_RTW_KLAIM']+"','"+r['NO_URUT']+"');\"><img src=\"../../images/minus.png\" /> Hapus</a></div>" ;
            }
          },
        <?php }?>
          { "title"     : "Tgl Diperbaharui",
            "data"      : "TGL_DIPERBAHARUI",
            "className" : "dt-body-center dt-head-center"  },
          { "title"     : "Tipe Evaluasi",
            "data"      : "NAMA_TIPE_EVALUASI",
            "className" : "dt-body-left dt-head-center"  },
          { "title"     : "Kuisioner",
            "data"      : "NAMA_KUSIONER",
            "className" : "dt-body-left dt-head-center"  },
          { "title"     : "Keterangan Jawaban",
            "data"      : "KETERANGAN_JAWABAN",
            "className" : "dt-body-left dt-head-center"  }
        ]
      });
}	
window.ReloadDataEvaluation=function(){
  loadDataEvaluation();
}
$(document).ready(function(){
   <?php if($noform==5){?>
    loadDataEvaluation();
   <?php }?>
    $('#eval_btncari').click(function() {
      NewWindow('pn5032_form_evaluasi_input.php?dataid=<?=$ls_kode_rtw;?>&task=New&parenttask=<?=$task;?>','_per_info',900,300,1);
    });
});
function deleteEvaluasi(par_kode,par_no)
{
    if(confirm('Hapus data Evaluasi?'))
    {
        deleteData('../ajax/pn5032_action_evaluasi.php','delEvaluasi','delEvaluasi',par_kode,par_no);
    }
}
</script> 
<?php /*****end LOCAL JAVASCRIPT LOCAL JAVASCRIPT INFORMASI DASAR*********************************/?>