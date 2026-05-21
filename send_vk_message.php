<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
header('Content-Type: application/json; charset=utf-8');

// Настройки
define('VK_ACCESS_TOKEN', 'vk1.a.u4GqxgzvbelhvjQf52Tj3fw8XcIRWciydcE3v64JyrLcfkzmV67UbokZGe1qKIvnSloLOfR2L_lRI_37ljT7SDmGpuz7o2y_blVjoc21_V3iEJi6zgQAIosSYE730AHVK5kxsxbYK-ZOUWk4LAH1g2yIssUtCSYXIiAn4kmAytny_EBk01ExeHs4JIAXfzqqNwQ30IjPj_e7oHrfRSsMCg');

// КОМУ ОТПРАВЛЯЕМ?
// Вариант 1: отправка в ЛС пользователя (требует, чтобы пользователь написал сообществу первым)
// define('RECIPIENT_USER_ID', 123456789); // положительное число
// Вариант 2: отправка в беседу/чат самого сообщества (если id сообщества 186392170)
define('RECIPIENT_PEER_ID', -186392170); // минус перед ID

// Проверяем метод запроса
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Метод не разрешен']);
    exit;
}

// Получаем данные
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$contact = isset($_POST['contact']) ? trim($_POST['contact']) : '';

if (empty($name) || empty($contact)) {
    http_response_code(400);
    echo json_encode(['error' => 'Имя и контакт обязательны']);
    exit;
}

// Формируем сообщение
$message = "Новая заявка на мероприятие\nИмя: $name\nКонтакт: $contact";

// Уникальный random_id
$random_id = round(microtime(true) * 1000) . mt_rand(1, 999);

// Параметры запроса к VK API
$params = [
    'peer_id'     => RECIPIENT_PEER_ID,
    'message'     => $message,
    'random_id'   => $random_id,
    'v'           => '5.131',
    'access_token'=> VK_ACCESS_TOKEN
];

// Отправка через cURL
$ch = curl_init('https://api.vk.com/method/messages.send');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curl_error = curl_error($ch);
curl_close($ch);

// Логируем ответ (удалите после отладки)
file_put_contents('vk_debug.log', date('Y-m-d H:i:s') . "\nURL: " . http_build_query($params) . "\nHTTP code: $http_code\nCURL error: $curl_error\nResponse: $response\n\n", FILE_APPEND);

if ($curl_error) {
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка cURL: ' . $curl_error]);
    exit;
}

if ($http_code !== 200) {
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка соединения с VK API', 'http_code' => $http_code]);
    exit;
}

$data = json_decode($response, true);
if (isset($data['error'])) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Ошибка VK API',
        'vk_code' => $data['error']['error_code'],
        'vk_msg' => $data['error']['error_msg']
    ]);
    exit;
}

// Успех
echo json_encode(['success' => true, 'response' => $data['response']]);
?>
