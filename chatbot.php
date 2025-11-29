<?php
session_start();
require 'includes/db_connection.php';

$user_id = $_SESSION['user_id'] ?? 0;

// === Имитация интересов и истории (в реальности — из БД) ===
$interests = "смартфоны, наушники, премиум техника, чёрный цвет, беспроводные устройства";
$history = [$user_id ? array_rand(range(1,100), 15) : []]; // 15 последних просмотренных id

header('Content-Type: application/json');

// Получаем весь каталог
$result = mysqli_query($connection, "
    SELECT p.id_product, p.Name, p.image, 
           MIN(ps.price) as price,
           c.name as category_name
    FROM Product p 
    LEFT JOIN `Product Store` ps ON p.id_product = ps.id_product
    JOIN Category c ON p.id_product_category = c.id_product_category
    GROUP BY p.id_product
");

$catalog = [];
while ($row = mysqli_fetch_assoc($result)) {
    $catalog[] = $row;
}

// Простейший алгоритм ранжирования
$scored = [];
$interest_words = array_map('trim', explode(',', strtolower($interests)));

foreach ($catalog as $item) {
    $score = 0;
    $name_lower = strtolower($item['Name']);
    $cat_lower = strtolower($item['category_name']);

    // 1. Приоритет — был в истории просмотров
    if (in_array($item['id_product'], $history[0] ?? [])) $score += 100;

    // 2. Совпадение по интересам
    foreach ($interest_words as $word) {
        if (strpos($name_lower, $word) !== false || strpos($cat_lower, $word) !== false) {
            $score += 30;
        }
    }

    // 3. Дешёвые — чуть выше (по желанию можно убрать)
    if ($item['price'] && $item['price'] < 15000) $score += 5;

    $scored[] = ['item' => $item, 'score' => $score];
}

// Сортируем по убыванию релевантности
usort($scored, fn($a, $b) => $b['score'] <=> $a['score']);

$top20 = array_slice($scored, 0, 20);

$response = [];
foreach ($top20 as $entry) {
    $p = $entry['item'];
    $reason = "Рекомендуем";

    if (in_array($p['id_product'], $history[0] ?? [])) {
        $reason = "Вы недавно смотрели похожие товары";
    } elseif ($entry['score'] >= 30) {
        $reason = "Соответствует вашим интересам: $interests";
    }

    $response[] = [
        "id" => $p['id_product'],
        "name" => $p['Name'],
        "price" => $p['price'] ? number_format($p['price']) . " ₽" : "Нет в наличии",
        "image"image" => "products_image/" . $p['image'],
        "reason" => $reason,
        "link" => "product_page.php?id=" . $p['id_product']
    ];
}

echo json_encode($response, JSON_UNESCAPED_UNICODE);