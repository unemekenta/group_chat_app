<?php define("ROOT", $_SERVER['DOCUMENT_ROOT']); ?>
<?php require_once ROOT."/php-app/header.php"; ?>

<main>
  <?php

  session_start();

  // ログイン状態のチェック
  if (!isset($_SESSION["NAME"])) {
    header("Location: logout.php");
    exit;
  }

   // DB関連
   $dsn = '******';
   $user = '******';
   $password = '******';
   $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
   $sql = "CREATE TABLE IF NOT EXISTS message"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "comment TEXT,"
    . "user_id INT,"
    . "userteam_id INT,"
    . "FOREIGN KEY (user_id) REFERENCES member(id),"
    . "FOREIGN KEY (userteam_id) REFERENCES userteam(id)"
    .");";
    $stmt = $pdo->query($sql);

    // 確認用コード
      // $sql ='SHOW TABLES';
      // $result = $pdo -> query($sql);
      // foreach ($result as $row){
      //   echo $row[0];
      //   echo '<br>';
      // }
      // echo "<hr>";
      //
      // $sql ='SHOW CREATE TABLE userteam';
      // $result = $pdo -> query($sql);
      // foreach ($result as $row){
      //   echo $row[1];
      // }
      // echo "<hr>";
    // 確認用コード終了

      // if (empty($_POST["team_name"])) {
      //   echo "グループ名が未入力です。";
      // }
      if (!empty($_POST["comment"])) {
        $user_id = $_SESSION["USER_ID"];
        $userteam_id = $_SESSION["USERTEAM_ID"];
        $sql = $pdo -> prepare("INSERT INTO message (user_id, userteam_id, comment) VALUES (:user_id, :userteam_id, :comment)");
        $comment = $_POST['comment'];
        $sql -> bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $sql -> bindParam(':userteam_id', $userteam_id, PDO::PARAM_INT);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> execute();
        echo "コメントを送信しました。";
      }

      if(!empty($_POST['deleteNo'])){
          $id = $_POST["deleteNo"];
          $sql = 'delete from message where id=:id';
          $stmt = $pdo->prepare($sql);
          $stmt->bindParam(':id', $id, PDO::PARAM_INT);
          $stmt->execute();

          echo "コメントを削除しました";
      }
  ?>
  <div class="container">
    <div class="text-center">
      <h1>Groupボード</h1>
    </div>
    <div class="container">
      <div class="content">
        <form class="form-horizontal" method= "post" action="">
          コメント
          <input type="text" class="form-control" value="" name="comment" placeholder="（例）こんにちは！"><br>
          <input type="submit" class="form-control bg-secondary text-white" value="コメント送信" name="submit" >
        </form>
      </div>
      <br>
      <div class="container">
        <button type="button" class="btn btn-primary" onclick="location.href='main.php'">
          メインページへ
        </button>
      </div>
      <br>
      <div class="container">
        <button type="button" class="btn btn-primary" onclick="location.href='logout.php'">
          ログアウト
        </button>
      </div>
      <br>
    </div>
    <div class="container">
      <p>コメント一覧</p>
      <div class="table-responsive">
        <table class="table table-striped table-bordered">
          <thead>
            <tr>
              <th>#</th>
              <th>コメント</th>
              <th>ユーザー名</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $userteam_id = $_SESSION["USERTEAM_ID"];
              $user_id = $_SESSION["USER_ID"];
              $sql = 'SELECT * FROM message WHERE userteam_id = '.$userteam_id.'';
              $stmt = $pdo->query($sql);
              $results = $stmt->fetchAll();
              foreach ($results as $row){
                echo ' <tr> <th scope="row">'.$row['id'].'</th>';
                $user_id = $row['user_id'];
                echo '<td>'.$row['comment'].'</td>';
                $sql = 'SELECT * FROM member WHERE id = '.$user_id.'';
                $stmt = $pdo->query($sql);
                foreach ($stmt as $row) {
                  $member_name = $row[1];
                  echo '<td>'.$member_name.'</td>';
                }
              }
            ?>
          </tbody>
        </table>
      </div>
    </div>
    <div class="container form-inline">
      <form class="form-horizontal" method= "post" action="">
        <div class="form-group">
          <input type="text" class="form-control" name="deleteNo" placeholder="削除対象番号"><br>
          <button type="submit" class="btn btn-danger" name="delete" value="">
            削除
          </button>
        </div>
      </form>
    </div>
    <br>
  
</main>

<?php require_once ROOT."/php-app/footer.php"; ?>
