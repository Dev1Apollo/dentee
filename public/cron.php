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
$sql = "SELECT * FROM `appointments` where appointment_date='".$date."' and deleted_at is null"; 
$res = mysqli_query($dbconn, $sql);
if (mysqli_num_rows($res) > 0) {
    while ($row = mysqli_fetch_array($res)) {
        if(isset($row['patient_id'])){
            $patients = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `patients` where patient_id='".$row['patient_id']."' and branch_id='".$row['branch_id']."'"));
            $branches = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `branches` where branch_id='".$row['branch_id']."'"));
            $msg = "*Appointment Reminder for ". $patients['name_prefix'] ." " . $patients['name'] ."*
    
Dear ". $patients['name_prefix'] ." " . $patients['name'] .",
                
This is a friendly reminder of your upcoming dental appointment at Vraj Group of Dental Clinics.
                
Your appointment is scheduled for ".date('d-m-Y',strtotime($row['appointment_date']))." at ".$row['appointment_time']." at our ".$branches['branch_name'].". 
Branch addres : ".$branches['address']."
Branch location link : ".$branches['strAddressLink']."
                
We look forward to seeing you soon!
                
Best regards,
Vraj Group of Dental Clinics";

            // $data = "http://api.bulkcampaigns.com/api/wapi?json=true&apikey=".$key."&mobile=".$patients['mobile_no']."&msg=".urlencode($msg);
            //$data = "https://newweb.technomantraa.com/api/send?number=91".$patients['mobile_no']."&type=text&message=".urlencode($msg)."&instance_id=65C48823AC1D6&access_token=65c486860588c";
            $data = "https://newweb.technomantraa.com/api/send?number=91".$patients['mobile_no']."&type=text&message=".urlencode($msg)."&instance_id=666946D557590&access_token=65c486860588c";
            $ret = file_get_contents($data);
    		$result = json_decode($ret);
    		//return $result;
        }
        
    }
}

$sqlOne = "SELECT DISTINCT doctor_id,users.user_name,users.mobile_no,branch_id FROM `appointments` inner join users on appointments.doctor_id=users.user_id where appointment_date='".$date."' and appointments.deleted_at is null";
$resOne = mysqli_query($dbconn, $sqlOne);
if (mysqli_num_rows($resOne) > 0) {
    while ($rowOne = mysqli_fetch_array($resOne)) {
        if(isset($rowOne['doctor_id'])){
            $appointments = mysqli_query($dbconn,"SELECT * FROM `appointments` WHERE doctor_id=".$rowOne['doctor_id']." and appointment_date='".$date."' and branch_id='".$rowOne['branch_id']."' and deleted_at is null order by STR_TO_DATE(appointment_time, '%h:%i %p') asc");
            $branches = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `branches` where branch_id='".$rowOne['branch_id']."'"));
            $msg = "*Daily Appointment Reminder*

Dear ".$rowOne['user_name'].",
            
This is a reminder for your scheduled appointments today at Vraj Group of Dental Clinics. Please be prepared for the following:
    
";
    $icounter = 1;
            while($appointment = mysqli_fetch_assoc($appointments)){
                $patients = mysqli_fetch_array(mysqli_query($dbconn,"SELECT * FROM `patients` where patient_id='".$appointment['patient_id']."' and branch_id='".$rowOne['branch_id']."'"));
                
$msg .= "".
$icounter. ") " . $patients['name_prefix'] ." " . $patients['name'] ." - ".$appointment['appointment_time']." (".$branches['branch_name'].")

";    
$icounter++;
            }
$msg .="Thank you for your commitment to providing excellent dental care.
Best regards,
Vraj Group of Dental Clinics";

            //$data = "http://api.bulkcampaigns.com/api/wapi?json=true&apikey=".$key."&mobile=".$rowOne['mobile_no']."&msg=".urlencode($msg);
            //$data = "https://newweb.technomantraa.com/api/send?number=91".$rowOne['mobile_no']."&type=text&message=".urlencode($msg)."&instance_id=65C48823AC1D6&access_token=65c486860588c";
            $data = "https://newweb.technomantraa.com/api/send?number=91".$rowOne['mobile_no']."&type=text&message=".urlencode($msg)."&instance_id=666946D557590&access_token=65c486860588c";
            $ret = file_get_contents($data);
    		$result = json_decode($ret);
        }
    }
}

?>