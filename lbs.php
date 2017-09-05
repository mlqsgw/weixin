<?php
//定义接口地址
//$url = "http://api.map.baidu.com/parking/search?ak=UxjZsry2jxLGm84OXXK3Y2shtqNONUYM&location=116.313064,40.048541&coordtype=bd09ll";
$url = "http://api.map.baidu.com/geocoder/v2/?batch=false&location=30.735580,120.804909&output=json&pois=0&ak=UxjZsry2jxLGm84OXXK3Y2shtqNONUYM";
//模拟http中get请求
$str = file_get_contents($url);
//$str = trim($str,"renderReverse&&renderReverse()");
$str = json_decode($str,true);
$address_one = $str['result']['formatted_address'];
$address_two = $str['result']['sematic_description'];
$address = $address_one.$address_two;
print_r($address);