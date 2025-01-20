<?php
/*
//error_reporting(E_ALL);
//ini_set('display_errors','On');
*/
$link = $_GET['url'];
//echo 'link : '. $link . '<br>';
$link = base64_decode($link);
//echo 'hasil : ' . $link;

//header('Location : ' . $link);
//echo '<META HTTP-EQUIV=REFRESH CONTENT="1; '.$link.'">';
/*
echo '
header("HTTP/1.1 301 Moved Permanently");
header( "Location: $link" );
';
<frameset rows="100%,*">
<frame name="framename" src='.$link.'>
</frameset>
*/

/*
<script type="text/javascript">
$(document).ready(function(e) {
  $('iframe').attr('src','.$link.');
});
</script>



*/


echo '
<script type="text/javascript" src="../../javascript/jquery.min.js"></script>
<script type="text/javascript">
$(function() {
    $(this).bind("contextmenu", function(e) {
        e.preventDefault();
    });
}); 
</script>
<script type="text/JavaScript"> 
    function killCopy(e){ return false } 
    function reEnable(){ return true } 
    document.onselectstart=new Function ("return false"); 
    if (window.sidebar)
    { 
        document.onmousedown=killCopy; 
        document.onclick=reEnable; 
    } 
</script>

<html>
<head>
<style type="text/css">
html {
overflow: hidden;
}
</style>
</head>
<body oncontextmenu=return false; onkeydown=return false; onmousedown=return false; style="overflow: hidden" > 
 <iframe width=100%  height=100% frameborder=0 border=0 cellspacing=0
         src='.$link.'></iframe>

</body>		
</html>
';

?>
