<html>
<head>
    <meta charset='UTF-8'>
    <title>GUESTBOOK</title>
</head>
<body>
<?php
	//путь до папки абсолютный (константа __DIR__)
	$folderName = __DIR__.'/messages';

//Если папки с таким именем не существует, то создаём новую
	if (!is_dir($folderName)) mkdir($folderName); // is_dir — определяет, является ли имя файла каталогом, mkdir — создаёт каталог
	$messages = scandir($folderName); //scandir передает имена файлов из папки

//Отображение всех существующих сообщений в папке messages
	$count = count($messages); //count — подсчитывает количество элементов массива 
	for ($i = 2; $i < $count; $i++) { // исключаем специальные управляющие символы "." (точка) и ".." (две точки)
		$currentFileName = $messages[$i];
		$arrTextMessage = file("messages/$currentFileName"); //получает содержимое файла в виде массива

		$name = htmlspecialchars($arrTextMessage[0]); //htmlspecialchars — преобразует специальные символы в HTML-сущности
		$mail = htmlspecialchars($arrTextMessage[1]);
		echo $name .'<br/>'; 
		echo "<a href='mailto:$mail'>$mail</a>".'<br/>';

		for ($j = 2; $j < count($arrTextMessage); $j++) { //3-я и последующие строки - текст сообщения
			echo htmlspecialchars($arrTextMessage[$j]);
			echo '<br/>';
		}
		echo '<hr/>';
    }

//Формы ввода: имя, мыло, текст
    echo '<form action="index.php" method="post">
	 <p>Your Name: <input type="text" name="name" /></p>
	 <p>Your Email: <input type="email" name="email" /></p>
	 <textarea name="message"></textarea>
	 <p><input type="submit" /></p>
	</form>';
	$name1 = $_POST['name']; //принимает значение name через POST
	$email1 = $_POST['email'];
	$message1 = $_POST['message'];

//Запись нового сообщения
	if ($name1 && $email1 && $message1 && filter_var($email1, FILTER_VALIDATE_EMAIL)) { // возвращает отфильтрованные данные или false, если фильтрация завершилась неудачей.
		$time = date('d.m.Y_h.i.s'); //вывод системной даты/времени
		$newFile = "messages/$time.txt";
		$textNewFile = "$name1\n$email1\n$message1";
		file_put_contents($newFile, $textNewFile); //файл будет создан, если его нет; запись данных в указанный файл

		echo htmlspecialchars($name1).'<br/>';
		echo "<a href='mailto:$email1'>$email1</a>".'<br/>';
		echo htmlspecialchars($message1) .'<br/>';
	} else {
		echo 'Error while adding your message!';
	}
//НЕИСПРАВНОСТЬ: При обновлении страницы новое сообщение дублируется
?>
</body>
</html>