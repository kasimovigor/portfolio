<?php
// Настройки
define('VK_ACCESS_TOKEN', 'vk1.a.u4GqxgzvbelhvjQf52Tj3fw8XcIRWciydcE3v64JyrLcfkzmV67UbokZGe1qKIvnSloLOfR2L_lRI_37ljT7SDmGpuz7o2y_blVjoc21_V3iEJi6zgQAIosSYE730AHVK5kxsxbYK-ZOUWk4LAH1g2yIssUtCSYXIiAn4kmAytny_EBk01ExeHs4JIAXfzqqNwQ30IjPj_e7oHrfRSsMCg');
define('YOUR_USER_ID', club186392170); // ваш числовой ID

// Получаем данные из POST-запроса
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$contact = isset($_POST['contact']) ? trim($_POST['contact']) : '';

if (empty($name) || empty($contact)) {
    http_response_code(400);
    echo json_encode(['error' => 'Имя и контакт обязательны']);
    exit;
}

// Формируем текст сообщения
$message = "Новая заявка на мероприятие\nИмя: $name\nКонтакт: $contact";

// Отправляем сообщение через VK API
$params = [
    'user_id' => YOUR_USER_ID,
    'message' => $message,
    'random_id' => rand(1, 999999),
    'v' => '5.131',
    'access_token' => VK_ACCESS_TOKEN
];

$url = 'https://api.vk.com/method/messages.send?' . http_build_query($params);
$response = file_get_contents($url);

// Проверяем результат
if ($response) {
    echo json_encode(['success' => true]);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Ошибка отправки сообщения']);
}
?>