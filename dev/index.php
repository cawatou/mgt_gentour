<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("dev");
//$APPLICATION->RestartBuffer();
// Логин и пароль от API (tourvisor.ru)
$login = 'i@neoz.su';
$pass = 'jICPOQJ7';

//Получаем список городов вылета
$departure = file_get_contents('http://tourvisor.ru/xml/list.php?format=xml&type=departure&authlogin=' . $login . '&authpass=' . $pass . '&format=json');
$departure = json_decode($departure); 
$departure = $departure->lists->departures->departure;

/*
$test = file_get_contents('http://tourvisor.ru/xml/search.php?authlogin=' . $login . '&authpass=' . $pass . '&datefrom=30.07.2017&dateto=30.07.2017&nightsfrom=4&nightsto=4&adults=2&operators=&meal=0&stars=1&rating=0&hoteltypes=&country=47&regions=423&departure=9&pricefrom=0&priceto=200001&currency=0&directonly=0&showoperator=1&pricetype=0&format=json');
$json = json_decode($test, 1);
$reqid = $json['result']['requestid'];
sleep(25);

for($i=1; $i<=10; $i++){
    $res = file_get_contents('http://tourvisor.ru/xml/result.php?authlogin=' . $login . '&authpass=' . $pass . '&requestid=' . $reqid . '&type=result&page='.$i.'&onpage=100&format=json');
    $result[] = json_decode($res, 1);
}*/


/*==========================================================================================================*/
$rsSections = CIBlockSection::GetList(array(), array('IBLOCK_ID' => 20));
while ($arSection = $rsSections->Fetch()) {
    $sections[$arSection['ID']] = $arSection;
    $arSelect = Array("ID", "NAME", "PROPERTY_DATEFROM", "PROPERTY_COUNTRY", "PROPERTY_DEPARTURE", "PROPERTY_CURORT", "PROPERTY_DAYCOUNT", "PROPERTY_MIN_PRICE");
    $arFilter = Array("IBLOCK_ID" => 20, "SECTION_ID" => $arSection['ID'], "ACTIVE" => "Y");
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize" => 50), $arSelect);
    while ($ob = $res->GetNextElement()) {
        $tour = $ob->GetFields();
        $tours[$arSection['ID']][] = $tour;
    }
}
?>
<div class="container">
    <div class="overflow ">
        <div class="row">
            <?= "<pre>" . print_r($result, 1) . "</pre>"; ?>
            <form id="gen_tours">
                <div class="col-md-6">
                    <input class="form-control" type="text" name="date_from" placeholder="Дата от:">
                    <select class="departure" name="departure">
                        <option value="0">Выберите город</option>
                    </select>
                    <select class="country" name="country">
                        <option value="0">Выберите страну</option>
                    </select>
                    <select class="regions" name="regions[]" multiple>
                        <option value="0">Выберите Курорт</option>
                    </select>
                    <input type="text" id="region_name" name="region_name" placeholder="Название региона" value="">
                </div>
                <div class="col-md-6">
                    <input class="form-control" type="text" name="date_to" placeholder="Дата до:">
                    <input class="form-control" type="text" name="hotel3" placeholder="Отель 0-3*">
                    <input class="form-control" type="text" name="hotel4" placeholder="Отель 4*">
                    <input class="form-control" type="text" name="hotel5" placeholder="Отель 5*">
                </div>

                <input type="hidden" name="requestid" class="requestid" value="0">
                <input type="hidden" name="status" class="status " value="0">
                <pre class="result"></pre>
                <input type="submit" value="Начать генерацию"/>
            </form>
        </div>
    </div>
</div>


<div class="container">
    <div class="overflow ">
        <div class="row">
            <div class="col-md-12">
                <div class="row searchline">
                    <div class="col-md-1">
                        <b>Фильтр</b>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group ">
                            <label for=" ">Выберите город отправления </label>
                            <input id=" " name="filter_city" class="form-control"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group ">
                            <label for=" ">Укажите страну </label>
                            <input id=" " name="filter_country" class="form-control"/>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group ">
                            <label for=" "> &nbsp;</label>
                            <button>Применить</button>
                        </div>
                    </div>
                </div>

                <table class="table  table-striped">
                    <thead>
                    <tr>
                        <th>Тур</th>
                        <th>Название</th>
                        <th>Отправление</th>
                        <th>Страна/Курорт</th>
                        <th>Даты</th>
                        <th>Цена</th>
                        <th>Туроператор</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?
                    $arFilter = Array("IBLOCK_ID" => 20, "UF_GENERATED" => 0);
                    $res = CIBlockSection::GetList(Array("ID" => "DESC"), $arFilter, true, Array("ID", "IBLOCK_ID", "ACTIVE", "SORT", "PICTURE", "NAME", "UF_DEPARTURE"));
                    while ($resr = $res->GetNext()) {


                        $arSelect = Array("ID", "IBLOCK_ID", "ACTIVE", "SORT", "TIMESTAMP_X", "NAME", "PREVIEW_PICTURE", "PROPERTY_COUNTRY", "PROPERTY_PRICE", "PROPERTY_MIN_PRICE", "PROPERTY_PRICEDISCOUNT", "PROPERTY_CURORT", "PROPERTY_DAYCOUNT", "PROPERTY_TUROPERATOR", "PROPERTY_DATEFROM", "PROPERTY_DATETO", "PROPERTY_DEPARTURE");
                        $arFilter2 = Array("IBLOCK_ID" => 20, "IBLOCK_SECTION_ID" => $resr['ID']);
                        $res2 = CIBlockElement::GetList(Array(), $arFilter2, false, Array("nPageSize" => 50), $arSelect);
                        while ($ob = $res2->GetNextElement()) {
                            $arFields = $ob->GetFields();
                            /*
                             echo "<pre>";
                                var_dump($arFields);
                                echo "</pre>";
                             $arProps = $ob->GetProperties();
                            echo "<pre>";
                                var_dump($arProps);
                                echo "</pre>";
                                */
                        }

                        ?>
                        <tr>
                            <th scope="row"><?= $resr['ID'] ?></th>
                            <td><?= $resr['NAME'] ?></td>
                            <td>из <?= $arFields['PROPERTY_DEPARTURE_VALUE'] ?></td>
                            <td><?= $arFields['PROPERTY_COUNTRY_VALUE'] ?>
                                /<?= $arFields['PROPERTY_CURORT_VALUE'] ?></td>
                            <td><?= $arFields['PROPERTY_DATEFROM_VALUE'] ?>
                                ,<br> <?= $arFields['PROPERTY_DATETO_VALUE'] ?></td>
                            <td><s><?= $arFields['PROPERTY_MIN_PRICE'] ?></s>
                                <s><?= $arFields['PROPERTY_PRICE_VALUE'] ?></s><br> <?= $arFields['PROPERTY_PRICEDISCOUNT_VALUE'] ?>
                            </td>
                            <td><?= $arFields['PROPERTY_TUROPERATOR'] ?> </td>

                            <td><a class="delete fa  fa-trash-o" data-id="<?= $resr['ID'] ?>"></a></td>
                        </tr>
                    <? } ?>
                    </tbody>
                </table>
            </div>
        </div>
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
        margin-top: 50px;
    }

    .city_departure {
        color: black;
    }
</style>


<? /*
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
*/ ?>