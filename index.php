<?php
//проверяем есть ли ключ ссылки и он не пустой
if (isset($_GET) && ($_GET != null))
{
	$mass = file ('url.txt');//загружаем список адресов
	for ($x=0;$x<=count($mass);$x++) {
		foreach (unserialize($mass[$x]) as $sh=>$url)
		{
			//ищем ключ в списке, если находим - переходим по ссылке 
			if (($_SERVER['QUERY_STRING'] == $sh)) {header("Location: $url"); };
		}	
	}
	//если не нашли - выведем ошибку
	echo "Неверная ссылка";
}
//если ключа нет - отобразим форму и остановим скрипт
else {
	require('form.htm');
}
?>
