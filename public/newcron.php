<?php

date_default_timezone_set("Asia/Calcutta");
error_reporting(E_ALL);
$dbhost = "localhost";
$dbuser = "vrajdahj";
$dbpass = "Vraj@Apollo@786";
$dbname = "vrajdahj_vgdc";
$dbconn = mysqli_connect("$dbhost", "$dbuser", "$dbpass",$dbname) or die('Could not connect: ' . mysqli_connect_error($dbconn));
$key="029ca92e908590bf49c8470d99844b84";

$date = date('Y-m-d');

$branchs = mysqli_query($dbconn,"SELECT * FROM `branches` where deleted_at is null and branch_id in (16,15,2,4,7,17,19)");
// $MsgQuotation = "*New Case Quotation Added Amount  :*
// ";
$MsgQuotation = "*New Bill Generated Amount :*
";
$MsgPatient = "
*New Case Arrived Today Number :*
";
    
$MsgDailyCollection = "
*Daily Collection :*
";
    
if (mysqli_num_rows($branchs) > 0) {
    $iCounter = 1;
    while($branch = mysqli_fetch_assoc($branchs)){
        //$quotation = mysqli_fetch_assoc(mysqli_query($dbconn,"SELECT sum(amount) as Amount FROM `quotation` where branch_id='".$branch['branch_id']."' and created_at like '".$date."%'"));
        $quotation = mysqli_fetch_assoc(mysqli_query($dbconn,"select sum(net_amount) as Amount from order_master where branch_id='".$branch['branch_id']."' and created_at like '".$date."%'"));
        $Amount = isset($quotation['Amount']) ? $quotation['Amount'] : 0;
$MsgQuotation .=
$iCounter.") ". $branch['branch_name'] ." : " .$Amount."
";
        
        $patient = mysqli_fetch_assoc(mysqli_query($dbconn,"SELECT count(*) as count FROM `patients` where branch_id='".$branch['branch_id']."' and created_at like '".$date."%'"));
        $PCount = isset($patient['count']) ? $patient['count'] : 0;
$MsgPatient .=
$iCounter.") ". $branch['branch_name'] ." : " .$PCount."
";
        
        $DailyCollection = mysqli_fetch_assoc(mysqli_query($dbconn,"SELECT sum(order_payment_detail.amount) as amount from order_payment_detail inner join order_master on order_master.order_master_id=order_payment_detail.order_id
        where order_payment_detail.istatus=0 and order_master.is_paid!=0 and order_payment_detail.clinic_id=1 and order_master.istatus=0  and order_payment_detail.branch_id='".$branch['branch_id']."' and order_payment_detail.payment_date like '".$date."%'"));
        $DailyCollectionAmount = isset($DailyCollection['amount']) ? $DailyCollection['amount'] : 0;
$MsgDailyCollection .=
$iCounter.") ". $branch['branch_name'] ." : " .$DailyCollectionAmount."
";
        $iCounter++;
    }
}

$msg = $MsgQuotation ." " . $MsgPatient . " " .$MsgDailyCollection;

// $data = "https://newweb.technomantraa.com/api/send?number=917046673769&type=text&message=".urlencode($msg)."&instance_id=666946D557590&access_token=65c486860588c";
// $ret = file_get_contents($data);
// $result = json_decode($ret);

// $data = "https://newweb.technomantraa.com/api/send?number=919904500629&type=text&message=".urlencode($msg)."&instance_id=666946D557590&access_token=65c486860588c";
// $ret = file_get_contents($data);
// $result = json_decode($ret);

$data = "https://newweb.technomantraa.com/api/send?number=918401442448&type=text&message=".urlencode($msg)."&instance_id=666946D557590&access_token=65c486860588c";
$ret = file_get_contents($data);
$result = json_decode($ret);


$data = "https://newweb.technomantraa.com/api/send?number=919724630450&type=text&message=".urlencode($msg)."&instance_id=666946D557590&access_token=65c486860588c";
$ret = file_get_contents($data);
$result = json_decode($ret);

?>