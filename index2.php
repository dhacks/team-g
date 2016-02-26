<?php
require_once 'init.php';
//投稿を取得
$db = connectDb();
$records = getRecord($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if ($_POST['file_name'] != ""){
    $user_name = $_POST['user_name'];
    $file_name = $_POST['file_name'];
    $category = $_POST['category'];
    writeRecord($db, $user_name, $file_name, $category);
    // 2重投稿防止のためにリロードする処理
    header('Location: index.php');
    exit;
}
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <title>D村のうわさ - DHacks 2015 winter</title>
    <meta charset="UTF-8" />
    <LINK rel="stylesheet" type="text/css" href="style.css">
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    </head>
    <body>
        <ul class="menu">
            <li class="menu__mega">
                <a href="#" class="init-bottom">Menu mega</a>
                <ul class="menu__second-level">
                    <li><a href="#">Child Menu</a></li>
                    <li><a href="#">Child Menu</a></li>
                    <li><a href="#">Child Menu</a></li>
                    <li><a href="#">Child Menu</a></li>
                    <li><a href="#">Child Menu</a></li>
                </ul>
            </li>

        </ul>


        <div class="btn1" id="record" onclick="clickRecord()">
            <div class="mic"></div>
        </div>
        <div class="btn1"id="stop" onclick="clickStop()">
         <div class="play"></div>
     </div>
     <div class="btn1" id="play" onclick="clickPlay()">
        <div class="search"></div>
        <div id="wami"></div>
        <div id="meter"></div> 
    </div>
</body>
</html>

