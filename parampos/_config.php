<?php
/**
 * @param string $CLIENT_CODE     : Terminal ID. It can access from Param account
 * @param string $CLIENT_USERNAME : Username. It can access from Param account
 * @param string $CLIENT_PASSWORD : Password. It can access from Param account
 * @param string $GUID            : Key belonging to member workplace
 * @param string $MODE            : PROD/TEST
 **/

$_url = 'http://test-dmz.ew.com.tr:8080/turkpos.ws/service_turkpos_test.asmx';
//$_url = 'https://dmzws.ew.com.tr/turkpos.ws/service_turkpos_prod.asmx';
    
$GUID            = '0c13d406-873b-403b-9c09-a5766840d98c';
$CLIENT_CODE     = 10738;
$CLIENT_USERNAME = 'Test';
$CLIENT_PASSWORD = 'Test';
$MODE            = "TEST"; // PROD

$hosturl         = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$failUrl         = $hosturl."/param-entegrasyonu-master/unsuccesfull.php";
$successUrl      = $hosturl."/param-entegrasyonu-master/succesfull.php";
$payAction       = $hosturl."/param-entegrasyonu-master/parampos/soap.php";
$ipAddress       = ($_SERVER['REMOTE_ADDR']=="::1")?'127.0.0.1':$_SERVER['REMOTE_ADDR'];


$cardHolderPhone = "";
$transactionId   = time();
$orderId         = "1".time();
$orderId         = time();
$referenceUrl    = $hosturl;
$extraData1      = " ";
$extraData2      = " ";
$extraData3      = " ";
$extraData4      = " ";
$extraData5      = " ";
