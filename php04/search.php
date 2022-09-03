<?php
    require_once('config.php');
    require_once('functions.php');
    $title = 'お通じ記録を検索 | ' . SITE_NAME;

    $where= [];
    $params = [];
    $start_date = '';
    $end_date = '';
    $keyword = '';

    // パラメータをチェック
    if (!empty($_GET['start_date'])) {
        $start_date = $_GET['start_date'];
        $where[] = 'CAST(datetime AS DATE) >= :start_date';
        $params[':start_date'] = $start_date;
    }
    if (!empty($_GET['end_date'])) {
        $end_date = $_GET['end_date'];
        $where[] = 'CAST(datetime AS DATE) <= :end_date';
        $params[':end_date'] = $end_date;
    }
    if (!empty($_GET['keyword'])) {
        $keyword = $_GET['keyword'];
        $where[] = 'memo LIKE :memo';
        $params[':memo'] = '%' . $keyword . '%';
    }
    

    if (!empty($where)) {
    // $where配列の要素を「 AND 」で繋ぐ
    $where = implode(' AND ', $where);

    $pdo = connectDB();
    $sql = 'SELECT * FROM schedules WHERE ' . $where . ' ORDER BY datetime ASC'; 
    $stmt = $pdo->prepare($sql);

    foreach ($params as $key => $val) {
        $stmt->bindValue($key, $val, PDO::PARAM_STR);
    }

    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            <div class="col-lg-8 offset-lg-2">
                <h4 class="text-center">予定を検索</h4>
                
                <form class="row row-cols-lg-auto g-2 align-items-center">
                    <div class="col-12 dp-parent">
                        <label class="visually-hidden" for="inputStartDate">から</label>
                        <input type="text" name="start_date" id="inputDateTime" class="form-control search-date" placeholder="開始日"value="<?= $start_date; ?>">
                    </div>
                
                    <div class="col-12 dp-parent">
                        <label class="visually-hidden" for="inputEndDate">まで</label>
                        <input type="text" name="end_date" id="inlineFormInputGroupUsername" class="form-control search-date" placeholder="終了日"value="<?= $end_date; ?>">
                    </div>
                
                    <div class="col-12">
                        <label class="visually-hidden" for="inputTask">キーワード</label>
                        <input type="text" name="keyword" id="inputTask" class="form-control" placeholder="キーワード"value="<?= $keyword; ?>">
                    </div>
                
                    <div class="col-12 d-grid">
                        <button type="submit" class="btn btn-success">検索</button>
                    </div>
                </form>
 
                <?php if (!empty($where)): ?>
                    <h6 class="mt-5">検索結果：<?= count($rows); ?>件</h6>
                    <?php if (count($rows) > 0):?>
                        <table class="table mt-4">
                            <thead>
                                <tr>
                                    <th style="width: 20%;">開始日時</th>
                                    <th style="width: 20%;">終了日時</th>
                                    <th>お通じの記録</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rows as $row): ?>
                                    <tr>
                                        <td><?= date('Y/n/j H:i', strtotime($row['datetime'])); ?></td>
                                        <td><?= date('Y/n/j H:i', strtotime($row['datetime'])); ?></td>
                                        <td><a href="detail_2.php?ymd=<?= date('Y-m-d', strtotime($row['datetime'])); ?>"><?= $row['memo']; ?></a></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <div class="alert alert-danger mt-4" role="alert">
                            お通じの記録が見つかりませんでした。
                        </div>
                    <?php endif; ?>
                    <div class="mt-4"><a href="search.php" class="btn btn-sm btn-link" role="button">検索条件をクリア</a></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

    <?php require_once('elements/footer.php'); ?>

</body>
</html>