<?
  if($task=="edit" || $task=="view")
  {
			   // for ($i=0; $i<count($cebox); $i++) { 
			   for ($i=0; $i<ExtendedFunction::count($cebox); $i++) { 
				${"s".$cebox[$i]} = $_GET["s".$cebox[$i]];
				if(${"s".$cebox[$i]}=="") ${"s".$cebox[$i]} = $_POST["s".$cebox[$i]];
          		if (${"s".$cebox[$i]} !="")
          		{
  						   $editid  = ${"s".$cebox[$i]};
						   	 $editid1 = ${"t".$cebox[$i]}; /*update by Budi - 14/03/2008, utk primary key lebih dari 1*/
						   	 $editid2 = ${"u".$cebox[$i]};
						   	 $editid3 = ${"v".$cebox[$i]};								 
  						}
  			 }
	}		
	//$pagepopprint = $filename."?task=print&editid=".$editid;
	$pagepopprint = $filename."?task=print&editid=$editid&editid1=$editid1&editid2=$editid2&editid3=$editid3";
	
  // role vs crud
	if(isset($_GET['mid']) && ($_GET['mid'] <> $_SESSION['idact']))
	{
		unset($_SESSION['idact']);
		$_SESSION['idact'] = $_GET['mid'];
	}
	
	$sql = "select kode_fungsi, kode_menu, tambah c,ubah u,hapus d, 1 r from sijstk.sc_fungsi_menu ".
			 	 "where kode_fungsi='".$_SESSION['regrole']."' ".
			 	 "and kode_menu='".$_SESSION['idact']."'";

	$DB->parse($sql);
	$DB->execute();
	$row = $DB->nextrow();

	$mnu_view     = "<div style=\"float:left;\"><div class=icon>".
									"<a href=\"javascript:if (document.adminForm.boxchecked.value == 0){ alert('Please select an item from the list to view'); } else {hideMainMenu(); submitbutton('view', '');}\">".
									"<img src=\"http://$HTTP_HOST/images/application_get.png\" align=\"absmiddle\" border=\"0\"> View".
									"</a></div></div>";
	
	$mnu_addnew   = "<div style=\"float:left;\"><div class=icon><a id='btn_new' href=\"javascript:hideMainMenu();submitbutton('new');\">".
									"<img src=\"http://$HTTP_HOST/images/app_form_add.png\" align=\"absmiddle\" border=\"0\"> New</a></div></div>";
	
	$mnu_edit			= "<div style=\"float:left;\"><div class=icon><a id='btn_edit'  href=\"javascript:if (document.adminForm.boxchecked.value == 0){ alert('Please select an item from the list to edit'); } else {hideMainMenu(); submitbutton('edit', '');}\">".
									"<img src=\"http://$HTTP_HOST/images/app_form_edit.png\" align=\"absmiddle\" border=\"0\"> Edit</a></div></div>";
	
	$mnu_delete		= "<div style=\"float:left;\"><div class=icon><a id='btn_delete' href=\"javascript:if (document.adminForm.boxchecked.value == 0){ alert('Please make a selection from the list to delete'); } else if (confirm('Are you sure you want to delete selected items? ')){ submitbutton('delete');}\">".
									"<img src=\"http://$HTTP_HOST/images/app_form_delete.png\" align=\"absmiddle\" border=\"0\"> Delete</a></div></div>";

	$mnu_publish	= "<div style=\"float:left;\"><div class=icon><a href=\"javascript:if (document.adminForm.boxchecked.value == 0){ alert('Please make a selection from the list to publish'); } else {submitbutton('publish', '');}\">".
  								"<img src=\"http://$HTTP_HOST/images/monitor_add.png\"  alt=\"Publish\" name=\"publish\" title=\"Publish\" align=\"absmiddle\" border=\"0\" /> Publish</a></div></div>";
	
	$mnu_unpublish= "<div style=\"float:left;\"><div class=icon><a href=\"javascript:if (document.adminForm.boxchecked.value == 0){ alert('Please make a selection from the list to unpublish'); } else {submitbutton('unpublish', '');}\">".
				  				"<img src=\"http://$HTTP_HOST/images/monitor_delete.png\"  alt=\"Unpublish\" name=\"unpublish\" title=\"Unpublish\" align=\"absmiddle\" border=\"0\" />	Unpublish</a></div></div>";
	
	if ($row["C"]) { 
		$showbtn_addnew 	 	 = $mnu_addnew;
		//$showbtn_published   = $mnu_publish;
		//$showbtn_unpublished = $mnu_unpublish;
	}
	
	if ($row["R"]) { 
	  $showbtn_view		= $mnu_view;
	}
	
	if ($row["U"]) { 
	  $showbtn_edit 		= $mnu_edit;
	}
	
	if ($row["D"]) { 
	  $showbtn_delete		= $mnu_delete;
	}
	?>

		<div id="actmenu">
				<? 
				if($hidemainmenu==0)
				{?>
				<div id="actbutton">
				<?
					echo $showbtn_view;
					if(isset($ispublished)){
      		echo $showbtn_published;
      		echo $showbtn_unpublished;
					}
					echo $showbtn_edit;
					echo $showbtn_delete;
					echo $showbtn_addnew;
				} 
				else 
				{
				
				  if($gs_formtype=="wysiwyg")
					{
					?>
					  <div id="actbutton">
					  <? 
  					if($task=="new") 
  					{ 
  					?>
  					<!--<input type="submit" name="tambahbaru" class="tomboltop" value="SAVE" >-->
						<div style="float:left;"><div class="icon">
						<input type="image" name="tambahbaru" src="http://<?=$HTTP_HOST;?>/images/save-as.gif" align="absmiddle"  value="SAVE" > Save
						</div></div>
  					<? 
  					} 
  					else 
  					{ 
  					?>
						<!--<input type="submit" name="updatedata" class="tomboltop" value="SAVE" >-->
						<div style="float:left;"><div class="icon">
						<input type="image" name="updatedata" src="http://<?=$HTTP_HOST;?>/images/save-as.gif" align="absmiddle"  value="SAVE" > Save
						</div></div>
  					<? 
  				  }
  					?>
  					<!--<input type="button" value="CLOSE" class="tomboltop" onclick="javascript:submitbutton('cancel');" >-->
						<div style="float:left;"><div class="icon">
						<input type="image" name="updatedata" onclick="javascript:submitbutton('cancel');" src="http://<?=$HTTP_HOST;?>/images/file_cancel.gif" align="absmiddle"  value="CLOSE" > Close
						</div></div>
						
						</div>					
					<?
					}
					else
					{
					?>
					<div id="actbutton">
					<?
    					if($task=="view"){
							?>
							<div style="float:left;"><div class="icon">
							<a href="javascript:popupPrint('<?=$pagepopprint;?>');">
							<img src="http://<?=$HTTP_HOST;?>/images/printer.png" align="absmiddle" border="0"> Print</a>
							</div></div>
							<?
							}
							else
							{
      					if($task=="new") 
      					{ 
      					?>
								<div style="float:left;"><div class="icon">
      					<a id='btn_save' href="javascript:hideMainMenu();submitbuttonEdit('save');"><img src="http://<?=$HTTP_HOST;?>/images/save-as.gif" align="absmiddle" border="0"> Save</a>
      					</div></div>
								<? 
      					} 
      					else 
      					{ 
      					?>
								<div style="float:left;"><div class="icon">
      					<a id='btn_save' href="javascript:hideMainMenu();submitbuttonEdit('saveupdate');"><img src="http://<?=$HTTP_HOST;?>/images/save-as.gif" align="absmiddle" border="0"> Save</a>
      				  </div></div>
								<? 
      				  }
    					 }
							
    					?>
							<div style="float:left;"><div class="icon">
    					<a id='btn_close' href="javascript:submitbutton('cancel');"><img src="http://<?=$HTTP_HOST;?>/images/file_cancel.gif" align="absmiddle" border="0"> Close</a> 
    				 	</div></div>
						 <?
    			
					 }
					 }
  				?>
			</div>
		</div>		
			 
	
