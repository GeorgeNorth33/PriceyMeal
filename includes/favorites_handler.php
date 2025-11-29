<?php
session_start();
require 'db_connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Необходима авторизация']);
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $action = $data['action'] ?? '';
    $product_id = intval($data['product_id'] ?? 0);

    if (!$product_id) {
        echo json_encode(['success' => false, 'message' => 'Неверный ID товара']);
        exit();
    }

    switch ($action) {
        case 'add':
            // Проверяем, не добавлен ли уже товар в избранное
            $check_query = "SELECT id FROM UserFavorites WHERE user_id = ? AND product_id = ?";
            $check_stmt = mysqli_prepare($connection, $check_query);
            mysqli_stmt_bind_param($check_stmt, 'ii', $user_id, $product_id);
            mysqli_stmt_execute($check_stmt);
            mysqli_stmt_store_result($check_stmt);

            if (mysqli_stmt_num_rows($check_stmt) > 0) {
                echo json_encode(['success' => false, 'message' => 'Товар уже в избранном']);
                exit();
            }

            // Добавляем в избранное
            $insert_query = "INSERT INTO UserFavorites (user_id, product_id) VALUES (?, ?)";
            $insert_stmt = mysqli_prepare($connection, $insert_query);
            mysqli_stmt_bind_param($insert_stmt, 'ii', $user_id, $product_id);
            
            if (mysqli_stmt_execute($insert_stmt)) {
                echo json_encode(['success' => true, 'message' => 'Товар добавлен в избранное']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Ошибка при добавлении в избранное']);
            }
            break;

        case 'remove':
            // Удаляем из избранного
            $delete_query = "DELETE FROM UserFavorites WHERE user_id = ? AND product_id = ?";
            $delete_stmt = mysqli_prepare($connection, $delete_query);
            mysqli_stmt_bind_param($delete_stmt, 'ii', $user_id, $product_id);
            
            if (mysqli_stmt_execute($delete_stmt)) {
                echo json_encode(['success' => true, 'message' => 'Товар удален из избранного']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Ошибка при удалении из избранного']);
            }
            break;

        case 'check':
            // Проверяем, добавлен ли товар в избранное
            $check_query = "SELECT id FROM UserFavorites WHERE user_id = ? AND product_id = ?";
            $check_stmt = mysqli_prepare($connection, $check_query);
            mysqli_stmt_bind_param($check_stmt, 'ii', $user_id, $product_id);
            mysqli_stmt_execute($check_stmt);
            mysqli_stmt_store_result($check_stmt);
            
            $is_favorite = mysqli_stmt_num_rows($check_stmt) > 0;
            echo json_encode(['success' => true, 'is_favorite' => $is_favorite]);
            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Неизвестное действие']);
    }
}
?>