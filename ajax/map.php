<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->RestartBuffer();

$id = $_REQUEST['id'];


CModule::IncludeModule("iblock");
$arSelect1 = Array("ID","NAME","PREVIEW_PICTURE","IBLOCK_ID","PROPERTY_*");
$arFilter1 = Array("ID"=>IntVal($id));
$res1 = CIBlockElement::GetList(Array(), $arFilter1, false, Array("nPageSize"=>50), $arSelect1);


$arr = array();
$i=0;
	while($ob = $res1->GetNextElement())
	{
		 $arFields2 = $ob->GetFields();
		// print_r($arFields2);
		 $arProps = $ob->GetProperties();
		// print_r($arProps);
	
	
		$MAP = explode(",", $arProps["office_latlong"]["VALUE"]);
		
	}

$PLACEMARKS = array(
	array(
	'TEXT' => $arProps['office_adres']['VALUE'], 
	'LON' => $MAP[1], 
	'LAT' => $MAP[0]
	),
 );
	$APPLICATION->IncludeComponent("bitrix:map.google.view",".default",array(
    "API_KEY" => "AIzaSyDVcwlJJsdy7gvq6LePrBSLE5UvPuIqrvg",
	"INIT_MAP_TYPE" => "MAP",
	"MAP_DATA" => serialize(
                  array(
                     'google_lat' => $MAP[0], // координаты центра карты
                     'google_lon' =>  $MAP[1], // используем координаты последнего маркера
                     'google_scale' => 10, // масштаб карты 0-20
                     'PLACEMARKS' => $PLACEMARKS // подготовленный ранее массив маркеров
                     )
               ),
    "MAP_WIDTH" => "100%",
    "MAP_HEIGHT" => "558px",
	"CONTROLS" => array(
		"SMALL_ZOOM_CONTROL",
		"TYPECONTROL",
		"SCALELINE"
	),
    "OPTIONS" => array(
            "ENABLE_DBLCLICK_ZOOM",
            "ENABLE_DRAGGING",
            "ENABLE_KEYBOARD"
        ),
    "MAP_ID" => "googlemaps".$_REQUEST['id']
    )
	);
	?>