<?php

require_once('config.php');
require_once('functions.php');

$title = SITE_NAME;

// 前月・次月リンクが押された場合は、GETパラメータから年月を取得
if (isset($_GET['ym'])) {
    $ym = $_GET['ym'];
} else {
    // 今月の年月を表示
    $ym = date('Y-m');
}

// タイムスタンプを作成し、フォーマットをチェックする
$timestamp = strtotime($ym . '-01');
if ($timestamp === false) {
    $ym = date('Y-m');
    $timestamp = strtotime($ym . '-01');
}

// 該当月の日数を取得
$day_count = date('t', $timestamp);

// 1日が何曜日か 1:月 2:火 ... 7:日
$youbi = date('N', $timestamp);

// カレンダーのタイトルを作成　例）2021年3月
$html_title = date('Y年n月', $timestamp);

// 前月・次月の年月を取得
$prev = date('Y-m', strtotime('-1 month', $timestamp));
$next = date('Y-m', strtotime('+1 month', $timestamp));

// 今日の日付　例）2021-03-05
$today = date('Y-m-d');

// カレンダー作成の準備
$weeks = [];
$week = '';

// 第１週目：空のセルを追加
// 例）１日が木曜日だった場合、月曜日から水曜日の３つ分の空セルを追加する
$week .= str_repeat('<td></td>', $youbi-1);

// データベースに接続
$pdo = connectDB();

// カレンダー作成
for ( $day = 1; $day <= $day_count; $day++, $youbi++) {
                                  
    $date = $ym . '-' . sprintf('%02d', $day);

    // 予定を取得
    $rows = getSchedulesByDate($pdo, $date);

    // HTML作成
    if ($date == $today) {
        $week .= '<td class="today">';
    } else {
        $week .= '<td>';
    }
    $week .= '<a href="detail_2.php?ymd=' . $date . '">' . $day;

    if (!empty($rows)) {
        $week .= '<div class="badges">';
            foreach ($rows as $row) {
                $katachi = date('H:i', strtotime($row['datetime'])) . ' ' . $row['katachi'];
                $week .= '<span class="badge text-wrap ' . $row['color'] . '">' . $katachi . '</span>';
            }
        $week .= '</div>';
    }

    $week .= '</a></td>';


    // 日曜日、または、最終日の場合
    if ($youbi % 7 == 0 || $day == $day_count) {

        if ($day == $day_count && $youbi % 7 != 0) {
            // 月の最終日の場合、空セルを追加
            // 例）最終日が金曜日の場合、土・日曜日の空セルを追加
            $week .= str_repeat('<td></td>', 7 - $youbi % 7);
        }

        // weeks配列にtrと$weekを追加する
        $weeks[] = '<tr>' . $week . '</tr>';

        // weekをリセット
        $week = '';
    }
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <title>便秘記録アプリ</title>
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
    <!--<header>
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header"><a class="navbar-brand" href="select.php">データ一覧</a></div>
                <div class="navbar-header"><a class="navbar-brand" href="login.php">ログイン</a></div>
                <div class="navbar-header"><a class="navbar-brand" href="logout.php">ログアウト</a></div>
            </div>
        </nav>
    </header>-->
    <!-- Head[End] -->

    <!-- Main[Start] -->
    <form method="POST" action="confirm.php">
        <div class="jumbotron">
            <fieldset>
                <p>ー</p>
                <p>ー</p>
                <legend>フリーアンケート</legend>
                <label>名前：<input type="text" name="name"></label><br>
                <label>Email：<input type="text" name="email"></label><br>
                <label>年齢：<input type="text" name="age"></label><br>
                <label><textArea name="naiyou" rows="4" cols="40"></textArea></label><br>
                <input type="submit" value="送信">
            </fieldset>
        </div>
    </form>



    <!DOCTYPE html>
<html lang="ja" class="h-100">
<head>
    <?php require_once('elements/head.php'); ?>

    <style>
        .container {
            font-family: 'Noto Sans JP', sans-serif;
            margin-top: 80px;
        }
        a {
            text-decoration: none;
        }
        th {
            height: 30px;
            text-align: center;
        }
        td {
            height: 100px;
        }
        .today {
            background: orange !important;
        }
        
    </style>

</head>



<body class="d-flex flex-column h-100">
    
    <?php require_once('elements/navbar.php'); ?>

    <main>
    <div class="container">
        <table class="table table-bordered calendar">
            <thead>
                <tr class="head-cal fs-4">
                    <th colspan="1" class="text-start"><a href="index.php?ym=<?= $prev; ?>">&lt;</a></th>
                    <th colspan="5"><?= $html_title; ?></th>
                    <th colspan="1" class="text-end"><a href="index.php?ym=<?= $next; ?>">&gt;</a></th>
                </tr>
                <tr class="head-week">
                    <th>月</th>
                    <th>火</th>
                    <th>水</th>
                    <th>木</th>
                    <th>金</th>
                    <th>土</th>
                    <th>日</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($weeks as $week) { echo $week; }?>
            </tbody>
        </table>
    </div>
</main>
    
    

    <?php require_once('elements/footer.php'); ?>

</body>
</html>
    <!-- Main[End] -->
</body>

</html>
