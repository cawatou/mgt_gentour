<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php"); 

CModule::IncludeModule("iblock");
CModule::IncludeModule("form");
CModule::IncludeModule("main");


if($_REQUEST['id']==7) {
	$el = new CIBlockElement;

	switch($_REQUEST['form_radio_vote']) {
		case 53:
		$plus = 5;
		break;
		case 54:
		$plus = 4;
		break;
		case 55:
		$plus = 3;
		break;
		case 56:
		$plus = 2;
		break;
		case 57:
		$plus = 1;
		break;
	}

	$ELEMENT_ID = $_REQUEST['form_hidden_61'];

	$PRODUCT_IBLOCK_ID = 7;

	$db_props = CIBlockElement::GetProperty($PRODUCT_IBLOCK_ID, $ELEMENT_ID, array("sort" => "asc"), Array());


	while($ar_props = $db_props->Fetch()) {

		$FORUM_TOPIC_ID = IntVal($ar_props["VALUE"]);

		if($ar_props['ID'] == 107) {
			$getPropeertySumm = (int) $ar_props['VALUE'];
		}
		if($ar_props['ID'] == 254) {
			$getPropeertyCount = (int) $ar_props['VALUE'];
		}

		$PROP[$ar_props['ID']] = $ar_props['VALUE'];


	}

	$val = $getPropeertySumm + $plus;
	$count = $getPropeertyCount+ 1;


	$PROP[107] = (int) $val;
	$PROP[254] = (int) $count;


	$arLoadProductArray = Array(
		"MODIFIED_BY"    => $USER->GetID(), 
		"IBLOCK_SECTION" => false,          
		"PROPERTY_VALUES"=> $PROP
		);

	if($res = $el->Update($ELEMENT_ID, $arLoadProductArray)) {

	}
	else {
		echo 'Error';
	}

}



if($_REQUEST['id'] == 9 || $_REQUEST['id'] == 5) {
	
	include_once ("sms_prosto.php");


	$API_KEY = "73d9f3Wl80PiS1ysrtjDS563z";

	// Из модального (Подписаться на sms рассылку)
	if(isset($_REQUEST['form_text_74'])) $city = $_REQUEST['form_text_74'];
	// Из модального (Отправить заявку на тур или оплатить в офисе)
	if(isset($_REQUEST['form_hidden_40'])) $city = $_REQUEST['form_hidden_40'];

	if(isset($_REQUEST['form_text_73'])) $phone = preg_replace('~\D+~','',$_REQUEST['form_text_73']);
	if(isset($_REQUEST['form_text_37'])) $phone = preg_replace('~\D+~','',$_REQUEST['form_text_37']);

	$soc = $_REQUEST['form_dropdown_social'];
	// 69 - вацап
	// 70 - вайбер
	// 71 - телеграм
	// 72 - смс
	if($_REQUEST['form_checkbox_sms_spam']) $soc = 72;
 	
	switch($soc){
		case 69:
		$socName = 'whatsapp';
		$socName2 = 'watsapp';
		break;
		case 70:
		$socName = 'viber';
		break;
		case 71:
		$socName = 'telegram';
		break;
		case 72:
		$socName = 'sms';
	}

	if($socName2) {
		$city2 = $city . ' ' . $socName2;
	}
	$city = $city . ' ' . $socName;
	
	// Просматриваем список абонентских баз, которые добавлены на сервисе
	$baseId = 0;
	$key = $API_KEY; // API KEY (запросите в Support@)
	$result = smsapi_get_list_base_nologin_key($key, $params = NULL);

	//Далее, пример обработки полученных данных
	if (isset($result['response'])) {
		
		if ($result['response']['msg']['err_code'] > 0) {
			// Получили ошибку
			print $result['response']['msg']['err_code']; // код ошибки
			print $result['response']['msg']['text']; // текстовое описание ошибки
			
		} else {
			// Запрос прошел без ошибок	
			// Получаем нужные данные			
			$my_arr = $result['response']['data']; // тут получили массив, в котором содержится список абонентских баз (ключ массива - это id базы, параметр base_title - это название базы, created - дата создания базы)									

			foreach ($my_arr as $key => $value) {
				if($city == $value['base_title']) {
					$baseId = $key;
				}
				else if($city2 == $value['base_title']) {
					$baseId = $key;
				}					
			}
			
			unset($my_arr);
			unset($value);
			
		}			
	}

	
// Добавляем контакт в базу
// Перед добавлением база должна быть создана, также нужно предварительно знать id этой базы

	$key = $API_KEY; // API KEY (запросите в Support@)
	$id_base = $baseId; // id Базы в которую добавляем номер
	$phone = $phone; // Телефон абонента в любом формате, главное чтобы было 11 цифр
	// $fam = false; // фамилия, не обязательный параметр
	// $name = false; // имя, не обязательный параметр

	$result = smsapi_add_number_to_base_nologin_key($key, $phone, $id_base, array()); // Возвращает массив $result с ответом сервера
	//var_dump($result); // Получили ответ в виде массива. Раскомментируйте, чтобы посмотреть, что возвращает $result
	//Далее, пример обработки полученных данных
	if (isset($result['response'])) {
		
		if ($result['response']['msg']['err_code'] > 0) {
			// Получили ошибку
			print $result['response']['msg']['err_code']; // Код ошибки
			print $result['response']['msg']['text']; // Текстовое описание ошибки
			
		} else {
			// Запрос прошел без ошибок, получаем нужные данные			
			print $result['response']['data']['id_phone']; // тут получили id вставленного в базу номера абонента
		}			
	}

//	echo "<pre>".print_r($city, 1)."</pre>";
//	echo "<pre>".print_r($phone, 1)."</pre>";
//	echo "<pre>".print_r($socName, 1)."</pre>";
//	echo "<pre>".print_r($baseId, 1)."</pre>";
//	echo "<pre>".print_r($result, 1)."</pre>";
//	exit();
	// $req = file_get_contents("http://api.sms-prosto.ru/?key=".$API_KEY."&method=add_number_to_base&phone=".$phone."&id_base=".$idbase."&fam=Any&name=Body");
	//echo  $req;
}

// echo 'id: '.$_REQUEST['id'];
// if($_REQUEST['id']==2) {


// 	$rsResults = CFormResult::GetList(2, ($by="s_timestamp"), ($order="desc"), array(), $is_filtered,"Y",10);

// 	while ($arResult = $rsResults->Fetch()){
// 		$id = (int) $arResult['ID'] + 1;

// 	}

// 	$el = new CIBlockElement;

// 	$PROP[165] = 'N';
// 	$PROP[166] = $id;
// 	$PROP[246] = 'false';



// 	$NAME = "ответ в чат ".$_REQUEST['id']." от ".date('d-m-Y H:i:s');


// 	$arLoadProductArray = Array(
// 		"MODIFIED_BY"    => $USER->GetID(), 
// 		"IBLOCK_ID"      => 26,
// 		"PROPERTY_VALUES"=> $PROP,
// 		"NAME"           => $NAME,
// 		"ACTIVE"         => "Y",            
// 		"PREVIEW_TEXT"   => $_REQUEST['form_textarea_28'],
// 	);


// 	 if($el->Add($arLoadProductArray)) {
// 	 	echo 1100;
// 	 }
// 	 else {
// 	 	echo 9999;
// 	 }

// }

if($_REQUEST['id']==7) $tpl ="vote"; else  $tpl ="question";
$APPLICATION->IncludeComponent("bitrix:form.result.new",$tpl,Array(
	
	"SEF_MODE" => "N", 
	"WEB_FORM_ID" => $_REQUEST['id'], 
	"EDIT_URL" => "edit.php",
	"CHAIN_ITEM_TEXT" => "", 
	"CHAIN_ITEM_LINK" => "", 
	"IGNORE_CUSTOM_TEMPLATE" => "N", 
	
	"SUCCESS_URL" => "", 
	"COMPONENT_TEMPLATE" => $tpl,
	
	"LIST_URL" => "result.php", 
	"USE_EXTENDED_ERRORS" => "Y", 
	"CACHE_TYPE" => "A", 
	"CACHE_TIME" => "0", 
	
	"VARIABLE_ALIASES" => Array("RESULT_ID"=>"RESULT_ID","WEB_FORM_ID"=>"WEB_FORM_ID")
	
	)
);


?>