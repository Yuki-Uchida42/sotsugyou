<?php
    require_once('config.php');
    require_once('functions.php');

    $title = 'お通じの記録の編集 | ' . SITE_NAME;

    // 存在・形式チェック
    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        header('Location:index.php');
        exit();
    }

    $schedule_id = $_GET['id'];

    $pdo = connectDB();

    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        // 編集する予定データを取得する
        $sql = 'SELECT * FROM schedules WHERE schedule_id = :schedule_id LIMIT 1';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':schedule_id', $schedule_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // データが見つからなかった場合
        if (empty($row) || $row === false) {
            header('Location:index.php');
            exit();
        }

        $datetime = $row['datetime'];
        $katachi = $row['katachi'];
        $color = $row['color'];
        $memo = $row['memo'];
        $err = [];

    } else {
        // 予定を編集する
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


        // 2. SQL文の作成
        $sql = 'UPDATE schedules 
                SET datetime = :datetime, katachi = :katachi, color = :color, memo = :memo,modified_at = now() 
                WHERE schedule_id = :schedule_id';

        // 3. SQL文を実行する準備
        $stmt = $pdo->prepare($sql);

        // 4. 値をセット
        $stmt->bindValue(':datetime', $datetime, PDO::PARAM_STR);
        $stmt->bindValue(':katachi', $katachi, PDO::PARAM_STR);
        $stmt->bindValue(':color', $color, PDO::PARAM_STR);
        $stmt->bindValue(':memo', $memo, PDO::PARAM_STR);
        $stmt->bindValue(':schedule_id', $schedule_id, PDO::PARAM_INT);

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
                <h4 class="text-center">お通じの記録の編集</h4>

                <form method="post">
                    <div class="mb-4 dp-parent">
                            <label for="inputDateTime" class="form-label">お通じ時間</label>
                            <input type="text" name="datetime" id="inputDateTime" 
                                class="form-control task-datetime " 
                                placeholder="お通じがあった日時を選択して下さい。"value="<?= $datetime; ?>">
                        </div>
        
                        <div class="mb-5">
                            <label for="selectColor" class="form-label">かたち</label>
                            <select name="katachi" id="selectColor" class="form-select <?= $katachi; ?> <?php if (!empty($err['katachi'])) echo 'is-invalid'; ?>">
                                <?php foreach(KATACHI_LIST as $key => $val):?>
                                    <option value="<?= $key; ?>" <?php if ($katachi == $key) echo 'selected'; ?>><?= $val; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (!empty($err['katachi'])): ?>
                                <div id="selectColorFeedback" class="invalid-feedback">
                                    * <?= $err['katachi']; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-5">
                            <label for="selectColor" class="form-label">色</label>
                            <select name="color" id="selectColor" class="form-select <?= $color; ?> <?php if (!empty($err['color'])) echo 'is-invalid'; ?>">
                                <?php foreach(COLOR_LIST as $key => $val):?>
                                    <option value="<?= $key; ?>" <?php if (color == $key) echo 'selected'; ?>><?= $val; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (!empty($err['color'])): ?>
                                <div id="selectColorFeedback" class="invalid-feedback">
                                    * <?= $err['color']; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        
        
                        <div class="mb-4">
                            <label for="inputTask" class="form-label">メモ</label>
                            <input type="text" name="memo" id="inputTask" class="form-control"placeholder="メモ" value="<?= $memo; ?>">
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">更新</button>
                        </div>
                </form>

            </div>
        </div>
    </div>
</main>

    <?php require_once('elements/footer.php'); ?>
</body>
</html>