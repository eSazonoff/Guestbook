<html>
<head>
    <meta charset='UTF-8'>
    <title>GUESTBOOK</title>
</head>
<body>
<?php
	$folderName = __DIR__.'/messages'; //абсолютный путь к папке

//Если папки с таким именем не существует, то создаём новую
	if (!is_dir($folderName)) mkdir($folderName); // is_dir — определяет, является ли имя файла каталогом, mkdir — создаёт каталог
	$messages = scandir($folderName); //scandir передает имена файлов из папки

	//ПРИНЯТИЕ ФОРМЫ И СОХРАНЕНИЕ СООБЩЕНИЙ В ПАПКЕ
//данные формы принимаю до того как отобразятся сообщения. То есть получая новое сообщение, сперва сохраняем его, а потом выводим все сообщения включая новые.

$error_info = '';//сообщение об ошибке

//Проверка формы на submit
if(isset($_POST['submit']))
{
	$name = isset($_POST['name']) ? trim($_POST['name']) : '';
	$email = isset($_POST['email']) ? trim($_POST['email']) : '';
	$message = isset($_POST['message']) ? trim($_POST['message']) : '';

	if(strlen($name) > 0 && strlen($email) > 0 && strlen($message) > 0 && filter_var($email, FILTER_VALIDATE_EMAIL) !== false) //фильтр-проверка на правильность и наличие вводимых пользователем данных
	{
		$time = date('d.m.Y_h.i.s'); //вывод системной даты/времени
		
		$save_file = $messages.'/'.$time;
		$save_data = "{$name}\n{$email}\n{$message}";
		file_put_contents($save_file, $save_data);
	}
	else
	{
		$error_info = "<p>Error while adding your message.</p>";
	}
}

echo $error_info;

//Отображение всех существующих сообщений в папке messages
	$count = count($messages); //count — подсчитывает количество элементов массива 
	for ($i = 2; $i < $count; $i++) { // исключаем специальные управляющие символы "." (точка) и ".." (две точки)
		$currentFileName = $messages[$i];
		$arrTextMessage = file("{$messages}/".$currentFileName); //получает содержимое файла в виде массива

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
	 <p><input name="submit" type="submit" /></p>
	</form>';

//НЕИСПРАВНОСТЬ: При обновлении страницы новое сообщение дублируется
?>
</body>
</html>