<?php
  $name = $_POST['name']; // input name
  $phone = $_POST['phone']; // input phone
 

  $message = "Интерес к мероприятию".PHP_EOL."Имя: ".$name.PHP_EOL."Телефон: ".$phone.; //Обрабатываем данные из формы, для передачи их в письме PHP_EOL - это перенос на другую стороку

  send(23423534,$message); // id беседы с заказчиком

  function send($id , $message) {
    $url = 'https://api.vk.com/method/messages.send';
    $params = array(
      'user_id' => $id, 23290553// Кому отправляем
      'message' => $message,   // Что отправляем
      'access_token' => 'vk1.a.u4GqxgzvbelhvjQf52Tj3fw8XcIRWciydcE3v64JyrLcfkzmV67UbokZGe1qKIvnSloLOfR2L_lRI_37ljT7SDmGpuz7o2y_blVjoc21_V3iEJi6zgQAIosSYE730AHVK5kxsxbYK-ZOUWk4LAH1g2yIssUtCSYXIiAn4kmAytny_EBk01ExeHs4JIAXfzqqNwQ30IjPj_e7oHrfRSsMCg',  
      'v' => '5.62',
    );

    $result = file_get_contents($url, false, stream_context_create(array(
        'http' => array(
          'method'  => 'POST',
          'header'  => 'Content-type: application/x-www-form-urlencoded',
          'content' => http_build_query($params)
        )
    )));
  }
?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Отправка формы</title>
</head>
<body>
	<div class="loader">
		<div class="center">
			<h1 style="text-align: center;">Всё ок!</h1>
		</div>
	</div>
</body>
</html>


