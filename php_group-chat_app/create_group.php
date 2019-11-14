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
   $sql = "CREATE TABLE IF NOT EXISTS userteam"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32)"
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
      if (!empty($_POST["team_name"]) && !empty($_POST["add_user"])) {

        $sql = $pdo -> prepare("INSERT INTO userteam (team_name) VALUES (:team_name)");
        $name = $_POST['team_name'];
        $sql -> bindParam(':team_name', $name, PDO::PARAM_STR);
        $sql -> execute();

        $sql = 'SELECT * FROM userteam ORDER BY id DESC LIMIT 1';
        $stmt = $pdo->query($sql);
        $last_id = $stmt->fetchAll();
        foreach ($last_id as $row){
          $team_id = $row['id'];
        }

        $select_ids = $_POST['add_user'];
        foreach ($select_ids as $select_id) {
          $add_id = $select_id;
          $sql = 'update member set userteam_id=:userteam_id where id = '.$add_id.'';
          $stmt = $pdo->prepare($sql);
          $stmt->bindParam(':userteam_id', $team_id, PDO::PARAM_INT);
          $stmt->execute();
        }
        echo "グループを登録しました。";
      }

      if(!empty($_POST['deleteNo'])){
          $id = $_POST["deleteNo"];
          $sql = 'delete from userteam where id=:id';
          $stmt = $pdo->prepare($sql);
          $stmt->bindParam(':id', $id, PDO::PARAM_INT);
          $stmt->execute();

          echo "グループを削除しました";
      }
  ?>
  <div class="container">
    <div class="title">
      <h2 class="text-center">Group登録</h2>
    </div>
    <div class="container">
      <form class="form-horizontal" method= "post" action="">
        <div class="form-group">
          Name：
          <input type="text" class="form-control" value="" name="team_name" placeholder="（例）OOゼミ"><br>
          追加メンバー：
          <br>
          <?php
            $sql = 'SELECT * FROM member';
            $stmt = $pdo->query($sql);
            $results = $stmt->fetchAll();
            echo '<div class="checkbox p-3">';
            foreach ($results as $row){
              echo '<input type="checkbox" value="'.$row['id'].'" name="add_user[]">'.$row['name'].'　　';
            }
            echo'</div>';
          ?>
          <button type="submit" class="btn btn-danger" id="signUp" name="signUp" value="">
            グループ作成
          </button>
        </div>
      </form>
    </div>
    <div class="container">
      <button type="button" class="btn btn-primary mb-4" onclick="location.href='main.php'">
        メインページへ
      </button>
      <button type="button" class="btn btn-primary mb-4" onclick="location.href='logout.php'">
        ログアウト
      </button>
    </div>
    
    <div class="container">
      <h2 class="text-center">チーム一覧</h2>
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>チーム名</th>
            <th>メンバー</th>
          </tr>
        </thead>
        <tbody>
        <?php
          $sql = 'SELECT * FROM userteam';
          $stmt = $pdo->query($sql);
          $results = $stmt->fetchAll();
          foreach ($results as $row){
            echo ' <tr> <th scope="row">'.$row['id'].'</th>';
            $userteam_id = $row['id'];
            echo '<td>'.$row['team_name'].'</td>';
            echo '<td>';
            $sql = 'SELECT * FROM member WHERE userteam_id = '.$userteam_id.'';
            $stmt = $pdo->query($sql);
            foreach ($stmt as $row) {
              $member_name = $row[1];
              echo ''.$member_name.'　　';
            }
            echo '</td>';
          }
        ?>
    </div>
    <br>
    <div class="container form-inline">
      <form method= "post" action="">
        <div class="form-group">
          <input type="text" class="form-control" name="deleteNo" placeholder="削除対象番号">
        </div>
        <button type="submit" class="btn btn-danger" id="delete" name="delete" value="">
          削除
        </button>
      </form>
    </div>
    <br>
  </div>
</main>
<?php require_once ROOT."/php-app/footer.php"; ?>
