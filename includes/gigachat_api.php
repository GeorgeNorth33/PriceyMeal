<?php
class GigaChatAPI {
    private $client_id;
    private $client_secret;
    private $auth_code;
    private $access_token;
    private $api_url = 'https://gigachat.devices.sberbank.ru/api/v1';
    
    public function __construct($client_id, $client_secret, $auth_code) {
        $this->client_id = $client_id;
        $this->client_secret = $client_secret;
        $this->auth_code = $auth_code;
        $this->getAccessToken();
    }
    
    private function getAccessToken() {
        // Проверяем, есть ли сохраненный токен
        if (isset($_SESSION['gigachat_access_token']) && 
            isset($_SESSION['gigachat_token_expires']) && 
            $_SESSION['gigachat_token_expires'] > time()) {
            $this->access_token = $_SESSION['gigachat_access_token'];
            return;
        }
        
        // Получаем новый токен
        $url = 'https://ngw.devices.sberbank.ru:9443/api/v2/oauth';
        
        $headers = [
            'Authorization: Basic ' . base64_encode($this->client_id . ':' . $this->client_secret),
            'RqUID: ' . $this->generateRqUID(),
            'Content-Type: application/x-www-form-urlencoded'
        ];
        
        $data = http_build_query([
            'scope' => 'GIGACHAT_API_PERS'
        ]);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_code === 200) {
            $result = json_decode($response, true);
            $this->access_token = $result['access_token'];
            
            // Сохраняем токен в сессии
            $_SESSION['gigachat_access_token'] = $this->access_token;
            $_SESSION['gigachat_token_expires'] = time() + $result['expires_in'] - 60; // минус 60 секунд для запаса
        } else {
            throw new Exception('Ошибка получения токена: ' . $response);
        }
    }
    
    private function generateRqUID() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
    
    public function sendMessage($message, $context = []) {
        $url = $this->api_url . '/chat/completions';
        
        $headers = [
            'Authorization: Bearer ' . $this->access_token,
            'Content-Type: application/json'
        ];
        
        $messages = $context;
        $messages[] = [
            'role' => 'user',
            'content' => $message
        ];
        
        $data = [
            'model' => 'GigaChat',
            'messages' => $messages,
            'temperature' => 0.7,
            'max_tokens' => 512
        ];
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_code === 200) {
            $result = json_decode($response, true);
            return $result['choices'][0]['message']['content'];
        } else {
            throw new Exception('Ошибка API GigaChat: ' . $response);
        }
    }
    
    // Метод для анализа продуктов и рекомендаций
    public function analyzeProducts($products, $user_query) {
        $context = "Ты помощник в интернет-магазине продуктов. Анализируй товары и давай рекомендации.";
        
        $products_info = "";
        foreach ($products as $product) {
            $products_info .= "Товар: {$product['name']}, Цена: от {$product['min_price']} руб., Категория: {$product['category']}\n";
        }
        
        $message = "Пользователь спрашивает: '{$user_query}'\n\nДоступные товары:\n{$products_info}\n\nДайте рекомендации и ответьте на вопрос пользователя.";
        
        return $this->sendMessage($message, [['role' => 'system', 'content' => $context]]);
    }
}
?>