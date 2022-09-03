<?php
    require_once('config.php');
    require_once('functions.php');
    
    $title = 'お通じの記録 | ' . SITE_NAME;

    // エラーメッセージを入れる配列を用意
    $err = [];


    if ($_SERVER['REQUEST_METHOD'] == 'POST') {

        $datetime = $_POST['datetime'];
        $katachi =  $_POST['katachi'];
        $color = $_POST['color'];
        $memo = $_POST['memo'];
        // 入力チェック
        if ($datetime == '') {
            $err['datetime'] = 'お通じ時間を入力して下さい。';
        }

        

        // エラーが無ければデータベースに保存
        if (empty($err)) {

        // 1. データベースに接続
        $pdo = connectDB();

        // 2. SQL文の作成
        $sql = 'INSERT INTO schedules(datetime, katachi, color, memo , created_at, modified_at)
        VALUES(:datetime, :katachi, :color, :memo, now(), now())';

        // 3. SQL文を実行する準備
        $stmt = $pdo->prepare($sql);

        // 4. 値をセット
        $stmt->bindValue(':datetime', $datetime, PDO::PARAM_STR);
        $stmt->bindValue(':katachi', $katachi, PDO::PARAM_STR);
        $stmt->bindValue(':color', $color, PDO::PARAM_STR);
        $stmt->bindValue(':memo', $memo, PDO::PARAM_STR);

        // 5. ステートメントを実行
        $stmt->execute();

        // 予定詳細画面に遷移
        header('Location:detail_2.php?ymd='.date('Y-m-d', strtotime($datetime)));
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ja" class="h-100">
<head>
    <?php require_once('elements/head.php'); ?>
</head>
<body class="d-flex flex-column h-100">
    
    <?php require_once('elements/navbar.php'); ?>

<main>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <h4 class="text-center">お通じの記録</h4>

                <form method="post">
                    <div class="mb-4 dp-parent">
                        <label for="inputDateTime" class="form-label">お通じ時間</label>
                        <input type="text" name="datetime" id="inputDateTime" 
                            class="form-control task-datetime " 
                            placeholder="お通じがあった日時を選択して下さい。">
                        
                    </div>
    
                    <div class="mb-5">
                        <label for="selectColor" class="form-label">かたち</label>
                        <select name="katachi" id="selectColor" class="form-select bg-light">
                            <option value="ー" selected>デフォルト</option>
                            <option value="ころころ">ころころ</option>
                            <option value="かたち">かちかち</option>
                            <option value="理想形">理想形</option>
                            <option value="ほそい">ほそい</option>
                            <option value="どこどろ">どろどろ</option>
                            <option value="ぴちゃ">びちゃ</option>
                        </select>
                    </div>
    
                    <div class="mb-5">
                        <label for="selectColor" class="form-label">色</label>
                        <select name="color" id="selectColor" class="form-select bg-light">
                            <option value="bg-light" selected>デフォルト</option>

                            <option value="bg-warning">オレンジ</option>
                            <option value="bg-danger">赤</option>
                            <option value="bg-success">緑</option>
                            <option value="bg-secondary">グレー</option>
                            <option value="bg-dark">黒</option>
                        </select>
                        
                    </div>

                    <div class="mb-4">
                        <label for="inputTask" class="form-label">メモ</label>
                        <input type="text" name="memo" id="inputTask" class="form-control">
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">登録</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</main>

    <?php require_once('elements/footer.php'); ?>
</body>
</html>