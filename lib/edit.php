<?
 $cid = filter_input(INPUT_GET, 'cid');
 $submit = filter_input(INPUT_POST, 'submit');
 $name = filter_input(INPUT_POST, 'name');
 $email = filter_input(INPUT_POST, 'email');
 $text = filter_input(INPUT_POST, 'text');
 $finished = filter_input(INPUT_POST, 'finished');
if(isset($submit)){
	$text="<a style='color: green;'".$text.'</a>Отредактировано Администратором';
	 $mysqli = new mysqli('localhost', 'root', '', 'project');
            $query = "UPDATE `chat` SET `user_login`=?,`email`=?,`text`=?,`finished`=? WHERE `chat_id`=?";
            if ($stmt = $mysqli->prepare($query)) {
                $stmt->bind_param("ssssi", $name,$email,$text,$finished,$cid);
                $stmt->execute();
				$stmt->close();
			}
			
            $mysqli->close();
}
 $mysqli = new mysqli('localhost', 'root', '', 'project');
            $query = "SELECT `user_login`, `email`,`text` FROM `chat` WHERE `chat_id`=? ";
            if ($stmt = $mysqli->prepare($query)) {
                $stmt->bind_param("i", $cid);
                $stmt->execute();
                $stmt->bind_result($login, $email,$text);
                while ($stmt->fetch()) {
                    sprintf("%s (%s)\n",$login, $email,$text);
                }
                $stmt->close();
            }
            $mysqli->close();

			?>
            <div style="margin-left: 35%">
			<form  method="POST">
            Имя:<input name="name" type="text" value='<?echo $login?>'><a>Ваша Почта:</a><input name="email" type="text" value="<? echo $email;?>"> <br>
            <a>Ваш комментарий</a><br>
			<textarea rows="10" cols="45" name="text"><? echo $text;?></textarea><br>
                <select name="finished">
                    <option value="yes">Завершено</option>
                    <option value="">Не завершено</option>
                </select>
            <input name="submit" type="submit" value="Изменить">
            </form>
            </div>
			