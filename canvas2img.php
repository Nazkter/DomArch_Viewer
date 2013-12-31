<?php

$folder= "downloads/".rand(1, 999999);
mkdir($folder, 0755);
for($index=0;$index<count($_POST);$index++){
    $canv = explode(",", $_POST['canv'.$index]);
    $base64 = $canv[1];
    $bin = base64_decode($base64);
    file_put_contents($folder."/".$index.".png", $bin);
}

$zip = new ZipArchive();  
if ($zip->open($folder."/architectures.zip", ZIPARCHIVE::CREATE)==TRUE) {  
   dir2zip($folder, $zip);
   $zip -> close;  
}
$zipName=$folder."/architectures.zip";
download_file($zipName, "architectures.zip"); 

function dir2zip($dir, $zip) {  
   if (is_dir($dir)){   
      foreach (scandir($dir) as $item) {   
         if ($item == '.' || $item == '..') continue;   
         dir2zip($dir . "/" . $item, $zip);  
      }  
   }else{  
      $zip->addFile($dir);  
   }  
}
function download_file($archivo, $downloadfilename = null) {

    if (file_exists($archivo)) {
        $downloadfilename = $downloadfilename !== null ? $downloadfilename : basename($archivo);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: public");
        header("Content-Description: File Transfer");
        header("Content-type: application/octet-stream");
        header("Content-Disposition: attachment; filename=\"".$downloadfilename."\"");
        header("Content-Transfer-Encoding: binary");
        header('Content-Length: ' . filesize($archivo));
        ob_end_flush();
        @readfile($archivo);
        exit;
    }else{
         header('Location:'.$archivo);
    }

}
?>  