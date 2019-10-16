<?php
// Страница регистрации нового пользователя

// Соединямся с БД
$lang = filter_input(INPUT_COOKIE, 'language');
$submit = filter_input(INPUT_POST, 'submit');

if (!$lang) {
    $b = 'Логин';$c = 'Пароль';$d='Телефон';$f='Аватар';$g='Мужской';$j='Жеский';$h='О себе';$i='Возраст';$k='Главная';
}else{
    $b = 'Login'; $c = 'Password';$d='phone';$f='Avatar';$g='Male';$j='Female';$h='About yourself';$i='Age';$k='Homepaage';
}
function generateCode($length = 6)
{
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
    $code = "";
    $clen = strlen($chars) - 1;
    while (strlen($code) < $length) {
        $code .= $chars[mt_rand(0, $clen)];
    }
    return $code;
    $err[]='genercode <br>';

}
if(isset($submit))
{
	$login = filter_input(INPUT_POST, 'login');
	$gender = filter_input(INPUT_POST, 'gender');
	$about = filter_input(INPUT_POST, 'about');
	$age = filter_input(INPUT_POST, 'age');
	$email = filter_input(INPUT_POST, 'email');
    $phone = filter_input(INPUT_POST, 'phone');
    $password = filter_input(INPUT_POST, 'password');
    $tmp_name=$_FILES['picture']['tmp_name'];
    $type=$_FILES['picture']['type'];
    $file = 'path/to/image.jpg';
    $image_mime = image_type_to_mime_type(exif_imagetype($tmp_name));
    $array=['image/gif','image/jpeg','image/png','image/png'];
    foreach($array as $arrays){
        if($arrays==$image_mime){
            $alpha[]=$arrays;
        }
    }
    if (is_uploaded_file($_FILES['picture']['tmp_name'])) {
        $name=md5(generateCode(10)).$_FILES['picture']['name'];
        $photo='files/pics/'.$name;
    }
    $errru = [];
    $erreng = [];
$error=[];

    preg_match('/^(\s*)?(\+)?([- _():=+]?\d[- _():=+]?){10,14}(\s*)?$/', $phone,$matches);

    // проверям логин
    if(!preg_match("/^[a-zA-Z0-9]+$/",$_POST['login']))
    {
      $err[] = "Логин может состоять только из букв английского алфавита и цифр";
      $erreng[] = "Login must contain english letters and numbers";
    }

    if(strlen($login) < 3 or strlen($login) > 30)
    {
         $err[] = "Логин должен быть не меньше 3-х символов и не больше 30";
		       $erreng[] = "login must be over 3 and 30 symbols";

    }
if((is_array($matches)and count($matches)<=0) or(!is_array($matches)))
    {
         $err[] = "Тетефон должен быть как +79686536542 или 89686536542";
		       $erreng[] = "phone must be like +79686536542 or 89686536542";

    }
    if((is_array($alpha)and count($alpha)<=0) or(!is_array($alpha)))
    {
         $err[] = "Выберите формат Изображения только png,gif,jpeg";
		       $erreng[] = "Choose image format png,gif,jpes";

    }
    // проверяем, не сущестует ли пользователя с таким именем
    $mysqli = new mysqli('localhost', 'root', '', 'project');
    $query = "SELECT user_id FROM users WHERE user_login=? LIMIT 1";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("s", $login);
        $stmt->execute();
        $stmt->bind_result($log);
        while ($stmt->fetch()) {
            sprintf("%s", $log);
        }
        $stmt->close();
    }
    $mysqli->close();
    if(strlen($log) > 0)
    {
        $err[] = "Пользователь с таким логином уже существует в базе данных";
		      $erreng[] = "User with same name is exists in database";

    }

    // Если нет ошибок, то добавляем в БД нового пользователя

    if((is_array($err) and count(err)<=0) or (!is_array($err)))
    {

    // Убераем лишние пробелы и делаем двойное хеширование
    $password = md5(md5($password));
        $mysqli = new mysqli('localhost', 'root', '', 'project');
        // Вытаскиваем из БД запись, у которой логин равняеться введенному
        $query = "INSERT INTO `users` (`user_id`, `user_login`, `user_password`, `user_hash`, `phon`,`photo`,`gender`,`about`,`age`) VALUES (NULL, ?, ?, '',?,?,?,?,?);";
        if ($stmt = $mysqli->prepare($query)) {
            // Запустить выражение
            $stmt->bind_param("ssssssi",$login,$password,$phone,$photo,$gender,$about,$age);
            $stmt->execute();
            $stmt->close();
        }
        // Закрыть соединение
        $mysqli->close();

        move_uploaded_file($tmp_name, "$photo");
        echo "<img src='files/misc/v.png' style='height: 10px;width: 10px'><p>Вы успешно зарегистрированы</p>";
    }
    else
    {
        if(!$lang){
            $erro=$err;
        }else{
            $erro=$erreng;
        }
    echo "<b>При регистрации произошли следующие ошибки:</b><br>";
    foreach($erro AS $error)
    {
         echo "<img src='files/misc/x.png' style='height:10px;width=10px'> <p style='color:red'>".$error."</p><br>";
    }
    }
}
?>

<div style="border:1px solid black;width:300px;background-image:linear-gradient(to top, #fefcea, #f1da36);">
<div style="padding:20px";>
<a href="../index.php"><?=$k?></a>
<form enctype="multipart/form-data" method="POST">

<table>
	<tr>
		<td><?=$b?></td>
		<td><input name="login" type="text" required><br></td>
	</tr>
	<tr>
		<td><?=$c?></td>
		<td><input name="password" type="password" required></td>
	</tr>
	<tr>
		<td><?=$d?></td>
		<td><input name="phone" type="text" required></td>
	</tr>
	<tr>
		<td><?=$f?></td>
		<td><input name="picture" type="file" ></td>
	</tr>
	<tr>
		<td><?=$g?></td>
		<td><select name='gender'>
				<option value='m'><?=$g;?></option>
				<option value='f'><?=$j;?></option>
			</select></td>
	</tr>	
	<tr>
		<td><?=$h?></td>
		<td><textarea name="about" ></textarea></td>
	</tr>	
	<tr>
		<td><?=$i?></td>
		<td><input name="age" type="text" ></td>
	</tr>


</table>    
	<input name="submit" type="submit">
</form>
</div></div>