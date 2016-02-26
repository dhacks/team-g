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
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DHacks</title>
  <link rel="stylesheet" type="text/css" href="style.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
</head>
<body>



  <div id="recorder" style="text-align: center;">
    <div style="clear: both; height: 200px;">
      <div class="btn1" id="record" style="float:left;" onclick="clickRecord()">
        <div class="mic"></div>
      </div>
      <div class="btn1" id="stop" style="float:left;" onclick="clickStop()">
       <div class="play"></div>
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
  <select name="category">
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
  } catch (Exception $e) {
    print $e->getMessage();
  }
  ?>
  <?php if (isset($post_by)): ?>
  <li class="list-group-item">
    <div class="container-fluid">
      <h3>投稿者：<?php print escape($value['user_name']) ?></h3>
      <!-- 投稿ファイルネーム -->
        <p class="small text-muted reply-to">@<?php echo escape($post_by['name']) ?></p>

    <div class="btn1" id="on_air" style="float:left;" onclick="clickOnAir('<?php echo $post_by['name'] ?>')">
      <div class="play"></div>
    </div>

        <!-- カテゴリ -->
        <p>カテゴリ：<?php print escape($value['category']) ?></p>
        <!-- 投稿日時 -->
        <p class="small"><?php print $value['created_at'] ?></p>
        <!-- 返信・削除ボタン -->
        <p class="text-right">
          <button type="button" class="btn btn-primary reply-btn">
          <span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span>　いいね
        </button>
        <?php echo $post_by['good'] ?>
        <button type="button" class="btn btn-danger reply-btn" name="delete_post">
        <span class="glyphicon glyphicon-thumbs-down" aria-hidden="true"></span>　駄作
      </button>
        <?php echo $post_by['bad'] ?>
    </p>
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