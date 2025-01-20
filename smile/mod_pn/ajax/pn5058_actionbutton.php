	<!-- ACTION BUTTON ---------------------------------------------------------->
  <div id="actmenu">
  	<div id="actbutton">
  		<div style="float:left;">
  		<?PHP
  		if(isset($_REQUEST["task"]))
  		{ 
  		 	?>
  			<?PHP
  			if($_REQUEST["task"] == "Edit" || $_REQUEST["task"] == "New")
  			{
				if($_GET['dataid'] == "")
				{
  			 	?>
          <div style="float:left;"><div class="icon">
          	<a id="btn_save" href="javascript:void(0)"><img src="http://<?=$HTTP_HOST;?>/images/save-as.gif" align="absmiddle" border="0"> Save</a>
          </div></div>
          <?PHP
				}
        }; 
        ?>        
        <div style="float:left;"><div class="icon">
        	<a id="btn_close" href="http://<?=$HTTP_HOST;?>/mod_pn/form/pn5058.php?mid=<?=$mid;?>"><img src="http://<?=$HTTP_HOST;?>/images/file_cancel.gif" align="absmiddle" border="0"> Close</a> 
        </div></div>
        <?PHP
      } 
  		else 
  		{
        ?>
        <!--<div class="icon">
        	<a href="javascript:void(0)" id="btn_view">
        	<img src="http://<?=$HTTP_HOST;?>/images/application_get.png" align="absmiddle" border="0"> View</a></div></div>-->
        <div style="float:left;"><div class="icon">
        	<a id="btn_edit" href="javascript:void(0)" ><img src="http://<?=$HTTP_HOST;?>/images/app_form_edit.png" align="absmiddle" border="0"> Edit</a></div></div>
        <!--<div style="float:left;"><div class="icon">
        	<a id="btn_delete" href="javascript:void(0)"><img src="http://<?=$HTTP_HOST;?>/images/app_form_delete.png" align="absmiddle" border="0"> Delete</a></div></div>
        -->
				<div style="float:left;">
        <div class="icon"><a id="btn_new" href="javascript:void(0)">
        	<img src="http://<?=$HTTP_HOST;?>/images/app_form_add.png" align="absmiddle" border="0"> New</a>
  			</div></div>
        <?PHP
  		}
  		?>
  		</div>	
  	</div>
  </div>
	
	<?
	if($_REQUEST["task"] == "Edit" || $_REQUEST["task"] == "View" || $_REQUEST["task"] == "New")
  {
	  if($_REQUEST["task"] == "New")
    {
      /*----------------------------------------------------------------------*/
      /* PF A.0.1 Create Initial Value 
      ------------------------------------------------------------------------*/
      //Kantor -----------------------------------------------------------------
      $ls_kode_kantor = isset($ls_kode_kantor) ? $ls_kode_kantor : $gs_kantor_aktif;
      if($ls_kode_kantor=="")
      {
       	$ls_kode_kantor =  $gs_kantor_aktif;
      }	
      //Tgl Agenda : sysdate ---------------------------------------------------
      $sql = "select to_char(sysdate,'dd/mm/yyyy') as tgl from dual ";
      $DB->parse($sql);
      $DB->execute();
      $row = $DB->nextrow();
      $ld_tgl_klaim 	 = $row["TGL"];	
      $ld_tgl_lapor 	 = $row["TGL"];
  		$ld_tgl_kejadian = $row["TGL"];
  		$ls_status_submit_agenda = "T";			
		}else
		{
      //query data -------------------------------------------------------------
      $sql = "
				SELECT a.kode_agenda_kelayakan kode_klaim,
					  a.kode_kantor,
					  a.kode_segmen,
					  a.kode_perusahaan,
					  (SELECT nama_perusahaan
					  FROM sijstk.kn_perusahaan
					  WHERE kode_perusahaan = a.kode_perusahaan
					  ) nama_perusahaan,
					  (SELECT npp
					  FROM sijstk.kn_perusahaan
					  WHERE kode_perusahaan = a.kode_perusahaan
					  ) npp,
					  a.kode_divisi,
					  (SELECT nama_divisi
					  FROM sijstk.kn_divisi
					  WHERE kode_perusahaan = a.kode_perusahaan
					  AND kode_segmen       = a.kode_segmen
					  AND kode_divisi       =a.kode_divisi
					  ) nama_divisi,
					  a.kode_proyek,
					  (SELECT nama_proyek FROM sijstk.jn_proyek WHERE kode_proyek = a.kode_proyek
					  ) nama_proyek,
					  (SELECT no_proyek FROM sijstk.jn_proyek WHERE kode_proyek = a.kode_proyek
					  ) no_proyek,
					  a.kode_tk,
					  a.nama_tk,
					  a.kpj,
					  a.nomor_identitas,
					  a.jenis_identitas,
					  a.kode_kantor_tk,
					  SUBSTR(a.kode_tipe_klaim,1,3) jenis_klaim,
					  a.kode_tipe_klaim,
					  (SELECT nama_tipe_klaim
					  FROM sijstk.pn_kode_tipe_klaim
					  WHERE kode_tipe_klaim = a.kode_tipe_klaim
					  ) nama_tipe_klaim,
					  a.kode_sebab_klaim,
					  (SELECT nama_sebab_klaim
					  FROM sijstk.pn_kode_sebab_klaim
					  WHERE kode_sebab_klaim = a.kode_sebab_klaim
					  ) nama_sebab_klaim,
					  (SELECT keyword
					  FROM sijstk.pn_kode_sebab_klaim
					  WHERE kode_sebab_klaim = a.kode_sebab_klaim
					  ) keyword_sebab_klaim,
					  (SELECT NVL(flag_meninggal,'T')
					  FROM sijstk.pn_kode_sebab_klaim
					  WHERE kode_sebab_klaim = a.kode_sebab_klaim
					  ) flag_meninggal,
					  (SELECT NVL(flag_agenda_12,'T')
					  FROM sijstk.pn_kode_sebab_klaim
					  WHERE kode_sebab_klaim = a.kode_sebab_klaim
					  ) flag_agenda_12,
					  TO_CHAR(a.tgl_kelayakan,'dd/mm/yyyy') tgl_klaim,
					  TO_CHAR(a.tgl_lapor,'dd/mm/yyyy') tgl_lapor,
					  a.kanal_pelayanan,
					  a.keterangan,
					  a.status_cek_kelayakan,
					  a.status_kelayakan,
					  a.ket_kelayakan,
					  case
						when nvl(a.status_cek_kelayakan,'T') = 'Y' and nvl(a.status_kelayakan,'T') = 'Y' then 'LAYAK'
						when nvl(a.status_cek_kelayakan,'T') = 'Y' and nvl(a.status_kelayakan,'T') = 'T' then 'TIDAK LAYAK'
						ELSE
							'BELUM CEK KELAYAKAN'
					end keterangan_status_kelayakan
					FROM pn.pn_agenda_klaim_kelayakan a
					WHERE a.kode_agenda_kelayakan = '".$_GET['dataid']."' ";

      //echo $sql;
      $DB->parse($sql);
      $DB->execute();
      $row = $DB->nextrow();
      $ls_kode_klaim 						 		= $row['KODE_KLAIM'];		
      $ls_kode_kantor 							= $row['KODE_KANTOR'];
      $ls_kode_segmen 							= $row['KODE_SEGMEN'];
      $ls_kode_perusahaan 					= $row['KODE_PERUSAHAAN'];
      $ls_nama_perusahaan						= $row['NAMA_PERUSAHAAN'];
      $ls_npp 											= $row['NPP'];
      $ls_kode_divisi 							= $row['KODE_DIVISI'];
      $ls_nama_divisi 							= $row['NAMA_DIVISI'];
      $ls_kode_proyek 							= $row['KODE_PROYEK'];
      $ls_nama_proyek  							= $row['NAMA_PROYEK'];
			$ls_no_proyek  								= $row['NO_PROYEK'];
      $ls_kode_tk 									= $row['KODE_TK'];
      $ls_kpj 											= $row['KPJ'];
      $ls_nama_tk 									= $row['NAMA_TK'];
      $ls_nomor_identitas 					= $row['NOMOR_IDENTITAS'];
      $ls_jenis_identitas 					= $row['JENIS_IDENTITAS'];
      $ls_kode_kantor_tk 						= $row['KODE_KANTOR_TK'];
      $ls_kode_tipe_klaim 					= $row['KODE_TIPE_KLAIM'];
      $ls_nama_tipe_klaim						= $row['NAMA_TIPE_KLAIM'];
      $ls_kode_sebab_klaim 					= $row['KODE_SEBAB_KLAIM'];
      $ls_nama_sebab_klaim 					= $row['NAMA_SEBAB_KLAIM'];
			$ls_keyword_sebab_klaim				= $row['KEYWORD_SEBAB_KLAIM'];
  		$ls_jenis_klaim 							= $row['JENIS_KLAIM'];
      $ld_tgl_klaim 								= $row['TGL_KLAIM'];
      $ld_tgl_lapor 								= $row['TGL_LAPOR'];
		$ld_tgl_kejadian							= $row['TGL_KEJADIAN'];
		$ls_status_kepesertaan 				= $row['STATUS_KEPESERTAAN'];
		$ls_status_cek_kelayakan 					= $row['STATUS_CEK_KELAYAKAN'];
		$ls_status_kelayakan 					= $row['STATUS_KELAYAKAN'];
		$ls_keterangan_status_kelayakan 					= $row['KETERANGAN_STATUS_KELAYAKAN'];
		$ls_ket_kelayakan 					= $row['KET_KELAYAKAN'];
	}
  }
	//-- end ACTION BUTTON ------------------------------------------------------
	?>	
	
	<?
	//SUBMIT BUTTON -------------------------------------------------------------
  if($btn_task=="cek_kelayakan")
  {
    //cek kelayakan ----------------------------------------------------------
    $qry = "BEGIN PN.P_PN_PN5058.X_CEK_AGENDA_KELAYAKAN('$ls_kode_klaim','$username',:p_sukses,:p_mess);END;";		
	//echo $qry;
    $proc = $DB->parse($qry);				
    oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
    oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ls_sukses = $p_sukses;
		$ls_mess = $p_mess;	
  	
 	  $msg = $ls_mess;
		$task = "view";   		
    
    echo "<script language=\"JavaScript\" type=\"text/javascript\">";
    echo "window.location.replace('?&task=View&mid=$mid&kode_klaim=$ls_kode_klaim&dataid=$ls_kode_klaim&msg=$msg');";
		echo "alert('$msg')";
    echo "</script>";		
  }
		
	//end SUBMIT BUTTON ----------------------------------------------------------
	?>
	