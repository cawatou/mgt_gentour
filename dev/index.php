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
//echo "<pre>".print_r($User_city['city'] , 1)."</pre>";
?>
<?if(1 == 2):?>
<div>
    <section style="background:url('<?= $arr[1]['src'] ?>') no-repeat center center;">

        <div class="container hotindex">
            <div class="row">
                <div class="col-md-6 hotcy">
                    <div class="hotcity">
                        Горящие туры и путевки из

                        <div class="dropdown">
                            <select class="city-toggler city_departure departure" name="city_departure">
                                <option value="0">Выберите город</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-md-offset-4" style="text-align:right">
                    <div class="hotlist"><span class="fa fa-list-ul"></span></div>
                    <div class="hotthumb active"><span class="thumb"></span></div>

                </div>
            </div>

            <div class="owl-carousel gridview">
                <?foreach($sections as $k => $section):?>
                    <div class="item ">
                        <div class="hotblocks"
                             <? if ($t["PREVIEW_PICTURE"] != NULL) { ?>style="background-image: url(<?= CFile::GetPath($t["PREVIEW_PICTURE"]) ?>) !important;" <?
                        } ?> data-turid="<?= $t['ID'] ?>">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <span class="tur_country"><?=$tours[$k][0]['PROPERTY_COUNTRY_VALUE']?></span>
                                    <span class="tur_city"><?=$tours[$k][0]['PROPERTY_CURORT_VALUE']?></span>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <span class="tur_discount"> </span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <span class="tur_price"><?=$tours[$k][0]['PROPERTY_MIN_PRICE_VALUE']?> Р</span>
                                    <span class="tur_date"><i class="fa fa-plane redish"></i> Дата вылета: <?=$tours[$k][0]['PROPERTY_DATEFROM_VALUE']?></span>
                                    <span class="tur_night"><i class="calendar1"></i> Количество ночей: <?=$tours[$k][0]['PROPERTY_DAYCOUNT_VALUE']?></span>
                                    <span class="tur_update"><i class="fa fa-refresh redish"></i> Обновлено: <?=date('d.m.Y');?></span>
                                </div>
                            </div>
                        </div>
                        <div class="hotdeals">
                            <?foreach($tours[$k] as $tour):?>
                                <div>
                                    <span><?=$tour['PROPERTY_DATEFROM_VALUE']?></span>
                                    <span><?=$tour['PROPERTY_DAYCOUNT_VALUE']?> дней</span>
                                    <span><?=$tour['PROPERTY_MIN_PRICE_VALUE']?> Р</span>
                                </div>
                            <?endforeach?>
                        </div>
                    </div>
                <?endforeach?>
            </div>

            <div class="listview">
                <?foreach($sections as $k => $section):?>
                    <div class="deal">
                        <div class="hotlistview">
                            <div class="row">
                                <div class="col-md-2 ">
                                    <div class="pic"><img src="<?= CFile::GetPath($t["PREVIEW_PICTURE"]) ?>"
                                                          alt=""/></div>
                                </div>
                                <div class="hotlisted">
                                    <div class="col-md-2 ">
                                        <?=$tours[$k][0]['PROPERTY_COUNTRY_VALUE']?>,
                                        <b><?=$tours[$k][0]['PROPERTY_CURORT_VALUE']?></b>
                                    </div>
                                    <div class="col-md-2 ">
                                        <i class="fa fa-plane redish"></i><b> Дата
                                            вылета: <?=$tours[$k][0]['PROPERTY_DATEFROM_VALUE']?></b>
                                    </div>
                                    <div class="col-md-2 ">
                                        <i class="calendar2"></i><b> Количество
                                            ночей: <?=$tours[$k][0]['PROPERTY_DAYCOUNT_VALUE']?></b>
                                    </div>
                                    <div class="col-md-2 hotrefresh ">
                                        <i class="fa fa-refresh redish"></i>
                                        Обновлено: <?=date('d.m.Y');?>
                                    </div>
                                </div>
                                <div class="hotlistover">
                                    <div class="col-md-8 ">
                                        <?foreach($tours[$k] as $tour):?>
                                            <p>
                                                <b><?=$tour['PROPERTY_DATEFROM_VALUE']?></b>
                                                <?=$tour['PROPERTY_DAYCOUNT_VALUE']?> дней
                                                <i><?=$tour['PROPERTY_MIN_PRICE_VALUE']?> Р</i>
                                            </p>
                                       <?endforeach?>
                                    </div>
                                </div>

                                <div class="col-md-2 hotprice">
                                    <span class="price"><?=$tours[$k][0]['PROPERTY_MIN_PRICE_VALUE']?> Р</span>
                                    <span class="discount"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                <?endforeach?>
            </div>
        </div>

    </section>

</div>

<section class="vertical-scrolling sibling_tours">
    <div class="container hotturblock" >
        <div class="row r25">
            <div class="col-md-3 " >
                <div class="hotblocks">
                    <div class="row">
                        <div class="col-md-6 ">
                            <span class="tur_country">Тайланд</span>
                            <span class="tur_city">Патайя</span>
                        </div>
                        <div class="col-md-6 ">
                            <span class="tur_discount">-25%</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <span class="tur_price">20 000 Р</span>
                            <span class="tur_start">из кемерово</span>

                            <span class="tur_date"><i class="fa fa-plane redish"></i> Дата вылета: 18.01</span>
                            <span class="tur_night"><i class="calendar1"></i> Количество ночей: 7</span>
                            <span class="tur_update"><i class="fa fa-refresh redish"></i> Обновлено: 5 февраля в 15:30</span>
                        </div>
                    </div>
                </div>
                <div class="hotdeals">
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 ">
                <div class="hotblocks">
                    <div class="row">
                        <div class="col-md-6 ">
                            <span class="tur_country">Тайланд</span>
                            <span class="tur_city">Патайя</span>
                        </div>
                        <div class="col-md-6 ">
                            <span class="tur_discount">-25%</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <span class="tur_price">20 000 Р</span>
                            <span class="tur_start">из кемерово</span>

                            <span class="tur_date"><i class="fa fa-plane redish"></i> Дата вылета: 18.01</span>
                            <span class="tur_night"><i class="calendar1"></i> Количество ночей: 7</span>
                            <span class="tur_update"><i class="fa fa-refresh redish"></i> Обновлено: 5 февраля в 15:30</span>
                        </div>
                    </div>
                </div>
                <div class="hotdeals">
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 ">
                <div class="hotblocks">
                    <div class="row">
                        <div class="col-md-6 ">
                            <span class="tur_country">Тайланд</span>
                            <span class="tur_city">Патайя</span>
                        </div>
                        <div class="col-md-6 ">
                            <span class="tur_discount">-25%</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <span class="tur_price">20 000 Р</span>
                            <span class="tur_start">из кемерово</span>
                            <span class="tur_date"><i class="fa fa-plane redish"></i> Дата вылета: 18.01</span>
                            <span class="tur_night"><i class="calendar1"></i> Количество ночей: 7</span>
                            <span class="tur_update"><i class="fa fa-refresh redish"></i> Обновлено: 5 февраля в 15:30</span>
                        </div>
                    </div>
                </div>
                <div class="hotdeals">
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 ">
                <div class="hotblocks">
                    <div class="row">
                        <div class="col-md-6 ">
                            <span class="tur_country">Тайланд</span>
                            <span class="tur_city">Патайя</span>
                        </div>
                        <div class="col-md-6 ">
                            <span class="tur_discount">-25%</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <span class="tur_price">20 000 Р</span>
                            <span class="tur_start">из кемерово</span>

                            <span class="tur_date"><i class="fa fa-plane redish"></i> Дата вылета: 18.01</span>
                            <span class="tur_night"><i class="calendar1"></i> Количество ночей: 7</span>
                            <span class="tur_update"><i class="fa fa-refresh redish"></i> Обновлено: 5 февраля в 15:30</span>
                        </div>
                    </div>
                </div>
                <div class="hotdeals">
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="row r25">
            <div class="col-md-3 " >
                <div class="hotblocks">
                    <div class="row">
                        <div class="col-md-6 ">
                            <span class="tur_country">Тайланд</span>
                            <span class="tur_city">Патайя</span>
                        </div>
                        <div class="col-md-6 ">
                            <span class="tur_discount">-25%</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <span class="tur_price">20 000 Р</span>
                            <span class="tur_start">из краснодара</span>

                            <span class="tur_date"><i class="fa fa-plane redish"></i> Дата вылета: 18.01</span>
                            <span class="tur_night"><i class="calendar1"></i> Количество ночей: 7</span>
                            <span class="tur_update"><i class="fa fa-refresh redish"></i> Обновлено: 5 февраля в 15:30</span>
                        </div>
                    </div>
                </div>
                <div class="hotdeals">
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 ">
                <div class="hotblocks">
                    <div class="row">
                        <div class="col-md-6 ">
                            <span class="tur_country">Тайланд</span>
                            <span class="tur_city">Патайя</span>
                        </div>
                        <div class="col-md-6 ">
                            <span class="tur_discount">-25%</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <span class="tur_price">20 000 Р</span>
                            <span class="tur_start">из ростова</span>

                            <span class="tur_date"><i class="fa fa-plane redish"></i> Дата вылета: 18.01</span>
                            <span class="tur_night"><i class="calendar1"></i> Количество ночей: 7</span>
                            <span class="tur_update"><i class="fa fa-refresh redish"></i> Обновлено: 5 февраля в 15:30</span>
                        </div>
                    </div>
                </div>
                <div class="hotdeals">
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 ">
                <div class="hotblocks">
                    <div class="row">
                        <div class="col-md-6 ">
                            <span class="tur_country">Тайланд</span>
                            <span class="tur_city">Патайя</span>
                        </div>
                        <div class="col-md-6 ">
                            <span class="tur_discount">-25%</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <span class="tur_price">20 000 Р</span>
                            <span class="tur_start">из владикавказа</span>

                            <span class="tur_date"><i class="fa fa-plane redish"></i> Дата вылета: 18.01</span>
                            <span class="tur_night"><i class="calendar1"></i> Количество ночей: 7</span>
                            <span class="tur_update"><i class="fa fa-refresh redish"></i> Обновлено: 5 февраля в 15:30</span>
                        </div>
                    </div>
                </div>
                <div class="hotdeals">
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                </div>
            </div>
            <div class="col-md-3 ">
                <div class="hotblocks">
                    <div class="row">
                        <div class="col-md-6 ">
                            <span class="tur_country">Тайланд</span>
                            <span class="tur_city">Патайя</span>
                        </div>
                        <div class="col-md-6 ">
                            <span class="tur_discount">-25%</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <span class="tur_price">20 000 Р</span>
                            <span class="tur_start">из екатеринбурга</span>

                            <span class="tur_date"><i class="fa fa-plane redish"></i> Дата вылета: 18.01</span>
                            <span class="tur_night"><i class="calendar1"></i> Количество ночей: 7</span>
                            <span class="tur_update"><i class="fa fa-refresh redish"></i> Обновлено: 5 февраля в 15:30</span>
                        </div>
                    </div>
                </div>
                <div class="hotdeals">
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container hotturblock-mobile" >
    <div class="hot-carusel owl-carousel">
        <div class="item">
            <div class="wraps " >
                <div class="hotblocks">
                    <div class="row">
                        <div class="col-xs-6 ">
                            <span class="tur_country">Тайланд</span>
                            <span class="tur_city">Патайя</span>
                        </div>
                        <div class="col-xs-6 ">
                            <span class="tur_discount">-25%</span>
                            <span class="tur_price">20 000 Р</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">

                            <span class="tur_date"><i class="fa fa-plane redish"></i> Дата вылета: 18.01</span>
                            <span class="tur_night"><i class="calendar1"></i> Количество ночей: 7</span>
                            <span class="tur_update"><i class="fa fa-refresh redish"></i> Обновлено: 5 февраля в 15:30</span>
                        </div>
                    </div>
                </div>
                <div class="hotdeals">
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                </div>
            </div>
            <div class="wraps " >
                <div class="hotblocks">
                    <div class="row">
                        <div class="col-xs-6 ">
                            <span class="tur_country">Тайланд</span>
                            <span class="tur_city">Патайя</span>
                        </div>
                        <div class="col-xs-6 ">
                            <span class="tur_discount">-25%</span>
                            <span class="tur_price">20 000 Р</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">

                            <span class="tur_date"><i class="fa fa-plane redish"></i> Дата вылета: 18.01</span>
                            <span class="tur_night"><i class="calendar1"></i> Количество ночей: 7</span>
                            <span class="tur_update"><i class="fa fa-refresh redish"></i> Обновлено: 5 февраля в 15:30</span>
                        </div>
                    </div>
                </div>
                <div class="hotdeals">
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                </div>
            </div>

        </div>
        <div class="item">
            <div class="wraps " >
                <div class="hotblocks">
                    <div class="row">
                        <div class="col-xs-6 ">
                            <span class="tur_country">Тайланд</span>
                            <span class="tur_city">Патайя</span>
                        </div>
                        <div class="col-xs-6 ">
                            <span class="tur_discount">-25%</span>
                            <span class="tur_price">20 000 Р</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">


                            <span class="tur_date"><i class="fa fa-plane redish"></i> Дата вылета: 18.01</span>
                            <span class="tur_night"><i class="calendar1"></i> Количество ночей: 7</span>
                            <span class="tur_update"><i class="fa fa-refresh redish"></i> Обновлено: 5 февраля в 15:30</span>
                        </div>
                    </div>
                </div>
                <div class="hotdeals">
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                </div>
            </div>
            <div class="wraps ">
                <div class="hotblocks">
                    <div class="row">
                        <div class="col-xs-6 ">
                            <span class="tur_country">Тайланд</span>
                            <span class="tur_city">Патайя</span>
                        </div>
                        <div class="col-xs-6 ">
                            <span class="tur_discount">-25%</span>
                            <span class="tur_price">20 000 Р</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">

                            <span class="tur_date"><i class="fa fa-plane redish"></i> Дата вылета: 18.01</span>
                            <span class="tur_night"><i class="calendar1"></i> Количество ночей: 7</span>
                            <span class="tur_update"><i class="fa fa-refresh redish"></i> Обновлено: 5 февраля в 15:30</span>
                        </div>
                    </div>
                </div>
                <div class="hotdeals">
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="wraps" >
                <div class="hotblocks">
                    <div class="row">
                        <div class="col-xs-6 ">
                            <span class="tur_country">Тайланд</span>
                            <span class="tur_city">Патайя</span>
                        </div>
                        <div class="col-xs-6 ">
                            <span class="tur_discount">-25%</span>
                            <span class="tur_price">20 000 Р</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">


                            <span class="tur_date"><i class="fa fa-plane redish"></i> Дата вылета: 18.01</span>
                            <span class="tur_night"><i class="calendar1"></i> Количество ночей: 7</span>
                            <span class="tur_update"><i class="fa fa-refresh redish"></i> Обновлено: 5 февраля в 15:30</span>
                        </div>
                    </div>
                </div>
                <div class="hotdeals">
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                </div>
            </div>
            <div class="wraps " >
                <div class="hotblocks">
                    <div class="row">
                        <div class="col-xs-6 ">
                            <span class="tur_country">Тайланд</span>
                            <span class="tur_city">Патайя</span>
                        </div>
                        <div class="col-xs-6 ">
                            <span class="tur_discount">-25%</span>
                            <span class="tur_price">20 000 Р</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12">


                            <span class="tur_date"><i class="fa fa-plane redish"></i> Дата вылета: 18.01</span>
                            <span class="tur_night"><i class="calendar1"></i> Количество ночей: 7</span>
                            <span class="tur_update"><i class="fa fa-refresh redish"></i> Обновлено: 5 февраля в 15:30</span>
                        </div>
                    </div>
                </div>
                <div class="hotdeals">
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                    <div>
                        <span>25.02</span>
                        <span>10 дней</span>
                        <span>25 000 Р</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</section>

<?//echo "<pre>".print_r($sections, 1)."</pre>";?>
<?endif?>





<div class="container">
    <div class="overflow ">
        <div class="row">
            <form id="gen_tours">
                <div class="col-md-6">
                    <input class="form-control" type="text" name="date_from" placeholder="Дата от:">
                    <select class="departure" name="departure">
                        <option value="0">Выберите город</option>
                    </select>
                    <select class="country" name="country">
                        <option value="0">Выберите страну</option>
                    </select>
                    <select class="regions" name="regions">
                        <option value="0">Выберите Курорт</option>
                    </select>
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