<?php
require_once '_config.php';

if(!strstr($_POST['tutar'], ".")) {
    $_POST['tutar'] = $_POST['tutar'].",00";
}

if(!strstr($_POST['tutar'], ",")){
    $total        =  number_format($_POST['tutar'],2,",",".");
} else{
    $total        = $_POST['tutar'];
}

$taksitData   =  explode("-", $_POST['taksit']);
$generalTotal =  $taksitData[1];


$securityString      = $CLIENT_CODE.$GUID.$_POST['pos_id'].$taksitData[0].$total.$generalTotal.$orderId.$failUrl.$successUrl;
$xml_data = '<x:Envelope xmlns:x="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tur="https://turkpos.com.tr/">
    <x:Header/>
    <x:Body>
        <tur:SHA2B64>
            <tur:Data>'.$securityString.'</tur:Data>
        </tur:SHA2B64>
    </x:Body>
</x:Envelope>';

$ch = curl_init($_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
curl_close($ch);

$clean_xml = str_ireplace(['SOAP-ENV:', 'SOAP:'], '', $output);
$xml = simplexml_load_string($clean_xml);
$islem_Hash = $xml->Body->SHA2B64Response->SHA2B64Result;

$xml_data = '<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:s="http://www.w3.org/2001/XMLSchema" xmlns:tns="https://turkpos.com.tr/">
  <soap:Body>
    <tns:TP_Islem_Odeme_WNS>
      <tns:G>
        <tns:CLIENT_CODE>'.$CLIENT_CODE.'</tns:CLIENT_CODE>
        <tns:CLIENT_USERNAME>'.$CLIENT_USERNAME.'</tns:CLIENT_USERNAME>
        <tns:CLIENT_PASSWORD>'.$CLIENT_PASSWORD.'</tns:CLIENT_PASSWORD>
      </tns:G>
      <tns:SanalPOS_ID>'.$_POST['pos_id'].'</tns:SanalPOS_ID>
      <tns:GUID>'.$GUID.'</tns:GUID>
      <tns:KK_Sahibi>'.$_POST['cardname'].'</tns:KK_Sahibi>
      <tns:KK_No>'.$_POST['cardnumber'].'</tns:KK_No>
      <tns:KK_SK_Ay>'.$_POST['expmonth'].'</tns:KK_SK_Ay>
      <tns:KK_SK_Yil>'.$_POST['expyear'].'</tns:KK_SK_Yil>
      <tns:KK_CVC>'.$_POST['cvv'].'</tns:KK_CVC>
      <tns:KK_Sahibi_GSM>'.$cardHolderPhone.'</tns:KK_Sahibi_GSM>
      <tns:Hata_URL>'.$failUrl.'</tns:Hata_URL>
      <tns:Basarili_URL>'.$successUrl.'</tns:Basarili_URL>
      <tns:Siparis_ID>'.$orderId.'</tns:Siparis_ID>
      <tns:Siparis_Aciklama>DESC</tns:Siparis_Aciklama>
      <tns:Taksit>'.$taksitData[0].'</tns:Taksit>
      <tns:Islem_Tutar>'.$_POST['tutar'].'</tns:Islem_Tutar>
      <tns:Toplam_Tutar>'.$taksitData[1].'</tns:Toplam_Tutar>
      <tns:Islem_Hash>'.$islem_Hash.'</tns:Islem_Hash>
      <tns:Islem_Guvenlik_Tip>3d</tns:Islem_Guvenlik_Tip>
      <tns:Islem_ID>'.$transactionId.'</tns:Islem_ID>
      <tns:IPAdr>'.$ipAddress.'</tns:IPAdr>
      <tns:Ref_URL>'.$referenceUrl.'</tns:Ref_URL>
      <tns:Data1/>
      <tns:Data2/>
      <tns:Data3/>
      <tns:Data4/>
      <tns:Data5/>
    </tns:TP_Islem_Odeme_WNS>
  </soap:Body>
</soap:Envelope>';
//echo $xml_data;exit;

$ch = curl_init($_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
curl_close($ch);

$clean_xml = str_ireplace(['SOAP-ENV:', 'SOAP:'], '', $output);
$xml = simplexml_load_string($clean_xml);
$result = $xml->Body->TP_Islem_Odeme_WNSResponse->TP_Islem_Odeme_WNSResult;

if($result->Islem_ID == 0){
    if(isset($result->Sonuc_Str)){
		$message = $result->Sonuc_Str;
		$code    = $result->Islem_ID;
		echo "<script>window.location.href='".$failUrl."?message=".$message."&code=".$code."';</script>";
        //echo $result->Sonuc_Str;
    }
} else{
    echo "<script>window.location.href='".$xml->Body->TP_Islem_Odeme_WNSResponse->TP_Islem_Odeme_WNSResult->UCD_URL."';</script>";
}
