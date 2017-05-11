<?php
//проверяем передана ли переменная методом POST и не пустая ли она
if (isset($_POST['url']) && ($_POST['url'] != null)) {
	//если переданная переменная ссылки содержит протокол, то используем её. 
	if (preg_match('#(http:\/\/|https:\/\/).*#', $_POST['url']))
	{
		$url = $_POST['url'];
	}
	else
	//если не содержит протокол - добавим протокол http
	{
		$url = "http://".$_POST['url'];
	};
	//если пользователь ввел свою короткую ссылку и она не пустая
	if (isset($_POST['sh_url']) && ($_POST['sh_url'] != null)) 
	{
		//если есть используем пользовательское сокращение
		//сначала проверим пользовательское сокращение
		if (preg_match('#[^a-zA-Z0-9]+#', $_POST['sh_url']) or ($_POST['sh_url'] == '0') )//ищем не латиницу и не цифры или равна 0
		{
			//если найдем 0, не латинские символы или не цифры - выведем сообщение об ошибке и завершаем скрипт
			die("Ошибка! Неверные символы.");
		}
		//ещё проверим есть ли в списке переменная короткой ссылки
		//загрузим список из файла
		$mass = file ('url.txt');
		for ($x=0;$x<=count($mass);$x++) 
		{
			foreach (unserialize($mass[$x]) as $sh=>$u)
			{
				if ($_POST['sh_url'] == $sh)
				{
					//если есть в списке - выведем сообщение об ошибке и завершаем скрипт
					die("Ошибка! Сокращение уже есть."); 
				}
			}	
					//если ссылки нет в списке - используем её
					$sh_url = $_POST['sh_url'];			
		}
		//конец проверки
	}
	//если пользователь не ввел свое сокращение	
	else 
	{
		// - генерируем случайный адрес сокращения
		do 
		{
			//объявляем пустую переменую для случайного адреса
			$sh_url = "";
			//массив для генерации случайного адреса
			$array = array ('a','A','b','B','c','C','d','D','e','E','f','F','g','G','h','H','i','I','j','J','k','K','l','L','m','M','n','N','o','O','p','P',
							'q','Q','r','R','s','S','t','T','u','v','V','w','W','x','X','y','Y','z','Z','0','1','2','3','4','5','6','7','8','9');
			//цикл для генерации из 4 символов
			for ($x=0; $x<=3; $x++)
			{
				//берем случайное число и получаем случайный символ из массива 
				$i = rand(0, count($array)-1);
				//добавляем полученный символ в сокращение
				$sh_url .= $array[$i];
			}
			//проверим есть ли в списке сгенерированный ключ
			//загрузим список из файла
			$mass = file ('url.txt');
			for ($x=0;$x<=count($mass);$x++)
			{
				foreach (unserialize($mass[$x]) as $sh=>$u)
				{
					if ($sh_url == $sh) 
					{
						//если ключ есть - нужно будет ещё раз генерировать, вызовем повтор этого цикла
						$log = true;
					}
				}
			}
			//конец проверки
		}
		while($log);//конец цикла если ключа нет в списке, инече повтор
	}
	//конец если пользователь не ввел сокращение
		//запишем ссылку и ключ в файл
		$str= array ($sh_url => $url);
		$file = fopen('url.txt', 'a+');
		fwrite ($file, serialize($str).PHP_EOL);
		fclose ($file);
		//выведем на экран ссылку
		echo "<a href='http://".$_SERVER['SERVER_NAME']."?".$sh_url."'>".$_SERVER['SERVER_NAME']."/?".$sh_url."</a>";
}
?>
