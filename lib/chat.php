<?php	

class chat{
    public $user_login;
    public $text;
    public $date;
    public $sort;
    public $order;
    public $email;
    public $photo;
    public $chatid;
    public $finished;
    public $autoriselog;
    public $autoriselanguage;
    public $autoriserole;
	public $mysqlihost;
    public $mysqliusr;
    public $mysqlipwd;
    public $mysqlidb;
    public function __construct()
    {
		  $this->mysqlihost = 'localhost';
        $this->mysqliusr = 'root';
        $this->mysqlipwd = '';
        $this->mysqlidb = 'project';

        $submit = filter_input(INPUT_POST, 'submit');
        $name = filter_input(INPUT_POST, 'name');
        $text = filter_input(INPUT_POST, 'text');
        $email = filter_input(INPUT_POST, 'email');

        if(isset($submit)){
            $date=time();
            $mysqli = new mysqli($this->mysqlihost, $this->mysqliusr, $this->mysqlipwd, $this->mysqlidb);
            // Вытаскиваем из БД запись, у которой логин равняеться введенному
            $query = "INSERT INTO `chat` (`chat_id`, `user_login`, `text`, `dater`, `email`, `finished`) VALUES (NULL,?,?,?,?,'')";
            if ($stmt = $mysqli->prepare($query)) {
                // Запустить выражение
                $stmt->bind_param("ssss",$name,$text,$date,$email);
                $stmt->execute();
                $stmt->close();
            }
            // Закрыть соединение
            $mysqli->close();
            ?>
            <script> document.location.href = "/lib/redirr.php";</script>
            <?
        }


    }
        public function chat($sort='dater'){
    $pag=3;
    $page = filter_input(INPUT_GET, 'page');
    $page=$page+1;
    $end=$page*$pag;

    $mysqli = new mysqli($this->mysqlihost, $this->mysqliusr, $this->mysqlipwd, $this->mysqlidb);
    $query = "SELECT count(1) FROM `chat`";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->execute();
        $stmt->bind_result($counter);

        while ($stmt->fetch()) {
            sprintf("%s", $counter);
        }
        $stmt->close();
    }
    $mysqli->close();
    if($counter<$end){
        $end=$counter;
    }
    for($i=($page-1)*$pag;$i<$end;$i++) {
        $mysqli = new mysqli($this->mysqlihost, $this->mysqliusr, $this->mysqlipwd, $this->mysqlidb);
        $query = "SELECT `chat`.`chat_id`,`chat`.`user_login`,`chat`.`text`, `chat`.`dater`,`chat`.`email`,`chat`.`finished`,`users`.`photo`,`users`.`role`  FROM `chat` LEFT JOIN `users` ON `chat`.`user_login` = `users`.`user_login` ORDER BY ".$this->order." LIMIT ".$i.",1 ";
        $stmt = $mysqli->prepare($query);
            $stmt->execute();
            $stmt->bind_result($this->chatid,$this->user_login, $this->text,$this->dater,$this->email,$this->finished,$this->photo,$role);
            while ($stmt->fetch()) {
                sprintf("%s (%s)\n", $this->chatid,$this->user_login, $this->text,$this->dater,$this->email,$this->finished,$this->photo,$role);
            }
          
        $mysqli->close();
		if(strlen($this->photo)==0){
			$this->photo='files/misc/login.png';
		}
        if($this->finished=='yes'){
		    $fsd='<a style="color:green">&#10004</a>';
        }else{
            $fsd='<a style="color:red">x</a>';
        }
        $today = date("Y-m-d H:i:s",$this->dater);
		if($this->autoriserole=='admin'){
		    $edit='<a href="/lib/edit.php?cid='.$this->chatid.'">Править</a>';
        }
        if(!$this->autoriselanguage){

		    echo"
		    <div class='message'>
				<div class='hramka ttop'></div>
				<div class='midle ttop'>
		        <div class='ab bc'>
						
		            <img class='chimage' src='/lib/".$this->photo."'><br>
		            <div class='mm'>
		                 <a>Имя:$this->user_login</a><br>
		                <a>Почта:$this->email</a><br>
		                <a>Завершено:$fsd</a><br>
		                <a>Дата:$today </a><br>
		               $edit
                    </div>
		        </div>
		        <div class='ab nnn'>
		            
		               <a class='some'>$this->text</a>  
                    
		        
                </div>
							
            </div>
            </div>
		    ";
        }elseif ($this->autoriselanguage){
            echo"
		    <div class='message'>
							<div class='hramka ttop'></div>

		        <div class='ab bc'>
		            <img class='chimage' src='/lib/".$this->photo."'><br>
		            <div class='mm'>
		                 <a>Name:$this->user_login</a><br>
		                <a>Mail:$this->email</a><br>
		                <a>Order done:$fsd</a><br>
		                <a>Datetime:$today </a><br>
		                 $edit
                    </div>
		        </div>
		        <div class='ab nnn'>
		            
		               <a class='some'>$this->text</a>  
                    
		        
                </div>
            </div>
		    ";
        }
        }
    }






		 public function pagination(){
            $pag=3;
             $page = filter_input(INPUT_GET, 'page');

             $mysqli = new mysqli($this->mysqlihost, $this->mysqliusr, $this->mysqlipwd, $this->mysqlidb);
           $query = "SELECT count(1) FROM `chat`";
           if ($stmt = $mysqli->prepare($query)) {
               $stmt->execute();
               $stmt->bind_result($counter);

               while ($stmt->fetch()) {
                   sprintf("%s", $counter);
               }
               $stmt->close();
           }
           $mysqli->close();
		   $counter=ceil($counter/$pag);
		   if($counter<=6){
		   		for($i=1;$i<=$counter;$i++){
		   		    $ppage=$i-1;
		   			$a="
		   			<a class='atba' style='color:black;border: 1px solid red;' onClick=\"window . location . href = 'index.php?page={$ppage} '\">$i</a>
		   			";
					echo "$a";
		  		 }
		   }elseif($counter>6){
		   		for($i=$page-3;$i<=$page+2;$i++){
                    $ppage=$i-1;

                    $a="
		   			<a class='atba' style='color:black;border: 1px solid red;' onClick=\"window . location . href = 'index.php?page={$i} '\">$i</a>
		   			";
					echo "$a";
		  		 }
				echo "...<a class='atba' onClick=\"window . location . href = 'index.php?page={$counter} '\">$counter</a>";

		   }
			 
		 }
		 public function  atcdrawinput(){
        
            if(!$this->autoriselanguage) {

                $a = '
            <a class="сс">Оставить Задание</a>

            <form  method="POST">
            <a>Имя:</a><input name="name" width="200" type="text"><a>Почта:</a><input name="email"  type="text"> <br>
            <a>Ваше Задание</a><br>
			<textarea rows="10" cols="45" name="text"></textarea><br>
            <input name="submit" type="submit" value="Ввести">
            </form>';
            echo "$a";}else{
                $a = '
            <a class="сс">Add Order</a>

            <form  method="POST">
            <a>Name:</a><input name="name" type="text"> <a>Email:</a><input name="email" type="text"> <br>
            <a>Your message:</a><br>
			<textarea rows="10" cols="45" name="text"></textarea><br>
            <input name="submit" type="submit" value="Ввести">
            </form>';
                echo "$a";

            }
        
    }
		public function srt(){
			            if(!$this->autoriselanguage) {
			$a = '
            <form class="srt" method="POST">
							Сортировать По

            <input name="ulgch" type="submit" value="Имени">
            <input name="uemailch" type="submit" value="Почте">
            <input name="statusch" type="submit" value="Статусу">
            </form>';
            echo "$a";
									}else{
										
									$a = '
			
            <form  method="POST">
			Sort By
            <input name="ulgch" type="submit" value="Name">
            <input name="uemailch" type="submit" value="Email">
            <input name="statusch" type="submit" value="Status">
            </form>';
            echo "$a";	
										
									}
		}
}