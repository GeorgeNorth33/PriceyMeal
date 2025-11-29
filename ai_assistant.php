<?php
session_start();
require 'includes/db_connection.php';
require 'includes/gigachat_config.php';
require 'includes/gigachat_api.php';

if ($_POST['action'] === 'ask_ai') {
    header('Content-Type: application/json');
    
    try {
        // Инициализируем GigaChat
        $gigachat = new GigaChatAPI(
            GIGACHAT_CLIENT_ID,
            GIGACHAT_CLIENT_SECRET,
            GIGACHAT_AUTH_CODE
        );
        
        $user_message = $_POST['message'];
        
        // Получаем список товаров для контекста
        $products_query = "
            SELECT p.Name as name, 
                   MIN(ps.price) as min_price,
                   c.name as category
            FROM Product p 
            LEFT JOIN `Product Store` ps ON p.id_product = ps.id_product 
            LEFT JOIN Category c ON p.id_product_category = c.id_product_category
            GROUP BY p.id_product
            LIMIT 10
        ";
        
        $products_result = mysqli_query($connection, $products_query);
        $products = [];
        
        while ($product = mysqli_fetch_assoc($products_result)) {
            $products[] = $product;
        }
        
        // Получаем ответ от AI
        $response = $gigachat->analyzeProducts($products, $user_message);
        
        echo json_encode([
            'success' => true,
            'response' => $response
        ]);
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
    
    exit;
}
?>