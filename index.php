<?php
require_once 'init.php';
//投稿を取得
$db = connectDb();
$records = getRecord($db);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if ($_POST['file_name'] != ""){
    $file_name = $_POST['file_name'];

    if(!(isset($_POST['user_name']))){
      $user_name = '名無しさん';
    }else{
      $user_name = $_POST['user_name'];
    }

    $category = $_POST['category'];
    writeRecord($db, $user_name, $file_name, $category);
    // 2重投稿防止のためにリロードする処理
    header('Location: index.php');
    exit;
  }
}

if($_SERVER['REQUEST_METHOD'] == 'GET'){
  $records = getCategory($db,$_GET['cat']);
}



?>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DHacks</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
  <H1>D村のうわさ</h1>
    <H2>
      D村へようこそ!<p>
      D村のあれこれをタレコんでください。<p>
      なるべく明言は避け、語尾は「〜らしい」「〜っぽい」の形が推奨です。<p>
    </H2>


    <div class="checker">
      <ul class="menu" style="list-style:none;">
        <li class="menu__mega">
          <a href="#" class="init-bottom">カテゴリ</a>
          <ul class="menu__second-level" style="list-style:none;">
            <li>
<!-- 
              <input type="radio" name="s3" id="select1" value="1" checked="" href="localhost/dhacks/index.php?cat=couple">
              <label for="select1">カップル</label>
 -->
              <a href="index.php?cat=couple">カップル</a>
            </li>
            <li>
              <a href="index.php?cat=etiquette">エチケット</a>
            </li>
            <li>
              <a href="index.php?cat=gossip">噂</a>
            </li>
            <li>
              <a href="index.php?cat=r18">R18</a>
            </li>
            <li>
              <a href="index.php?cat=others">その他</a>
            </li>
          </ul>
        </li>
      </ul>
    </div>



    <div id="recorder" style="text-align: center;">
      <div style="clear: both; height: 200px;">
        <div class="btn1" id="record" style="float:left;" onclick="clickRecord()">
          <div class="mic"></div>
        </div>
        <div class="btn1" id="stop" style="float:left;" onclick="clickStop()">
         <div class="stop"></div>
       </div>
       <div class="btn1" id="play" style="float:left;" onclick="clickPlay()">
        <div class="play"></div>
      </div>
    </div>
    <div id="wami"></div>
    <div id="meter" style="width: 340px; height: 200px;"></div> <!--justgageはサイズ指定必須-->
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>
  <script src="recorder.js"></script>
  <script src="raphael-2.1.4.min.js"></script>
  <script src="justgage.js"></script>
  <script src="wami.js"></script>
  <form action="index.php" method="POST">
    <select name="category" style="color:black">
      <option value="couple">カップル</option>
      <option value="etiquette">エチケット</option>
      <option value="gossip">噂</option>
      <option value="r18">R18</option>
      <option value="others">その他</option>
    </select>
    <input type="text" id="userName" name="user_name" placeholder="投稿者名">
    <input type="hidden" name="file_name" value="">
    <button type="submit" class="btn btn-primary" id="post-button" onClick="fileReset()">投稿する</button>
  </form>




  <div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">
      <h3 class="panel-title">みんなの投稿</h3>
    </div>
    <!-- List group -->
    <ul class="list-group">
      <?php if (!$records): ?>
      <li class="list-group-item">
        <div class="container-fluid">
          <p class="text-center">
            <strong>投稿がありません。</strong>
          </p>
        </div>
      </li>
      <?php else: ?>
      <?php foreach ($records as $key => $value):
      try {
        $post_by = getRecordData($db, $value['id']);
      }catch (Exception $e) {
        print $e->getMessage();
      }
      ?>
      <?php if (isset($post_by)): ?>
      <li class="list-group-item">
        <div class="container-fluid" style="text-align:center;">
          <h3 style="color:black">投稿者：<?php echo escape($value['user_name']) ?></h3>
          <!-- 投稿ファイルネーム -->

          <div class="btn1" id="on_air"  onclick="clickOnAir('<?php echo $post_by['name'] ?>')">
            <div class="play"></div>
          </div>

          <!-- カテゴリ -->
          <h3 style="color:black">カテゴリ：<?php print escape($value['category']) ?></h3>
          <!-- 投稿日時 -->
          <p class="small" style="color:black"><?php print $value['created_at'] ?></p>
          <!--<p class="text-right">
            <button type="button" class="btn btn-primary reply-btn">
              <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true" ></span>　いいね
            </button>
            <?php echo $post_by['good'] ?>
            <button type="button" class="btn btn-danger reply-btn" name="delete_post">
              <span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span>　駄作
            </button>
            <?php echo $post_by['bad'] ?>
          </p>
        -->
        </div>
      </li>
      <?php endif; ?>
      <?php endforeach; ?>
      <?php endif; ?>
    </ul>
  </div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>


<script type="text/javascript">
$(function(){
  $("#record").click(function(){
    $('#record').css('visibility', 'hidden');
    $('#stop').css('visibility', 'visible');
    $('#play').css('visibility', 'hidden');
  });
});

$(function(){
  $("#stop").click(function(){
    $('#record').css('visibility', 'visible');
    $('#stop').css('visibility', 'hidden');
    $('#play').css('visibility', 'visible');
  });
});

</script>

</body>
</html>