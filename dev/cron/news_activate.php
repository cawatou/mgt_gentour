#!/usr/bin/php5
<?
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
$_SERVER['DOCUMENT_ROOT'] = '/var/www/tour/data/www/tour.skipodevelop.com';
$_SERVER['SERVER_NAME'] = 'tour.skipodevelop.com';
require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/include/prolog_before.php");
CModule::IncludeModule("iblock");
$arSelect = Array("IBLOCK_ID", "ID", "NAME", "ACTIVE", "ACTIVE_FROM", "ACTIVE_TO");
$arFilter = Array("IBLOCK_ID"=>30);
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nTopCount"=>6), $arSelect);
while($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    if($arFields['ACTIVE'] == "Y") $enable[] = $arFields;
    else $disable[] = $arFields;
}    

ini_set('date.timezone', 'Europe/Moscow');
$el = new CIBlockElement;
foreach($enable as $element){
    $date_disable = strtotime($element['ACTIVE_TO']);
    $date_now = strtotime(date("Y-m-d H:i:s"));
    if($date_disable <= $date_now){
        $arLoadProductArray = Array("ACTIVE" => 'N');
        $res = $el->Update($element['ID'], $arLoadProductArray);
    }
}

foreach($disable as $element){
    $date_enable = strtotime($element['ACTIVE_FROM']);
    $date_disable = strtotime($element['ACTIVE_TO']);
    $date_now = strtotime(date("Y-m-d H:i:s"));
    if($date_enable <= $date_now && $date_disable >= $date_now){
        $arLoadProductArray = Array("ACTIVE" => 'Y');
        $res = $el->Update($element['ID'], $arLoadProductArray);
    }
}
?>