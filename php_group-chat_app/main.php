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
  
  $dsn = '******';
  $user = '******';
  $password = '******';
  $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
  
  if (!empty ($_SESSION["USERTEAM_ID"])){
    $userteam_id = $_SESSION["USERTEAM_ID"];
    $sql = 'SELECT * FROM userteam WHERE id = '.$userteam_id.'';
    $stmt = $pdo->query($sql);
    foreach ($stmt as $row) {
      $team_name = $row[1];
    }
  }else{
    $team_name = "グループに所属していません";
  }
  
  ?>
  <div class="container">
    <div class="container">
      <h3 class="mb-3">こんにちは！</h3>
      <p class="m-4">ようこそ<strong><?=htmlspecialchars($_SESSION["NAME"], ENT_QUOTES); ?></strong>さん</p>
      <p class="m-4">所属グループ名：<?=htmlspecialchars($team_name); ?></p>
      <p class="m-4">チームメンバー：
        <?php
        if (!empty ($userteam_id)){
          $sql = 'SELECT * FROM member WHERE userteam_id = '.$userteam_id.'';
          $stmt = $pdo->query($sql);
          foreach ($stmt as $row) {
            $member_name = $row[1];
            echo ''.$member_name.'　　';
          }
        }else{
        }
        ?>
      </p>
    </div>
    <div class="container">
      <?php
        if (!empty ($userteam_id)){
        echo "
            <a href='message.php'>
            <button class='btn btn-primary m-2'>
            チームページへ
            </button>
            </a>
          ";
        }else{

        }
      ?>
      <button type="button" class="btn btn-primary m-2" onclick="location.href='create_group.php'">
        グループ作成
      </button>
      <button type="button" class="btn btn-primary m-2" onclick="location.href='logout.php'">
        ログアウト
      </button>
    </div>
  </div>
</main>

<?php require_once ROOT."/php-app/footer.php"; ?>
