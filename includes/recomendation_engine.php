<?php
class RecommendationEngine {
    private $gigachat;
    private $db;
    
    public function __construct($gigachat, $db_connection) {
        $this->gigachat = $gigachat;
        $this->db = $db_connection;
    }
    
    // Получаем интересы пользователя
    private function getUserInterests($user_id) {
        $query = "SELECT interests FROM UserPreferences WHERE user_id = ?";
        $stmt = mysqli_prepare($this->db, $query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        if ($row = mysqli_fetch_assoc($result)) {
            return $row['interests'];
        }
        
        // Если интересов нет, получаем базовые на основе истории просмотров
        return $this->getInterestsFromHistory($user_id);
    }
    
    // Определяем интересы на основе истории просмотров
    private function getInterestsFromHistory($user_id) {
        $query = "
            SELECT c.name as category_name, COUNT(*) as view_count
            FROM UserViewHistory uh
            JOIN Product p ON uh.product_id = p.id_product
            JOIN Category c ON p.id_product_category = c.id_product_category
            WHERE uh.user_id = ?
            GROUP BY c.name
            ORDER BY view_count DESC
            LIMIT 3
        ";
        
        $stmt = mysqli_prepare($this->db, $query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $interests = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $interests[] = $row['category_name'];
        }
        
        if (!empty($interests)) {
            return "Интересуется категориями: " . implode(", ", $interests);
        }
        
        return "Пользователь еще не определил свои предпочтения";
    }
    
    // Получаем историю просмотров пользователя
    private function getUserViewHistory($user_id) {
        $query = "
            SELECT p.id_product, p.Name, c.name as category_name 
            FROM UserViewHistory uh 
            JOIN Product p ON uh.product_id = p.id_product 
            JOIN Category c ON p.id_product_category = c.id_product_category 
            WHERE uh.user_id = ? 
            ORDER BY uh.viewed_at DESC 
            LIMIT 50
        ";
        $stmt = mysqli_prepare($this->db, $query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $history = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $history[] = $row;
        }
        
        return $history;
    }
    
    // Получаем полный каталог товаров
    private function getProductCatalogue() {
        $query = "
            SELECT 
                p.id_product as id,
                p.Name as name,
                p.description,
                c.name as category,
                MIN(ps.price) as min_price,
                GROUP_CONCAT(DISTINCT s.store_name) as stores,
                COUNT(ps.id_product_store) as available_in_stores
            FROM Product p
            LEFT JOIN Category c ON p.id_product_category = c.id_product_category
            LEFT JOIN `Product Store` ps ON p.id_product = ps.id_product
            LEFT JOIN Store s ON ps.id_store = s.id_store
            GROUP BY p.id_product
            ORDER BY p.id_product
        ";
        
        $result = mysqli_query($this->db, $query);
        $catalogue = [];
        
        while ($row = mysqli_fetch_assoc($result)) {
            $catalogue[] = [
                'id' => (int)$row['id'],
                'name' => $row['name'],
                'category' => $row['category'],
                'min_price' => (float)$row['min_price'],
                'stores' => $row['stores'],
                'available_in_stores' => (int)$row['available_in_stores']
            ];
        }
        
        return $catalogue;
    }
    
    // Формируем промпт для GigaChat
    private function buildPrompt($user_id, $interests, $history, $catalogue) {
        $history_text = "";
        if (!empty($history)) {
            $history_text = "История просмотров пользователя:\n";
            foreach ($history as $item) {
                $history_text .= "- {$item['Name']} (ID: {$item['id_product']}, Категория: {$item['category_name']})\n";
            }
        } else {
            $history_text = "История просмотров пуста";
        }
        
        $catalogue_json = json_encode($catalogue, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        
        $prompt = <<<PROMPT
Ты — помощник по подбору товаров. Проанализируй каталог товаров и выбери те, которые лучше всего подходят под описание интересов пользователя. Также учти его историю просмотров.

Интересы пользователя: {$interests}

История просмотров пользователя: {$history_text}

Полный каталог товаров (в формате JSON): {$catalogue_json}

Инструкции:

1. Отбери TOP-10 самых релевантных товаров.
2. Приоритет отдавай товарам, которые соответствуют интересам И находятся в истории просмотров.
3. Затем товарам, которые соответствуют интересам.
4. Затем товарам из категорий, которые пользователь просматривал.
5. В результате предоставь JSON-массив только с ID отобранных товаров и кратким обоснованием релевантности (1 предложение) для каждого.
6. Выведи результат в формате JSON: [ {"id": "ID товара", "name": "Наименование товара", "reason": "Соответствует интересу в молочных продуктах"}, ... ]

ВАЖНО: Верни ТОЛЬКО JSON-массив, без дополнительного текста!
PROMPT;

        return $prompt;
    }
    
    // Основной метод для получения рекомендаций
    public function getRecommendations($user_id) {
        try {
            // Получаем данные пользователя
            $interests = $this->getUserInterests($user_id);
            $history = $this->getUserViewHistory($user_id);
            $catalogue = $this->getProductCatalogue();
            
            // Формируем промпт
            $prompt = $this->buildPrompt($user_id, $interests, $history, $catalogue);
            
            // Отправляем запрос к GigaChat
            $response = $this->gigachat->sendMessage($prompt);
            
            // Парсим JSON ответ
            $recommendations = $this->parseGigaChatResponse($response);
            
            // Сохраняем рекомендации в базу
            $this->saveRecommendations($user_id, $recommendations);
            
            return $recommendations;
            
        } catch (Exception $e) {
            error_log("Ошибка рекомендательной системы: " . $e->getMessage());
            return $this->getFallbackRecommendations($user_id);
        }
    }
    
    // Парсим ответ от GigaChat
    private function parseGigaChatResponse($response) {
        // Ищем JSON в ответе
        preg_match('/\[\s*\{.*\}\s*\]/s', $response, $matches);
        
        if (isset($matches[0])) {
            $json_string = $matches[0];
        } else {
            $json_string = $response;
        }
        
        $recommendations = json_decode($json_string, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Ошибка парсинга JSON от GigaChat: " . json_last_error_msg());
        }
        
        return $recommendations;
    }
    
    // Сохраняем рекомендации в базу
    private function saveRecommendations($user_id, $recommendations) {
        if (empty($recommendations)) return;
        
        // Удаляем старые рекомендации
        $delete_query = "DELETE FROM UserRecommendations WHERE user_id = ?";
        $stmt = mysqli_prepare($this->db, $delete_query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        
        // Сохраняем новые рекомендации
        $insert_query = "
            INSERT INTO UserRecommendations (user_id, product_id, reason, ranking, created_at) 
            VALUES (?, ?, ?, ?, NOW())
        ";
        
        $stmt = mysqli_prepare($this->db, $insert_query);
        $ranking = 1;
        
        foreach ($recommendations as $item) {
            if (isset($item['id']) && isset($item['reason'])) {
                mysqli_stmt_bind_param($stmt, "iisi", 
                    $user_id, 
                    $item['id'], 
                    $item['reason'], 
                    $ranking
                );
                mysqli_stmt_execute($stmt);
                $ranking++;
            }
        }
    }
    
    // Резервные рекомендации (если GigaChat недоступен)
    private function getFallbackRecommendations($user_id) {
        // Рекомендации на основе истории просмотров
        $query = "
            SELECT 
                p.id_product as id,
                p.Name as name,
                CONCAT('Рекомендуем на основе вашего интереса к ', c.name) as reason
            FROM UserViewHistory uh
            JOIN Product p ON uh.product_id = p.id_product
            JOIN Category c ON p.id_product_category = c.id_product_category
            WHERE uh.user_id = ?
            GROUP BY p.id_product
            ORDER BY COUNT(*) DESC
            LIMIT 10
        ";
        
        $stmt = mysqli_prepare($this->db, $query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $recommendations = [];
        $ranking = 1;
        
        while ($row = mysqli_fetch_assoc($result)) {
            $recommendations[] = [
                'id' => (int)$row['id'],
                'name' => $row['name'],
                'reason' => $row['reason'],
                'ranking' => $ranking++
            ];
        }
        
        // Если истории нет, рекомендуем популярные товары
        if (empty($recommendations)) {
            $query = "
                SELECT 
                    p.id_product as id,
                    p.Name as name,
                    'Популярный товар' as reason
                FROM Product p
                LEFT JOIN `Product Store` ps ON p.id_product = ps.id_product
                GROUP BY p.id_product
                ORDER BY COUNT(ps.id_product_store) DESC
                LIMIT 10
            ";
            
            $result = mysqli_query($this->db, $query);
            $ranking = 1;
            
            while ($row = mysqli_fetch_assoc($result)) {
                $recommendations[] = [
                    'id' => (int)$row['id'],
                    'name' => $row['name'],
                    'reason' => $row['reason'],
                    'ranking' => $ranking++
                ];
            }
        }
        
        return $recommendations;
    }
    
    // Получаем сохраненные рекомендации
    public function getSavedRecommendations($user_id) {
        $query = "
            SELECT 
                r.product_id as id,
                p.Name as name,
                r.reason,
                r.ranking
            FROM UserRecommendations r
            JOIN Product p ON r.product_id = p.id_product
            WHERE r.user_id = ?
            ORDER BY r.ranking ASC
        ";
        
        $stmt = mysqli_prepare($this->db, $query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        
        $recommendations = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $recommendations[] = [
                'id' => (int)$row['id'],
                'name' => $row['name'],
                'reason' => $row['reason'],
                'ranking' => (int)$row['ranking']
            ];
        }
        
        return $recommendations;
    }
}
?>