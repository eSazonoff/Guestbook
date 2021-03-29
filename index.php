<html>
<head>
    <meta charset='UTF-8'>
    <title>GUESTBOOK</title>
</head>
<body>
<?php
	$folderName = __DIR__.'/messages';

	if (!is_dir($folderName)) {
		mkdir($folderName);
	}

	$messages = scandir($folderName);

//Данные формы принимаютя до отображения сообщения. Значит получая новое сообщение, сперва сохраняем его, а потом выводим все сообщения из папки, включая новые.

//Проверка данных получаемых формой
if (isset($_POST['submit'])) {
	$author = isset($_POST['author']) ? trim($_POST['author']) : ''; //удаляем пробелы из строки, иначе пустая строка
	$email = isset($_POST['email']) ? trim($_POST['email']) : '';
	$message = isset($_POST['message']) ? trim($_POST['message']) : '';

	if (strlen($author) > 0 && strlen($email) > 0 && strlen($message) > 0 && filter_var($email, FILTER_VALIDATE_EMAIL) !== false) {
		$time = date('d.m.Y_h.i.s');
		$save_file = $folderName . '/' . $time;
		$save_data = "{$author}\n{$email}\n{$message}";
		file_put_contents($save_file, $save_data);
	} else {
		echo 'Error while adding your message.' . '<br/>';
	}
}

//Отображение всех существующих сообщений в папке messages
$messages = scandir($folderName);
$count = count($messages);

for ($i = 2; $i < $count; $i++) { //исключаем управляющие символы каталога, занимающие первые две ячейки
	$currentFileName = $messages[$i];
	$arrTextMessage = file("{$folderName}/" . $currentFileName);

	$author = htmlspecialchars($arrTextMessage[0]);
	$mail = htmlspecialchars($arrTextMessage[1]);

	echo $author . '<br/>';
	echo "<a href='mailto:$mail'>mailto:$mail</a>" . '<br/>';

	for ($j = 2; $j < count($arrTextMessage); $j++) {
		echo htmlspecialchars($arrTextMessage[$j]) . '<br/>';
	}

	echo '<hr/>';
}

//Форма ввода данных: имя, почта, сообщение
echo '<form action="index.php" method="post">
	<p>Your Name: <input type="text" name="author" /></p>
	<p>Your Email: <input type="email" name="email" /></p>
	<textarea name="message"></textarea>
	<p><input name="submit" type="submit" /></p>
	</form>';
?>
</body>
</html>