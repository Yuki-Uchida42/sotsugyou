<?php
// タイムゾーンの設定
date_default_timezone_set('Asia/Tokyo');

define('SITE_NAME', '便秘記録アプリ');

// データベース接続
define('DB_HOST', 'localhost');
define('DB_NAME', 'gs_db4');
define('DB_USER', 'root');
define('DB_PASS', 'root');

// カラーリスト
$colorList = [
    'bg-red' => 'デフォルト',
    'bg-danger' => '赤',
    'bg-warning' => 'オレンジ',
    'bg-success' => '緑',
    'bg-dark' => '黒',
    'bg-secondary' => 'グレー'
];
define('COLOR_LIST', $colorList);

// かたちリスト
$katachiList = [
    'かたち' => 'かちかち',
    '理想形' => '理想系',
    'ほそい' => 'ほそい',
    'どろどろ' => 'どろどろ',
    'ぴちゃ' => 'ぴちゃ',
];
define('KATACHI_LIST', $katachiList);
?>

