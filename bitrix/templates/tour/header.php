<?
define("AUTH","i@neoz.su");
define("PASS","jICPOQJ7");
GLOBAL $cityArr,$operatorArr,$countryArr,$TVID,$User_city;
if(CModule::IncludeModule("altasib.geoip"))
{
	$CITY_ID = $APPLICATION->get_cookie("CITY_ID");
	
	if(empty($CITY_ID)){
		$User_city = ALX_GeoIP::GetAddr();
	}else {
		
	}
}
CModule::IncludeModule("iblock");
$yvalue = 16;
$arSelect = Array("ID","NAME", "UF_CITYID");
$arFilter = Array("IBLOCK_ID"=>16 );
$res = CIBlockSection::GetList(Array("NAME"=>"ASC"), $arFilter, false,$arSelect);
while($ob = $res->GetNextElement())
{
	 $arFields = $ob->GetFields();
	 $city[$arFields['ID']] = $arFields['NAME'];
	if(!empty($CITY_ID)){
		 $officeForCity = $CITY_ID;
		 if( $arFields['ID']==$CITY_ID ) {
			 $User_city['city']  = $arFields['NAME'];
			 $User_city['tvid'] = $arFields['UF_CITYID'];
		 }
	}
	else {
		if( $arFields['NAME']==$User_city['city'] )  $officeForCity = $arFields['ID'];
	}
	$city_tourvisor[$arFields['ID']] = $arFields['UF_CITYID'];
}

/********meals*********/
$meals = array();
\Bitrix\Main\Loader::includeModule('highloadblock');
$hlblock_id = 5;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Entity;

$hlblock = HL\HighloadBlockTable::getById($hlblock_id)->fetch();
$entity = HL\HighloadBlockTable::compileEntity($hlblock);

$entity_data_class = $entity->getDataClass();
$entity_table_name = $hlblock['TABLE_NAME'];

$arFilter = array();

$sTableID = 'tbl_'.$entity_table_name;
$rsData = $entity_data_class::getList(array(
	"select" => array('*'),
	"filter" => $arFilter,
	"order" => array("UF_TVID"=>"ASC")
));
$rsData = new CDBResult($rsData, $sTableID);
while($arRes = $rsData->Fetch()){
	
	$meals[$arRes["ID"]]['id'] = $arRes["UF_TVID"];
	$meals[$arRes["ID"]]['name'] = $arRes["UF_MEALNAME"];
	$meals[$arRes["ID"]]['fullname'] = $arRes["UF_FULLNAME"];
	$meals[$arRes["ID"]]['russian'] = $arRes["UF_MEALRUSSIAN"];
	$meals[$arRes["ID"]]['russianfull'] = $arRes["UF_MEALRUSSIANFULL"];
			
}

/*
	справочники
	$cityArr городов
	$operatorArr туроператор
	$countryArr стран
*/
$json = file_get_contents('http://tourvisor.ru/xml/list.php?authlogin='.AUTH.'&authpass='.PASS.'&format=json&type=departure');
$cityArr = json_decode($json);

$json = file_get_contents('http://tourvisor.ru/xml/list.php?authlogin='.AUTH.'&authpass='.PASS.'&format=json&type=operator');
$operatorArr = json_decode($json);

$json = file_get_contents('http://tourvisor.ru/xml/list.php?authlogin='.AUTH.'&authpass='.PASS.'&format=json&type=country');
$countryArr = json_decode($json);

foreach($cityArr->lists->departures->departure as $c) {
	if ($c->name == $User_city['city']) {
		$TVID = $c->id;
	} else {
		$TVID = $User_city['tvid'];
	}
	$tvcity[$c->id] = $c->name;
}?>


<!--[if IE]><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>"><![endif]-->
<!--[if gt IE]><!--><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>"><!--<![endif]-->
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<META HTTP-EQUIV="Expires" CONTENT="<?php echo date(DATE_RFC822,strtotime("7 day")); ?>">

<?
	$APPLICATION->ShowHeadStrings();
	$APPLICATION->ShowHeadScripts();
	$APPLICATION->ShowMeta('keywords');
	$APPLICATION->ShowMeta('description');
?>
<title><?$APPLICATION->ShowTitle()?></title>
<link rel="shortcut icon" type="image/x-icon" href="<?=SITE_TEMPLATE_PATH?>/favicon.ico" />

<script>


	var Recaptchafree = Recaptchafree || {};
	Recaptchafree.items = new Array();
	Recaptchafree.render = function() { 
	   if(window.grecaptcha){
	        var elements = document.querySelectorAll('div.g-recaptcha');
	        var widget;
	        for (var i = 0; i < elements.length; i++) {
	            if(elements[i].innerHTML === "") {
	                widget = grecaptcha.render(elements[i], {
	                    'sitekey' : elements[i].getAttribute("data-sitekey"),
	                    'theme' : elements[i].getAttribute("data-theme"),
	                    'size' : elements[i].getAttribute("data-size")
	                });
	                Recaptchafree.items.push(widget);
	            }
	        }
	    }    
	};

	Recaptchafree.reset = function() { 
	   if(window.grecaptcha){
	        for (var i = 0; i < Recaptchafree.items.length; i++){
	            grecaptcha.reset(Recaptchafree.items[i]); 
	        }
	        Recaptchafree.render();
	    }    
	};

	function onloadRecaptchafree(){
	    Recaptchafree.render();
	}

	setTimeout(function(){
		Recaptchafree.reset();

	}, 10000)

	
</script>
<style>
#page-preloader {
    position: fixed;
    left: 0;
    top: 0;
    right: 0;
    bottom: 0;
    background: #fff;
    z-index: 100500;
}

#page-preloader .spinner {
        width: 276px;
    height: 76px;
    position: absolute;
    left: 50%;
    top: 50%;
    background: url(/bitrix/templates/tour/images/preloaderlogo.png) no-repeat 50% 50%;
    -webkit-background-size: 100%;
    background-size: 100%;
    margin: -30px 0 0 -138px;
}
</style>
</head>
<body>
<?//if($_REQUEST['dev']) echo "<pre>".print_r($cityArr, 1)."</pre>";?>
<div class="iecontainer">

<div id="page-preloader"><span class="spinner"></span></div>
<div id="panel"><?//$APPLICATION->ShowPanel();?></div>
<div class="formobileviewport"></div>


<div id="change-resolution-need-refresh">
	<p>
		<img src="/upload/medialibrary/36b/21de26bcca6b3168d25da3602abbc802-red-refresh-line-icon-svg.png" alt=""> <br>
		<span>Для корректного просмотра страницы требуется перезагрузка</span>
	</p>
</div>


<?foreach ($city as $key => $c) $array[mb_substr($c,0,1)][$key] = $c;
$citychko ='';	 
foreach($array as $symbol => $sub_array)
{
    $citychko .= '<li><h3>'.  $symbol.'</h3><div class="letterlist">';
    foreach($sub_array as $key => $ct)
    {
        $citychko .=  '<p><a href="#" data-citytv="'.$city_tourvisor[$key].'" data-citytvname="'.$tvcity[$city_tourvisor[$key]].'" data-city="'.$key.'" ';  if($ct==$User_city['city']) $citychko .=  ' class="active" '; $citychko .=  '>'.$ct.'</a></p>' ;
    }
    $citychko .=  '</div></li>	';
}
//if($_REQUEST['dev']) echo "<pre>".print_r($tvcity, 1)."</pre>";
?>
<ul class="navigation">
			<li class="nav-item cl"><a></a></li>
			<li class="mobile-city"> <span style="margin-right: 0;"><?=$User_city['city']?></span> <i class="glyphicon glyphicon-triangle-bottom" style="color:#db3636;font-size:0.5em;margin-right: 20px;"></i>
			</li>
			<li class="mobile-calls"> <a href="#" class="callback" data-toggle="modal" data-target="#callback"><i class="glyphicon glyphicon-earphone" style="color:#fff;margin-right:10px;"></i> Заказать обратный звонок</a> </li>
			<?$APPLICATION->IncludeComponent(
				"bitrix:menu",
				"tabs",
				Array(
					"ROOT_MENU_TYPE"	=>	"top",
					"MAX_LEVEL"	=>	"1",
					"USE_EXT"	=>	"N",
					"MENU_CACHE_TYPE" => "A",
					"MENU_CACHE_TIME" => "3600",
					"MENU_CACHE_USE_GROUPS" => "N",
					"MENU_CACHE_GET_VARS" => Array()
				)
			);?>
		</ul>
<div class="cityAll2" ><ol id="cityx2"><?=$citychko?></ol></div>
		<nav class="navbar">
			<ul>
				<li><a href="/" noindex="noindex"><img style="height: 52px" src="<?=SITE_TEMPLATE_PATH?>/images/logosecondversion.png"></a></li>
				<li class="citys">
					<span style="margin-right: 0;"><?=$User_city['city']?></span>
					<i class="glyphicon glyphicon-triangle-bottom" style="color:#db3636;font-size:0.5em;margin-right: 20px;"></i>
					<div class="cityAll" ><ol id="cityx"><?=$citychko?></ol></div>
				</li>
				<li class="phones"> 
				<span><b> </b></span> 
				<span class="p2"> </i></span>
				</li>
				<li class="addresses" style="position:relative;"><i class="glyphicon glyphicon-map-marker red-style" ></i> <b>Адреса офисов</b> 
					<div class="adressAll">
						<span class="mob-close">X</span>
						<div class="choodeTown"><?=$User_city['city']?> <i class="glyphicon glyphicon-triangle-bottom" style="color:#db3636;font-size:0.5em;margin-right: 20px;"></i></div>
						<ul></ul>
						<br style="clear:both;">
					</div>
				</li>
				<li class="lk"> <?
				if ($USER->IsAuthorized()){?>
					<a href="/lk/" class="fa fa-user" ></a>
				<?}else {?>
					<a href="#" class="fa fa-user" data-toggle="modal" data-target="#login" ></a>
				<?}?>
					 
				</li>
				<li> <a href="#" class="callback" data-toggle="modal" data-target="#callback" ><i class="glyphicon glyphicon-earphone" style="color:#fff;margin-right:10px;"></i> Заказать обратный звонок</a> </li>
				<li class='expandMenu'> 
					<i class="line"></i>
					<i class="line"></i>
					<i class="line"></i>
				</li> 
			</ul>
		</nav>
