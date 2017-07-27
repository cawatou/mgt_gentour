<?require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
CModule::IncludeModule("iblock");
CModule::IncludeModule("catalog");
// Логин и пароль от API (tourvisor.ru)
$login = 'i@neoz.su';
$pass = 'jICPOQJ7';
//$test = file_get_contents('http://tourvisor.ru/xml/list.php?type=hotel&hotcountry=1&authlogin='.$login.'&authpass='.$pass.'&format=json');
//$test = json_decode($test, 1);
//echo "<pre>".print_r($test, 1)."</pre>";

if(isset($_REQUEST['get_city'])) {
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/get_city.txt', print_r($_REQUEST['get_city'], 1));
	//Получаем список городов вылета
	$departure = file_get_contents('http://tourvisor.ru/xml/list.php?format=xml&type=departure&authlogin=' . $login . '&authpass=' . $pass . '&format=json');
	$departure = json_decode($departure);
	$departure = $departure->lists->departures->departure;

	echo json_encode($departure);
}

if(isset($_REQUEST['departure_id'])){
    // Получаем список стран
    $country = file_get_contents('http://tourvisor.ru/xml/list.php?format=xml&type=country&cndep='.$_REQUEST['departure_id'].'&authlogin='.$login.'&authpass='.$pass.'&format=json');
    $country = json_decode($country);
    $country = $country->lists->countries->country;
    echo json_encode($country);
}

if(isset($_REQUEST['country_id'])){
    // Получаем список курортов
    $regions = file_get_contents('http://tourvisor.ru/xml/list.php?format=xml&type=region&regcountry='.$_REQUEST['country_id'].'&authlogin='.$login.'&authpass='.$pass.'&format=json');
    $regions = json_decode($regions);
    $regions = $regions->lists->regions->region;
    echo json_encode($regions);
}

// AJAX загрузка витрины туров при выборе города
if(isset($_REQUEST['load_tour']) && isset($_REQUEST['city_id'])){
	$APPLICATION->IncludeComponent("bitrix:news.list",
		"showcase",
		array(
			"IBLOCK_ID" => 20,
			"PROPERTY_CODE" => array(),
			"FILTER_NAME" => 'arrFilter',
			"CACHE_TYPE" => 'N',
			"PRICE_CODE" => array("BASE"),
			"INCLUDE_SUBSECTIONS" => "Y",
			"SHOW_ALL_WO_SECTION" => "Y",
 		),
		false
	);


}

if(isset($_REQUEST['load_siblings'])){
	$APPLICATION->IncludeComponent("bitrix:news.list",
		"showcase_sibling",
		array(
			"IBLOCK_ID" => 20,
			"PROPERTY_CODE" => array(),
			"FILTER_NAME" => 'arrFilter',
			"CACHE_TYPE" => 'N',
			"PRICE_CODE" => array("BASE"),
			"INCLUDE_SUBSECTIONS" => "Y",
			"SHOW_ALL_WO_SECTION" => "Y",
		),
		false 
	);
}

if(isset($_REQUEST['dev'])){
	$date = new DateTime('tomorrow');
	$tomorrow = $date->format('d.m.Y');
	echo $tomorrow;
	/*$arSelect = Array("ID", "NAME", "PROPERTY_group", "PROPERTY_country", "PROPERTY_region", "PROPERTY_star", "PROPERTY_tourvisor_id");
	$arFilter = Array("IBLOCK_ID" => 29, "ACTIVE" => "Y");
	$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize" => 9999), $arSelect);
	while ($ob = $res->GetNextElement()) {
		$hotel = $ob->GetFields();
		$BX_group['tourvisor_id'][] = $hotel['PROPERTY_TOURVISOR_ID_VALUE'];
		$BX_group['group'][$hotel['PROPERTY_TOURVISOR_ID_VALUE']] = $hotel['PROPERTY_GROUP_VALUE'];
	}
	echo "<pre>".print_r($BX_group, 1)."</pre>";*/
}

/*==========================================================================================================*/
// ================== Генерация витрины туров ==============================================================*/
/*==========================================================================================================*/
if(isset($_REQUEST['get_requestid'])) {
    $departure = $_REQUEST['departure'];
    $country = $_REQUEST['country'];
    $regions = $_REQUEST['regions'];
	if(count($regions) > 1) $regions = implode(',', $regions);
	else $regions = $regions[0];

    $date_from = $_REQUEST['date_from'];
    $date_to = $_REQUEST['date_to'];
	$requestid = get_requestid($login, $pass, $departure, $country, $regions, $date_from, $date_to);

	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/log/requestid.txt', $requestid);
	echo $requestid;
}

if(isset($_REQUEST['get_status'])){
	$requestid = explode(',', $_REQUEST['requestid']);
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/requestid.txt', print_r($requestid, 1));
    $status = get_status($login, $pass, $requestid);
    echo $status;
}

if(isset($_REQUEST['get_result'])){
	$requestid = explode(',', $_REQUEST['requestid']);
	$date_from = $_REQUEST['date_from'];
	$date_to = $_REQUEST['date_to'];
	$star_3 = $_REQUEST['hotel3'];
	$star_4 = $_REQUEST['hotel4'];
	$star_5 = $_REQUEST['hotel5'];
	$cat_name = $_REQUEST['cat_name'];
	$departure = $_REQUEST['departure'];
	
	$tours = get_result($login, $pass, $requestid, $date_from, $date_to, $star_3, $star_4, $star_5, $cat_name, $departure);

	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/json/'.$_REQUEST['cat_name'].'-['.$_REQUEST['date_from'].' - '.$_REQUEST['date_to'].'].json', json_encode($tours));
    echo "Создается временный файл туров .. (Не перезагружайте страницу)";
}

if(isset($_REQUEST['new_items'])){
	$cat_name = $_REQUEST['cat_name'];
	$departure = $_REQUEST['departure'];
	$departure_name = $_REQUEST['departure_name'];
	$date_from = $_REQUEST['date_from'];
	$date_to = $_REQUEST['date_to'];
	$country = $_REQUEST['country'];
	$regions = $_REQUEST['regions'];
	if(count($regions) > 1) $regions = implode(',', $regions);
	else $regions = $regions[0];
	$star_3 = $_REQUEST['hotel3'];
	$star_4 = $_REQUEST['hotel4'];
	$star_5 = $_REQUEST['hotel5'];
	// Получаем массив из json'a
    $json = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/dev/json/'.$cat_name.'-['.$date_from.' - '.$date_to.'].json');
    $tours = json_decode($json, 1);
	// Данные для автогенерации тура
	$form_data = $date_from.'_'.$date_to.'_'.$departure.'_'.$country.'_'.$regions.'_'.$star_3.'_'.$star_4.'_'.$star_5.'_'.$departure_name;

	add_tours($cat_name, $departure, $departure_name, $tours, $form_data);
    echo "Туры добавлены.";
}
?>