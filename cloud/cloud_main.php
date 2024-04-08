<?php
session_start();
if (!isset($_SESSION['id'])) {
	header("Location: ../main/index.php");
}
error_reporting(error_reporting() & ~E_NOTICE);
date_default_timezone_set("Asia/TaiPei");
$conn = new PDO('mysql:host=localhost; dbname=company', 'root', '123qwe') or die(mysqli_error($conn));
$allow_delete = true;
$allow_upload = true;
$allow_create_folder = true;
$allow_direct_link = true;
$allow_show_folders = true;

$disallowed_extensions = ['php'];
$hidden_extensions = ['php'];

$PASSWORD = '';
//uid導入
$UID = $_SESSION['id'];
//個人雲端已使用空間(經過單位轉換)
$current_usage = GetDirectorySize('../cloud/'.$_SESSION['id']);
//個人雲端已使用空間(Byte)
$current_usage_Byte = folderSize_Byte('../cloud/'.$_SESSION['id']);
//個人雲端已使用比例
$current_usage_rate = folderSizeRATE('../cloud/'.$_SESSION['id']);
$questurl = @$_GET['file'];
$questurl = substr($questurl,0,10);
setlocale(LC_ALL, 'en_US.UTF-8');

$tmp_dir = dirname($_SERVER['SCRIPT_FILENAME']);
if (DIRECTORY_SEPARATOR === '\\') $tmp_dir = str_replace('/', DIRECTORY_SEPARATOR, $tmp_dir);
$tmp = get_absolute_path($tmp_dir . '/' . @$_REQUEST['file']);
$checkuid = @$_REQUEST['file'];
$checkuid = substr($checkuid, 0, 7);
if ($_SESSION['statu'] == '管理者') $UID = '';
//錯誤訊息
if ($tmp === false)
	err(404, 'File or Directory Not Found 文件或目錄不存在');
if (substr($tmp, 0, strlen($tmp_dir)) !== $tmp_dir)
	err(403, "Forbidden 禁止訪問");
if (strpos(@$_REQUEST['file'], DIRECTORY_SEPARATOR) === 0)
	err(403, "Forbidden 禁止訪問");

//資料庫與cookie訊息
if (!$_COOKIE['_sfm_xsrf'])
	setcookie('_sfm_xsrf', bin2hex(openssl_random_pseudo_bytes(16)));
if ($_POST) {
	if ($_COOKIE['_sfm_xsrf'] !== $_POST['xsrf'] || !$_POST['xsrf'])
		err(403, "XSRF Failure XSRF錯誤");
}

//全域do動作反應區塊
$file = @$_REQUEST['file'] ?: '.';
$connectfile = @$_REQUEST['file'] ?: '.';
if (@$_GET['do'] == 'list') { //列出table
	$conn = mysqli_connect("localhost", "root", "123qwe", "company") or die(mysqli_error($conn));
	if ((is_dir($file) && $checkuid == $UID) || $_SESSION['statu'] == '管理者' || $questurl='questcloud') {
		$directory = $file;
		$result = [];
		$files = array_diff(scandir($directory), ['.', '..']);
		foreach ($files as $entry) if (!is_entry_ignored($entry, $allow_show_folders, $hidden_extensions)) {
			$i = $directory . '/' 	. $entry;
			$filedir = $i;
			$filedir = str_replace("./", "", $filedir);
			$sql_query1 = "SELECT tag1 FROM upload WHERE dir='$filedir'";
			$tag1 = mysqli_query($conn, $sql_query1);
			$row1 = mysqli_fetch_row($tag1);
			$sql_query2 = "SELECT tag2 FROM upload WHERE dir='$filedir'";
			$tag2 = mysqli_query($conn, $sql_query2);
			$row2 = mysqli_fetch_row($tag2);
			$sql_query3 = "SELECT tag3 FROM upload WHERE dir='$filedir'";
			$tag3 = mysqli_query($conn, $sql_query3);
			$row3 = mysqli_fetch_row($tag3);
			$stat = stat($i);
			$result[] = [
				'mtime' => $stat['mtime'],
				'size' => $stat['size'],
				'name' => basename($i),
				'path' => preg_replace('@^\./@', '', $i),
				'ext' => pathinfo($i, PATHINFO_EXTENSION),
				'is_dir' => is_dir($i),
				'is_deleteable' => $allow_delete && ((!is_dir($i) && is_writable($directory)) ||
					(is_dir($i) && is_writable($directory) && is_recursively_deleteable($i))),
				'tag1' => $row1,
				'tag2' => $row2,
				'tag3' => $row3,
			];
		}
	} else {
		err(412, "Not a Directory");
	}
	echo json_encode(['success' => true, 'is_writable' => is_writable($file), 'results' => $result]);
	exit;
} elseif (@$_POST['do'] == 'delete') {
	if ($allow_delete) {
		rmrf($file);
	}
	exit;
} elseif (@$_POST['do'] == 'mkdir' && $allow_create_folder) {
	$date = date('Y-m-d H:i:s');
	$folderr = $_POST['name'];
	$folderdir = $file . '/' . $folderr;
	$folderdir = str_replace('./', '', $folderdir);
	if (substr($folderdir, 0, 2) === '..' || file_exists($folderdir) != 1) {
		@mkdir($folderdir, 0777, true);
		$query = $conn->query("INSERT INTO upload (name,date,dir,type) VALUES ('$folderr','$date','$folderdir','folder')");
	}
	exit;
} elseif (@$_POST['do'] == 'upload' && $allow_upload) {
	foreach ($disallowed_extensions as $ext)
		if (preg_match(sprintf('/\.%s$/', preg_quote($ext)), $_FILES['file_data']['name']))
			err(403, "Files of this type are not allowed.");
		if (($current_usage_Byte+$_FILES['file_data']['size'])>107374182400)
			err(403, "儲存空間不足!");
	$date = date('Y-m-d H:i:s');
	$name = $_FILES['file_data']['name'];
	$size = $_FILES['file_data']['size'];
	$dir = $file;
	$type = $_FILES['file_data']['type'];
	$type = mime2ext($type);
	$filedir = $dir . '/' . $name;
	$filedir = str_replace("./", "", $filedir);
	if (file_exists($filedir)) {
		header("Refresh: 0;");
		exit;
	} else {
		$query = $conn->query("INSERT INTO upload (name,size,date,dir,type) VALUES ('$name','$size','$date','$filedir','$type')");
		$res = move_uploaded_file($_FILES['file_data']['tmp_name'], $file . '/' . $_FILES['file_data']['name']);
		header("Refresh: 0;");
		exit;
	}
} elseif (@$_POST['do'] == 'upload_folder' && $allow_upload) {
	$date = date('Y-m-d H:i:s');
	$folderdir = $file;
	$folderdir = str_replace('./', '', $folderdir);
	foreach ($disallowed_extensions as $ext)
		if (preg_match(sprintf('/\.%s$/', preg_quote($ext)), $_FILES['file_data']['name']))
			err(403, "Files of this type are not allowed.");
	if (($current_usage_Byte+$_FILES['file_data']['size'])>107374182400)
			err(403, "儲存空間不足!");
	$name = $_FILES['file_data']['name'];
	$size = $_FILES['file_data']['size'];
	$type = $_FILES['file_data']['type'];
	$type = mime2ext($type);
	$filedir = $file;
	$filedir = str_replace("./", "", $filedir);
	$folderr = substr($filedir,0,strrpos($filedir,"/"));
	if(!is_dir($folderr)){
		mkdir($folderr,0777,true);
		$query = $conn->query("INSERT INTO upload (name,size,date,dir,type) VALUES ('$name','$size','$date','$folderr','folder')");
	}
	if(!is_dir($filedir)){
		$res = move_uploaded_file($_FILES['file_data']['tmp_name'], $file);
		$query = $conn->query("INSERT INTO upload (name,size,date,dir,type) VALUES ('$name','$size','$date','$filedir','$type')");
	}
} elseif (@$_GET['do'] == 'download') {
	$filename = basename($file);
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	header('Content-Type: ' . finfo_file($finfo, $file));
	header('Content-Length: ' . filesize($file));
	header(sprintf(
		'Content-Disposition: attachment; filename=%s',
		strpos('MSIE', $_SERVER['HTTP_REFERER']) ? rawurlencode($filename) : "\"$filename\""
	));
	ob_flush();
	readfile($file);
	exit;
} elseif (isset($_GET['do']) && $_GET['do'] == 'view') {
	header('Content-Type: text/plain');
	echo file_get_contents($connectfile);
	exit;
} elseif (isset($_POST['do']) && $_POST['do'] == 'rename') {
	$oldname = $_POST['oldern'];
	$newname = $_POST['name'];
	$newname = strip_tags($newname);
	$newname = stripslashes($newname);
	$newname = preg_replace('/\n|\s|\t/', '', $newname);
	$newname = trim($newname);
	$newdir = $file;
	$newdir = str_replace($oldname, $newname, $newdir);
	rename($file, $newdir);
	echo json_encode(array('success' => true));
	$query = $conn->query("UPDATE upload SET name='$newname',dir='$newdir' WHERE dir='$file'");
	$query = $conn->query("UPDATE upload SET dir=regexP_replace(dir,'^$file','$newdir') where `dir` REGEXP '^$file.*'");
	exit;
} elseif (isset($_POST['do']) && $_POST['do'] == 'content') {
	header('Content-Type: text/plain');
	echo get_editable_content($connectfile);
	echo '<textarea id="editable" rows="20" style="height: 100%; box-sizing: border-box;width:100%">';
	echo htmlentities(file_get_contents($connectfile), ENT_QUOTES, 'utf-8');
	echo '</textarea>';
	exit;
} elseif (isset($_POST['do']) && $_POST['do'] == 'content-save') {
	file_put_contents($connectfile, html_entity_decode($_POST['content']));
	echo json_encode(array('success' => true));
	exit;
} elseif (@$_POST['do'] == 'retag') {
	$tag1 = $_POST['tag1'];
	$tag2 = $_POST['tag2'];
	$tag3 = $_POST['tag3'];
	$dir = $_POST['dir'];
	$query = $conn->query("UPDATE upload SET tag1='$tag1',tag2='$tag2',tag3='$tag3' WHERE dir='$dir'");
	exit;
} elseif (@$_POST['do'] == 'zip') {
	$filename = basename($file);
	$zip = new ZipArchive();
	$zipname = $file . ".zip";
	$zip->open($zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE);
	$files = new RecursiveIteratorIterator(
		new RecursiveDirectoryIterator(realpath($file)),
		RecursiveIteratorIterator::LEAVES_ONLY
	);
	foreach ($files as $name => $filez) {
		if (!$filez->isDir()) {
			$filePath = $filez->getRealPath();
			$relativePath = substr($filePath, strlen(realpath($file)) + 1);
			$zip->addFile($filePath, $relativePath);
		}
	}
	$zip->close();
	$date = date('Y-m-d H:i:s');
	$size = filesize($zipname);
	$name = basename($zipname);
	$tag1 = $filename . "的壓縮檔";
	$query = $conn->query("INSERT INTO upload (name,size,date,dir,type,tag1) VALUES ('$name','$size','$date','$zipname','zip','$tag1')");
	exit;
}
//檔案類型 轉換辨識區域
function mime2ext($mime)
{
	$mime_map = [
		'video/3gpp2'                                                               => '3g2',
		'video/3gp'                                                                 => '3gp',
		'video/3gpp'                                                                => '3gp',
		'application/x-compressed'                                                  => '7zip',
		'audio/x-acc'                                                               => 'aac',
		'audio/ac3'                                                                 => 'ac3',
		'application/postscript'                                                    => 'ai',
		'audio/x-aiff'                                                              => 'aif',
		'audio/aiff'                                                                => 'aif',
		'audio/x-au'                                                                => 'au',
		'video/x-msvideo'                                                           => 'avi',
		'video/msvideo'                                                             => 'avi',
		'video/avi'                                                                 => 'avi',
		'application/x-troff-msvideo'                                               => 'avi',
		'application/macbinary'                                                     => 'bin',
		'application/mac-binary'                                                    => 'bin',
		'application/x-binary'                                                      => 'bin',
		'application/x-macbinary'                                                   => 'bin',
		'image/bmp'                                                                 => 'bmp',
		'image/x-bmp'                                                               => 'bmp',
		'image/x-bitmap'                                                            => 'bmp',
		'image/x-xbitmap'                                                           => 'bmp',
		'image/x-win-bitmap'                                                        => 'bmp',
		'image/x-windows-bmp'                                                       => 'bmp',
		'image/ms-bmp'                                                              => 'bmp',
		'image/x-ms-bmp'                                                            => 'bmp',
		'application/bmp'                                                           => 'bmp',
		'application/x-bmp'                                                         => 'bmp',
		'application/x-win-bitmap'                                                  => 'bmp',
		'application/cdr'                                                           => 'cdr',
		'application/coreldraw'                                                     => 'cdr',
		'application/x-cdr'                                                         => 'cdr',
		'application/x-coreldraw'                                                   => 'cdr',
		'image/cdr'                                                                 => 'cdr',
		'image/x-cdr'                                                               => 'cdr',
		'zz-application/zz-winassoc-cdr'                                            => 'cdr',
		'application/mac-compactpro'                                                => 'cpt',
		'application/pkix-crl'                                                      => 'crl',
		'application/pkcs-crl'                                                      => 'crl',
		'application/x-x509-ca-cert'                                                => 'crt',
		'application/pkix-cert'                                                     => 'crt',
		'text/css'                                                                  => 'css',
		'text/x-comma-separated-values'                                             => 'csv',
		'text/comma-separated-values'                                               => 'csv',
		'application/vnd.msexcel'                                                   => 'csv',
		'application/x-director'                                                    => 'dcr',
		'application/vnd.openxmlformats-officedocument.wordprocessingml.document'   => 'docx',
		'application/x-dvi'                                                         => 'dvi',
		'message/rfc822'                                                            => 'eml',
		'application/x-msdownload'                                                  => 'exe',
		'video/x-f4v'                                                               => 'f4v',
		'audio/x-flac'                                                              => 'flac',
		'video/x-flv'                                                               => 'flv',
		'image/gif'                                                                 => 'gif',
		'application/gpg-keys'                                                      => 'gpg',
		'application/x-gtar'                                                        => 'gtar',
		'application/x-gzip'                                                        => 'gzip',
		'application/mac-binhex40'                                                  => 'hqx',
		'application/mac-binhex'                                                    => 'hqx',
		'application/x-binhex40'                                                    => 'hqx',
		'application/x-mac-binhex40'                                                => 'hqx',
		'text/html'                                                                 => 'html',
		'image/x-icon'                                                              => 'ico',
		'image/x-ico'                                                               => 'ico',
		'image/vnd.microsoft.icon'                                                  => 'ico',
		'text/calendar'                                                             => 'ics',
		'application/java-archive'                                                  => 'jar',
		'application/x-java-application'                                            => 'jar',
		'application/x-jar'                                                         => 'jar',
		'image/jp2'                                                                 => 'jp2',
		'video/mj2'                                                                 => 'jp2',
		'image/jpx'                                                                 => 'jp2',
		'image/jpm'                                                                 => 'jp2',
		'image/jpeg'                                                                => 'jpeg',
		'image/pjpeg'                                                               => 'jpeg',
		'application/x-javascript'                                                  => 'js',
		'application/json'                                                          => 'json',
		'text/json'                                                                 => 'json',
		'application/vnd.google-earth.kml+xml'                                      => 'kml',
		'application/vnd.google-earth.kmz'                                          => 'kmz',
		'text/x-log'                                                                => 'log',
		'audio/x-m4a'                                                               => 'm4a',
		'audio/mp4'                                                                 => 'm4a',
		'application/vnd.mpegurl'                                                   => 'm4u',
		'audio/midi'                                                                => 'mid',
		'application/vnd.mif'                                                       => 'mif',
		'video/quicktime'                                                           => 'mov',
		'video/x-sgi-movie'                                                         => 'movie',
		'audio/mpeg'                                                                => 'mp3',
		'audio/mpg'                                                                 => 'mp3',
		'audio/mpeg3'                                                               => 'mp3',
		'audio/mp3'                                                                 => 'mp3',
		'video/mp4'                                                                 => 'mp4',
		'video/mpeg'                                                                => 'mpeg',
		'application/oda'                                                           => 'oda',
		'audio/ogg'                                                                 => 'ogg',
		'video/ogg'                                                                 => 'ogg',
		'application/ogg'                                                           => 'ogg',
		'font/otf'                                                                  => 'otf',
		'application/x-pkcs10'                                                      => 'p10',
		'application/pkcs10'                                                        => 'p10',
		'application/x-pkcs12'                                                      => 'p12',
		'application/x-pkcs7-signature'                                             => 'p7a',
		'application/pkcs7-mime'                                                    => 'p7c',
		'application/x-pkcs7-mime'                                                  => 'p7c',
		'application/x-pkcs7-certreqresp'                                           => 'p7r',
		'application/pkcs7-signature'                                               => 'p7s',
		'application/pdf'                                                           => 'pdf',
		'application/octet-stream'                                                  => 'pdf',
		'application/x-x509-user-cert'                                              => 'pem',
		'application/x-pem-file'                                                    => 'pem',
		'application/pgp'                                                           => 'pgp',
		'application/x-httpd-php'                                                   => 'php',
		'application/php'                                                           => 'php',
		'application/x-php'                                                         => 'php',
		'text/php'                                                                  => 'php',
		'text/x-php'                                                                => 'php',
		'application/x-httpd-php-source'                                            => 'php',
		'image/png'                                                                 => 'png',
		'image/x-png'                                                               => 'png',
		'application/powerpoint'                                                    => 'ppt',
		'application/vnd.ms-powerpoint'                                             => 'ppt',
		'application/vnd.ms-office'                                                 => 'ppt',
		'application/msword'                                                        => 'doc',
		'application/vnd.openxmlformats-officedocument.presentationml.presentation' => 'pptx',
		'application/x-photoshop'                                                   => 'psd',
		'image/vnd.adobe.photoshop'                                                 => 'psd',
		'audio/x-realaudio'                                                         => 'ra',
		'audio/x-pn-realaudio'                                                      => 'ram',
		'application/x-rar'                                                         => 'rar',
		'application/rar'                                                           => 'rar',
		'application/x-rar-compressed'                                              => 'rar',
		'audio/x-pn-realaudio-plugin'                                               => 'rpm',
		'application/x-pkcs7'                                                       => 'rsa',
		'text/rtf'                                                                  => 'rtf',
		'text/richtext'                                                             => 'rtx',
		'video/vnd.rn-realvideo'                                                    => 'rv',
		'application/x-stuffit'                                                     => 'sit',
		'application/smil'                                                          => 'smil',
		'text/srt'                                                                  => 'srt',
		'image/svg+xml'                                                             => 'svg',
		'application/x-shockwave-flash'                                             => 'swf',
		'application/x-tar'                                                         => 'tar',
		'application/x-gzip-compressed'                                             => 'tgz',
		'image/tiff'                                                                => 'tiff',
		'font/ttf'                                                                  => 'ttf',
		'text/plain'                                                                => 'txt',
		'text/x-vcard'                                                              => 'vcf',
		'application/videolan'                                                      => 'vlc',
		'text/vtt'                                                                  => 'vtt',
		'audio/x-wav'                                                               => 'wav',
		'audio/wave'                                                                => 'wav',
		'audio/wav'                                                                 => 'wav',
		'application/wbxml'                                                         => 'wbxml',
		'video/webm'                                                                => 'webm',
		'image/webp'                                                                => 'webp',
		'audio/x-ms-wma'                                                            => 'wma',
		'application/wmlc'                                                          => 'wmlc',
		'video/x-ms-wmv'                                                            => 'wmv',
		'video/x-ms-asf'                                                            => 'wmv',
		'font/woff'                                                                 => 'woff',
		'font/woff2'                                                                => 'woff2',
		'application/xhtml+xml'                                                     => 'xhtml',
		'application/excel'                                                         => 'xl',
		'application/msexcel'                                                       => 'xls',
		'application/x-msexcel'                                                     => 'xls',
		'application/x-ms-excel'                                                    => 'xls',
		'application/x-excel'                                                       => 'xls',
		'application/x-dos_ms_excel'                                                => 'xls',
		'application/xls'                                                           => 'xls',
		'application/x-xls'                                                         => 'xls',
		'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'         => 'xlsx',
		'application/vnd.ms-excel'                                                  => 'xlsx',
		'application/xml'                                                           => 'xml',
		'text/xml'                                                                  => 'xml',
		'text/xsl'                                                                  => 'xsl',
		'application/xspf+xml'                                                      => 'xspf',
		'application/x-compress'                                                    => 'z',
		'application/x-zip'                                                         => 'zip',
		'application/zip'                                                           => 'zip',
		'application/x-zip-compressed'                                              => 'zip',
		'application/s-compressed'                                                  => 'zip',
		'multipart/x-zip'                                                           => 'zip',
		'text/x-scriptzsh'                                                          => 'zsh',
	];

	return isset($mime_map[$mime]) ? $mime_map[$mime] : false;
}
//回傳資料夾大小(Byte)
function folderSize_Byte($path)
{
    $bytestotal = 0;
    $path = realpath($path);
    if($path!==false && $path!='' && file_exists($path)){
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object){
            $bytestotal += $object->getSize();
        }
    }
    return $bytestotal;
}
//回傳資料夾比例(以100G為可使用空間計算)
function folderSizeRATE($path)
{
    $bytestotal = 0;
    $path = realpath($path);
    if($path!==false && $path!='' && file_exists($path)){
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object){
            $bytestotal += $object->getSize();
        }
    }
	$bytestotal = $bytestotal/1073741824;
    return $bytestotal;
}
//回傳資料夾大小(經過單位轉換)
function GetDirectorySize($path){
    $bytestotal = 0;
    $path = realpath($path);
    if($path!==false && $path!='' && file_exists($path)){
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object){
            $bytestotal += $object->getSize();
        }
    }
	if($bytestotal<1024){$bytestotal=$bytestotal." Bytes";}
	elseif(($bytestotal<1048576)&&($bytestotal>1023)){$bytestotal=round($bytestotal/1024, 1)." KB";}
	elseif(($bytestotal<1073741824)&&($bytestotal>1048575)){$bytestotal=round($bytestotal/1048576, 1)." MB";}
	else{$bytestotal=round($bytestotal/1073741824, 1)." GB";}
    return $bytestotal;
}
//按鈕套用其他 jquery function (table顯示檔案右側 編輯的按鈕)
function get_editable_content($file)
{
	echo '<style>body{margin-top:30px;}.always-top{position:fixed;top:5px;}</style>';
	echo '<span class="always-top">';
	echo '<button id="#save-inside" onclick="';
	echo 'window.opener.jQuery(window.opener.document).trigger(';
	echo '\'save.popup\',';
	echo "['" . htmlentities($file, ENT_QUOTES, 'utf-8') . "',";
	echo 'document.getElementById(\'editable\').value]';
	echo ');';
	echo 'window.close();">save</button><button id="#cancel-inside" onclick="window.close();">cancel</button></span>';
}
function write_to_console($data) {
 $console = $data;
 if (is_array($console))
 $console = implode(',', $console);

 echo "<script>console.log('Console: " . $console . "' );</script>";
}
//檔案是否顯示 權限設定區塊 
function is_entry_ignored($entry, $allow_show_folders, $hidden_extensions)
{
	if ($entry === basename(__FILE__)) {
		return true;
	}

	if (is_dir($entry) && !$allow_show_folders) {
		return true;
	}

	$ext = strtolower(pathinfo($entry, PATHINFO_EXTENSION));
	if (in_array($ext, $hidden_extensions)) {
		return true;
	}

	return false;
}
//刪除資料庫與本地檔案
function rmrf($dir)
{
	$conn = new PDO('mysql:host=localhost; dbname=company', 'root', '123qwe') or die(mysqli_error($conn));
	if (is_dir($dir)) {
		$files = array_diff(scandir($dir), ['.', '..']);
		foreach ($files as $file)
			rmrf("$dir/$file");
		rmdir($dir);
		$query = $conn->query("delete from upload where dir='$dir'");
	} else {
		unlink($dir);
		$query = $conn->query("delete from upload where dir='$dir'");
	}
}
//允不允許刪除
function is_recursively_deleteable($d)
{
	$stack = [$d];
	while ($dir = array_pop($stack)) {
		if (!is_readable($dir) || !is_writable($dir))
			return false;
		$files = array_diff(scandir($dir), ['.', '..']);
		foreach ($files as $file) if (is_dir($file)) {
			$stack[] = "$dir/$file";
		}
	}
	return true;
}
//獲取路徑
function get_absolute_path($path)
{
	$path = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $path);
	$parts = explode(DIRECTORY_SEPARATOR, $path);
	$absolutes = [];
	foreach ($parts as $part) {
		if ('.' == $part) continue;
		if ('..' == $part) {
			array_pop($absolutes);
		} else {
			$absolutes[] = $part;
		}
	}
	return implode(DIRECTORY_SEPARATOR, $absolutes);
}
//錯誤指令訊息
function err($code, $msg)
{
	http_response_code($code);
	echo json_encode(['error' => ['code' => intval($code), 'msg' => $msg]]);
	exit;
}
//檔案大小判斷
function asBytes($ini_v)
{
	$ini_v = trim($ini_v);
	$s = ['g' => 1 << 30, 'm' => 1 << 20, 'k' => 1 << 10];
	return intval($ini_v) * ($s[strtolower(substr($ini_v, -1))] ?: 1);
}
//上傳上限
$MAX_UPLOAD_SIZE = min(asBytes(ini_get('post_max_size')), asBytes(ini_get('upload_max_filesize')));
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<html>

<head>
	<!--html head-->
	<?php include '../php/html_head.php' ?>
	<!--即時更新需求182版本-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
	<!--雲端檔案tabe顯示設定 function-->
	<script>
		//table目錄排序功能 uid導入
		var $UID = '<?php echo $UID ?>';
		var $current_usage = '<?php echo $current_usage ?>';
		(function($) {
			$.fn.tablesorter = function() {
				var $table = this;
				this.find('th').click(function() {
					var idx = $(this).index();
					var direction = $(this).hasClass('sort_asc');
					$table.tablesortby(idx, direction);
				});
				return this;
			};
			$.fn.tablesortby = function(idx, direction) {
				var $rows = this.find('tbody tr');

				function elementToVal(a) {
					var $a_elem = $(a).find('td:nth-child(' + (idx + 1) + ')');
					var a_val = $a_elem.attr('data-sort') || $a_elem.text();
					return (a_val == parseInt(a_val) ? parseInt(a_val) : a_val);
				}
				$rows.sort(function(a, b) {
					var a_val = elementToVal(a),
						b_val = elementToVal(b);
					return (a_val > b_val ? 1 : (a_val == b_val ? 0 : -1)) * (direction ? 1 : -1);
				})
				this.find('th').removeClass('sort_asc sort_desc');
				$(this).find('thead th:nth-child(' + (idx + 1) + ')').addClass(direction ? 'sort_desc' : 'sort_asc');
				for (var i = 0; i < $rows.length; i++)
					this.append($rows[i]);
				this.settablesortmarkers();
				return this;
			}
			$.fn.retablesort = function() {
				var $e = this.find('thead th.sort_asc, thead th.sort_desc');
				if ($e.length)
					this.tablesortby($e.index(), $e.hasClass('sort_desc'));

				return this;
			}
			$.fn.settablesortmarkers = function() {
				this.find('thead th span.indicator').remove();
				this.find('thead th.sort_asc').append('<span class="indicator">&darr;<span>');
				this.find('thead th.sort_desc').append('<span class="indicator">&uarr;<span>');
				return this;
			}
		})(jQuery);
		//搜尋功能 綁定id #filterName 觸發
		$(function() {
			$('#filterName').on('keyup', function() {
				if ($('#filterName').val() == "") {
					$('table tbody tr').hide()
						.filter(":contains(' ')")
						.show();
				} else {
					$('table tbody tr').hide()
						.filter(":contains('" + ($('#filterName').val()) + "')")
						.show();
				}
			})
		})
		//table每一行整體 檔案名稱到最後的刪除按鈕
		$(function() {
			var oldname = "";
			var XSRF = (document.cookie.match('(^|; )_sfm_xsrf=([^;]*)') || 0)[2];
			var MAX_UPLOAD_SIZE = <?php echo $MAX_UPLOAD_SIZE ?>;
			var $tbody = $('#list');
			$(window).bind('hashchange', list).trigger('hashchange');
			$('#table').tablesorter();
			//刪除按鈕
			$(document).on('click', '.delete', function(event) {
				var alert = confirm('are you sure want to delete? 確定刪除?');
				if (!alert) {
					return false;
				}
				$.post("", {
					'do': 'delete',
					file: $(this).attr('data-file'),
					xsrf: XSRF
				}, function(response) {
					list();
				}, 'json');
				return false;
			});
			//不允許 編輯檔案 css class變動
			function disable_content_editable($parent) {
				$parent.find('.rename').removeClass('is-hidden');
				$parent.find('.save').removeClass('is-visible');
				$parent.find('.cancel').removeClass('is-visible');
				$parent.find('.name').attr('contentEditable', false);
			}
			//允許 編輯檔案 css class變動
			function enable_content_editable($parent) {
				$parent.find('.save').addClass('is-visible');
				$parent.find('.cancel').addClass('is-visible');
				$parent.find('.rename').addClass('is-hidden');
				$parent.find('.name').attr('contentEditable', true).focus();
			}
			//當按下class name 後再按下 13=enter 27=esc 動作 存取/取消
			$(document).on('keydown.name', '.name', function(e) {
				if (e.keyCode == 13) {
					e && e.preventDefault && e.preventDefault();
					$(this).closest('.first').find('.save button').trigger('click.save');
					return false;
				} else if (e.keyCode == 27) {
					e && e.preventDefault && e.preventDefault();
					$(this).closest('.first').find('.cancel button').trigger('click.cancel');
					return false;
				}
			});
			//點擊class rename 的按鈕 (重新命名) > rename按鈕會消失
			$(document).on('click.rename', '.rename button', function(event) {
				var $el = $(this);
				var $parent = $el.closest('.first');
				oldname = $parent.find('.name').text();
				enable_content_editable($parent);
				$("#rename").attr("style", "display:none");
				return false;
			});
			//點擊class retag 的按鈕 (標籤)
			$(document).on('click.retag', '.retag button', function(event) {
				$("#loginModal3").modal('show');
				var $el = $(this);
				var $parent = $el.closest('.sec');
				var $alltags = $parent.text();
				var arr = $alltags.split('、');
				if (arr[0] != undefined && arr[1] != undefined && arr[2] != undefined) {
					arr[2] = arr[2].replace("  修改標籤", "");
				} else if (arr[0] != undefined && arr[1] != undefined) {
					arr[1] = arr[1].replace("  修改標籤", "");
				} else if (arr[0] != undefined) {
					arr[0] = arr[0].replace("  修改標籤", "");
				}
				var $tagdir = $(this).attr('data-file');
				$('#tagtext1').val(arr[0]);
				$('#tagtext2').val(arr[1]);
				$('#tagtext3').val(arr[2]);
				//邏輯修改 --標籤編輯modal 改成按鈕觸發
				$('button[id=retagsubmit]').click(function(e) {
					$("#loginModal3").modal('hide');
					$('.modal-backdrop').remove();
					$.post("", {
						'do': 'retag',
						tag1: $('#tagtext1').val(),
						tag2: $('#tagtext2').val(),
						tag3: $('#tagtext3').val(),
						dir: $tagdir,
						xsrf: XSRF
					}, function(response) {
						list();
					}, 'json');
					return false;
				});
			});
			//點擊class save 的按鈕 (改名的存取)
			$(document).on('click.save', '.save button', function(event) {
				var $el = $(this);
				var $parent = $el.closest('.first');
				disable_content_editable($parent);
				$.post("", {
					'do': 'rename',
					file: $(this).attr('data-file'),
					oldern: oldname,
					name: $parent.find('.name').text(),
					xsrf: XSRF
				}, function(response) {
					list();
				}, 'json');
				return false;
			});
			//點擊class cancel 的按鈕 (改名的取消)
			$(document).on('click.cancel', '.cancel button', function(event) {
				var $el = $(this);
				var $parent = $el.closest('.first');
				document.execCommand('undo');
				$parent.find('.name').blur();
				disable_content_editable($parent);
				return false;
			});

			//class save.popup動作 (下載檔案 按鈕)
			jQuery(document).on('save.popup', function(e, f, response) {
				$.post("", {
					'do': 'content-save',
					file: f,
					content: response,
					xsrf: XSRF
				}, function(response) {
					var $saved = $('<span class="saved">&nbsp;|&nbsp;Saved!</span>').insertAfter($('.edit [data-file="' + f + '"]').parent()).fadeIn();
					window.setTimeout(function() {
						$saved.fadeOut();
					}, 5000);
				}, 'json');
			});
			//class zip動作 (壓縮檔案 按鈕)
			$(document).on('click', '.zip', function(data) {
				$.post("", {
					'do': 'zip',
					file: $(this).attr('data-file'),
					xsrf: XSRF
				}, function(response) {
					list();
				}, 'json');
				return false;
			});
			//class mkdir 編輯 動作 () ?
			$('#mkdir').submit(function(e) {
				var hashval = decodeURIComponent(window.location.hash.substr(1)),
					$dir = $(this).find('[name=name]');
				e.preventDefault();
				$dir.val().length && $.post('?', {
					'do': 'mkdir',
					name: $dir.val(),
					xsrf: XSRF,
					file: hashval
				}, function(data) {
					list();
				}, 'json');
				$dir.val('');
				return false;
			});
			//(檔案)上傳檔案modal 功能:拖曳上傳 大小辨別 錯誤報告
			<?php if ($allow_upload) : ?>
				$('#file_drop_target').on('dragover', function() {
					$(this).addClass('drag_over');
					return false;
				}).on('dragend', function() {
					$(this).removeClass('drag_over');
					return false;
				}).on('drop', function(e) {
					e.preventDefault();
					var files = e.originalEvent.dataTransfer.files;
					$.each(files, function(k, file) {
						uploadFile(file);
					});
					$(this).removeClass('drag_over');
				});
				$('input[name=file]').change(function(e) {
					$("#loginModal2").modal('hide');
					$('.modal-backdrop').remove();
					e.preventDefault();
					$.each(this.files, function(k, file) {
						uploadFile(file);
					});
				});


				function uploadFile(file) {
					var folder = decodeURIComponent(window.location.hash.substr(1));
					if (file.size > MAX_UPLOAD_SIZE) {
						var $error_row = renderFileSizeErrorRow(file, folder);
						$('#upload_progress').append($error_row);
						window.setTimeout(function() {
							$error_row.fadeOut();
						}, 5000);
						return false;
					}
					var $row = renderFileUploadRow(file, folder);
					$('#upload_progress').append($row);
					var fd = new FormData();
					fd.append('file_data', file);
					fd.append('file', folder);
					fd.append('xsrf', XSRF);
					fd.append('do', 'upload');
					var xhr = new XMLHttpRequest();
					xhr.open('POST', '?');
					xhr.onload = function() {
						$row.remove();
						list();
					};
					xhr.upload.onprogress = function(e) {
						if (e.lengthComputable) {
							$row.find('.progress').css('width', (e.loaded / e.total * 100 | 0) + '%');
						}
					};
					xhr.send(fd);
				}

				function renderFileUploadRow(file, folder) {
					return $row = $('<div/>')
						.append($('<span class="fileuploadname" />').text((folder ? folder + '/' : '') + file.name))
						.append($('<div class="progress_track"><div class="progress"></div></div>'))
						.append($('<span class="size" />').text(formatFileSize(file.size)))
				};

				function renderFileSizeErrorRow(file, folder) {
					return $row = $('<div class="error" />')
						.append($('<span class="fileuploadname" />').text('Error: ' + (folder ? folder + '/' : '') + file.name))
						.append($('<span/>').html(' file size - <b>' + formatFileSize(file.size) + '</b>' +
							' exceeds max upload size of <b>' + formatFileSize(MAX_UPLOAD_SIZE) + '</b>'));
				}
			<?php endif; ?>
			//(資料夾 批量)上傳檔案modal 功能:拖曳上傳 大小辨別 錯誤報告
			<?php if ($allow_upload) : ?>
				$('#file_drop_target').on('dragover', function() {
					$(this).addClass('drag_over');
					return false;
				}).on('dragend', function() {
					$(this).removeClass('drag_over');
					return false;
				}).on('drop', function(e) {
					e.preventDefault();
					var files = e.originalEvent.dataTransfer.files;
					$.each(files, function(k, file) {
						uploadFiles(file);
					});
					$(this).removeClass('drag_over');
				});
				$('input[id=files]').change(function(e) {
					$("#loginModal").modal('hide');
					$('.modal-backdrop').remove();
					e.preventDefault();
					$.each(this.files, function(k, file) {
						uploadFiles(file);
					});
				});

				function uploadFiles(file) {
					var folder = decodeURIComponent(window.location.hash.substr(1))+'/'+(file.webkitRelativePath);
					if (file.size > MAX_UPLOAD_SIZE) {
						var $error_row = renderFileSizeErrorRow(file, folder);
						$('#upload_progress').append($error_row);
						window.setTimeout(function() {
							$error_row.fadeOut();
						}, 5000);
						return false;
					}
					var $row = renderFileUploadRow(file, folder);
					$('#upload_progress').append($row);
					var fd = new FormData();
					fd.append('file_data', file);
					fd.append('file', folder);
					fd.append('xsrf', XSRF);
					fd.append('do', 'upload_folder');
					var xhr = new XMLHttpRequest();
					xhr.open('POST', '?');
					xhr.onload = function() {
						$row.remove();
						list();
					};
					xhr.upload.onprogress = function(e) {
						if (e.lengthComputable) {
							$row.find('.progress').css('width', (e.loaded / e.total * 100 | 0) + '%');
						}
					};
					xhr.send(fd);
				}

				function renderFileUploadRow(file, folder) {
					return $row = $('<div/>')
						.append($('<span class="fileuploadname" />').text((folder ? folder + '/' : '') + file.name))
						.append($('<div class="progress_track"><div class="progress"></div></div>'))
						.append($('<span class="size" />').text(formatFileSize(file.size)))
				};

				function renderFileSizeErrorRow(file, folder) {
					return $row = $('<div class="error" />')
						.append($('<span class="fileuploadname" />').text('Error: ' + (folder ? folder + '/' : '') + file.name))
						.append($('<span/>').html(' file size - <b>' + formatFileSize(file.size) + '</b>' +
							' exceeds max upload size of <b>' + formatFileSize(MAX_UPLOAD_SIZE) + '</b>'));
				}
			<?php endif; ?>



			//list 導向table列表整體 + 空資料訊息
			function list() {
				var hashval = window.location.hash.substr(1);
				$.get('?do=list&file=' + hashval, function(data) {
					$tbody.empty();
					$('#breadcrumb').empty().html(renderBreadcrumbs(hashval));
					if (data.success) {
						$.each(data.results, function(k, v) {
							$tbody.append(renderFileRow(v));
						});
						!data.results.length && $tbody.append('<tr><td class="empty" colspan=5>This folder is empty 此資料夾是空的</td></tr>')
						data.is_writable ? $('body').removeClass('no_write') : $('body').addClass('no_write');
					} else {
						console.warn(data.error.msg);
					}
					$('#table').retablesort();
				}, 'json');
			}
			//table整體資料
			function renderFileRow(data) {
				var $link;
				if (data.is_dir) {
					$link = $('<a class="name" />')
						.attr('href',
							data.is_dir ? '#' + data.path : './' + data.path)
						.text(data.name);
				} else {
					$link = $('<a class="name" target="_BLANK" />')
						.attr('href', '?do=view&file=' + encodeURIComponent(data.path))
						.text(data.name);
				}
				$renamepath = data.path;
				var $rename_el = $('<span class="rename">&nbsp;&nbsp;<button class="btn btn-sm rename">重新命名</button></span>');
				var $save_el = $('<span class="save">&nbsp&nbsp;<button class="btn btn-sm save">儲存</button></span>');
				var $cancel_el = $('<span class="cancel">&nbsp;&nbsp;<button class="btn btn-sm cancel">取消</button></span>');
				$save_el.find('button').attr('data-file', data.path);
				var $retag_el = $('<span class="retag">&nbsp;&nbsp;<button class="btn btn-sm retag">修改標籤</button></span>');
				$retag_el.find('button').attr('data-file', data.path);
				var $tgx = $('<input type="hidden" id="tgx4" value=renamepath />');
				var $dl_link = $('<a/>').attr('href', '?do=download&file=' + encodeURIComponent(data.path))
					.addClass('download').text('下載檔案');
				var $delete_link = $('<a href="#" />').attr('data-file', data.path).addClass('delete').text('刪除檔案');
				var $zip_link = $('<a href="#" data-file="' + data.path + '"  class="zip">壓縮資料夾</a>');
				var perms = [];
				if (data.tag1 != '') perms.push(data.tag1);
				if (data.tag2 != '') perms.push(data.tag2);
				if (data.tag3 != '') perms.push(data.tag3);
				var $html = $('<tr />')
					.addClass(data.is_dir ? 'is_dir' : '')
					.addClass('ext-' + data.ext)
					.append(
						$('<td class="first"></td>').append($link)
						.append($rename_el)
						.append($save_el)
						.append($cancel_el)
					)
					.append(
						$('<td></td>').attr('data-sort', data.is_dir ? -1 : data.size).html($('<span class="size"></span>').text(formatFileSize(data.size)))
					)
					.append($('<td></td>').attr('data-sort', data.mtime).text(formatTimestamp(data.mtime)))
					.append($('<td class="sec"></td>').text(perms.join('、')).append($retag_el))
					.append($('<td class="last"></td>').append($dl_link)
						.append($zip_link).append(data.is_deleteable ? $delete_link : '')
					)
				return $html;
			}
			//現在雲端所在目錄(有超連結)
			function renderBreadcrumbs(path) {
				var base = "",
					$html = $('<div/>').append($('<a href=#' + $UID + '>我的雲端資料庫</a></div>'));
				$.each(path.split("/"), function(k, v) {
					if (v) {
						var v_as_text = decodeURIComponent(v);
						$html.append($('<span/>').text(' ▸ '))
							.append($('<a/>').attr('href', '#' + base + v).text(v_as_text));
						base += v + "/";
					}
				});
				return $html;
			}
			//檔案上傳時間轉換中文
			function formatTimestamp(unix_timestamp) {
				var m = ['1月', '2月', '3月', '4月', '5月', '6月', '7月', '8月', '9月', '10月', '11月', '12月'];
				var d = new Date(unix_timestamp * 1000);
				return [d.getFullYear(), "年", m[d.getMonth()], d.getDate(), "日", d.getHours() >= 12 ? '下午' : '上午',
					(d.getHours() % 12 || 12), ":", (d.getMinutes() < 10 ? '0' : '') + d.getMinutes()
				].join('');
			}
			//檔案大小轉換顯示
			function formatFileSize(bytes) {
				var s = ['bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB'];
				for (var pos = 0; bytes >= 1000; pos++, bytes /= 1024);
				var d = Math.round(bytes * 10);
				return pos ? [parseInt(d / 10), ".", d % 10, " ", s[pos]].join('') : bytes + ' bytes';
			}
		})
	</script>
	<!--css 部分跟function綁定-->
	<style>
		/*table按鈕 */
		span button.rename {
			background-color: rgba(130, 182, 202, 0.63);
		}

		span button.save {
			background-color: rgba(156, 231, 172);
		}

		span button.cancel {
			background-color: rgba(212, 223, 112);
		}

		span button.retag {
			background-color: rgba(130, 182, 202);
		}

		/*  */
		th {
			font-weight: normal;
			color: #1F75CC;
			background-color: #F0F9FF;
			padding: .5em 1em .5em .2em;
			text-align: left;
			cursor: pointer;
			user-select: none;
		}

		th .indicator {
			margin-left: 6px
		}

		thead {
			/* border-top: 1px solid #82CFFA; */
			border-bottom: 1px solid #96C4EA;
			border-left: 1px solid #E7F2FB;
			border-right: 1px solid #E7F2FB;
		}

		#mkdir {
			display: inline-block;
			float: right;
			padding-top: 16px;
		}

		#file_drop_target {
			/*width: 400px; */
			padding: 10px;
			border: 4px dashed #ccc;
			font-size: 20px;
			color: #ccc;
			text-align: left;
			/* float: right; */
			margin-right: 10px;
		}

		/* 資料夾上傳 modal */
		#file_drop_target+div {
			/*width: 400px; */
			padding: 10px;
			font-size: 20px;
			color: #ccc;
			text-align: left;
			/* float: right; */
			margin-right: 10px;
		}

		#file_drop_target.drag_over {
			border: 4px dashed #96C4EA;
			color: #96C4EA;
		}

		/*  */
		#upload_progress {
			padding: 4px 0;
		}

		#upload_progress .error {
			color: #a00;
		}

		#upload_progress>div {
			padding: 3px 0;
		}

		.no_write #mkdir,
		.no_write #file_drop_target {
			display: none
		}

		.progress_track {
			display: inline-block;
			width: 200px;
			height: 10px;
			border: 1px solid #333;
			margin: 0 4px 0 10px;
		}

		.progress {
			background-color: #82CFFA;
			height: 10px;
		}

		#breadcrumb {
			padding-top: 4px;
			font-size: 25px;
			color: #A9BAC4;
			display: inline-block;
			float: left;
		}

		#folder_actions {
			width: 50%;
			float: right;
		}

		a,
		a:visited {
			color: #00c;
			text-decoration: none
		}

		a:hover {
			text-decoration: underline
		}

		.sort_hide {
			display: none;
		}

		table {
			border-collapse: collapse;
			width: 100%;
		}

		table th {
			cursor: pointer;
		}

		thead {
			max-width: 1024px
		}

		td {
			padding: .2em 1em .2em .2em;
			border-bottom: 1px solid #def;
			height: 30px;
			font-size: 12px;
			white-space: nowrap;
		}

		td.first {
			font-size: 14px;
			white-space: normal;
		}

		td.sec {
			font-size: 14px;
			white-space: normal;
		}

		td.empty {
			color: #777;
			font-style: italic;
			text-align: center;
			padding: 3em 0;
		}

		.is_dir .size {
			color: transparent;
			font-size: 0;
		}

		.is_dir .size:before {
			content: "--";
			font-size: 14px;
			color: #333;
		}

		.is_dir .download {
			visibility: hidden
		}

		.is_dir .zip {
			display: inline
		}

		.zip {
			display: none
		}

		.ext-zip {
			background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAADdgAAA3YBfdWCzAAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAI0SURBVFiF7Vctb1RRED1nZu5977VQVBEQBKZ1GCDBEwy+ISgCBsMPwOH4CUXgsKQOAxq5CaKChEBqShNK222327f79n0MgpRQ2qC2twKOGjE352TO3Jl76e44S8iZsgOww+Dhi/V3nePOsQRFv679/qsnV96ehgAeWvBged3vXi+OJewMW/Q+T8YCLr18fPnNqQq4fS0/MWlQdviwVqNpp9Mvs7l8Wn50aRH4zQIAqOruxANZAG4thKmQA8D7j5OFw/iIgLXvo6mR/B36K+LNp71vVd1cTMR8BFmwTesc88/uLQ5FKO4+k4aarbuPnq98mbdo2q70hmU0VREkEeCOtqrbMprmFqM1psoYAsg0U9EBtB0YozUWzWpVZQgBxMm3YPoCiLpxRrPaYrBKRSUL5qn2AgFU0koMVlkMOo6G2SIymQCAGE/AGHRsWbCRKc8VmaBN4wBIwkZkFmxkWZDSFCwyommZSABgCmZBSsuiHahA8kA2iZYzSapAsmgHlgfdVyGLTFg3iZqQhAqZB923GGUgQhYRVElmAUXIGGVgedQ9AJJnAkqyClCEkkfdM1Pt13VHdxDpnof0jgxB+mYqO5PaCSDRIAbgDgdpKjtmwm13irsnq4ATdKeYcNvUZAt0dg5NVwEQFKrJlpn45lwh/LpbWdela4K5QsXEN61tytWr81l5YSY/n4wdQH84qjd2J6vEz+W0BOAGgLlE/AMAPQCv6e4gmWYC/QF3d/7zf8P/An4AWL/T1+B2nyIAAAAASUVORK5CYII=) no-repeat scroll 0px 10px;
			padding: 15px 0 10px 40px;
		}

		a.delete {
			display: inline-block;
			background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAYAAACNMs+9AAAABGdBTUEAAK/INwWK6QAAABl0RVh0U29mdHdhcmUAQWRvYmUgSW1hZ2VSZWFkeXHJZTwAAADtSURBVHjajFC7DkFREJy9iXg0t+EHRKJDJSqRuIVaJT7AF+jR+xuNRiJyS8WlRaHWeOU+kBy7eyKhs8lkJrOzZ3OWzMAD15gxYhB+yzAm0ndez+eYMYLngdkIf2vpSYbCfsNkOx07n8kgWa1UpptNII5VR/M56Nyt6Qq33bbhQsHy6aR0WSyEyEmiCG6vR2ffB65X4HCwYC2e9CTjJGGok4/7Hcjl+ImLBWv1uCRDu3peV5eGQ2C5/P1zq4X9dGpXP+LYhmYz4HbDMQgUosWTnmQoKKf0htVKBZvtFsx6S9bm48ktaV3EXwd/CzAAVjt+gHT5me0AAAAASUVORK5CYII=) no-repeat scroll 0 2px;
			color: #d00;
			margin-left: 15px;
			font-size: 11px;
			padding: 0 0 0 13px;
		}

		.name {
			background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABgAAAAYCAYAAADgdz34AAABAklEQVRIie2UMW6DMBSG/4cYkJClIhauwMgx8CnSC9EjJKcwd2HGYmAwEoMREtClEJxYakmcoWq/yX623veebZmWZcFKWZbXyTHeOeeXfWDN69/uzPP8x1mVUmiaBlLKsxACAC6cc2OPd7zYK1EUYRgGZFkG3/fPAE5fIjcCAJimCXEcGxKnAiICERkSIcQmeVoQhiHatoWUEkopJEkCAB/r+t0lHyVN023c9z201qiq6s2ZYA9jDIwx1HW9xZ4+Ihta69cK9vwLvsX6ivYf4FGIyJj/rg5uqwccd2Ar7OUdOL/kPyKY5/mhZJ53/2asgiAIHhLYMARd16EoCozj6EzwCYrrX5dC9FQIAAAAAElFTkSuQmCC) no-repeat scroll 0px 12px;
			padding: 15px 0 10px 40px;
		}

		.is_dir .name {
			background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAYAAABzenr0AAAABHNCSVQICAgIfAhkiAAAAAlwSFlzAAADdgAAA3YBfdWCzAAAABl0RVh0U29mdHdhcmUAd3d3Lmlua3NjYXBlLm9yZ5vuPBoAAAI0SURBVFiF7Vctb1RRED1nZu5977VQVBEQBKZ1GCDBEwy+ISgCBsMPwOH4CUXgsKQOAxq5CaKChEBqShNK222327f79n0MgpRQ2qC2twKOGjE352TO3Jl76e44S8iZsgOww+Dhi/V3nePOsQRFv679/qsnV96ehgAeWvBged3vXi+OJewMW/Q+T8YCLr18fPnNqQq4fS0/MWlQdviwVqNpp9Mvs7l8Wn50aRH4zQIAqOruxANZAG4thKmQA8D7j5OFw/iIgLXvo6mR/B36K+LNp71vVd1cTMR8BFmwTesc88/uLQ5FKO4+k4aarbuPnq98mbdo2q70hmU0VREkEeCOtqrbMprmFqM1psoYAsg0U9EBtB0YozUWzWpVZQgBxMm3YPoCiLpxRrPaYrBKRSUL5qn2AgFU0koMVlkMOo6G2SIymQCAGE/AGHRsWbCRKc8VmaBN4wBIwkZkFmxkWZDSFCwyommZSABgCmZBSsuiHahA8kA2iZYzSapAsmgHlgfdVyGLTFg3iZqQhAqZB923GGUgQhYRVElmAUXIGGVgedQ9AJJnAkqyClCEkkfdM1Pt13VHdxDpnof0jgxB+mYqO5PaCSDRIAbgDgdpKjtmwm13irsnq4ATdKeYcNvUZAt0dg5NVwEQFKrJlpn45lwh/LpbWdela4K5QsXEN61tytWr81l5YSY/n4wdQH84qjd2J6vEz+W0BOAGgLlE/AMAPQCv6e4gmWYC/QF3d/7zf8P/An4AWL/T1+B2nyIAAAAASUVORK5CYII=) no-repeat scroll 0px 10px;
			padding: 15px 0 10px 40px;
		}

		.download {
			background: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAB2klEQVR4nJ2ST2sTQRiHn5mdmj92t9XmUJIWJGq9NHrRgxQiCtqbl97FqxgaL34CP0FD8Qv07EHEU0Ew6EXEk6ci8Q9JtcXEkHR3k+zujIdUqMkmiANzmJdnHn7vzCuIWbe291tSkvhz1pr+q1L2bBwrRgvFrcZKKinfP9zI2EoKmm7Azstf3V7fXK2Wc3ujvIqzAhglwRJoS2ImQZMEBjgyoDS4hv8QGHA1WICvp9yelsA7ITBTIkwWhGBZ0Iv+MUF+c/cB8PTHt08snb+AGAACZDj8qIN6bSe/uWsBb2qV24/GBLn8yl0plY9AJ9NKeL5ICyEIQkkiZenF5XwBDAZzWItLIIR6LGfk26VVxzltJ2gFw2a0FmQLZ+bcbo/DPbcd+PrDyRb+GqRipbGlZtX92UvzjmUpEGC0JgpC3M9dL+qGz16XsvcmCgCK2/vPtTNzJ1x2kkZIRBSivh8Z2Q4+VkvZy6O8HHvWyGyITvA1qndNpxfguQNkc2CIzM0xNk5QLedCEZm1VKsf2XrAXMNrA2vVcq4ZJ4DhvCSAeSALXASuLBTW129U6oPrT969AK4Bq0AeWARs4BRgieMUEkgDmeO9ANipzDnHnFB0KgAxwATaAFeID5DQNatLGdaXOWAAAAAElFTkSuQmCC) no-repeat scroll 0px 5px;
			padding: 4px 0 4px 20px;
		}

		.first .save,
		.first .cancel {
			display: none;
		}

		.first .save.is-visible,
		.first .rename.is-visible,
		.first .cancel.is-visible {
			display: inline-block;
		}

		.first .rename.is-hidden {
			display: none;
		}

		.saved {
			color: green;
			display: none;
			font-weight: bold;
		}

		td.last {
			text-align: right;
		}
	</style>
</head>

<body>
	<!--TOP Navbar -->
	<?php include '../php/topnavbar.php'; ?>
	<!--整體-->
	<div class="container-fluid">
		<div class="row">
			<!--left side bar 側邊導航-->
			<div class="col-lg-2">
				<?php include '../php/leftsidenavbar.php'; ?>
			</div>
			<!--雲端-整體頁面-->
			<main class="col-lg-10 px-md-5">
				<!--雲端-搜尋區塊-->
				<!--路徑顯示-->
				<h1 class="h2 d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
					<div id="breadcrumb">&nbsp;</div>
				</h1>
				<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
					<!--搜尋+檔案按鈕-->
					<!--儲存空間配額-->
					<div class="row">
						<progress class="col-lg-10" id="file" max="100" value=<?php echo $current_usage_rate ?>>
						</progress>
						<div class="col-lg-10">目前使用量：<?php echo $current_usage ?> 
						</div>
						<div class="col-lg-10">(儲存空間配額：100 GB)<?php if($current_usage_rate>=90) echo "雲端快滿了!" ?>
						</div>
					</div>
					<div class="btn-toolbar mb-2 mb-md-0">
						<div class="btn-group me-2">
							<input class="form-control" placeholder="搜尋..." type="text" id="filterName" name="filterName" required>
						</div>
						<div class="dropdown">
						<button class="btn btn-secondary dropdown-toggle btn-info" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
							<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-cloud-plus" viewBox="0 0 16 16">
							<path fill-rule="evenodd" d="M8 5.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V10a.5.5 0 0 1-1 0V8.5H6a.5.5 0 0 1 0-1h1.5V6a.5.5 0 0 1 .5-.5z" />
							<path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z" />
							</svg>
							新增
						</button>
						<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
							<li>
							<a class="dropdown-item" type="button" data-bs-toggle="modal" data-bs-target="#loginModal2">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-up" viewBox="0 0 16 16">
								<path d="M8.5 11.5a.5.5 0 0 1-1 0V7.707L6.354 8.854a.5.5 0 1 1-.708-.708l2-2a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 7.707V11.5z" />
								<path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
								</svg>
								檔案上傳
							</a>
							</li>
							<li>
							<a class="dropdown-item border-bottom" type="button" data-bs-toggle="modal" data-bs-target="#loginModal">
								<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cloud-arrow-up" viewBox="0 0 16 16">
								<path fill-rule="evenodd" d="M7.646 5.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708l2-2z" />
								<path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z" />
								</svg>
								資料夾上傳
							</a>
							</li>
							<li>
							<a class="dropdown-item">
								<a class="button" type="text" class="disable">
									<form action="?" method="post" id="mkdir">
										<input id="dirname" type="text" name=name value="" placeholder="資料夾名稱...">
										<input type="submit" value="建立資料夾">
									</form>
								</a>
							</a>
							</li>
						</ul>
						</div>
					</div>
				</div>
				
				<!--上傳讀取條-->
				<div id="upload_progress"></div>
				<!--所有委任-列表區塊 + 分頁導航-->
				<div class="table-responsive">
					<table id="table" class="table table-hover table-sm">
						<thead>
							<tr class="info">
								<th>名稱</th>
								<th>大小</th>
								<th>生成日期</th>
								<th>標籤</th>
								<th>功能</th>
							</tr>
						</thead>
						<!--目前這裡直接id list上面函式蓋掉-->
						<tbody id="list">
						</tbody>
					</table>
				</div>
			</main>
		</div>
	</div>
	<!-- footer copyright-->
	<?php include '../php/footer.php'; ?>
	<!--防呆警告 模态框（Modal) **未使用-->
	<div class="modal fade" id="dum-delete-Modal" tabindex="-1">
		<div class="modal-dialog modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle" viewBox="0 0 16 16">
						<path d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.146.146 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.163.163 0 0 1-.054.06.116.116 0 0 1-.066.017H1.146a.115.115 0 0 1-.066-.017.163.163 0 0 1-.054-.06.176.176 0 0 1 .002-.183L7.884 2.073a.147.147 0 0 1 .054-.057zm1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566z" />
						<path d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995z" />
					</svg>
					<h5 class="modal-title">確定刪除?</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>

				<div class="modal-body">
					<form class="form-horizontal">
						<div class="form-group">
							<label class="control-label p-2">刪除代碼為:446611(6位隨機亂數)</label>
							<input type="text" class="form-control" placeholder="請輸入對應的刪除代碼..." required>
						</div>

						<div class="modal-footer">
							<div class="form-group">
								<div class="d-flex justify-content-end">
									<button type="submit" class="btn btn-danger">確定</button>
									<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
								</div>
							</div>
						</div>

					</form>
				</div>

			</div>
		</div>
	</div>
	<!--修改檔案 模态框（Modal)-->
	<div class="modal fade" id="loginModal3" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard-check" viewBox="0 0 16 16">
						<path fill-rule="evenodd" d="M10.854 7.146a.5.5 0 0 1 0 .708l-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7.5 9.793l2.646-2.647a.5.5 0 0 1 .708 0z" />
						<path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z" />
						<path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z" />
					</svg>
					<h5 class="modal-title">修改標籤</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row">
						<label class="col-sm-3 control-label p-2">標籤1:</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" placeholder="自行輸入..." id="tagtext1" name="tagtext1">
						</div>
					</div>

					<div class="row">
						<label class="col-sm-3 control-label p-2">標籤2:</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" placeholder="自行輸入..." id="tagtext2" name="tagtext2">
						</div>
					</div>

					<div class="row">
						<label class="col-sm-3 control-label p-2">標籤3:</label>
						<div class="col-sm-9">
							<input type="text" class="form-control" placeholder="自行輸入..." id="tagtext3" name="tagtext3">
						</div>
					</div>

					<div class="modal-footer">
						<div class="d-flex justify-content-end">
							<button type="button" class="btn btn-info" id="retagsubmit">確定修改</button>
							<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--資料夾上傳 模态框（Modal)-->
	<div class="modal fade" id="loginModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-cloud-arrow-up" viewBox="0 0 16 16">
						<path fill-rule="evenodd" d="M7.646 5.146a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 6.707V10.5a.5.5 0 0 1-1 0V6.707L6.354 7.854a.5.5 0 1 1-.708-.708l2-2z" />
						<path d="M4.406 3.342A5.53 5.53 0 0 1 8 2c2.69 0 4.923 2 5.166 4.579C14.758 6.804 16 8.137 16 9.773 16 11.569 14.502 13 12.687 13H3.781C1.708 13 0 11.366 0 9.318c0-1.763 1.266-3.223 2.942-3.593.143-.863.698-1.723 1.464-2.383zm.653.757c-.757.653-1.153 1.44-1.153 2.056v.448l-.445.049C2.064 6.805 1 7.952 1 9.318 1 10.785 2.23 12 3.781 12h8.906C13.98 12 15 10.988 15 9.773c0-1.216-1.02-2.228-2.313-2.228h-.5v-.5C12.188 4.825 10.328 3 8 3a4.53 4.53 0 0 0-2.941 1.1z" />
					</svg>
					<h5 class="modal-title">上傳資料夾內所有檔案到此</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<?php if ($allow_upload) : ?>
						<div id="file_drop_target">
							拖曳資料夾至此 批量上傳</br>
							<input type="file" name="files[]" id="files" multiple directory="" webkitdirectory="" moxdirectory="" />
						</div>
						<div class="justify-content-start row">
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
	<!--檔案上傳 模态框（Modal)-->
	<div class="modal fade" id="loginModal2" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-scrollable">
			<div class="modal-content">
				<div class="modal-header">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-arrow-up" viewBox="0 0 16 16">
						<path d="M8.5 11.5a.5.5 0 0 1-1 0V7.707L6.354 8.854a.5.5 0 1 1-.708-.708l2-2a.5.5 0 0 1 .708 0l2 2a.5.5 0 0 1-.708.708L8.5 7.707V11.5z" />
						<path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z" />
					</svg>
					<h5 class="modal-title">上傳檔案到此</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<?php if ($allow_upload) : ?>
						<div id="file_drop_target">
							拖曳檔案至此處上傳</br>
							<input type="file" name="file" multiple />
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>
	</div>
</body>
</html>