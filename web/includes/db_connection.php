<?php

$connection = mysqli_connect("web","root","","MealDB") or die ("Ошибка".mysqli_error($connection));


$query_product = "SELECT * FROM `Product`";
$result_PRODUCT = mysqli_query($connection,$query_product);

$query_product_store = "SELECT * FROM `Product Store`";
$result_PRODUCT_STORE = mysqli_query($connection,$query_product_store);

$query_store = "SELECT * FROM `Store`";
$result_STORE = mysqli_query($connection,$query_store);

$query_store_logos = "SELECT sl.*, s.store_name FROM `Store Logos` sl JOIN `Store` s ON sl.id_store = s.id_store";
$result_LOGOS = mysqli_query($connection,$query_store_logos);

$query_users = "SELECT * FROM `users`";
$result_USERS = mysqli_query($connection,$query_users);

$query_cart = "SELECT * FROM `Cart`";
$result_CART = mysqli_query($connection,$query_cart);

$query_cart_product = "SELECT * FROM `Cart Product`";
$result_CART_PRODUCT = mysqli_query($connection,$query_cart_product);

$query_category = "SELECT * FROM `Cart Product`";
$result_CATEGORY = mysqli_query($connection,$query_category);

?>