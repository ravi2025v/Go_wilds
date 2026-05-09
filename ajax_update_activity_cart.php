<?php
session_name("GoWilds_Session");
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['activity_cart'])) {
    $_SESSION['activity_cart'] = [];
}

$action = $_POST['action'] ?? '';
$id = intval($_POST['id'] ?? 0);
$title = $_POST['title'] ?? '';
$price = floatval($_POST['price'] ?? 0);

if ($action === 'add' && $id > 0) {
    $_SESSION['activity_cart'][$id] = [
        'title' => $title,
        'price' => $price
    ];
} elseif ($action === 'remove' && $id > 0) {
    unset($_SESSION['activity_cart'][$id]);
} elseif ($action === 'clear') {
    $_SESSION['activity_cart'] = [];
}

echo json_encode([
    'success' => true,
    'cart_count' => count($_SESSION['activity_cart']),
    'cart_total' => array_sum(array_column($_SESSION['activity_cart'], 'price'))
]);
?>
