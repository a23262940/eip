<?php
$mailLink='{imap.gmail.com:993/imap/ssl}INBOX' ; //imagp連線地址：不同主機地址不同
$mailUser = 's10827055@gm.cyut.edu.tw'; //郵箱使用者名稱
$mailPass = 'Asd12345@'; //郵箱密碼
$mbox = imap_open($mailLink,$mailUser,$mailPass); //開啟信箱imap_open
$totalrows = imap_num_msg($mbox); //取得信件數
/**
* 匹配提取信件頭部資訊
* @param String $str
*/
function matchMailHead($str){
    $headList = array();
    $headArr = array(
      'from',
      'to',
      'date',
      'subject'
    );
    foreach ($headArr as $key){
      if(preg_match('/'.$key.':(.*?)[\n\r]/is', $str,$m)){  //
        $match = trim($m[1]);
        $headList[$key] = $key=='date'?date('Y-m-d H:i:s',strtotime($match)):$match;
        $headList[$key]=imap_utf8($headList[$key]);
    }
    }
    return $headList;
  }
?>

<!DOCTYPE html>
<html>
<meta http-equiv=”Content-Type” content=”text/html; charset=utf-8″>
    <title>
        132
    </title>
    <body>
    <?php
        for ($i=$totalrows;$i>0;$i--){
            $headers = imap_fetchheader($mbox, $i); //獲取信件標頭
           // $mailBody = imap_fetchbody($mbox, $i, 0); //獲取信件正文
            $aa = matchMailHead($headers);
            print_r($aa);
            echo "<br>";
            if($i<=1350){
                break;
            }
        }
    ?>
    </body>
</html>