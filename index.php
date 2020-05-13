
<?php
 print <<<EOT
 
 <meta name="viewport" content="width=device-width,initial-scale=1">
    <style type="text/css">body {zoom:2.0;}</style>

EOT;
include 'barcode.php';

$generator = new barcode_generator();

date_default_timezone_set("Asia/Shanghai");
$mailServer="****"; //IMAP主机
$mailLink="{{$mailServer}:993/imap/ssl}INBOX" ; //imap连接地址
$mailUser = '****'; //邮箱用户名
$mailPass = '****'; //邮箱密码
$mbox = imap_open($mailLink,$mailUser,$mailPass) or die("can't connect: " . imap_last_error()); //开启信箱imap_open
$totalrows = imap_num_msg($mbox); //取得信件数
//echo $totalrows;

//$i=$totalrows;$i>0;$i--
for ($i=$totalrows;$i>0;$i--){      
    $headers = imap_fetchheader($mbox, $i); //获取信件标头
    //echo $headers;
    ///Subject:(.*?)[\n\r]/
        $Subjects = preg_match('/Subject: You have a package to pick up at the Foxhall Hub (.*?)[\n\r]/', $headers,$Subject);  //提取邮件的标题
        //echo $Subjects[1];
        preg_match('/Date:(.*?)[\n\r]/', $headers,$m);
        
        //echo $headers;
        //echo $Subject[0];
        $kw = "/Subject: You have a package to pick up at the Foxhall Hub (.*?)/";   
        $sub1 = trim($Subject[0]);
        //echo $sub1;
        //echo  $headers;
        $sub2 = trim($kw);
        
        
        if(preg_match($sub2,$sub1)){ 
                
                preg_match('/\d+/',$Subject[0],$code);
                
                //echo $code[0];
                $symbology='code-128';
                $data="AZ{$code[0]}LK";
                //echo $data;
                $options='h=1';
                $svg = $generator->render_svg($symbology, $data, $options);
                echo '<p style="font-size:10pt;text-align:center">'.$svg.'<p>';
                
                //echo '<p style="font-size:5pt;text-align:center">'.$m[0].'<p>';
            break;  
        }
}
imap_close($mbox);
?>
