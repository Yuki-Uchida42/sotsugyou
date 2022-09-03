<!DOCTYPE html>
<html lang="ja">

<?=
$name   = $_POST['name'];
$email  = $_POST['email'];
$naiyou = $_POST['naiyou'];
$age    = $_POST['age'];

?>

<head>
    <meta charset="UTF-8">
    <title>登録内容確認</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        div {
            padding: 10px;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <!-- Head[Start] -->
    <header>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header"><a class="navbar-brand" href="select.php">データ一覧</a></div>
                <div class="navbar-header"><a class="navbar-brand" href="login.php">ログイン</a></div>
                <div class="navbar-header"><a class="navbar-brand" href="logout.php">ログアウト</a></div>
            </div>
        </nav>
    </header>
    <!-- Head[End] -->

    <!-- Main[Start] -->
    <form method="POST" action="insert.php">
        <div class="jumbotron">
            <fieldset>
                <legend>登録内容確認</legend>
                <label>名前：<input type="hidden" name="name" value="<?= $name ?>"><p><?=$name?></p></label><br>
                <label>Email：<input type="hidden" name="email" value="<?= $email ?>"><p><?=$email?></p></label><br>
                <label>年齢：<input type="hidden" name="age" value="<?= $age ?>"><p><?=$age?></p></label><br>
                <label><textArea name="naiyou" rows="4" cols="40"></textArea></label><br>
                <input type="submit" value="確認">
            </fieldset>
        </div>
    </form>
    <!-- Main[End] -->
</body>

</html>
