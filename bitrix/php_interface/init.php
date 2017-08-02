<?/**
 * $date_tours - Массив соответствий периодов дат и продолжительностей ночей (по нему собирается список requestid)
 *
 * $n2_5 - количество туров от 2 до 5 ночей
 * $n6_8 - количество туров от 6 до 8 ночей
 * $n9_15 - количество туров от 9 до 14 ночей
 * $n16_20 - количество туров от 15 до 20 ночей
 *
 * // В каждом двухнедельном периоде по 2 плашки
 * $periods = Array(1=>2, 2=>2, 3=>2, 4=>2) - массив двухнедельных периодов
 *
 **/
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", "OnAfterIBlockElementHandler", 1000);
AddEventHandler("iblock", "OnAfterIBlockElementAdd", "OnAfterIBlockElementHandler", 1000);
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", "OnBeforeIBlockElementUpdateHandler", 1000);
AddEventHandler("iblock", "OnBeforeIBlockElementDelete", "OnBeforeIBlockElementDeleteHandler", 1000);
AddEventHandler("iblock", "OnAfterIBlockSectionAdd", "OnAfterIBlockSectionAddHandler", 1000);
$old_name = "";
$QUESTION_ID = 37; // ID вопроса, в который мы будем добавлять ответы
function OnAfterIBlockSectionAddHandler (&$arFields)
{
// //Обработка данных при создании секции iblock
// 	function OnAfterIBlockSectionAddHandler(&$arFields)
//     {
//         if($arFields["ID"]>0)
//              AddMessage2Log("Запись с кодом ".$arFields["ID"]." добавлена.");
//         else
//              AddMessage2Log("Ошибка добавления записи (".$arFields["RESULT_MESSAGE"].").");
//     }
// //Подлючение и работа с базой старого сайта
// 	$dbHost='localhost';
// 	$dbName='mybase';
// 	$dbUser='myuser';
// 	$dbPass='mypassword';
// 	$myConnect = mysql_connect($dbHost,$dbUser,$dbPass));
// 	mysql_select_db($dbName,$myConnect);
// 	$qwer=mysql_query("select * from `mytable`",$myConnect);
// 	while ($arr=mysql_fetch_array($qwer))
// 	{
// 		echo $arr[0].' '.$arr[1];
// 	}
// 	mysql_close($myConnect);


}
function OnBeforeIBlockElementUpdateHandler (&$arFields)
{
	global $old_name;
	$arr = CIblockElement::GetByID($arFields["ID"]);
	if ($ar = $arr->Fetch())
		$old_name = trim ($ar["NAME"]);
}
function OnBeforeIBlockElementDeleteHandler ($ID)
{
	global $QUESTION_ID;
	$arr = CIblockElement::GetByID($ID);
	if ($ar = $arr->Fetch())
	{
		CModule::IncludeModule("form");
		$rsAnswersDel = CFormAnswer::GetList($QUESTION_ID, $by="s_id", $order="desc", Array("MESSAGE" => $ar["NAME"]), $is_filtered);
		while ($arAnswerDel = $rsAnswersDel->Fetch())
			CFormAnswer::Delete($arAnswerDel["ID"]);
	}
}
function OnAfterIBlockElementHandler(&$arFields)
{
	//Здесь надо поменять ID инфоблока, из которого мы будем брать названия элементов
	if ($arFields["IBLOCK_ID"] != '7' || intval($arFields["RESULT"]) <= 0)
		return $arFields;
	global $old_name, $QUESTION_ID;

	CModule::IncludeModule("form");
	//Удаляем старое значение ответа при возможном Update.
	if (strlen($old_name)>0)
	{
		$rsAnswersDel = CFormAnswer::GetList($QUESTION_ID, $by="s_id", $order="desc", Array("MESSAGE" => $old_name), $is_filtered);
		while ($arAnswerDel = $rsAnswersDel->Fetch())
			CFormAnswer::Delete($arAnswerDel["ID"]);
	}
	//Добавляем новое значение
	$rsAnswers = CFormAnswer::GetList($QUESTION_ID, $by="s_id", $order="desc", Array(), $is_filtered);
	$arAnswer = $rsAnswers->Fetch();
	if (!$arAnswer)
	{
		$arAdd = Array("QUESTION_ID"=> $QUESTION_ID, "MESSAGE"=> $arFields["NAME"], "VALUE"=> $arFields["ID"], "FIELD_TYPE"=> "dropdown");
		CFormAnswer::Set($arAdd, false, $QUESTION_ID);
	}
	else
	{
		$bnew = true;
		do{
			if ($arAnswer["MESSAGE"] == $arFields["NAME"])
			{
				$bnew = false; break;
			}
		}while ($arAnswer = $rsAnswers->Fetch());
		if ($bnew)
		{
			$arAdd = Array("QUESTION_ID"=> $QUESTION_ID, "MESSAGE"=> $arFields["NAME"], "VALUE"=> $arFields["ID"], "FIELD_TYPE"=> "dropdown");
			CFormAnswer::Set($arAdd, false, $QUESTION_ID);
		}
	}
}
// =================================== AUTO GENERATOR =============================================================
function get_requestid($login, $pass, $departure, $country, $regions, $date_from, $date_to){
	$today = new DateTime('today');
	$tommorow = new DateTime('tomorrow');
	$start_time = new DateTime($date_from);	
	if ($start_time < $today) $start_time = $tommorow;
	$end_time = new DateTime($date_to);
	$temp_time = new DateTime($date_from);
	$interval = $start_time->diff($end_time)->days;
	if($interval < 56 || $interval > 56){
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
	for($i=1; $i<=3; $i++){
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
				$date_tours['n9_15']['nights'] = Array('9', '15');
				$date_tours['n9_15']['date'] = $date_interval['date'];
				break;
			case 4:
				$date_tours['n16_20']['nights'] = Array('16', '20');
				$date_tours['n16_20']['date'] = $date_interval['date'];
				break;
		}
	}
	$requestid = $get = '';
	foreach($date_tours as $k => $duration){
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
			sleep (0.1);
			$json = file_get_contents('http://tourvisor.ru/xml/search.php?' . $get);
			$json = json_decode($json, 1);
			file_put_contents($_SERVER['DOCUMENT_ROOT'].'/reqid_json.txt', print_r($json, 1), FILE_APPEND);

			if($requestid == '' && $json['result']['requestid'] != '') $requestid = $json['result']['requestid'];
			elseif($requestid != '' && $json['result']['requestid'] != '') $requestid = $requestid.','.$json['result']['requestid'];
			$get = '';		
		}
	}
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/log/dev/date_tours.txt', print_r($date_tours, 1));
	return $requestid;
}
function get_status($login, $pass, $requestid){
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/log/dev/requestid.txt', print_r($requestid, 1));
	foreach($requestid as $k => $id) {
		$json = file_get_contents('http://tourvisor.ru/xml/result.php?authlogin=' . $login . '&authpass=' . $pass . '&requestid=' . $id . '&type=status&format=json');
		$json = json_decode($json, 1);
		if($json['data']['status']['progress'] == 100) file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/log/dev/status.txt', 'reqid '.$id.' = '.$json['data']['status']['progress']."\r\n", FILE_APPEND);		
		if($status == '') $status = $json['data']['status']['progress'];
		else $status = $status + $json['data']['status']['progress'];
	}
	$status = $status / count($requestid);
	return $status;
}
function get_result($login, $pass, $requestid, $date_from, $date_to, $star_3, $star_4, $star_5, $cat_name = '', $departure, $regions, $cron = false){
	// Получаем из админки отели отсортированные по группам вручную
	$arSelect = Array("ID", "NAME", "PROPERTY_group", "PROPERTY_country", "PROPERTY_region", "PROPERTY_star", "PROPERTY_tourvisor_id");
	$arFilter = Array("IBLOCK_ID" => 29, "ACTIVE" => "Y");
	$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize" => 9999), $arSelect);
	while ($ob = $res->GetNextElement()) {
		$hotel = $ob->GetFields();
		$BX_group['tourvisor_id'][] = $hotel['PROPERTY_TOURVISOR_ID_VALUE'];
		$BX_group['group'][$hotel['PROPERTY_TOURVISOR_ID_VALUE']] = $hotel['PROPERTY_GROUP_VALUE'];
	}
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/log/dev/BX_group.txt', print_r($BX_group, 1));
	$periods = Array(1 => 0, 2 => 0, 3 => 0, 4 => 0); // Периоды двухнедельных интервалов
	// Если самое дешевое предложение не больше 5 дней период от 2 до 5 дней не учитывается ($n6_8 прибавляется на 1 => $n6_8 = 3)
	$n2_5 = 1; // Одна дата на продолжительность от 2 до 5 дней (аналогично остальные)
	$n6_8 = 3;
	$n9_15 = 4;
	$n16_20 = 0;
	$empty = $previous_key = 0;
	$cnt = 0;
	$period_num = 1;
	$start_time = new DateTime($date_from);
	$end_time = new DateTime($date_to);
	$interval = $start_time->diff($end_time)->days;
	if($interval < 56) $interval = 56; // 8 недель
	$iteration = ceil($interval/14);
	
	$reserved = array();
	$j = 0;
	foreach($requestid as $k => $id) {
		sleep (0.1);
		$json = file_get_contents('http://tourvisor.ru/xml/result.php?authlogin=' . $login . '&authpass=' . $pass . '&requestid=' . $id . '&type=result&onpage=100000&format=json');
		$json = json_decode($json, 1);
		if(isset($json['data']['result']['hotel'])){
			$result[$id] = $json['data']['result']['hotel'][0];
			$result[$id]['departure'] = $departure;
			$result[$id]['nights'] = $json['data']['result']['hotel'][0]['tours']['tour'][0]['nights'];
			$result[$id]['date'] = $json['data']['result']['hotel'][0]['tours']['tour'][0]['flydate'];
			$result[$id]['tours'] = '';
			
			foreach($json['data']['result']['hotel'] as $k => $extra_hotels){
				if($k == 0) continue;
				foreach ($extra_hotels['tours']['tour'] as $extra_tour){
					$compare = 0;
					if($extra_tour['flydate'] == $result[$id]['date'] && $extra_tour['nights'] == $result[$id]['nights']) continue;
					foreach($reserved as $reserve){
						$night = key($reserve);
						$date = current($reserve);
						if($extra_tour['nights'] == $night && $extra_tour['flydate'] == $date) $compare++;					
					}
					if($compare == 0) {	
						$j++;
						$extra_tours[$j] = $extra_hotels;
						$extra_tours[$j]['departure'] = $departure;
						$extra_tours[$j]['nights'] = $extra_tour['nights'];
						$extra_tours[$j]['date'] = $extra_tour['flydate'];
						$extra_tours[$j]['tours'] = '';
						
						$reserved[] = Array($extra_tour['nights'] => $extra_tour['flydate']);
					}
				}
			}
		}else{	
			$result[$id] = '';
			$empty++;			
		}	
		
		// Составляем массив соответствия айди запросов к номеру двухнедельного периода		
		$periods_reqid[$id] = $period_num;
		if($period_num == 4) $period_num = 1;
		else $period_num++;		
		$cnt++;
	}

	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/log/dev/result_before.txt', print_r($result, 1));
	$i = $j = 0;
	//$extra_tours = array_reverse($extra_tours);
	foreach($result as $id => $check){
		$i++;
		$compare = 0;
		if($check == '' && $i > 4){
			$j++;
			if(count($extra_tours) > 0) $result[$id] = $extra_tours[$j];
		}
	}
	
	
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/log/dev/reserved.txt', print_r($reserved, 1));
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/log/dev/result_after.txt', print_r($result, 1));
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/log/dev/extra_tours.txt', print_r($extra_tours, 1));
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/log/dev/periods_reqid.txt', print_r($periods_reqid, 1));
	if(count($result) == $empty && $cron == false) exit('empty');
	

	/**===========================================================================================================
	 *          Находми предложение (Самое дешевое)
	 * ======================================================================================================= **/
	foreach($result as $k => $hotels){
		if(!is_array($hotels)) continue;
		// Находим минимальную цену и записываем id запроса, количество дней и дату для поиска остальных отелей
		if($temp['min_price'] == 0 || $temp['min_price'] > $hotels['price'] && !array_key_exists($k, $tours)) {
			$temp['min_price'] = $hotels['price'];
			$temp['requestid'] = $k;
			$temp['date'] = $hotels['date'];
			$temp['nights'] = $hotels['nights'];
			$temp['departure'] = $departure;
			$temp['regions'] = $regions;
			$temp['country'] = $hotels['countrycode'];
		}
	}
	if($temp['nights'] > 5 && $temp['nights'] < 9){
		$n6_8 = 3; // Прибавим и сразу же отнимаем
		$n9_15 = 4;
		$first_n6_8 = $temp['requestid'];
	}elseif($temp['nights'] > 8 && $temp['nights'] < 15 ){
		$n6_8 = 4;
		$n9_15 = 3;
	}else{
		$n6_8 = 3;
		$n9_15 = 4;
	}
	if(count($temp) > 1) $tours[$temp['requestid']] = compose_tour($temp, $temp['requestid'], $cat_name);

	$period_key = $periods_reqid[$temp['requestid']]; // Получаем номер ключа массива $periods;
	$periods[$period_key]++;
//	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/log/dev/periods.txt', print_r($periods, 1)."\n\r", FILE_APPEND);
//	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/log/dev/temp.txt', print_r($temp, 1)."\n\r", FILE_APPEND);
	$temp = Array();
	$temp['min_price'] = 0;
	$n2_5 = 0;
	
	/**================================================================================================================
	 * 					Находми предложения продолжительностю от 9 до 15 ночей
	 * ============================================================================================================ **/
	$ratio_1 = $iteration*2;
	$ratio_2 = $iteration*3;
	$tours = find_tour($n9_15, $result, $ratio_1, $ratio_2, $periods_reqid, $departure, $regions, $cat_name, $periods, $tours);

	$n9_15 = $n9_15 - count($tours);

	

	
	/**================================================================================================================
	 * 					Находми предложения продолжительностю от 6 до 8 ночей
	 * ============================================================================================================ **/
	$ratio_1 = $iteration;
	$ratio_2 = $iteration*2;
	$tours = find_tour($n6_8, $result, $ratio_1, $ratio_2, $periods_reqid, $departure, $regions, $cat_name, $periods, $tours);

	$n6_8 = 0;
	
	foreach($tours as $k => $data){
		for($i=1; $i <= 3; $i++) {
			if($i == 1) $stars = '1';
			if($i == 2) $stars = '4';
			if($i == 3) $stars = '5';
			$get = 'authlogin=' . $login . '&authpass=' . $pass . '&datefrom=' . $data['flydate'] . '&dateto=' . $data['flydate'] . '&nightsfrom=' . $data['nights'] . '&nightsto=' . $data['nights'] . '&adults=2&operators=&meal=0&stars='.$stars.'&rating=0&hoteltypes=&country=' . $data['country'] . '&regions=' . $data['regions'] . '&departure=' . $data['departure'] . '&pricefrom=0&priceto=200001&currency=0&directonly=0&showoperator=1&pricetype=0&format=json';
			sleep (0.1);
			file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/dev/log/dev/get.txt', print_r($get, 1) . "\n\r", FILE_APPEND);
			//$get = 'authlogin=' . $login . '&authpass=' . $pass . '&departure='.$data['departure'].'&country='.$data['country'].'&datefrom='.$data['flydate'].'&dateto='.$data['flydate'].'&nightsfrom='.$data['nights'].'&nightsto='.$data['nights'].'&format=json';
			$res = file_get_contents('http://tourvisor.ru/xml/search.php?' . $get);
			$json = json_decode($res, 1);
			$reqid[$k][] = $json['result']['requestid'];
			sleep(0.1);
		}
	}
	sleep (30);
	$result = Array();
	foreach ($reqid as $k => $ids){
		foreach($ids as $id){
			for($i=1; ; $i++){
				sleep (0.1);
				$res = file_get_contents('http://tourvisor.ru/xml/result.php?authlogin=' . $login . '&authpass=' . $pass . '&requestid=' . $id . '&type=result&page='.$i.'&onpage=100&format=json');
				$result = json_decode($res, 1);

				$tours[$k]['hotels_rid'][] = $id;
				if(count($result['data']['result']['hotel']) == 0) break;
				$tt[$id][] = json_decode($res, 1);
				foreach ($result['data']['result']['hotel'] as $hotel){
					$tours[$k]['hotels'][] = compose_hotel($hotel, $hotel['tours']['tour'][0]);
				}
			}
		}
		
	}

	
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/log/dev/tours.txt', print_r($tours, 1));
	/**================================================================================================================
	 * 					Фильтруем отели по звездам и по группам их Битрикс
	 * ============================================================================================================ **/
	$filter_hotels = Array();
	$tours = hotels_filter($tours, $star_3, $star_4, $star_5, $BX_group);
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/log/dev/tours_after_filter.txt', print_r($tours, 1));
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/log/dev/periods.txt', print_r($periods, 1));
	
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/log/dev/tt.txt', print_r($tt, 1));
	return $tours;
}
function add_tours($cat_name, $departure, $departure_name, $tours, $form_data){
	//die();
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/cat_name.txt', print_r($cat_name, 1));
	$min_cat_price = $tours;
	$min_cat_price = array_shift($min_cat_price);
	$tour_catid = add_section($cat_name, 20, $departure, $min_cat_price['min_price'], $form_data, false);
	$hotel_catid = add_section($cat_name, 23, false, false, false, $tour_catid);
	foreach($tours as $tour){
		$PROP = array(
			'DEPARTURE' => $departure_name,
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
};
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
function add_section($name, $iblock_id, $departure = '', $min_price = '', $form_data = '', $tour_catid = ''){
	//if($tour_catid != "") file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/log/dev/form_data.txt', print_r($form_data, 1));
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
	if($form_data) $arFields['UF_FORM_DATA'] = $form_data;
	if($tour_catid) $arFields['UF_TOUR_ID'] = $tour_catid;
	$arFields['UF_GENERATED'] = 0;
	$id = $bs->Add($arFields);
	return $id;
}
function compose_tour($tour, $key, $cat_name){
	$result['flydate'] = $tour['date'];
	$result['name'] = $tour['date']."_".$key;
	$result['cat_name'] = $cat_name;
	$result['night_interval'] = $tour['nights'];
	$result['min_price'] = $tour['min_price'];
	$result['departure'] = $tour['departure'];
	if(is_array($tour['regions'])){
		foreach($tour['regions'] as $k => $region){
			if($k == 0) $regions = $region;
			else $regions = $regions.','.$region;
		}
	}else{
		$regions = $tour['regions'];
	}
	$result['regions'] = $regions;
	$result['country'] = $tour['country'];
	$result['nights'] = $tour['nights'];
	return $result;
}
function compose_hotel($hotel, $tour){  
	$result['countryname'] = $hotel['countryname'];
	$result['regionname'] = $hotel['regionname'];
	$result['hotelcode'] = $hotel['hotelcode'];
	$result['hotelname'] = $hotel['hotelname'];
	$result['picturelink'] = $hotel['picturelink'];
	$result['hotelstars'] = $hotel['hotelstars'];
	
	$result['operatorname'] = $tour['operatorname'];
	$result['mealrussian'] = $tour['mealrussian'];
	$result['tourid'] = $tour['tourid'];
	$result['nights'] = $tour['nights'];	
	$result['price'] = $tour['price'];
	return $result;
}
function find_tour($tour_count, $result, $ratio_1, $ratio_2, $periods_reqid, $departure, $regions, $cat_name, $periods, $tours){
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/log/dev/count.txt', print_r('+ '.$tour_count , 1)."\n\r", FILE_APPEND);
	$temp['min_price'] = 0;	
	for($i=1; $i <= $tour_count; $i++){
		file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/log/dev/count.txt', print_r('- '.$i, 1)."\n\r", FILE_APPEND);
		$cnt = 0;
		foreach($result as $k => $hotels){
			if(!is_array($hotels)) {
				$cnt++;
				continue;
			}
			//начало периода
			if($cnt >= $ratio_1 && $cnt < $ratio_2 && !array_key_exists($k, $tours)){
				if($periods[$periods_reqid[$k]] == 2) continue;
				if($temp['min_price'] == 0 || $temp['min_price'] > $hotels['price']) {
					$temp['min_price'] = $hotels['price'];
					$temp['requestid'] = $k;
					$temp['date'] = $hotels['date'];
					$temp['nights'] = $hotels['nights'];
					$temp['departure'] = $departure;
					$temp['regions'] = $regions;
					$temp['country'] = $hotels['countrycode'];
				}

			}
			$cnt++; // Для поиска начала периода (от 9 до 15 дней)
		}
		if(count($temp) > 1) $tours[$temp['requestid']] = compose_tour($temp, $temp['requestid'], $cat_name);

		$period_key = $periods_reqid[$temp['requestid']]; // Получаем номер ключа массива $periods;
		$periods[$period_key]++;
		file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/log/dev/periods.txt', print_r($periods, 1)."\n\r", FILE_APPEND);
		file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/log/dev/temp.txt', print_r($temp, 1)."\n\r", FILE_APPEND);
		$temp = Array();
		$temp['min_price'] = 0;		
	}
	return $tours;
}

function hotels_filter($tours, $star_3, $star_4, $star_5, $BX_group){
	foreach($tours as $requestid => $tour){
	
		foreach($tour['hotels'] as $hotel){
			// При записи в качестве ключа передаем 'hotelcode' - для избежания дублей
			/*
			$better_three = false;
			if($hotel['hotelstars'] <= 3) {
				if($hotel['hotelstars'] == 3) $better_three = true;
				if($better_three == true && $hotel['hotelstars'] < 3) continue;
				$filter_hotels['3stars'][$hotel['hotelcode']] = $hotel;
			}
			*/
			if($hotel['hotelstars'] <= 3) $filter_hotels['3stars'][$hotel['hotelcode']] = $hotel;
			if($hotel['hotelstars'] == 4) $filter_hotels['4stars'][$hotel['hotelcode']] = $hotel;
			if($hotel['hotelstars'] == 5) $filter_hotels['5stars'][$hotel['hotelcode']] = $hotel;
		}
		if($star_3 < count($filter_hotels['3stars']) && $star_3 != 0) {
			$iteration = floor(count($filter_hotels['3stars']) / $star_3);
			$rest = count($filter_hotels['3stars']) % $star_3;
			for ($i = 1; $i <= $star_3; $i++) {
				if ($i == 1) $start = 0;
				else $start = $start + $iteration;
				if($i == $star_3) $iteration = $iteration + $rest; // В последнем массиве добавляются все остальные элементы которые остались при округлении

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
				else $start = $start + $iteration;
				if($i == $star_4) $iteration = $iteration + $rest; // В последнем массиве добавляются все остальные элементы которые остались при округлении

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
				else $start = $start + $iteration;
				if($i == $star_5) $iteration = $iteration + $rest; // В последнем массиве добавляются все остальные элементы которые остались при округлении

				$filter_hotels['group']['5s'][] = array_slice($filter_hotels['5stars'], $start, $iteration);
			}
		}elseif(count($filter_hotels['5stars']) != 0){
			foreach ($filter_hotels['5stars'] as $hotel) $hotels[$requestid][] = $hotel;
		}
		foreach($filter_hotels['group'] as $groups){
			foreach($groups as $group){
				$i=0;
				$temp_hotel = '';
				foreach($group as $hotel){
					if(in_array($hotel['hotelcode'], $BX_group['tourvisor_id'])){
						$group_value = $BX_group['group'][$hotel['hotelcode']];
						if($group_value == 1) {
							$hotels[$requestid][] = $hotel;
							//file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/log/dev//first_group.txt', print_r($hotel, 1), FILE_APPEND);
							break;
						}else{
							$temp_hotel = $hotel;
							//file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/log/dev//temp_hotel.txt', print_r($temp_hotel, 1), FILE_APPEND);
						}
					}
					$i++;
					if($i == count($group) && $temp_hotel == '') $hotels[$requestid][] = $group[0];
					if($i == count($group) && is_array($temp_hotel)) $hotels[$requestid][] = $temp_hotel;
				}
			}
		}
		file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/log/dev/hotels.txt', print_r($hotels, 1));
		file_put_contents($_SERVER['DOCUMENT_ROOT'].'/dev/log/dev/filter_hotels_'.$requestid.'.txt', print_r($filter_hotels, 1));
		$filter_hotels = Array();
	}
	foreach($tours as $requestid => $tour){
		if(isset($hotels[$requestid])) $tours[$requestid]['hotels'] = $hotels[$requestid];
	}
	return $tours;
}
?>