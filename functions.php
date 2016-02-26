<?php
function connectDb(){
	try{
		return new PDO(DSN,DB_USER,DB_PASSWORD);
	}catch(PDOException $e){
		print $e->getMessage();
		exit;
	}
}

function getRecord($pdo)
{

  $sql = 'SELECT * FROM records ORDER by `created_at` DESC';
  $statement = $pdo->prepare($sql);
  $statement->execute();

  if ($rows = $statement->fetchAll(PDO::FETCH_ASSOC)) {
    return $rows;
  }else {
    return false;
  }
}

function getCategory($pdo,$category)
{

  $sql = 'SELECT * FROM records WHERE category=:category ORDER by `created_at` DESC';
  $statement = $pdo->prepare($sql);
  $statement->bindValue(':category', $category, PDO::PARAM_STR);
  $statement->execute();

  if ($rows = $statement->fetchAll(PDO::FETCH_ASSOC)) {
    return $rows;
  }else {
    return false;
  }
}

function getRecordData($pdo,$id)
{
  $sql = 'SELECT * FROM records WHERE id=:id';
  $statement = $pdo->prepare($sql);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();

    if ($row = $statement->fetch()) {
        return $row;
    } else {
        throw new Exception('ユーザデータを取得できません');
    }
}

function writeRecord($db,$user_name,$file_name,$category)
{
  $sql = 'INSERT INTO records (name,user_name,category) VALUES (:file_name,:user_name,:category)';
  $statement = $db->prepare($sql);
  $statement->bindValue(':user_name', $user_name, PDO::PARAM_STR);
  $statement->bindValue(':file_name', $file_name, PDO::PARAM_STR);
  $statement->bindValue(':category', $category, PDO::PARAM_STR);
  $statement->execute();
}

/*
function getFileName()
{
  require 'save.php';
  $file_name = $name;
  return $file_name;
}*/


























function getUserId($email, $password, $db) {
  $sql = "SELECT id, password FROM users WHERE email = :email";
  $statement = $db->prepare($sql);
  $statement->bindValue(':email', $email, PDO::PARAM_STR);
  $statement->execute();
  $row = $statement->fetch();
  if (password_verify($password, $row['password'])) {
    return $row['id'];
  } else {
    return false;
  }
}

function escape($s) {
  return htmlspecialchars($s, ENT_QUOTES, "UTF-8");
}

function isSignin()
{
    if (!isset($_SESSION['user_id'])) {
        return false;
    } else {
        return true;
    }
}

function getUserData($pdo,$id)
{
  $sql = 'SELECT * FROM users WHERE id=:id';
  $statement = $pdo->prepare($sql);
  $statement->bindValue(':id',$id,PDO::PARAM_INT);
  $statement->execute();

  if($row = $statement->fetch()){
    return $row;
  }else{
    throw new Exception('ユーザデータを取得できません');
  }
}

function writePost(PDO $pdo, $id, $text)
{
    $replyUserId = getReplyId($pdo, $text);
    $sql = 'INSERT INTO posts (user_id,in_reply_to_user_id,text) VALUES (:user_id, :reply_user_id, :text)';
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':user_id', $id, PDO::PARAM_INT);
    $statement->bindValue(':reply_user_id', $replyUserId, PDO::PARAM_INT);
    $statement->bindValue(':text', $text, PDO::PARAM_STR);
    $statement->execute();
}

function getUserIdByScreenName(PDO $pdo, $screenName)
{
    $sql = 'SELECT id FROM users WHERE `screen_name` = :screen_name';
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':screen_name', $screenName, PDO::PARAM_STR);
    $statement->execute();
    if ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        return $row['id'];
    } else {
        return null;
    }
}

function getReplyId(PDO $pdo, $text)
{
    $at = preg_match('/@(?P<screen_name>[a-zA-Z0-9]+) /', $text, $mention);
    if ($at) {
        return getUserIdByScreenName($pdo, $mention["screen_name"]);
    }
    return null;
}

function getTimeline($pdo,$start,$postsNum)
{
  $sql = 'SELECT * FROM posts ORDER BY `created_at` DESC LIMIT :start, :postsNum';
  $statement = $pdo->prepare($sql);
  $statement->bindValue(':start', $start, PDO::PARAM_INT);
  $statement->bindValue(':postsNum', $postsNum, PDO::PARAM_INT);
  $statement->execute();
  if($rows = $statement->fetchAll(PDO::FETCH_ASSOC)){
    return $rows;
  }else{
    return false;
  }
}

function deletePost($pdo,$id)
{
  $sql = 'DELETE FROM posts WHERE id = :id';
  $statement = $pdo->prepare($sql);
  $statement->bindValue(':id',$id,PDO::PARAM_INT);
  $statement->execute();
}

function postsCounter($pdo)
{
  $sql = 'SELECT COUNT(*) FROM posts';
  $statement = $pdo->prepare($sql);
  $statement->execute();
  if($row = $statement->fetch(PDO::FETCH_NUM)){
    return $row[0];
  }else{
    return 0;
  }
}

function getReplyTimeline(PDO $pdo, $userId)
{
  $sql = 'SELECT * FROM posts WHERE `in_reply_to_user_id` = :user_id ORDER BY `created_at` DESC';
  $statement = $pdo->prepare($sql);
  $statement->bindValue(':user_id', $userId, PDO::PARAM_INT);
  $statement->execute();

  if ($rows = $statement->fetchAll(PDO::FETCH_ASSOC)) {
    return $rows;
  } else {
    return false;
  }
}

function emailExists($email,PDO $pdo)
{
  $sql = 'SELECT * FROM users  WHERE email = :email';
  $statement = $pdo->prepare($sql);
  $statement->bindValue(':email', $email, PDO::PARAM_STR);
  $statement->execute();
  $row = $statement->fetch();
  return $row ? true : false;
}

function setToken(){
  $token = sha1(uniqid(mt_rand(),true));
  $_SESSION['token'] = $token;
}

function checkToken(){
  if (empty($_SESSION['token']) || ($_SESSION['token'] != $_POST['token'])){
    print "不正POSTが行われました！";
    header('HTTP',true,400);
  }
}