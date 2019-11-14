<?php define("ROOT", $_SERVER['DOCUMENT_ROOT']); ?>
<?php require_once ROOT."/php-app/header.php"; ?>

<main>
  <?php
    session_start();
    
    if (isset($_SESSION["NAME"])) {
      $errorMessage = "ログアウトしました。";
    }
    else {
      $errorMessage = "セッションがタイムアウトしました。";
    }
    // セッション変数のクリア
    $_SESSION = array();
    // クッキーの破棄は不要
    //if (ini_get("session.use_cookies")) {
    //    $params = session_get_cookie_params();
    //    setcookie(session_name(), '', time() - 42000,
    //        $params["path"], $params["domain"],
    //        $params["secure"], $params["httponly"]
    //    );
    //}
    // セッションクリア
    @session_destroy();
  ?>
  
  <div class="container">
    <h2>ログアウト</h2>
    <br>
    <div><?php echo $errorMessage; ?></div>
  </div>
  <br>
  <div class="container">
    <button type="button" class="btn btn-primary" onclick="location.href='login.php'">
      ログイン画面に戻る
    </button>
  </div>
</main>

<?php require_once ROOT."/php-app/footer.php"; ?>
