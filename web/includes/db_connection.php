<?php
$servername = "localhost"; // Например, localhost
$username = "";   // Имя пользователя БД
$password = "";   // Пароль пользователя БД
$dbname = "MealDB";       // Имя базы данных

// Создание подключения
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка подключения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully!";

// После выполнения необходимых операций, не забудьте закрыть подключение
// $conn->close(); 
?>