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
	$tt = 45455;
	$tt = floor($tt / 100) * 100;
	echo $tt;

	/*
	$params = Array(
		'authlogin' => $login,
		'authpass' => $pass,
		'departure' => 1,
		'country' => 16,
		'datefrom' => '30.05.2017',
		'dateto' => '04.06.2017',
		'regions' => 134,
		'nightsfrom' => 2,
		'nightsto' => 5,
		'format' => 'json',
	);
	foreach ($params as $k => $value) {
		if ($get == '') $get = $k . '=' . $value;
		else $get = $get . '&' . $k . '=' . $value;
	}
	//$get = 'authlogin=i@neoz.su&authpass=jICPOQJ7&departure=10&country=2&datefrom=17.05.2017&dateto=17.05.2017&regions=7&nightsfrom=7&nightsto=7&format=json';
	echo "<pre>".print_r($get, 1)."</pre>";
	$json = file_get_contents('http://tourvisor.ru/xml/search.php?' . $get);
	$json = json_decode($json, 1);
	echo "<pre>".print_r($json, 1)."</pre>";
	$requestid = $json['result']['requestid'];
	echo $requestid;
	sleep(10);
	$json = file_get_contents('http://tourvisor.ru/xml/result.php?authlogin=' . $login . '&authpass=' . $pass . '&requestid=' . $requestid . '&type=result&onpage=100&format=json');
	$json = json_decode($json, 1);
	$result[$id] = $json['data']['result']['hotel'];
	echo "<pre>".print_r($result, 1)."</pre>";


	$json = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/dev/json/Казань - Таиланд(Паттайя)-[04.04.2017 - 04.06.2017].json');
    $tours = json_decode($json, 1);
	$star_3 = 10;
	$star_4 = 7;
	$star_5 = 3;
	$tours = hotels_filter($tours, $star_3, $star_4, $star_5);
    echo "<pre>".print_r($tours, 1)."</pre>";
*/
}

/*==========================================================================================================*/
// ================== Генерация витрины туров ==============================================================*/
/*==========================================================================================================*/
if(isset($_REQUEST['get_requestid'])) {
//	$departure = 1;
//	$country = 2;
//	$regions = 8;
//	$date_from = '31.08.2017';
//	$date_to = '11.11.2017';

    $departure = $_REQUEST['departure'];
    $country = $_REQUEST['country'];
    $regions = $_REQUEST['regions'];
    $date_from = $_REQUEST['date_from'];
    $date_to = $_REQUEST['date_to'];

	$start_time = new DateTime($date_from);
	$end_time = new DateTime($date_to);
	$temp_time = new DateTime($date_from);
	$interval = $start_time->diff($end_time)->days;
	if($interval < 56){
		$interval = 56; // 8 недель
		$end_time = $temp_time->modify('+'.$interval.' day');
		$temp_time = new DateTime($date_from);
	}
	$iteration = ceil($interval/14);

	for($i=1; $i<=$iteration; $i++){
		if($i == 1) $temp_time->modify('+13 day');
		else $temp_time->modify('+14 day');

		if($i == $iteration) $temp_time = $end_time;
		$date_interval['date'][] = Array($start_time->format('d.m.Y'), $temp_time->format('d.m.Y'));
		$start_time->modify('+14 day');
	}

	for($i=1; $i<=4; $i++){
		switch ($i) {
			case 1:
				$date_tours['n2_5']['nights'] = Array('2', '5');
				$date_tours['n2_5']['date'] = $date_interval['date'];
				break;
			case 2:
				$date_tours['n6_8']['nights'] = Array('6', '8');
				$date_tours['n6_8']['date'] = $date_interval['date'];
				break;
			case 3:
				$date_tours['n9_14']['nights'] = Array('9', '14');
				$date_tours['n9_14']['date'] = $date_interval['date'];
				break;
			case 4:
				$date_tours['n15_20']['nights'] = Array('15', '20');
				$date_tours['n15_20']['date'] = $date_interval['date'];
				break;
		}
	}
	$requestid = $get = '';
	foreach($date_tours as $duration){
		foreach($duration['date'] as $date){
			$params = Array(
				'authlogin' => $login,
				'authpass' => $pass,
				'departure' => $departure,
				'country' => $country,
				'regions' => $regions,
				'datefrom' => $date[0],
				'dateto' => $date[1],
				'nightsfrom' => $duration["nights"]['0'],
				'nightsto' => $duration["nights"]['1'],
				'format' => 'json',
			);
			foreach ($params as $k => $value) {
				if ($get == '') $get = $k . '=' . $value;
				else $get = $get . '&' . $k . '=' . $value;
			}
			file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/get.txt', $get."\r\n", FILE_APPEND);
			$json = file_get_contents('http://tourvisor.ru/xml/search.php?' . $get);
			$json = json_decode($json, 1);

			file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/json.txt', print_r($json, 1)."\r\n", FILE_APPEND);

			if($requestid == '') $requestid = $json['result']['requestid'];
			else $requestid = $requestid.','.$json['result']['requestid'];
			$get = '';
		}
	}
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/array_reqid.txt', print_r($date_tours, 1));
	echo $requestid;
}

if(isset($_REQUEST['get_status'])){
	$requestid = explode(',', $_REQUEST['requestid']);
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/requestid.txt', print_r($requestid, 1));
    foreach($requestid as $k => $id) {
        $json = file_get_contents('http://tourvisor.ru/xml/result.php?authlogin=' . $login . '&authpass=' . $pass . '&requestid=' . $id . '&type=status&format=json');
        $json = json_decode($json, 1);
		file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/status.txt', $json['data']['status']['progress']."\r\n", FILE_APPEND);
		if($status == '') $status = $json['data']['status']['progress'];
        else $status = $status + $json['data']['status']['progress'];
    }
	$status = $status / count($requestid);
    echo $status;
}

if(isset($_REQUEST['get_result'])){
	// Если самое дешевое предложение не больше 5 дней период от 2 до 5 дней не учитывается ($n6_8 прибавляется на 1 => $n6_8 = 3)
	$n2_5 = 1; // Одна дата на продолжительность от 2 до 5 дней (аналогично остальные)
	$n6_8 = 2;
	$n9_14 = 4;
	$n15_20 = 1;
	$temp['min_price'] = 0;
	$requestid = explode(',', $_REQUEST['requestid']);
	foreach($requestid as $k => $id) {
		 $json = file_get_contents('http://tourvisor.ru/xml/result.php?authlogin=' . $login . '&authpass=' . $pass . '&requestid=' . $id . '&type=result&onpage=1000&format=json');
		 $json = json_decode($json, 1);
		 if(isset($json['data']['result']['hotel'])) $result[$id] = $json['data']['result']['hotel'];
		 else $result[$id] = 0;
		 //unset($json);
	}
	$empty = 0;
	foreach($result as $item){
		if($item == 0) $empty++;
	}
	if(count($result) == $empty) exit('empty');
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/result.txt', print_r($result, 1));

	/**===========================================================================================================
      *          Находми первое предложение (Самое дешевое)
      * ======================================================================================================= **/
	foreach($result as $k => $requestid){
		foreach ($requestid as $hotel){
			// Находим минимальную цену и записываем id запроса, количество дней и дату для поиска остальных отелей
			if($temp['min_price'] == 0 || $temp['min_price'] > $hotel['price']) {
				$temp['min_price'] = $hotel['price'];
				$temp['requestid'] = $k;
				$temp['date'] = $hotel['tours']['tour'][0]['flydate'];
				$temp['nights'] = $hotel['tours']['tour'][0]['nights'];
			}
		}
	}

	if($temp['nights'] > 5 && $temp['nights'] < 9){
		$n6_8 = 2; // Прибавим и сразу же отнимаем
		$first_n6_8 = $temp['requestid'];
	}elseif($temp['nights'] > 8 && $temp['nights'] < 15 ){
		$n6_8 = 3;
		$n9_14 = 3;
	}elseif($temp['nights'] > 15){
		$n6_8 = 3;
		$n15_20 = 0;
	}

	if(count($temp) > 1) $tours[$temp['requestid']] = compose_tour($temp, $temp['requestid']);

	foreach($result[$temp['requestid']] as $hotel){
		foreach ($hotel['tours']['tour'] as $tour){
			if($tour['flydate'] == $temp['date'] && $tour['nights'] == $temp['nights']){
				$tours[$temp['requestid']]['hotels'][] = compose_hotel($hotel, $tour);
				break;
			}
		}
	}
	$temp = Array();
	$temp['min_price'] = 0;


	/**===========================================================================================================
	 *          Находми второе предложение (Самая ближайщая дата + 4 дня и Самое дешевое)
	 * ======================================================================================================= **/
//	$date_from = '31.08.2017';
//	$date_to = '11.11.2017';
    $date_from = $_REQUEST['date_from'];
    $date_to = $_REQUEST['date_to'];
	$start_time = new DateTime($date_from);
	$end_time = new DateTime($date_to);
	$interval = $start_time->diff($end_time)->days;
	$iteration = ceil($interval/14);
	$period[] = $start_time->format('d.m.Y');
	// Добавляем еще 3 дня
	for($i=0; $i<3; $i++){
		$start_time->modify('+1 day');
		$period[] = $start_time->format('d.m.Y');
	}

	// Найдем все отели с любой продолжительностью ночей из первого двухнедельного интервала
	$i = 0;
	$requestid = explode(',', $_REQUEST['requestid']);
	foreach($requestid as $k => $id){
		$surplus = $i % $iteration;
		if($surplus == 0){
			$temp['requestid'][] = $id;
		}
		$i++;
	}

	foreach($temp['requestid'] as $req_id){
		foreach($result[$req_id] as $hotels){
			foreach($hotels['tours']['tour'] as $tour){
				if(in_array($tour['flydate'], $period) && $tour['nights'] > 5){
					if($temp['min_price'] == 0 || $temp['min_price'] > $tour['price']) {
						$temp['min_price'] = $tour['price'];
						$temp['requestid'] = $req_id;
						$temp['date'] = $tour['flydate'];
						$temp['nights'] = $tour['nights'];
					}
				}
			}
		}
	}

	if($temp['nights'] > 5 && $temp['nights'] < 9){
		$n6_8--;
		if(!isset($first_n6_8)) $first_n6_8 = $temp['requestid'];
	}elseif($temp['nights'] > 8 && $temp['nights'] < 15 ){
		$n9_14--;
	}elseif($temp['nights'] > 15){
		$n15_20 = 0;
	}

	if(count($temp) > 1) $tours[$temp['requestid']] = compose_tour($temp, $temp['requestid']);

	foreach($result[$temp['requestid']] as $hotel){
		if(!isset($hotel)) continue;
		foreach ($hotel['tours']['tour'] as $tour){
			if($tour['flydate'] == $temp['date'] && $tour['nights'] == $temp['nights']){
				$tours[$temp['requestid']]['hotels'][] = compose_hotel($hotel, $tour);
				break;
			}
		}
	}
	$temp = Array();
	$temp['min_price'] = 0;


	/**================================================================================================================
	 * 					Находми предложения продолжительностю от 6 до 8 ночей
	 * ============================================================================================================ **/
	// Определяем в каких массивах искать
	for($i=1; $i<=$n6_8; $i++){
		$cnt = 0;
		foreach($result as $k => $requestid){
			if($cnt >= $iteration && $cnt < $iteration*2 && $first_n6_8 != $k && !array_key_exists($k, $tours)){
				foreach ($requestid as $hotel){
					//начало периода (от 6 до 8 дней)
					if(!isset($hotel)) continue;
					if($temp['min_price'] == 0 || $temp['min_price'] > $hotel['price']) {
						$temp['min_price'] = $hotel['price'];
						$temp['requestid'] = $k;
						$temp['date'] = $hotel['tours']['tour'][0]['flydate'];
						$temp['nights'] = $hotel['tours']['tour'][0]['nights'];
					}
				}
			}
			$cnt++; // Для поиска начала периода (от 6 до 8 дней)
		}

		if(count($temp) > 1) $tours[$temp['requestid']] = compose_tour($temp, $temp['requestid']);

		foreach($result[$temp['requestid']] as $hotel){
			if(!isset($hotel)) continue;
			foreach ($hotel['tours']['tour'] as $tour){
				if($tour['flydate'] == $temp['date'] && $tour['nights'] == $temp['nights']){
					$tours[$temp['requestid']]['hotels'][] = compose_hotel($hotel, $tour);
					break;
				}
			}
		}

		$temp = Array();
		$temp['min_price'] = 0;
	}
	$n6_8 = 0;

	/**================================================================================================================
	 * 					Находми предложения продолжительностю от 9 до 14 ночей
	 * ============================================================================================================ **/
	for($i=1; $i <= $n9_14; $i++){
		$cnt = 0;
		foreach($result as $k => $requestid){
			//начало периода (от 9 до 14 дней)
			if($cnt >= $iteration*2 && $cnt < $iteration*3 && !array_key_exists($k, $tours)){
				foreach ($requestid as $hotel){
					if(!isset($hotel)) continue;
					if($temp['min_price'] == 0 || $temp['min_price'] > $hotel['price']) {
						$temp['min_price'] = $hotel['price'];
						$temp['requestid'] = $k;
						$temp['date'] = $hotel['tours']['tour'][0]['flydate'];
						$temp['nights'] = $hotel['tours']['tour'][0]['nights'];
					}
				}
			}
			$cnt++; // Для поиска начала периода (от 9 до 14 дней)

		}

		if(count($temp) > 1) $tours[$temp['requestid']] = compose_tour($temp, $temp['requestid']);

		foreach($result[$temp['requestid']] as $hotel){
			if(!isset($hotel)) continue;
			foreach ($hotel['tours']['tour'] as $tour){
				if($tour['flydate'] == $temp['date'] && $tour['nights'] == $temp['nights']){
					$tours[$temp['requestid']]['hotels'][] = compose_hotel($hotel, $tour);
					break;
				}
			}
		}

		$temp = Array();
		$temp['min_price'] = 0;
	}
	$n9_14 = 0;


	/**================================================================================================================
	 * 					Находми предложения продолжительностю от 15 до 20 ночей
	 * ============================================================================================================ **/
	$cnt = 0;
	foreach($result as $k => $requestid){
		//начало периода (от 15 до 20 дней)
		if($cnt >= $iteration*3 && $cnt < $iteration*4 && !array_key_exists($k, $tours)){
			foreach ($requestid as $hotel){
				if(!isset($hotel)) continue;
				if($temp['min_price'] == 0 || $temp['min_price'] > $hotel['price']) {
					$temp['min_price'] = $hotel['price'];
					$temp['requestid'] = $k;
					$temp['date'] = $hotel['tours']['tour'][0]['flydate'];
					$temp['nights'] = $hotel['tours']['tour'][0]['nights'];
				}
			}
		}
		$cnt++; // Для поиска начала периода (от 15 до 20 дней)
	}

	if(count($temp) > 1) $tours[$temp['requestid']] = compose_tour($temp, $temp['requestid']);

	foreach($result[$temp['requestid']] as $hotel){
		foreach ($hotel['tours']['tour'] as $tour){
			if($tour['flydate'] == $temp['date'] && $tour['nights'] == $temp['nights']){
				$tours[$temp['requestid']]['hotels'][] = compose_hotel($hotel, $tour);
				break;
			}
		}
	}
	$temp = Array();
	$temp['min_price'] = 0;

	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/tours.txt', print_r($tours, 1));
    /**================================================================================================================
     * 					Фильтруем отели по звездам и по группам их Битрикс
     * ============================================================================================================ **/
    $filter_hotels = Array();
    $star_3 = $_REQUEST['hotel3'];
    $star_4 = $_REQUEST['hotel4'];
    $star_5 = $_REQUEST['hotel5'];
//    $star_3 = 10;
//    $star_4 = 6;
//    $star_5 = 4;
    $BX_group = Array(15739,24908,4320,52085,7937,55505, 597,557);
	$tours = hotels_filter($tours, $star_3, $star_4, $star_5);
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/json/'.$_REQUEST['cat_name'].'-['.$_REQUEST['date_from'].' - '.$_REQUEST['date_to'].'].json', json_encode($tours));
    echo "Создается временный файл туров .. (Не перезагружайте страницу)";
}

if(isset($_REQUEST['new_items'])){
    $json = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/dev/json/'.$_REQUEST['cat_name'].'-['.$_REQUEST['date_from'].' - '.$_REQUEST['date_to'].'].json');
    $tours = json_decode($json, 1);
	$cat_name = $_REQUEST['cat_name'];
	$min_cat_price = $tours;
	$min_cat_price = array_shift($min_cat_price);
	
	$tour_catid = add_section($cat_name, 20, $_REQUEST['departure'], $min_cat_price['min_price']);
	$hotel_catid = add_section($cat_name, 23);
    foreach($tours as $tour){
		$PROP = array(
			'DEPARTURE' => $_REQUEST['departure_name'],
            'COUNTRY' => $tour['hotels'][0]['countryname'],
            'CURORT' => $tour['hotels'][0]['regionname'],
            'DATEFROM' => $tour['flydate'],
            'MIN_PRICE' => floor($tour['min_price'] / 100) * 100,
            'DAYCOUNT' => $tour['night_interval'],
        );

        $el = new CIBlockElement;
        // Параметры для символьного кода (Код необходим для построения url)
        $params = Array(
            "max_len" => "100", // обрезает символьный код до 100 символов
            "change_case" => "L", // буквы преобразуются к нижнему регистру
            "replace_space" => "_", // меняем пробелы на нижнее подчеркивание
            "replace_other" => "_", // меняем левые символы на нижнее подчеркивание
            "delete_repeat_replace" => "true", // удаляем повторяющиеся нижние подчеркивания
            "use_google" => "false", // отключаем использование google
        );

        $arLoadProductArray = array(
            'IBLOCK_ID' => 20,
            'IBLOCK_SECTION_ID' => $tour_catid,
            'NAME' => $tour['name'],
            "CODE" => CUtil::translit($tour['name'], "ru" , $params),
            'ACTIVE' => 'Y',
            'PROPERTY_VALUES' => $PROP
        );
        $tour_id = $el->Add($arLoadProductArray);

        $arFields = array(
            "ID" => $tour_id,
            "VAT_ID" => 1, //тип ндс
            "VAT_INCLUDED" => "Y" //НДС входит в стоимость
        );
        CCatalogProduct::Add($arFields);

        foreach($tour['hotels'] as $hotel){
			$PROP = array(
				'hotelcode' => $hotel['hotelcode'],
				'countryname' => $hotel['countryname'],
				'regionname' => $hotel['regionname'],
				'hotelstars' => $hotel['hotelstars'],
				'nights' => $hotel['nights'],
				'price' => floor($hotel['price'] / 100) * 100, // Округляем в меньшую сторону до 100
				'TOURIDBX' => $tour_id,
				'img' => trim($hotel['picturelink'], "//"),
				'meal' => $hotel['mealrussian'],
				'operatorname' => $hotel['operatorname'],
				'tourid' => $hotel['tourid'],
			);

			$el = new CIBlockElement;
			$code = $tour_id."_".uniqid();

			$arLoadProductArray = array(
				'IBLOCK_ID' => 23,
				'IBLOCK_SECTION_ID' => $hotel_catid,
				'NAME' => $hotel['hotelname'],
				"CODE" => CUtil::translit($code, "ru" , $params),
				'ACTIVE' => 'Y',
				'PROPERTY_VALUES' => $PROP
			);
			$element_id = $el->Add($arLoadProductArray);

			$arFields = array(
				"ID" => $element_id,
				"VAT_ID" => 1, //тип ндс
				"VAT_INCLUDED" => "Y" //НДС входит в стоимость
			);
			CCatalogProduct::Add($arFields);
        }

    }
    echo "Туры добавлены.";
}

/*==========================================================================================================*/
/*=========================================== FUNCTIONS ====================================================*/
/*==========================================================================================================*/

function sortFunction($keys){
    //если сортировка по нескольким полям
    if (is_array($keys)){
        return function ($a, $b) use ($keys){
            foreach ($keys as $k) {				
                if ($a[$k] != $b[$k]){
		    if($k == 'flydate'){
			return (strtotime($a[$k]) < strtotime($b[$k])) ? -1 : 1;
		    }
                    return ($a[$k] < $b[$k]) ? -1 : 1;
                }
            }
            return 0;
        };
    }else{ //если сортировка по одному полю
        return function ($a, $b) use ($keys){
            if ($a[$keys] == $b[$keys]){
                return 0;
            }
            return ($a[$keys] < $b[$keys]) ? -1 : 1;
        };
    }
}

function get_date($start_time, $period, $iteration){
    $result = Array();
    $value = '+'.$period.' day';
    for ($i=1; $i <= $iteration; $i++) {
		if($i == 1) $result[] = $start_time->format('d.m.Y');
		else $result[] = $start_time->modify($value)->format('d.m.Y');
    }
    return $result;
}

// Создаем новый раздел
function add_section($name, $iblock_id, $departure = '', $min_price = ''){
	// Параметры для символьного кода (Код необходим для построения url)
	$params = Array(
		"max_len" => "100", // обрезает символьный код до 100 символов
		"change_case" => "L", // буквы преобразуются к нижнему регистру
		"replace_space" => "_", // меняем пробелы на нижнее подчеркивание
		"replace_other" => "_", // меняем левые символы на нижнее подчеркивание
		"delete_repeat_replace" => "true", // удаляем повторяющиеся нижние подчеркивания
		"use_google" => "false", // отключаем использование google
	);

	$bs = new CIBlockSection;
	$arFields = Array(
		"IBLOCK_ID" => $iblock_id,
		"NAME" => $name,
		"CODE" => CUtil::translit($name, "ru" , $params),
	);

	if($departure) $arFields['UF_DEPARTURE'] = $departure;
	if($min_price) $arFields['UF_MIN_PRICE'] = floor($min_price / 100) * 100;
	$arFields['UF_GENERATED'] = 0;
	$id = $bs->Add($arFields);
	return $id;
}

function compose_tour($tour, $key){
	$result['flydate'] = $tour['date'];
	$result['name'] = $tour['date']."_".$key;
	$result['cat_name'] = $_REQUEST['cat_name'];
	$result['night_interval'] = $tour['nights'];
	$result['min_price'] = $tour['min_price'];
	return $result;
}

function compose_hotel($hotel, $tour){
	$result['countryname'] = $hotel['countryname'];
	$result['regionname'] = $hotel['regionname'];
	$result['hotelcode'] = $hotel['hotelcode'];
	$result['hotelname'] = $hotel['hotelname'];
	$result['picturelink'] = $hotel['picturelink'];
	$result['operatorname'] = $tour['operatorname'];
	$result['mealrussian'] = $tour['mealrussian'];
	$result['tourid'] = $tour['tourid'];
	$result['nights'] = $tour['nights'];
	$result['hotelstars'] = $hotel['hotelstars'];
	$result['price'] = $tour['price'];
	return $result;
}

function hotels_filter($tours, $star_3, $star_4, $star_5){
	foreach($tours as $requestid => $tour){
		foreach($tour['hotels'] as $hotel){
			if($hotel['hotelstars'] <= 3) $filter_hotels['3stars'][] = $hotel;
			if($hotel['hotelstars'] == 4) $filter_hotels['4stars'][] = $hotel;
			if($hotel['hotelstars'] == 5) $filter_hotels['5stars'][] = $hotel;
		}


		if($star_3 < count($filter_hotels['3stars']) && $star_3 != 0) {
			$iteration = floor(count($filter_hotels['3stars']) / $star_3);
			$rest = count($filter_hotels['3stars']) % $star_3;
			for ($i = 1; $i <= $star_3; $i++) {
				if ($i == 1) $start = 0;
				elseif($i == $star_3) $iteration = $iteration + $rest; // В последнем массиве добавляются все остальные элементы которые остались при округлении
				else $start = $start + $iteration;
				$filter_hotels['group']['3s'][$i] = array_slice($filter_hotels['3stars'], $start, $iteration);
			}
		}elseif(count($filter_hotels['3stars']) != 0){
			foreach($filter_hotels['3stars'] as $hotel) $hotels[$requestid][] = $hotel;
		}

		if($star_4 < count($filter_hotels['4stars']) && $star_4 != 0) {
			$iteration = floor(count($filter_hotels['4stars']) / $star_4);
			$rest = count($filter_hotels['4stars']) % $star_4;
			for ($i = 1; $i <= $star_4; $i++) {
				if ($i == 1) $start = 0;
				elseif($i == $star_4) $iteration = $iteration + $rest; // В последнем массиве добавляются все остальные элементы которые остались при округлении
				else $start = $start + $iteration;
				$filter_hotels['group']['4s'][] = array_slice($filter_hotels['4stars'], $start, $iteration);
			}
		}elseif(count($filter_hotels['4stars']) != 0){
			foreach($filter_hotels['4stars'] as $hotel) $hotels[$requestid][] = $hotel;

		}

		if($star_5 < count($filter_hotels['5stars']) && $star_5 != 0) {
			$iteration = floor(count($filter_hotels['5stars']) / $star_5);
			$rest = count($filter_hotels['5stars']) % $star_5;
			for ($i = 1; $i <= $star_5; $i++) {
				if ($i == 1) $start = 0;
				elseif($i == $star_5) $iteration = $iteration + $rest; // В последнем массиве добавляются все остальные элементы которые остались при округлении
				else $start = $start + $iteration;
				$filter_hotels['group']['5s'][] = array_slice($filter_hotels['5stars'], $start, $iteration);
			}
		}elseif(count($filter_hotels['5stars']) != 0){
			foreach ($filter_hotels['5stars'] as $hotel) $hotels[$requestid][] = $hotel;
		}




		foreach($filter_hotels['group'] as $groups){
			foreach($groups as $group){
				$i=0;
				foreach($group as $hotel){
					if(in_array($hotel['hotelcode'], $BX_group)){
						$hotels[$requestid][] = $hotel;
						break;
					}
					$i++;
					if($i == count($group)) $hotels[$requestid][] = $group[0];
				}
			}
		}
		//echo "<pre>".print_r($filter_hotels, 1)."</pre>";
		$filter_hotels = Array();
	}

	foreach($tours as $requestid => $tour){
		if(isset($hotels[$requestid])) $tours[$requestid]['hotels'] = $hotels[$requestid];
	}
	//echo "<pre>".print_r($hotels, 1)."</pre>";
	return $tours;
}