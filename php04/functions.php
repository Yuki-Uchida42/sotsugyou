<?php
// データベース接続
function connectDB() {
    try {
        $pdo = new PDO('mysql:dbname='.DB_NAME.';host='.DB_HOST, DB_USER, DB_PASS);
        return $pdo;

    } catch (PDOException $e) {
        exit($e->getMessage());
    }
}

// 日付から予定を取得
function getSchedulesByDate($pdo, $date) {
    $sql = 'SELECT * FROM schedules WHERE CAST(datetime AS DATE) = :datetime ORDER BY datetime ASC';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':datetime', $date, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetchAll();
}

// 文字列をエスケープ
function h($string) {
    return htmlspecialchars($string, ENT_QUOTES);
}
?>