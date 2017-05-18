#!/usr/bin/php5
<?
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
$_SERVER['DOCUMENT_ROOT'] = '/var/www/tour/data/www/tour.skipodevelop.com';
$_SERVER['SERVER_NAME'] = 'tour.skipodevelop.com';

file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/autogen.txt',  "write file  - ".date("d.m.Y H:i")."\r\n", FILE_APPEND);
/*
$start = microtime(true);
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
*/

$login = 'i@neoz.su';
$pass = 'jICPOQJ7';


require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");

$tours_sections = CIBlockSection::GetList(array(), array('IBLOCK_ID' => 20, "UF_GENERATED" => 0, "ACTIVE" => "Y"), false, array('ID', 'UF_FORM_DATA', 'NAME'));
while ($arSection = $tours_sections->Fetch()) {
    //echo "<pre>".print_r($arSection, 1)."</pre>";
    // Получаем id категории отелей из битрикса
    $hotels_sections = CIBlockSection::GetList(array(), array('IBLOCK_ID' => 23, "UF_TOUR_ID" => $arSection['ID']), false, array('ID'));
    while ($hotel_field = $hotels_sections->Fetch()) $hotelsection_id = $hotel_field['ID'];

    $form_data[] = $arSection['UF_FORM_DATA'];
    $form_fields = explode("_", $arSection['UF_FORM_DATA']);
    $today = date('d.m.Y');

    // Проверка дат
    if(strtotime($form_fields[1]) < strtotime($today)){
        $delete_forever[] = Array('tour_id' => $arSection['ID'], 'hotel_id' => $hotelsection_id);
        continue;
    }
    if($form_fields[0] < $today) $form_fields[0] = $today;

    $form_fields['tour_id'] = $arSection['ID'];
    $form_fields['hotel_id'] = $hotelsection_id;
    $form_fields['cat_name'] = $arSection['NAME'];
    
    $sections[] = $form_fields;
}

//echo "<pre>".print_r($sections, 1)."</pre>";


//file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/sections.txt', print_r($sections, 1));
//die();
// Получаем весь список запросов из турвизора
foreach($sections as $section){
    $date_from = $section[0];
    $date_to = $section[1];
    $departure = $section[2];
    $country = $section[3];
    $regions = $section[4];
    $requestid[] = get_requestid($login, $pass, $departure, $country, $regions, $date_from, $date_to);
}

//file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/requestid_cron.txt', print_r($requestid, 1));

//echo "<pre>".print_r($sections, 1)."</pre>";


sleep(120);
// Получаем результаты из турвизора
foreach ($requestid as $k => $rid){
    if($rid == '') {
        $all_tours[] = 0;
        continue;
    }
    $rid = explode(',', $rid);
    
    $date_from = $sections[$k][0];
    $date_to = $sections[$k][1];
    $star_3 = $sections[$k][5];
    $star_4 = $sections[$k][6];
    $star_5 = $sections[$k][7];
    $cat_name = $sections[$k]['cat_name'];
    
    $status[] = get_status($login, $pass, $requestid);
    $all_tours[] = get_result($login, $pass, $rid, $date_from, $date_to, $star_3, $star_4, $star_5, $cat_name);
}


file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/all_tours.txt', print_r($all_tours, 1));
//die();

// Удаляем и записываем новые элементы
foreach($sections as $k => $section){
    if($all_tours[$k] == 0) continue;
    CIBlockSection::Delete($section['tour_id']);
    CIBlockSection::Delete($section['hotel_id']);
    $cat_name = $section['cat_name'];
    $departure = $section[2];
    $departure_name = $section[8];
    add_tours($cat_name, $departure, $departure_name, $all_tours[$k], $form_data[$k]);
}


//echo "<pre>".print_r($requestid, 1)."</pre>";
//echo "<pre>".print_r($sections, 1)."</pre>";
//echo "<pre>".print_r($status, 1)."</pre>";
//echo "<pre>".print_r($all_tours, 1)."</pre>";
//$end = microtime(true);
//echo "<hr /> Script execution time: ".($end-$start)." sec!";
//echo "<hr />";
file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/endautogen.txt',  "wriда она te file  - ".date("d.m.Y H:i")."\r\n", FILE_APPEND);
?>