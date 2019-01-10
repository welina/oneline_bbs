<?php
  // ここにDBに登録する処理を記述する
  //登録処理
  // 1. DB接続
$dsn = 'mysql:dbname=oneline_bbs;host=localhost';
  //Date Source Name
  //DB情報 どこに接続するか
$user = 'root';  //誰が
$password = '';  //パスワードは何か
$dbh = new PDO($dsn,$user,$password);  //接続処理
  //dbh
  //Databese handle
  //データを扱うことができるやつ
$dbh->query('SET NAMES utf8');
  //文字コード設定


  //2.SQL実装
if (!empty($_POST)) { //POST送信かどうか
  $nickname = $_POST['nickname'];
  $comment = $_POST['comment'];
  //$_POSTは連想配列

  $sql = 'INSERT INTO `posts` (`nickname`, `comment`, `created`)VALUES (?, ?, NOW())';
  //?を使う理由
  //SQLインジェクション対策
  //NOW()はSQLＮＯ関数 現在日時を算出
  $data = [$nickname, $comment];
  //$data = array($nickname,$comment);
  $stmt = $dbh->prepare($sql);
  $stmt->execute($data);
  //ここで初めてSQLが実行される
}


//一覧表示
//
//
$sql = 'SELECT * FROM `posts`';
$stmt = $dbh->prepare($sql);
$stmt->execute();

$posts = [];  //取得したデータを格納するための配列
while (true) {  //全レコードを取得する
  $record = $stmt->fetch(PDO::FETCH_ASSOC);
  //1行ずつ処理
  if ($record == false){
  //レコードが存在存在しないときfalseになる
    break;
  }
  $posts[] = $record;

}

// echo '<pre>';
// var_dump($posts);
// echo '</pre>';

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>セブ掲示版</title>
</head>
<body>
  <!-- formタグにはmethodとaction必須 -->
  <!-- method: 送信方法 どうアクセスするか
       action: 送信先 アクセスする場所
       actionが空白の場合、自分自身に戻る -->
    <form method="post" action="">
      <!-- formタグ内のinputタグやtextareaタグのname属性が$_POST -->
      <p><input type="text" name="nickname" placeholder="nickname"></p>
      <p><textarea type="text" name="comment" placeholder="comment"></textarea></p>
      <p><button type="submit" >つぶやく</button></p>
    </form>
    <!-- ここにニックネーム、つぶやいた内容、日付を表示する -->
    <!-- 一覧表示 -->
    <!-- 投稿情報の全てを表示する ＝ 一件ずつ繰り返し表示処理をする
    $postsは配列なので、foreachが使える
    foreach ($配列名 as $任意の変数)
    foreach (複数形 as 単数形)-->
    <?php foreach ($posts as $posts): ?>
      <p><?php echo $posts['nickname'];?></p>
      <p><?php echo $posts['created'];?></p>
      <p><?php echo $posts['comment'];?></p>
      <hr>
    <?php endforeach; ?>

</body>
</html>