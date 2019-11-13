<?php

/**
* $_GET['message'] = Hata mesajı;
* $_GET['code']    = Hata kodu;
**/
session_start();
if(isset($_GET['message'])){
	echo ($_GET['message']);
	
} else{
	echo "Banka parayı çekemedi.No otorozizasyon.";
}
