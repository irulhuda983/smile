<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$dataid	      = $_POST["dataid"] . $_GET['dataid'];

$sqlJMO="SELECT *
FROM pn.pn_klaim_dokumen_tambahan a
WHERE a.kode_dokumen='D999' AND a.kode_klaim='{$dataid}'    
     AND ROWNUM = 1";
$DB->parse($sqlJMO);
$DB->execute();
if($row = $DB->nextrow())
{

header("Content-type:image/jpeg");


$url_image=$wsIpStorage.$row['PATH_URL']; 
$source_image = imagecreatefromjpeg($url_image);

$exif = @exif_read_data($url_image);
$orientation = $exif['Orientation'];

$deg = 0;
switch ($orientation) {
    case 3:
    $deg = 180;
    break;
    case 6:
    $deg = 270;
    break;
    case 8:
    $deg = 90;
    break;
}

$image = imagerotate($source_image, $deg, 0); 

// ----------BACKUP-------------
// $imageWidth=imagesx($source_image);
// $imageHeight=imagesy($source_image);

// if($imageWidth > $imageHeight){
//     $image = imagerotate($source_image,90,0);
// }else{
//     $image = $source_image;
// }

$watermarktext = "B P J S  K E T E N A G A K E R J A A N";
$watermarktext2 = "BPJS KETENAGAKERJAAN";
$watermarktext3 = "KLAIM JHT JMO";
$font = "../../assets/fonts/constan.ttf";
$fontsize = 20;
$fontsize2 = 12;
$fontsize3 = 32;
//$color = imagecolorallocate($image, 220, 220, 220);
$transparent = imagecolorallocatealpha($image, 255, 255, 255, 100);
$textangle = 0;


$width=imagesx($image);
$height=imagesy($image);
$newwidth= 600;
$newheight= 798;

$thumb = imagecreatetruecolor($newwidth, $newheight);

imagecopyresized($thumb, $image, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);


 imagettftext($thumb, $fontsize3, 0, 130, 75, $transparent, $font, $watermarktext3);   
 imagettftext($thumb, $fontsize3, 0, 130, 125, $transparent, $font, $watermarktext3);
 imagettftext($thumb, $fontsize, 0, 70, 200, $transparent, $font, $watermarktext);
 imagettftext($thumb, $fontsize2, 0, 70, 375, $transparent, $font, $watermarktext2);
 imagettftext($thumb, $fontsize2, 0, 70, 400, $transparent, $font, $watermarktext2);
 imagettftext($thumb, $fontsize2, 0, 70, 425, $transparent, $font, $watermarktext2);
 imagettftext($thumb, $fontsize2, 0, 70, 450, $transparent, $font, $watermarktext2);
 imagettftext($thumb, $fontsize2, 0, 70, 475, $transparent, $font, $watermarktext2);
 imagettftext($thumb, $fontsize2, 0, 310, 375, $transparent, $font, $watermarktext2);
 imagettftext($thumb, $fontsize2, 0, 310, 400, $transparent, $font, $watermarktext2);
 imagettftext($thumb, $fontsize2, 0, 310, 425, $transparent, $font, $watermarktext2);
 imagettftext($thumb, $fontsize2, 0, 310, 450, $transparent, $font, $watermarktext2);
 imagettftext($thumb, $fontsize2, 0, 310, 475, $transparent, $font, $watermarktext2);
 imagettftext($thumb, $fontsize, 0, 70, 600, $transparent, $font, $watermarktext);
 imagettftext($thumb, $fontsize, 0, 70, 650, $transparent, $font, $watermarktext);
 imagettftext($thumb, $fontsize, 0, 70, 700, $transparent, $font, $watermarktext);
 imagettftext($thumb, $fontsize, 0, 70, 750, $transparent, $font, $watermarktext);

//Then show it on the page
imagejpeg($thumb);
imagedestroy($thumb);
imagedestroy($image);
imagedestroy($source_image);
    
} 
else
{
    header('Content-Type: image/x-png'); //or whatever
    readfile('../../images/nopic.png');
}
?>