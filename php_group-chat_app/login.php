<?php define("ROOT", $_SERVER['DOCUMENT_ROOT']); ?>
<?php require_once ROOT."/php-app/header.php"; ?>

<main>
  <?php
  // require 'password.php';   // password_verfy()はphp 5.5.0以降の関数のため、バージョンが古くて使えない場合に使用
  // セッション開始
  session_start();
  
  $db['host'] = "******";  // DBサーバのURL
  $db['user'] = "******";  // ユーザー名
  $db['pass'] = "******";  // ユーザー名のパスワード
  $db['dbname'] = "******";  // データベース名
  
  // エラーメッセージの初期化
  $errorMessage = "";
  
  // ログインボタンが押された場合
  if (isset($_POST["login"])) {
      // 1. ユーザIDの入力チェック
      if (empty($_POST["email"])) {  // emptyは値が空のとき
          $errorMessage = 'ユーザーIDが未入力です。';
      } else if (empty($_POST["password"])) {
          $errorMessage = 'パスワードが未入力です。';
      }
  
      if (!empty($_POST["email"]) && !empty($_POST["password"])) {
          // 入力したユーザIDを格納
          $userid = $_POST["email"];
  
          // 2. ユーザIDとパスワードが入力されていたら認証する
          $dsn = 'mysql:dbname=tb210511db;host=localhost';
  
          // 3. エラー処理
          try {
              $dsn = '******';
              $user = '******';
              $password = '******';
              $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
              // $pdo = new PDO($dsn, $db['user'], $db['pass'], array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
  
              $stmt = $pdo->prepare('SELECT * FROM member WHERE email = ?');
              $stmt->execute(array($userid));
  
              $password = $_POST["password"];
  
              if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                  if ($_POST["password"] == $password) {
                  // if (password_verify($password, $row['password'])) {
                  //     session_regenerate_id(true);
  
                      // 入力したIDのユーザー名を取得
                      $id = $row['id'];
                      $sql = "SELECT * FROM member WHERE id = $id";  //入力したIDからユーザー名を取得
                      $stmt = $pdo->query($sql);
                      foreach ($stmt as $row) {
                          $row['name'];
                          $row['userteam_id'];
                          $row['id'];
                      }
                      $_SESSION["USER_ID"] = $row['id'];
                      $_SESSION["NAME"] = $row['name'];
                      $_SESSION["USERTEAM_ID"] = $row['userteam_id'];
                      header("Location: main.php");  // メイン画面へ遷移
                      exit();  // 処理終了
                  } else {
                      // 認証失敗
                      $errorMessage = '1ユーザーIDあるいはパスワードに誤りがあります。';
                  }
              } else {
                  // 4. 認証成功なら、セッションIDを新規に発行する
                  // 該当データなし
                  $errorMessage = 'ユーザーIDあるいはパスワードに誤りがあります。';
              }
          } catch (PDOException $e) {
              $errorMessage = 'データベースエラー';
              $errorMessage = $sql;
              // $e->getMessage() でエラー内容を参照可能（デバッグ時のみ表示）;
              echo $e->getMessage();
          }
      }
  }
  ?>

  <div class="container">
    <h1 class="text-center">ログイン</h1>
    <form class="form-horizontal" id="loginForm" name="loginForm" action="" method="POST">
        <fieldset>
            <div class="form-group">
              <font color="#ff0000">
                <?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?>
              </font>
            </div>
            <label for="userid">
              メールアドレス
            </label>
            <input type="text" class="form-control" id="email" name="email" placeholder="ユーザーIDを入力" value="<?php if (!empty($_POST["userid"])) {echo htmlspecialchars($_POST["userid"], ENT_QUOTES);} ?>">
            <br>
            <label for="password">
              パスワード
            </label>
            <input type="password" class="form-control" id="password" name="password" value="" placeholder="パスワードを入力">
            <br>
            <button type="submit" class="btn btn-danger" id="login" name="login" value="">
              ログイン
            </button>
        </fieldset>
        <br><br>
        <div class="container">
          <button type="button" class="btn btn-primary" onclick="location.href='resisteration.php'">
            ユーザー登録はこちら
          </button>
        </div>
    </form>
  </div>
</main>

<?php require_once ROOT."/php-app/footer.php"; ?>
