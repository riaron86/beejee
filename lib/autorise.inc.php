<?php
class autorise{
    public $ssid;
    public $triger;
    public $bsehash;
    public $usrdate;
    public $mysqlihost;
    public $mysqliusr;
    public $mysqlipwd;
    public $mysqlidb;
    public $submit;
    public $ilogin;
    public $ipassword;
    public $chckuid;
    public $chckuip;
    public $chckhash;
    public $language;
    public  $err;
	public $label;
	public $ru;
	public $exxit;
	public $eng;
	public $phon;
	public $picture;
	public $log;
	public $gen;
	public $about;
	public $age;
	public $role;
	public $messager=array();
	public $messageeng=array();
	public $order;

	public function __construct()
    {

        $this->chckhash = filter_input(INPUT_COOKIE, 'hash');
        $this->chckuid = filter_input(INPUT_COOKIE, 'id');
        $this->triger = filter_input(INPUT_COOKIE, 'triger');
        $this->language = filter_input(INPUT_COOKIE, 'language');
        $this->server = filter_input(INPUT_SERVER, 'REMOTE_ADDR');
        $this->usrdate = date("Y.m.d H:i:s");
     $this->mysqlihost = 'localhost';
        $this->mysqliusr = 'f0344877_project';
        $this->mysqlipwd = 'password';
        $this->mysqlidb = 'f0344877_project';
        $this->err = array();
        $this->submit = filter_input(INPUT_POST, 'submit');
        $this->exxit = filter_input(INPUT_POST, 'exxit');
        $this->eng = filter_input(INPUT_POST, 'eng');
        $this->ru = filter_input(INPUT_POST, 'ru');
        $this->ilogin = filter_input(INPUT_POST, 'login');
        $this->ipassword = filter_input(INPUT_POST, 'password');
		///////////////////////////////////////////////////
        $statuch = filter_input(INPUT_POST, 'statusch');
        $uemail = filter_input(INPUT_POST, 'uemailch');
        $ulg = filter_input(INPUT_POST, 'ulgch');
		$stnp = filter_input( INPUT_COOKIE, 'stnp');
		$lgnp = filter_input( INPUT_COOKIE, 'lgnp');
		$emnp = filter_input( INPUT_COOKIE, 'emnp');
        $sort = filter_input( INPUT_COOKIE, 'sort');
       
			if(isset($ulg)){
				if($lgnp=='ASC'){
                    setcookie("sort", "ulg", time()+60 * 60 * 24 * 30);
                       setcookie("lgnp", "DESC", time()+60 * 60 * 24 * 30);
                   }elseif($lgnp=='DESC'){
                    setcookie("sort", "ulg", time()+60 * 60 * 24 * 30);
                       setcookie("lgnp", "ASC", time()+60 * 60 * 24 * 30);
                   }
			}elseif($uemail){
				if($emnp=='ASC'){
                    setcookie("sort", "uemail", time()+60 * 60 * 24 * 30);
                       setcookie("emnp", "DESC", time()+60 * 60 * 24 * 30);
                   }elseif($emnp=='DESC'){
                    setcookie("sort", "uemail", time()+60 * 60 * 24 * 30);
                       setcookie("emnp", "ASC", time()+60 * 60 * 24 * 30);
                   }
			}elseif($statuch){
				if($stnp=='ASC'){
                    setcookie("sort", "statuch", time()+60 * 60 * 24 * 30);
                       setcookie("stnp", "DESC", time()+60 * 60 * 24 * 30);
                   }elseif($stnp=='DESC'){
                    setcookie("sort", "statuch", time()+60 * 60 * 24 * 30);
                       setcookie("stnp", "ASC", time()+60 * 60 * 24 * 30);
                   }
			}
				if(!$sort){
                    $this->order='dater';
				}elseif($sort=='ulg'){
                    $this->order='`chat`.`user_login` '.$lgnp.'';
				}elseif($sort=='uemail'){
                    $this->order='`chat`.`email` '.$emnp.'';
				}elseif($sort=='statuch'){
                    $this->order='`users`.`role` '.$stnp.'';
				}
        if(isset($this->eng)){
            setcookie("language", "true", time()+60 * 60 * 24 * 30);
            header("Refresh: 0");
        }elseif($this->ru){
            setcookie("language", "", time()+60 * 60 * 24 * 30);
            header("Refresh: 0");}
			if(isset($this->role)){
            setcookie("role", $this->role, time()+60 * 60 * 24 * 30);
            header("Refresh: 0");
        }
        if(isset($this->submit)){
            $mysqli = new mysqli($this->mysqlihost, $this->mysqliusr, $this->mysqlipwd, $this->mysqlidb);
            $query = "SELECT user_id, user_password FROM users WHERE user_login=? LIMIT 1";
            if ($stmt = $mysqli->prepare($query)) {
                $stmt->bind_param("s", $this->ilogin);
                $stmt->execute();
                $stmt->bind_result($this->ssid, $this->password);
                while ($stmt->fetch()) {
                    sprintf("%s (%s)\n", $this->ssid, $this->password);
                }
                $stmt->close();
            }
            $mysqli->close();
            if(!$this->ssid or !$this->password){
                $this->messager[]='Введите корректный логин';
                $this->messageeng[]='Enter correct login';
            }
            if($this->password == md5( md5($this->ipassword))){

                $hash=$this->generateCode(10);
                $this->addtocoookie($this->ssid,$hash);
                $this->addtodb($hash);
                $this->triger=true;
                setcookie("triger", $this->triger, time() + 60 * 60 * 24 * 30);
                header("Refresh:0");
            }else{
                $this->ttriger=false;
                setcookie("triger", $this->triger, time() + 60 * 60 * 24 * 30);

            }
        }
		if(isset($this->exxit)){ 
		 $this->clearcoookie();
				$this->triger=false;
                setcookie("triger", $this->triger, time() + 60 * 60 * 24 * 30);
				
		}

    }

    public function drawinput(){

	        if (!$this->language) {
                $b = 'Логин';$c = 'Пароль';$d='Войти';
            }else{
                $b = 'Login'; $c = 'Password';$d='Sign In';
            }

        if ($this->triger == false) {
           //////////////////////////
            $a = '<div style="margin-left:650px ">
        <form method="POST">
			<table>
				<tr>
					<td rowspan="2"><img src="\lib\files\misc\login.png" style="height:50px;width:50px;"></td>
					<td>'.$b.'</td>
					<td><input name="login" type="text" required></td>
				</tr>
				<tr>
					<td>'.$c.'</td>
					<td><input name="password" type="password" required></td>
				</tr>
				<tr>
					<td><input name="submit" type="submit" value="'.$d.'"></td>
					<td></td>
				</tr>
			</table>
            
            
        </form></div>';
            echo $a;
         $this->err[] = 'drawinput <br>';
        }elseif($this->triger == true){
            $mysqli = new mysqli($this->mysqlihost, $this->mysqliusr, $this->mysqlipwd, $this->mysqlidb);
            $query = "SELECT user_login,phon,photo,gender,about,age,role FROM users WHERE user_id=? LIMIT 1";
            if ($stmt = $mysqli->prepare($query)) {
                $stmt->bind_param("s", $this->chckuid);
                $stmt->execute();
                $stmt->bind_result($this->log,$this->phon,$this->picture,$this->gen,$this->about,$this->age,$this->role);
                while ($stmt->fetch()) {
                    sprintf("%s (%s)\n", $this->log,$this->phon,$this->picture,$this->gen,$this->about,$this->age,$this->role);
                }
                $stmt->close();
            }
            $mysqli->close();
        if (!$this->language) {
            echo "<div class='my'>Здравствуйте $this->log!!!<br>
            <img src='lib/".$this->picture."' style='height:150px;width:150px'><br>
            <img src='lib/files/misc/phone.png' style='height:12px;width:12px'>Ваш телефон:".$this->phon."<br>
            <img src='lib/files/misc/gn.jpg' style='height:12px;width:12px'>Пол:".$this->gen."<br>
            О себе:".$this->about."<br>
            Возраст:".$this->age."<br>
			<form method='post'>
						<input type='submit' name='exxit' Value='Выход'>
			</form></div>

            ";
        }else {
            echo "<div class='my'> Hello $this->log!!!<br>
            <img src='lib/".$this->picture."' style='height:150px;width:150px'><br>
           <img src='lib/files/misc/phone.png' style='height:12px;width:12px'> Your phone:".$this->phon."<br>
			<img src='lib/files/misc/gn.jpg' style='height:12px;width:12px'>	Gender:".$this->gen."<br>
            About:".$this->about."<br>
            Age".$this->age."<br>
			<form method='post'>
				<input type='submit' name='exxit' Value='Exit'>
			</form></div>

            ";
        } /* */

	    }

//////////////////////////////////////////
    }


    // Функция для генерации случайной строки
    public function generateCode($length = 6)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHI JKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;
        while (strlen($code) < $length) {
            $code .= $chars[mt_rand(0, $clen)];
        }
        return $code;
        $this->err[]='genercode <br>';

    }
    public function addtodb($hash)
    {            // Записываем в БД новый хеш авторизации и IP
        $mysqli = new mysqli($this->mysqlihost, $this->mysqliusr, $this->mysqlipwd, $this->mysqlidb);
        // Вытаскиваем из БД запись, у которой логин равняеться введенному
        $query = "UPDATE users SET user_hash= ? WHERE user_id= ?";
        if ($stmt = $mysqli->prepare($query)) {
            // Запустить выражение
            $stmt->bind_param("ss",$hash,$this->ssid);
            $stmt->execute();
            $stmt->close();
        }
        // Закрыть соединение
        $mysqli->close();
        $this->err[]='addtodb <br>';
    }
    public function addtocoookie($id,$hs){
        setcookie("id", $id, time() + 60 * 60 * 24 * 30);
        setcookie("hash", $hs, time() + 60 * 60 * 24 * 30);
        $this->err[]='addtoc';
    }
    public function clearcoookie(){
        setcookie("id", "", time()-60 * 60 * 24 * 30);
        setcookie("hash", "", time() -60 * 60 * 24 * 30);
        $this->err[]='clearc <br>';

    }
    public function addtousers(){

    }

    public function check(){
        if($this->chckhash and $this->chckuid and $this->chckhash!=0 and $this->chckuid!=0){
            $mysqli = new mysqli($this->mysqlihost, $this->mysqliusr, $this->mysqlipwd, $this->mysqlidb);
            $query = "SELECT user_hash FROM users WHERE user_id=? LIMIT 1";
            if ($stmt = $mysqli->prepare($query)) {
                $stmt->bind_param("s", $this->chckuid);
                $stmt->execute();
                $stmt->bind_result($this->bsehash);
                while ($stmt->fetch()) {
                    sprintf("%s", $this->bsehash);
                }
                $stmt->close();
            }
            $mysqli->close();
            if($this->chckhash == $this->bsehash){
                $this->triger=true;
                setcookie("triger", $this->triger, time() + 60 * 60 * 24 * 30);

            }else{
                $this->clearcoookie();
                $this->triger=false;
                setcookie("triger", $this->triger, time() + 60 * 60 * 24 * 30);

            }
        }
    }
	public function drawlg(){

        $a = 'Language/Язык
        <form method="POST">
           <input name="eng" type="submit" value="English">
		   <input name="ru" type="submit" value="Русский">
        </form>
		';
            echo $a;

	}
    public function ainitialize()
    {

        $this->addtousers();
        $this->check();

        $this->drawlg();
        $this->drawinput();
        if (!$this->language) {
            foreach ($this->messager as $msg) {
                echo "<p><img src='lib/files/misc/x.png' style='width: 10px; height: 10px;'>$msg </p>";
            }
        } elseif ($this->language) {
            foreach ($this->messageeng as $msg) {
                echo "<p><img src='lib/files/misc/x.png' style='width: 10px; height: 10px;'>$msg </p>";
            }
        }





    }


}
