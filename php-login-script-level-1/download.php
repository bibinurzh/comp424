<?php
if(!empty($_GET['file'])){
	$fileName=basename($_GET['file']);
	$filePath='/var/www/html/php-login-script-level-1/files/'.$fileName;
	if(!empty($fileName) && file_exists($filePath)) {
		header("Cache-Control: public");
		header("Content-Description: File Transfer");
		header("Content-Disposition: attachment; filename=$fileName");
		header("Content-Type: application/zip");
		header("Content-Transfer-Encoding: binary");
		readfile($filePath);
		exit;
}
}
