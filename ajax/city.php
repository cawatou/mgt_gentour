<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->RestartBuffer();



if(!isset($_REQUEST['city'])) die('die');
$check = CIBlockSection::GetByID(IntVal($_REQUEST['city']));
if($ar_res = $check->GetNext()) $APPLICATION->set_cookie("CITY_ID", $_REQUEST['city'], time()+60*60*24*30*12*2);
else $APPLICATION->set_cookie("CITY_ID", 95, time()+60*60*24*30*12*2);



CModule::IncludeModule("iblock");
$arSelect1 = Array("ID","NAME","PREVIEW_PICTURE","IBLOCK_ID","PROPERTY_*");
$arFilter1 = Array("SECTION_ID"=>IntVal($_REQUEST['city']));
$res1 = CIBlockElement::GetList(Array(), $arFilter1, false, Array("nPageSize"=>50), $arSelect1);

function vk_fans_count($vkID, $iscount = true) {
	$json_string = file_get_contents('http://api.vk.com/method/groups.getById?gid='.$vkID.'&fields=members_count');
	$json = json_decode($json_string, true);
	if($iscount){
	    return $json['response'][0]['members_count'];
	}
	else {
	    return $json['response'][0]['screen_name'];
	}
}


$arr = array();
$i=0;
	while($ob = $res1->GetNextElement())
	{
		 $arFields2 = $ob->GetFields();
		// print_r($arFields2);
		 $arProps = $ob->GetProperties();
		// print_r($arProps);
		
		$row['id'] = $arFields2['ID'];
		$row['name'] = $arFields2['NAME'];
		$row['pic'] = CFile::GetPath($arFields2["PREVIEW_PICTURE"]);
		$row['adr'] = $arProps['office_adres']['VALUE'];
		$row['fname'] = $arProps['office_address']['VALUE'];
		$row['phone'] = $arProps['office_phone']['VALUE'];
		$row['phone2'] = $arProps['office_phone2']['VALUE'];
		$row['time'] = $arProps['office_hours']['VALUE'];
		$row['holiday'] = $arProps['office_holiday']['VALUE'];
		$row['metro'] = $arProps['office_metro']['VALUE'];
		if($arProps['vk']['VALUE']!="") $row['vk'] = vk_fans_count($arProps['vk']['VALUE'], false);
		if($arProps['inst']['VALUE']!="") $row['inst'] = $arProps['inst']['VALUE'];
		if($arProps['ok']['VALUE']!="") $row['ok'] = $arProps['ok']['VALUE'];
		if($arProps['fb']['VALUE']!="") $row['fb'] = $arProps['fb']['VALUE'];
	
		$MAP = explode(",", $arProps["office_latlong"]["VALUE"]);
		$row['pl'][$i]["LON"] = $MAP[1]; //Заполняем массив маркера данными
        $row['pl'][$i]["LAT"] = $MAP[0];
		$row['pl'][$i]["TEXT"] = $arProps['office_adres']['VALUE'];
	
		if($row['adr']!='') array_push($arr, $row);
		$i++;
	}



echo json_encode($arr);