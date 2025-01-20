<?php
include __DIR__."/../../mod_sc/sc_session.php";
include __DIR__."/../../includes/conf_global.php";
require __DIR__."/../../includes/fungsi.php";
include __DIR__."/../../includes/class_database.php"; 
//$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$gs_pagetitle = isset($pagetitle) ? $pagetitle : $gs_pagetitle;
//Set Form Log
// Add by Robby Mar 15, 2017 22:57
//===========================================
$KD_FORM = explode('/',$_SERVER['REQUEST_URI']);
$KD_FORM = explode('.',$KD_FORM[4]);
$KD_FORM = strtoupper($KD_FORM[0]);
$ipfwd = getenv('HTTP_X_FORWARDED_FOR');
if ($ipfwd=='') $ipfwd = getenv('REMOTE_ADDR');
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName); //connect to db
$sql = "insert into sijstk.sc_audit_akses_form ( ".
       "	kode_user, tgl_akses, kode_form, ".
       "	inisial_fungsi, kantor_fungsi, ip_server, ". 
       "	nama_app_server, nama_host, ip_client, ".
       "	tgl_rekam, petugas_rekam) ".
			 "values ( ".
			 "	'".$_SESSION["USER"]."', sysdate, '".$KD_FORM."', ".
			 "	'".$_SESSION['regrole']."', '".$_SESSION['kdkantorrole']."', '".$_SERVER['HTTP_HOST']."', ".
			 "	'".$_SERVER['HTTP_HOST']."', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['REMOTE_ADDR']."' ".
			 "	sysdate, 'SIJSTK' ".
			 ")";			 					
$DB->parse($sql);
$DB->execute();
//========================
//ENDSet Form Log
$rootpath = (!empty($_SERVER['HTTPS'])) ? "https://".$HTTP_HOST : "http://".$HTTP_HOST;
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?=$gs_pagetitle;?></title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
    <link href="<?=$rootpath;?>/assets/fonts/font-awesome-4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?=$rootpath;?>/javascript/vue/vue-components/vue-component.css" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
    <style>
      input
      {
          font-size:11px;
      }
    </style>
  </head>
  <body class="appcontent">
    <script src="<?=$rootpath;?>/javascript/vue/vue.min.js"></script>
    <script src="<?=$rootpath;?>/javascript/moment.min.js"></script>
    <script src="<?=$rootpath;?>/javascript/vue/axios/axios.min.js"></script>
    <script>
      function loadScriptFromSrc(srcFrom){
        let scripts = Array
            .from(document.querySelectorAll('script'))
            .map(scr => scr.src);

        if (!scripts.includes(srcFrom)) {
          var tag = document.createElement('script');
          tag.src = srcFrom;
          document.getElementsByTagName('body')[0].appendChild(tag);
        }
      }
      function loadStyleFromHref(srcHref){
        let links = Array
            .from(document.querySelectorAll('link'))
            .map(scr => scr.href);

        if (!links.includes(srcHref)) {
          var tag = document.createElement('link');
          tag.href = srcHref;
          tag.rel="stylesheet";
          tag.type="text/css"; 
          document.getElementsByTagName('body')[0].appendChild(tag);
        }
      }
    </script>