<?php
include 'lib/autorise.inc.php';
include 'lib/chat.php';
$autorise=  new autorise;
$chat=new chat;
$chat->order=$autorise->order;

?>
<html>
<head>
    <title>my script</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/main.css" >
</head>
<body style="background-image: url(/lib/files/misc/background.jpg 100% 100% no-repeat);">

    <div class="header">
        <?php $autorise->ainitialize();?><br>
							<?php $chat->srt();?>
				
    </div>
	<div class="container">
		<div>
			<a href='lib\register.inc.php' class="reg"><? if(!$autorise->language){echo 'Зарегистрироватся';}elseif($autorise->language==true){echo 'Register';}?></a>
        </div>
		<?
            $chat->autoriselanguage=$autorise->language;
            $chat->autoriselog=$autorise->log;
            $chat->autoriserole=$autorise->role;
            ?><div style="margin-left: 10%;margin-top: 2%"><?
            $chat->chat();
            ?><div style="margin-top:10px;margin-bottom:10px;margin-left:40%;"><?
            $chat->pagination();
                            ?></div><?
            $chat->atcdrawinput();

                ?></div>


</div>
    <div class="footer">

            
        <div class="contacts nnn"><a>Телефон:(499)7777777</a><br>
            <a>Email:site@mysite.local.ru</a><br>
            <a>	&copy; 2010-2019 Mysite. Все права защищены</a><br>
        </div>


    </div>
</body>
</html>
