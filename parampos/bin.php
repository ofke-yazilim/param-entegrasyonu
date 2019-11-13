<?php


$tutar = $_GET['tutar'];

require_once '_config.php';

$xml_data = '<x:Envelope xmlns:x="http://schemas.xmlsoap.org/soap/envelope/" xmlns:tur="https://turkpos.com.tr/">
    <x:Header/>
    <x:Body>
        <tur:BIN_SanalPos>
            <tur:G>
                <tur:CLIENT_CODE>'.$CLIENT_CODE.'</tur:CLIENT_CODE>
                <tur:CLIENT_USERNAME>'.$CLIENT_USERNAME.'</tur:CLIENT_USERNAME>
                <tur:CLIENT_PASSWORD>'.$CLIENT_PASSWORD.'</tur:CLIENT_PASSWORD>
            </tur:G>
            <tur:BIN>'.$_GET['bin'].'</tur:BIN>
        </tur:BIN_SanalPos>
    </x:Body>
</x:Envelope>';

$ch = curl_init($_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: text/xml'));
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, "$xml_data");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
curl_close($ch);

$xmlString = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $output);

$xml = SimpleXML_Load_String($xmlString);

$xml = new SimpleXMLElement($xml->asXML());

$parse      = $xml->soapBody->BIN_SanalPosResponse->BIN_SanalPosResult->DT_Bilgi->diffgrdiffgram->NewDataSet->Temp;
$oranlarPos = (array) json_decode($parse->SanalPOS_ID,true);

$pos_id = $oranlarPos[0];

if($pos_id == 1014){
    $key = 0;
}

if($pos_id == 1013){
    $key = 1;
}

if($pos_id == 1011){
    $key = 2;
}

if($pos_id == 1029){
    $key = 3;
}

if($pos_id == 1008){
    $key = 4;
}

if($pos_id == 1012){
    $key = 5;
}

if($pos_id == 1018){
    $key = 6;
}

if($pos_id == 1009){
    $key = 7;
}

require_once 'taksit.php';