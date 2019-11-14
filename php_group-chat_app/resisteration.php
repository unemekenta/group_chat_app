<?php define("ROOT", $_SERVER['DOCUMENT_ROOT']); ?>
<?php require_once ROOT."/php-app/header.php"; ?>

<main class="col-sm-12 control-label">
   <!-- セッション開始 -->
  <?php
  // DB関連
    $dsn = '******';
    $user = '******';
    $password = '******';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    $sql = "CREATE TABLE IF NOT EXISTS member"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "email TEXT,"
    . "password TEXT,"
    . "userteam_id INT,"
    . "FOREIGN KEY (userteam_id) REFERENCES userteam(id)"
    .");";
    $stmt = $pdo->query($sql);
    if (isset($_POST["signUp"])) {
  
      if (empty($_POST["username"])) {
        echo "ユーザ名が未入力です。";
      } else if (empty($_POST["email"])) {
        echo "メールアドレスが未入力です。";
      } else if (empty($_POST["password"])) {
        echo "パスワードが未入力です。";
      }
      if (!empty($_POST["username"]) && !empty($_POST["email"]) && !empty($_POST["password"])) {
        $sql = $pdo -> prepare("INSERT INTO member (name, email, password) VALUES (:name, :email, :password)");
        $name = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $pass = password_hash($password, PASSWORD_DEFAULT);
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':email', $email, PDO::PARAM_STR);
        $sql -> bindParam(':password', $pass, PDO::PARAM_STR);
        $sql -> execute();
        echo "ユーザーを登録しました。";
      }
    }
    if(!empty($_POST['deleteNo'])){
        $id = $_POST["deleteNo"];
        $sql = 'delete from member where id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
  
        echo "ユーザーを削除しました";
    }
  ?>
  
  <div class="container">
    <h1 class="text-center">ユーザー登録</h1>
    <div class="container">
      <form class="form-horizontal" id="loginForm" name="loginForm" action="" method="POST">
        <div class="form-group">
          <label for="username" class="col-sm-2 control-label">名前</label><br>
          <input type="text" class="form-control" id="username" name="username" placeholder="ユーザー名を入力" value="<?php if (!empty($_POST["username"])) {echo htmlspecialchars($_POST["username"], ENT_QUOTES);} ?>">
        </div><br>
      
        <div class="form-group">
          <label for="email" class="col-sm-2 control-label">メールアドレス</label><br>
          <input type="text" class="form-control" id="email" name="email" value="" placeholder="メールアドレスを入力">
        </div><br>
      
        <div class="form-group">
          <label for="password" class="col-sm-2 control-label">パスワード</label><br>
          <input type="password" class="form-control" id="password" name="password" value="" placeholder="パスワードを入力"><br>
          <br>
          <button type="submit" class="btn btn-danger" id="signUp" name="signUp" value="">
            新規登録
          </button>
        </div><br>
      </form>
    </div>
    <br>
    <div class="container">
      <button type="button" class="btn btn-primary" onclick="location.href='login.php'">
        ログインはこちら
      </button>
    </div>
    <br><br>
    <div class="container">
      <h2>ユーザー一覧</h2>
      <div class="table-responsive">
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th>#</th>
            <th>名前</th>
            <th>メールアドレス</th>
            <th>パスワード</th>
            <th>チームID</th>
          </tr>
        </thead>
        <tbody>
      <?php
        $sql = 'SELECT * FROM member';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
          echo ' <tr> <th scope="row">'.$row['id'].'</th>';
          echo '<td>'.$row['name'].'</td>';
          echo '<td>'.$row['email'].'</td>';
          echo '<td>'.$row['password'].'</td>';
          echo '<td>'.$row['userteam_id'].'</td>';
        }?>
        </tbody>
      </table>
    </div>
  </div>
  
  <h3>ユーザー削除</h3>
  <div class="container form-inline">
    <form method= "post" action="">
      <div class=”form-group”>
        <input type="text" class="form-control" name="deleteNo" placeholder="削除対象番号">
        <button type="submit" class="btn btn-danger" name="delete">
          削除
        </button>
      </div>
    </form>
  </div>
</main>
  
<?php require_once ROOT."/php-app/footer.php"; ?>
