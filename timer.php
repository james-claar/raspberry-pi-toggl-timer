<?php

require "config.php";

function getData()
{
    global $api_token;
    global $url;
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_URL, "$url");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_USERPWD, "$api_token:api_token");

    $result = curl_exec($ch);

    curl_close ($ch);

    return $result;
}

function formatSeconds($seconds)
{
    $t = round($seconds);
    return sprintf('%02d:%02d:%02d', ($t/3600),($t/60%60), $t%60);
}


$json = getData();

$data = json_decode($json, true)["data"];

$description = $data['description'];
$duration = $data['duration'];

echo json_encode([
    0=>$description,
    1=>$duration
]);