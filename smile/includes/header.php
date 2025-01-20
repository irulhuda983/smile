<?
$mainid = $_GET['mid']=="" ? $_POST['mid'] : $_GET['mid'];
/*$sql = "select name from menu where id='".$mainid."'";
$res = mysql_query($sql);
$row = mysql_fetch_array($res);
$menuname = $row["name"];
*/
$gs_style = $_SESSION['ses_style']=='green' ? "style_green.css" : "style.css";
$menuname = $menuname=="" ? $pagetitle : $menuname;
$pagetitle = "SIJSTK &raquo; ".$menuname."";
$pagemode = isset($pagemode) ? $pagemode : $pagemodex;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
  <title><?=$pagetitle;?></title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<meta name="Publisher" content="jrobalian.com" />
	<meta name="Copyright" content="2008" />
	<meta name="Author" content="I Kade Budiarta" />
	<meta name="Description" content="Aplikasi SiInvest Online" />
  <meta name="KEYWORDS" content="siinvest, jamsostek, deposito, keuangan" />
  <link rel="stylesheet" href="http://<?=$HTTP_HOST;?>/style/<?=$gs_style;?>" media="screen" type="text/css" />
</head>
<? if($pagetype=="home") { ?>
<body id="index" class="front" onload="document.flogin.username.focus()">
<? }else{ ?>
<body id="index" class="front">
<form name="adminForm" id="adminForm" action="<?=$PHP_SELF;?>" method="post" enctype="multipart/form-data">
<? } ?>
	<div id="topnav">
		<div>
		    <ul>
				<li>Style : <a href="?chstyle=black">Black</a> | <a href="?chstyle=green">Green</a></li>
			</ul>
		</div>
	</div>
	
	<div id="pagewidth">
	
		<div id="header">
		  <h1><a href="/" title=""><span>SMILE</span></a></h1>
  			<div id="container_headertext">
  			SMILE
				<a href="#">Read More</a>
				</div>
        <div id="logokanan">
				<div class="customlogo">
				<!--
                <input type="text" name="q" size="24" class="searchField" value=""/> 
                <input type="button" name="bsubmit" value="search" />
								-->
        </div>	
			</div>
		    
    </div>
		
		<div id="main">
			<? 
			if($pagetype=="home")
			{
			?>
			<!--
			<div id="banner">                                                                                          
            <a href="#"><img src="http://<?=$HTTP_HOST;?>/images/banner_thebest.jpg" /></a>
            <a href="#"><img src="http://<?=$HTTP_HOST;?>/images/banner_photo_library.jpg" border="0" style="display: none;" /></a>
            <a href="#"><img src="http://<?=$HTTP_HOST;?>/images/banner_photo_gallery.jpg" border="0" style="display: none;" /></a>   
            <a href="#"><img src="http://<?=$HTTP_HOST;?>/images/banner_photography.jpg" border="0" style="display: none;" /></a>   
            <a href="#"><img src="http://<?=$HTTP_HOST;?>/images/banner_photo_workshop.jpg" border="0" style="display: none;" /></a>   
            <a href="#"><img src="http://<?=$HTTP_HOST;?>/images/banner_magazine.jpg" border="0" style="display: none;" /></a>   
      </div>
			-->
			<? 
			} else {
			?>
			<div class="menutitle" id="startgallery">
			  <div class="bg_<?=$pagemode;?>"><div class="titletext">
				<?
				if(isset($formname)){
				 $judulpage = $formname;
				} else {
				 $judulpage = isset($pagemode) ? "" : $menuname;
				}
				echo $judulpage;
			  ?></div></div>
			</div>
			<?
			} 
			?>	
			<div class="content">