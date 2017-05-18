<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("dev");
//$APPLICATION->RestartBuffer();
// Логин и пароль от API (tourvisor.ru)
$login = 'i@neoz.su';
$pass = 'jICPOQJ7';

//Получаем список городов вылета
$departure = file_get_contents('http://tourvisor.ru/xml/list.php?format=xml&type=departure&authlogin=' . $login . '&authpass=' . $pass . '&format=json');
$departure = json_decode($departure, 1);
$departure = $departure['lists']['departures']['departure'];
foreach($departure as $country){
    $countries[$country['id']] = $country['name'];
}

$regions = file_get_contents('http://tourvisor.ru/xml/list.php?format=xml&type=region&authlogin='.$login.'&authpass='.$pass.'&format=json');
$regions = json_decode($regions, 1);
$region_list = $regions['lists']['regions']['region'];
foreach($region_list as $region){
    $regions[$region['id']] = $region['name'];
}

/*==========================================================================================================*/
$arSelect = Array("ID", "NAME", "PROPERTY_group", "PROPERTY_country", "PROPERTY_region", "PROPERTY_star", "PROPERTY_tourvisor_id");
$arFilter = Array("IBLOCK_ID" => 29, "ACTIVE" => "Y");
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize" => 50), $arSelect);
while ($ob = $res->GetNextElement()) {
    $hotel = $ob->GetFields();
    $hotels[] = $hotel;
}
//echo "<pre>".print_r($departure , 1)."</pre>";
?>
<div class="container">
    <div class="overflow ">
        <div class="row">
            <form id="hotel_groups">
                <div class="col-md-3">
                    <select class="group" name="group">
                        <option value="0">Выбор группы</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="departure" name="departure">
                        <option value="0">Выберите страну</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="regions" name="regions">
                        <option value="0">Выберите Курорт</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select>
                        <option value="0">Выбор звездности (1-5)</option>
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                </div>
            </form>
        </div>
        <br>

        <?foreach($hotels as $hotel):?>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-md-2"><?=$hotel['PROPERTY_TOURVISOR_ID_VALUE']?></div>
                    <div class="col-md-2"><?=$hotel['NAME']?></div>
                    <div class="col-md-2"><?=$hotel['PROPERTY_STAR_VALUE']?> звезд</div>
                    <div class="col-md-2"><?=$countries[$hotel['PROPERTY_COUNTRY_VALUE']]?></div>
                    <div class="col-md-2"><?=$regions[$hotel['PROPERTY_REGION_VALUE']]?></div>
                    <div class="col-md-1">группа <?=$hotel['PROPERTY_GROUP_VALUE']?></div>
                    <div class="col-md-1"><a href="#" data-id="<?=$hotel['ID']?>">удалить</a></div>
                </div>
            </div>
            <br>
        <?endforeach?>
    </div>
</div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>

    <style>
        body {
            background: url(/bitrix/templates/tour/images/bgr/lk_bg.jpg);
            background-attachment: fixed;
            background-size: cover;
        }

        .overflow {
            background: rgba(255, 255, 255, .8);
            border-radius: 5px;
            padding: 25px 40px;
            margin-top: 70px;
        }
        .city_departure {
            color: black;
        }
    </style>


<?/*
$rsSections = CIBlockSection::GetList(array(), array('IBLOCK_ID' => 20));
while ($arSection = $rsSections->Fetch()){
    $arSelect = Array("ID", "NAME", "PROPERTY_DATEFROM", "PROPERTY_COUNTRY", "PROPERTY_DEPARTURE", "PROPERTY_CURORT");
    $arFilter = Array("IBLOCK_ID"=>20, "SECTION_ID"=>$arSection['ID'], "ACTIVE"=>"Y");
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
    echo "<pre>".print_r("<br><br><br>", 1)."</pre>";
    while($ob = $res->GetNextElement()){
        $arFields = $ob->GetFields();
        echo "<pre>".print_r($arFields, 1)."</pre>";
        $arSelect = Array("ID", "NAME");
        $arFilter = Array("IBLOCK_ID"=>23, "PROPERTY_tour_id"=>$arFields['ID']);
        $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
        echo "<pre>".print_r("<br><br><br>", 1)."</pre>";
        while($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            echo "<pre>" . print_r($arFields, 1) . "</pre>";
        }
    }
}
*/?>