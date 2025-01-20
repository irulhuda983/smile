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
  <input type="button" name="btncari" class="btn green" id="monit_btncari" value="Tambah">
</div>
<div class="clear"></div>
<?php }?>
<div id="formsplit">
  <div class="clear"></div>
  <table class="table table-striped table-bordered row-border hover responsive-table" id="mydata_monitoring" cellspacing="0" width="100%">
  <thead>
  </thead>
  <tbody id="listdata_monitoring">
  </tbody>
  </table>
  <div class="clear"></div>
</div>
<?php /*****LOCAL JAVASCRIPT INFORMASI DASAR*************************************/?>
<script type="text/javascript">
var rtw_evaluation='';
function loadDataMonitoring()
{
  preload(true);
  window.table = $('#mydata_monitoring').DataTable({
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
            e.TYPE           = 'querymonitoring';
            e.kode_rtw  = '<?=$ls_kode_rtw;?>'; 
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
            "data"      : "NO_URUT",
            "className" : "dt-body-center dt-head-center"   },
            <?php if(!$ro_data && $global_readonly==''){?>
          { "title"     : "#",
            "data"      : "KODE_RTW_KLAIM",
            "width"     : "1%",
            "className" : "dt-body-center dt-head-center",
            "render"    : function(d,t,r){
                return "<div style=\"width:110px;\"><a href=\"javascript:NewWindow('pn5032_form_monitoring_input.php?dataid="+r['KODE_RTW_KLAIM']+"&task=Edit&parenttask=<?=$task;?>&nourut="+r['NO_URUT']+"','_per_info',900,300,1);\"><img src=\"../../images/edit.gif\" /> Edit</a> &nbsp; | &nbsp; <a href=\"javascript:deleteMonitoring('"+r['KODE_RTW_KLAIM']+"','"+r['NO_URUT']+"');\"><img src=\"../../images/minus.png\" /> Hapus</a></div>" ;
            }
          },
        <?php }?>
          { "title"     : "Tgl Kunjungan",
            "data"      : "TGL_KUNJUNGAN",
            "className" : "dt-body-center dt-head-center"  },
          { "title"     : "Disablitas Fisik Fungsi",
            "data"      : "DIS_FISIK_FUNGSI",
            "className" : "dt-body-center dt-head-center"  },
          { "title"     : "Disabilitas Fisik Anatomis",
            "data"      : "DIS_FISIK_ANATOMIS",
            "className" : "dt-body-left dt-head-center"  },
          { "title"     : "Gangguan Psikis",
            "data"      : "GANGGUAN_PSIKIS",
            "className" : "dt-body-center dt-head-center"  },
          { "title"     : "Tindakan Operasi",
            "data"      : "TIN_MEDIS_OPERATIF",
            "className" : "dt-body-center dt-head-center"  },
          { "title"     : "Tindakan Non Operasi",
            "data"      : "TIN_MEDIS_NONOPERATIF",
            "className" : "dt-body-center dt-head-center"  },
          { "title"     : "Rehab Medis",
            "data"      : "REHAB_MEDIS",
            "className" : "dt-body-center dt-head-center"  },
          { "title"     : "Rehab Vocational",
            "data"      : "REHAB_VOCATIONAL",
            "className" : "dt-body-left dt-head-center"  },
          { "title"     : "Rehab Mental Psikis",
            "data"      : "REHAB_MENTALPSIKIS",
            "className" : "dt-body-center dt-head-center"  },
          { "title"     : "Diagnosa Awal",
            "data"      : "DIAGNOSA_AWAL",
            "className" : "dt-body-center dt-head-center"  },
          { "title"     : "Diagnosa Akhir",
            "data"      : "DIAGNOSA_AKHIR",
            "className" : "dt-body-center dt-head-center"  },
          { "title"     : "Tenaga Medis",
            "data"      : "TENAGA_MEDIS_FASKES",
            "className" : "dt-body-center dt-head-center"  },
          { "title"     : "Keterangan",
            "data"      : "KETERANGAN",
            "className" : "dt-body-center dt-head-center"  },
        ]
      });
}	
window.ReloadDataMonitoring=function(){
  loadDataMonitoring();
}
$(document).ready(function(){
   <?php if($noform==5){?>
    loadDataMonitoring();
   <?php }?>
    $('#monit_btncari').click(function() {
      NewWindow('pn5032_form_monitoring_input.php?dataid=<?=$ls_kode_rtw;?>&task=New&parenttask=<?=$task;?>','_per_info',900,300,1);
    });
});
function deleteMonitoring(par_kode,par_no)
{
    if(confirm('Hapus data Monitoring?'))
    {
        deleteData('../ajax/pn5032_action_monitoring.php','delMonitoring','delMonitoring',par_kode,par_no);
    }
}
</script> 
<?php /*****end LOCAL JAVASCRIPT LOCAL JAVASCRIPT INFORMASI DASAR*********************************/?>