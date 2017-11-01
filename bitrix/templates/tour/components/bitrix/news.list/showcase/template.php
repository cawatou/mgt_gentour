<?CModule::IncludeModule("iblock");
$rsSections = CIBlockSection::GetList(array("UF_MIN_PRICE" => "ASC"), array('IBLOCK_ID' => 20, "UF_DEPARTURE" => $_REQUEST['city_id']));
while ($arSection = $rsSections->Fetch()) {
    $secId = $arSection['ID'];
    $sections[$secId] = $arSection;
    $arSelect = Array("ID", "NAME", "TIMESTAMP_X", "PROPERTY_DATEFROM", "PROPERTY_COUNTRY", "PROPERTY_DISCOUNT", "PROPERTY_DEPARTURE", "PROPERTY_CURORT", "PROPERTY_DAYCOUNT", "PROPERTY_MIN_PRICE");
    $arFilter = Array("IBLOCK_ID" => 20, "SECTION_ID" => $arSection['ID'], "ACTIVE" => "Y");
    $res = CIBlockElement::GetList(Array("PROPERTY_DATEFROM" => "ASC", "ACTIVE"=>"Y"), $arFilter, false, Array("nPageSize" => 50), $arSelect);
    while ($ob = $res->GetNextElement()) {
        $tour = $ob->GetFields();
        $tours[$arSection['ID']][] = $tour;
        $departure = $tour['PROPERTY_DEPARTURE_VALUE'];
    }
}?>
<?/* ======================= Загрузка первых 4 ех туров в витрину ===============================================*/?>
<style>
    span.tur_country:last-child {
        font-size: 22px !important;
        margin-bottom: 17px !important;
    }
    @media only screen and (max-width: 1500px) {

        #franchize .fp-tableCell {
            vertical-align: middle !important;
            padding-bottom: 100px !important;
        }
    }
</style>
<?if(!function_exists('sortPrice')) {
    function sortPrice($arr){
        $minpriceIndex = 1000000000000000;
        $mI = $minpriceIndex;
        foreach($arr as $price=>$val) {
            $thisprice = (int) trim($val['PROPERTY_MIN_PRICE_VALUE']);
            if($minpriceIndex > $thisprice) {
                $mI = $price;
                $minpriceIndex = $thisprice;
            }
        }
        return $mI;
    }
}?>
<?if(count($sections) > 0 && isset($_REQUEST['showcase'])):?>
    <?$i=0;?>
    <div class="owl-carousel gridview">
        <?foreach ($sections as $k => $section):
            if(count($tours[$k])>0):
                $i++;                
                $bgr = NULL;
                $arSelect = Array();
                $arFilter = Array("IBLOCK_ID"=>21, "NAME"=>$tours[$k][0]['PROPERTY_COUNTRY_VALUE']);
                $result = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
                while($obj = $result->GetNextElement()) {
                    $arrReq = $obj->GetFields();
                    if(isset($arrReq["PREVIEW_PICTURE"])&&!empty($arrReq["PREVIEW_PICTURE"])){
                        $bgr=CFile::GetPath($arrReq["PREVIEW_PICTURE"]);
                    }
                    else
                        $bgr=NULL;
                    break;
                }

                $countryBigger = false;
                $groups = CIBlockElement::GetElementGroups($tours[$k][0]['ID'], true);
                while($ar_group = $groups->Fetch())
                    $ar_new_groups = $ar_group["NAME"];


                $ar_new_groups = explode('(', $ar_new_groups)[1];
                $ar_new_groups = explode(')', $ar_new_groups)[0];
                $countryBigger = false;
                if($ar_new_groups == '') {
                    $countryBigger = true;
                }?>
                <div class="item ">
                    <div class=" hotblocks"  <? if ($bgr != NULL) { ?>style="background-image: url(<?= $bgr ?>) !important;" <?} ?>>
                        <div class="row">
                            <div class="col-md-9 col-sm-6 col-xs-6">
                                <span class="tur_country" style="<? if($countryBigger){?>font-size: 1.5em;     margin-bottom: 16px;<?}?>"><?=$tours[$k][0]['PROPERTY_COUNTRY_VALUE'] ?></span>
                                <? if(!$countryBigger){?><span class="tur_city"><?=$ar_new_groups?></span><?}?>
                            </div>
                            <div class="col-md-3 col-sm-6 col-xs-6">
                                <?
                                if(!empty($tours[$k][0]['PROPERTY_MIN_PRICE_VALUE'])&&!empty($tours[$k][0]['PROPERTY_PRICEOLD_VALUE'])){
                                    $proc=round(100 - (($tours[$k][$mI]['PROPERTY_MIN_PRICE_VALUE']/$tours[$k][$mI]['PROPERTY_PRICEOLD_VALUE'])*100));
                                    if($proc>2){ ?><span class="tur_discount">-<?=$proc ?> %</span><?}
                                }
                                ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
				<span class="tur_price"><?
                    echo (int)($tours[$k][sortPrice($tours[$k])]['PROPERTY_MIN_PRICE_VALUE']/200)*100;
                    ?> Р</span>
                                <span class="tur_date"><i
                                        class="fa fa-plane redish"></i> Дата вылета: <?=date('d.m',strtotime($tours[$k][0]['PROPERTY_DATEFROM_VALUE'])) ?></span>
                                <span class="tur_night"><i
                                        class="calendar1"></i> Количество дней: <?=$tours[$k][0]['PROPERTY_DAYCOUNT_VALUE']+1 ?></span>
                                <span class="tur_update"><i
                                        class="fa fa-refresh redish"></i> Обновлено: <?= FormatDateFromDB($tours[$k][0]['TIMESTAMP_X'], "HH:i") ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="hotdeals" data-url = "/content/tours/?id=<?=$k;?>">
                        <?foreach ($tours[$k] as $tour):?>
                            <div>
                                <span><?= date('d.m',strtotime($tour['PROPERTY_DATEFROM_VALUE'])) ?></span>
                                <span><?= $tour['PROPERTY_DAYCOUNT_VALUE']+1 ?> дней</span>
                                <span><?= round($tour['PROPERTY_MIN_PRICE_VALUE']/2,-2) ?> Р</span>
                            </div>
                        <?endforeach?>
                    </div>
                </div>
                <?if($i == 4) {
                    $i = 0;
                    break;
                }?>
            <?endif?>
        <? endforeach ?>
    </div>

    <div class="listview">
        <? foreach ($sections as $k => $section): ?>
            <?


            $bgr = NULL;
            $arSelect = Array();
            $arFilter = Array("IBLOCK_ID"=>21, "NAME"=>$tours[$k][0]['PROPERTY_COUNTRY_VALUE']);
            $result = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
            while($obj = $result->GetNextElement()) {
                $arrReq = $obj->GetFields();
                if(isset($arrReq["PREVIEW_PICTURE"])&&!empty($arrReq["PREVIEW_PICTURE"])){
                    $bgr=CFile::GetPath($arrReq["PREVIEW_PICTURE"]);
                }
                else
                    $bgr=NULL;
                break;
            }


            ?>
            <?$i++?>
            <div class="deal">
                <div class="hotlistview">
                    <div class="row">
                        <div class="col-md-2 ">
                            <div class="pic"><img src="<? if ($bgr != NULL) { ?><?= $bgr ?> <?}else{ ?>/bitrix/templates/tour/images/bgrtour_03.png<? } ?>" alt=""/></div>
                        </div>


                        <div class="list-wrapper"  data-url="/content/tours/?id=<?=$k;?>">
                            <div class="hotlisted">
                                <div class="col-md-3 ">
                                    <?= $tours[$k][0]['PROPERTY_COUNTRY_VALUE'] ?>,
                                    <b><?= $tours[$k][0]['PROPERTY_CURORT_VALUE'] ?></b>
                                </div>
                                <div class="col-md-3 ">
                                    <i class="fa fa-plane redish"></i><b> Дата
                                        вылета: <?= date('d.m',strtotime($tours[$k][0]['PROPERTY_DATEFROM_VALUE'])) ?></b>
                                </div>
                                <div class="col-md-3 ">
                                    <i class="calendar2"></i><b> Количество
                                        дней: <?= $tours[$k][0]['PROPERTY_DAYCOUNT_VALUE']+1 ?></b>
                                </div>
                                <div class="col-md-3 hotrefresh ">
                                    <i class="fa fa-refresh redish"></i>
                                    Обновлено: <?= FormatDateFromDB($tours[$k][0]['TIMESTAMP_X'], "HH:i") ?>
                                </div>
                            </div>
                            <div class="hotlistover">
                                <div class="col-md-8 ">
                                    <? foreach ($tours[$k] as $tour): ?>
                                        <p>
                                            <b><?= date('d.m',strtotime($tour['PROPERTY_DATEFROM_VALUE'])) ?></b>
                                            <?= $tour['PROPERTY_DAYCOUNT_VALUE']+1 ?> дней
                                            <i><?= round($tour['PROPERTY_MIN_PRICE_VALUE']/2,-2) ?> Р</i>
                                        </p>
                                    <? endforeach ?>
                                </div>
                            </div>

                        </div>


                        <div class="col-md-2 hotprice">
						<span class="price"><? echo (int)($tours[$k][0]['PROPERTY_MIN_PRICE_VALUE']/200)*2;
                            ?> Р</span>
                            <?
                            if(!empty($tours[$k][0]['PROPERTY_MIN_PRICE_VALUE'])&&!empty($tours[$k][0]['PROPERTY_PRICEOLD_VALUE'])){
                                $proc=round(100 - (($tours[$k][0]['PROPERTY_MIN_PRICE_VALUE']/$tours[$k][0]['PROPERTY_PRICEOLD_VALUE'])*100));
                                if($proc>2){ ?><span class="tur_discount">-<?=$proc ?> %</span><?}
                            }
                            ?>

                        </div>
                    </div>
                </div>
            </div>
            <?if($i == 4) {
                $i = 0;
                break;
            }?>
        <? endforeach ?>
    </div>
<?endif?>


<?/* ======================= Загрузка остальных туров в витрину (в виде слайдера) ==============================*/?>
<?if(count($sections) > 4 && $_REQUEST['remaining_tours']):?>
    <style>
        .listviewz {
            display:none;
        }
    </style>
    <div class="onlyPc">
        <div class="slideTur s1">
            <h1 class="hottur">Горящие туры из <?=$departure?></h1>
            <div class="container hotturblock gridviewz">
                <div class="hot-carusel2 owl-carousel">
                    <?$i=0;?>
                    <?foreach ($sections as $k => $section):  ?>
                        <?$i++;
                        if($i <= 4) continue;
                        if(($i-1) % 2 == 0):?><div class="item s<?=$i?>"><?endif?>
                        <?$bgr = NULL;
                        $arSelect = Array();
                        $arFilter = Array("IBLOCK_ID"=>21, "NAME"=>$tours[$k][0]['PROPERTY_COUNTRY_VALUE']);
                        $result = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
                        while($obj = $result->GetNextElement()) {
                            $arrReq = $obj->GetFields();
                            if(isset($arrReq["PREVIEW_PICTURE"])&&!empty($arrReq["PREVIEW_PICTURE"])){
                                $bgr=CFile::GetPath($arrReq["PREVIEW_PICTURE"]);
                            }
                            else
                                $bgr=NULL;
                            break;
                        }
                        $countryBigger = false;
                        $groups = CIBlockElement::GetElementGroups($tours[$k][0]['ID'], true);
                        while($ar_group = $groups->Fetch())
                            $ar_new_groups = $ar_group["NAME"];

                        $ar_new_groups = explode('(', $ar_new_groups)[1];
                        $ar_new_groups = explode(')', $ar_new_groups)[0];

                        if($ar_new_groups == '') {
                            $countryBigger = true;
                        }     ?>
                        <div class="itmbl">
                            <div class="hotblocks" <? if ($bgr != NULL) { ?>style="background-image: url(<?= $bgr ?>) !important;" <?} ?>>
                                <div class="row">
                                    <div class="col-md-6 ">

                                        <span class="tur_country" style="<? if($countryBigger){?>font-size: 1.5em;margin-bottom: 16px;<?}?>"><?=$tours[$k][0]['PROPERTY_COUNTRY_VALUE']?></span>
                                        <? if(!$countryBigger){?><span class="tur_city"><?=$ar_new_groups?></span><?}?>
                                    </div>
                                    <div class="col-md-6 ">

                                        <?if($tours[$k][0]['PROPERTY_DISCOUNT_VALUE'] != NULL) { ?><span
                                            class="tur_discount">-<?=$tours[$k][0]['PROPERTY_DISCOUNT_VALUE']?>
                                            %</span>
                                        <? } ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
								<span class="tur_price"><?if(!empty($tours[$k][0]['PROPERTY_DISCOUNT_VALUE'])){
                                        echo $tours[$k][sortPrice($tours[$k])]['PROPERTY_MIN_PRICE_VALUE']-(($tours[$k][sortPrice($tours[$k])]['PROPERTY_MIN_PRICE_VALUE']/100)*$tours[$k][sortPrice($tours[$k])]['PROPERTY_DISCOUNT_VALUE']);
								}
                                    else
                                        echo (int)($tours[$k][sortPrice($tours[$k])]['PROPERTY_MIN_PRICE_VALUE']/200)*100;
                                    ?>	Р</span>
                                        <span class="tur_date"><i class="fa fa-plane redish"></i> Дата вылета: <?=FormatDateFromDB($tours[$k][0]['PROPERTY_DATEFROM_VALUE'], "DD.MM") ?></span>
                                        <span class="tur_night"><i class="calendar1"></i> Количество дней: <?=$tours[$k][0]['PROPERTY_DAYCOUNT_VALUE']+1 ?></span>
                                        <span class="tur_update"><i class="fa fa-refresh redish"></i> Обновлено: <?= FormatDateFromDB($tours[$k][0]['TIMESTAMP_X'], "HH:i") ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="hotdeals" data-url = "/content/tours/?id=<?=$k;?>">
                                <?foreach ($tours[$k] as $tour):?>
                                    <div>
                                        <span><?= date('d.m',strtotime($tour['PROPERTY_DATEFROM_VALUE'])) ?></span>
                                        <span><?= $tour['PROPERTY_DAYCOUNT_VALUE']+1 ?> дней</span>
                                        <span><?= (int)($tour['PROPERTY_MIN_PRICE_VALUE']/200)*100 ?> Р</span>
                                    </div>
                                <?endforeach?>
                            </div>
                        </div>
                        <?if( $i % 2 == 0):?></div><?endif?>
                        <?if ($i == 12) break;?>

                    <?endforeach?>
                    <?if( $i  % 2 == 1):?></div><?endif?>
            </div>
        </div>
        <div class="container hotturblock listviewz wtf">
            <?$i=0; 
            foreach ($sections as $k => $section):
                $i++;
                if($i <= 4) {continue;}
                $bgr = NULL;
                $arSelect = Array();
                $arFilter = Array("IBLOCK_ID"=>21, "NAME"=>$tours[$k][0]['PROPERTY_COUNTRY_VALUE']);
                $result = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
                while($obj = $result->GetNextElement()) {
                    $arrReq = $obj->GetFields();
                    if(isset($arrReq["PREVIEW_PICTURE"])&&!empty($arrReq["PREVIEW_PICTURE"])){
                        $bgr=CFile::GetPath($arrReq["PREVIEW_PICTURE"]);
                    }
                    else
                        $bgr=NULL;
                    break;
                }?>
                <div class="deal">
                    <div class="hotlistview">
                        <div class="row">
                            <div class="col-md-2 ">
                                <div class="pic"><img src="<? if ($bgr != NULL) { ?><?= $bgr ?> <?}else{ ?>/bitrix/templates/tour/images/bgrtour_03.png<? } ?>" alt=""/></div>
                            </div>
                            <div class="list-wrapper" data-url="/content/tours/?id=<?=$k;?>">
                                <div class="hotlisted">
                                    <div class="col-md-3 ">
                                        <?= $tours[$k][0]['PROPERTY_COUNTRY_VALUE'] ?>,
                                        <b><?= $tours[$k][0]['PROPERTY_CURORT_VALUE'] ?></b>
                                    </div>
                                    <div class="col-md-3 ">
                                        <i class="fa fa-plane redish"></i><b> Дата
                                            вылета: <?= date('d.m',strtotime($tours[$k][0]['PROPERTY_DATEFROM_VALUE'])) ?></b>
                                    </div>
                                    <div class="col-md-3 ">
                                        <i class="calendar2"></i><b> Количество
                                            дней: <?= $tours[$k][0]['PROPERTY_DAYCOUNT_VALUE']+1 ?></b>
                                    </div>
                                    <div class="col-md-3 hotrefresh ">
                                        <i class="fa fa-refresh redish"></i>
                                        Обновлено: <?= FormatDateFromDB($tours[$k][0]['TIMESTAMP_X'], "HH:i") ?>
                                    </div>
                                </div>
                                <div class="hotlistover">
                                    <div class="col-md-8 ">
                                        <? foreach ($tours[$k] as $tour): ?>
                                            <p>
                                                <b><?= date('d.m',strtotime($tour['PROPERTY_DATEFROM_VALUE'])) ?></b>
                                                <?= $tour['PROPERTY_DAYCOUNT_VALUE']+1 ?> дней
                                                <i><?= (int)($tour['PROPERTY_MIN_PRICE_VALUE']/200)*100 ?> Р</i>
                                            </p>
                                        <? endforeach ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 hotprice">
                                <span class="price">
                                    <? echo (int)($tours[$k][0]['PROPERTY_MIN_PRICE_VALUE']/200)*100;?> Р
                                </span>
                                <?if(!empty($tours[$k][0]['PROPERTY_MIN_PRICE_VALUE'])&&!empty($tours[$k][0]['PROPERTY_PRICEOLD_VALUE'])){
                                    $proc=round(100 - (($tours[$k][0]['PROPERTY_MIN_PRICE_VALUE']/$tours[$k][0]['PROPERTY_PRICEOLD_VALUE'])*100));
                                    if($proc>2){ ?><span class="tur_discount">-<?=$proc ?> %</span><?}
                                }?>
                            </div>
                        </div>
                    </div>
                </div>
                <?if($i == 12) break;?>
            <?endforeach?>
        </div>
    </div>
    <?if(count($sections) > 12):?>
        <div class="slideTur s2">
            <h1 class="hottur">Горящие туры из <?=$departure?></h1>
            <div class="container hotturblock gridviewz">
                <div class="hot-carusel2 owl-carousel">
                    <?$i=0;
                    foreach ($sections as $k => $section):
                        $i++;
                        if($i <= 12) {continue;}
                        if(($i-1) % 2 == 0):?><div class="item"><?endif?>
                        <?
                        $bgr = NULL;
                        $arSelect = Array();
                        $arFilter = Array("IBLOCK_ID"=>21, "NAME"=>$tours[$k][0]['PROPERTY_COUNTRY_VALUE']);
                        $result = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
                        while($obj = $result->GetNextElement()) {
                            $arrReq = $obj->GetFields();
                            if(isset($arrReq["PREVIEW_PICTURE"])&&!empty($arrReq["PREVIEW_PICTURE"])){
                                $bgr=CFile::GetPath($arrReq["PREVIEW_PICTURE"]);
                            }
                            else
                                $bgr=NULL;
                            break;
                        }
                        $countryBigger = false;
                        $groups = CIBlockElement::GetElementGroups($tours[$k][0]['ID'], true);
                        while($ar_group = $groups->Fetch())
                            $ar_new_groups = $ar_group["NAME"];

                        $ar_new_groups = explode('(', $ar_new_groups)[1];
                        $ar_new_groups = explode(')', $ar_new_groups)[0];

                        if($ar_new_groups == '') {
                            $countryBigger = true;
                        }?>
                        <div class="itmbl  s<?=$i?>">
                            <div class="hotblocks" <? if ($bgr != NULL) { ?>style="background-image: url(<?= $bgr ?>) !important;" <?} ?>>
                                <div class="row">
                                    <div class="col-md-6 ">
                                        <span class="tur_country"><?=$tours[$k][0]['PROPERTY_COUNTRY_VALUE']?></span>
                                        <? if(!$countryBigger){?><span class="tur_city"><?=$ar_new_groups?></span><?}?>
                                    </div>
                                    <div class="col-md-6 ">

                                        <?if($tours[$k][0]['PROPERTY_DISCOUNT_VALUE'] != NULL) { ?><span
                                            class="tur_discount">-<?=$tours[$k][0]['PROPERTY_DISCOUNT_VALUE']?>
                                            %</span>
                                        <? } ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
							<span class="tur_price"><?if(!empty($tours[$k][sortPrice($tours[$k])]['PROPERTY_DISCOUNT_VALUE'])){
                                    echo $tours[$k][sortPrice($tours[$k])]['PROPERTY_MIN_PRICE_VALUE']-(($tours[$k][sortPrice($tours[$k])]['PROPERTY_MIN_PRICE_VALUE']/100)*$tours[$k][sortPrice($tours[$k])]['PROPERTY_DISCOUNT_VALUE']);
                                }
                                else
                                    echo (int)($tours[$k][sortPrice($tours[$k])]['PROPERTY_MIN_PRICE_VALUE']/100)*100;
                                ?>	Р</span>
                                        <span class="tur_date"><i class="fa fa-plane redish"></i> Дата вылета: <?=FormatDateFromDB($tours[$k][0]['PROPERTY_DATEFROM_VALUE'], "DD.MM") ?></span>
                                        <span class="tur_night"><i class="calendar1"></i> Количество дней: <?=$tours[$k][0]['PROPERTY_DAYCOUNT_VALUE']+1 ?></span>
                                        <span class="tur_update"><i class="fa fa-refresh redish"></i> Обновлено: <?= FormatDateFromDB($tours[$k][0]['TIMESTAMP_X'], "HH:i") ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="hotdeals" data-url = "/content/tours/?id=<?=$k;?>">
                                <?foreach ($tours[$k] as $tour):?>
                                    <div>
                                        <span><?=date('d.m',strtotime($tour['PROPERTY_DATEFROM_VALUE'])) ?></span>
                                        <span><?= $tour['PROPERTY_DAYCOUNT_VALUE']+1 ?> дней</span>
                                        <span><?= round($tour['PROPERTY_MIN_PRICE_VALUE']/2,-2) ?> Р</span>
                                    </div>
                                <?endforeach?>
                            </div>
                        </div>
                        <?if( $i % 2 == 0):?></div><?endif?>
                        <?if ($i == 20) break;?>

                    <?endforeach?>
                    <?if( $i  % 2 == 1):?></div><?endif?>
            </div>
        </div>
        <div class="container hotturblock listviewz">
            <?$i=0; foreach ($sections as $k => $section): ?>
                <?$i++;
                if($i <= 12) {continue;}

                $bgr = NULL;
                $arSelect = Array();
                $arFilter = Array("IBLOCK_ID"=>21, "NAME"=>$tours[$k][0]['PROPERTY_COUNTRY_VALUE']);
                $result = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
                while($obj = $result->GetNextElement()) {
                    $arrReq = $obj->GetFields();
                    if(isset($arrReq["PREVIEW_PICTURE"])&&!empty($arrReq["PREVIEW_PICTURE"])){
                        $bgr=CFile::GetPath($arrReq["PREVIEW_PICTURE"]);
                    }
                    else
                        $bgr=NULL;
                    break;
                }
                ?>
                <div class="deal">
                    <div class="hotlistview">
                        <div class="row">
                            <div class="col-md-2 ">
                                <div class="pic"><img src="<? if ($bgr != NULL) { ?><?= $bgr ?> <?}else{ ?>/bitrix/templates/tour/images/bgrtour_03.png<? } ?>" alt=""/></div>
                            </div>

                            <div class="list-wrapper" data-url="/content/tours/?id=<?=$k;?>">
                                <div class="hotlisted">
                                    <div class="col-md-3 ">
                                        <?= $tours[$k][0]['PROPERTY_COUNTRY_VALUE'] ?>,
                                        <b><?= $tours[$k][0]['PROPERTY_CURORT_VALUE'] ?></b>
                                    </div>
                                    <div class="col-md-3 ">
                                        <i class="fa fa-plane redish"></i><b> Дата
                                            вылета: <?= date('d.m',strtotime($tours[$k][0]['PROPERTY_DATEFROM_VALUE'])) ?></b>
                                    </div>
                                    <div class="col-md-3 ">
                                        <i class="calendar2"></i><b> Количество
                                            дней: <?= $tours[$k][0]['PROPERTY_DAYCOUNT_VALUE']+1 ?></b>
                                    </div>
                                    <div class="col-md-3 hotrefresh ">
                                        <i class="fa fa-refresh redish"></i>
                                        Обновлено: <?= FormatDateFromDB($tours[$k][0]['TIMESTAMP_X'], "HH:i") ?>
                                    </div>
                                </div>
                                <div class="hotlistover">
                                    <div class="col-md-8 ">
                                        <? foreach ($tours[$k] as $tour): ?>
                                            <p>
                                                <b><?= date('d.m',strtotime($tour['PROPERTY_DATEFROM_VALUE'])) ?></b>
                                                <?= $tour['PROPERTY_DAYCOUNT_VALUE']+1 ?> дней
                                                <i><?= (int)($tour['PROPERTY_MIN_PRICE_VALUE']/200)*100 ?> Р</i>
                                            </p>
                                        <? endforeach ?>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-2 hotprice">
					<span class="price"><? echo (int)($tours[$k][0]['PROPERTY_MIN_PRICE_VALUE']/200)*100;
                        ?> Р</span>
                                <?
                                if(!empty($tours[$k][0]['PROPERTY_MIN_PRICE_VALUE'])&&!empty($tours[$k][0]['PROPERTY_PRICEOLD_VALUE'])){
                                    $proc=round(100 - (($tours[$k][0]['PROPERTY_MIN_PRICE_VALUE']/$tours[$k][0]['PROPERTY_PRICEOLD_VALUE'])*100));
                                    if($proc>2){ ?><span class="tur_discount">-<?=$proc ?> %</span><?}
                                }
                                ?>

                            </div>
                        </div>
                    </div>
                </div>
                <?if($i == 20) break;?>
            <?endforeach?>
        </div>
        </div>
    <?endif?>


    <?if(count($sections) > 20):?>
        <div class="slideTur s3">
            <h1 class="hottur">Горящие туры из <?=$departure?></h1>
            <div class="container hotturblock">
                <div class="hot-carusel2 owl-carousel">
                    <?$i=0;
                    foreach ($sections as $k => $section):
                        $i++;
                        if($i <= 20) continue;                        
                        if($i % 2 == 0):?><div class="item s<?=$i?>"><?endif?>
                        <?$bgr = NULL;
                        $arSelect = Array();
                        $arFilter = Array("IBLOCK_ID"=>21, "NAME"=>$tours[$k][0]['PROPERTY_COUNTRY_VALUE']);
                        $result = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
                        while($obj = $result->GetNextElement()) {
                            $arrReq = $obj->GetFields();
                            if(isset($arrReq["PREVIEW_PICTURE"])&&!empty($arrReq["PREVIEW_PICTURE"])){
                                $bgr=CFile::GetPath($arrReq["PREVIEW_PICTURE"]);
                            }
                            else
                                $bgr=NULL;
                            break;
                        }

                        $countryBigger = false;
                        $groups = CIBlockElement::GetElementGroups($tours[$k][0]['ID'], true);
                        while($ar_group = $groups->Fetch())
                            $ar_new_groups = $ar_group["NAME"];

                        $ar_new_groups = explode('(', $ar_new_groups)[1];
                        $ar_new_groups = explode(')', $ar_new_groups)[0];

                        if($ar_new_groups == '') {
                            $countryBigger = true;
                        }?>
                        <div class="itmbl">
                            <div class="hotblocks" <? if ($bgr != NULL) { ?>style="background-image: url(<?= $bgr ?>) !important;" <?} ?>>
                                <div class="row">
                                    <div class="col-md-6 ">
                                        <span class="tur_country"><?=$tours[$k][0]['PROPERTY_COUNTRY_VALUE']?></span>
                                        <? if(!$countryBigger){?><span class="tur_city"><?=$ar_new_groups?></span><?}?>
                                    </div>
                                    <div class="col-md-6 ">

                                        <?if($tours[$k][0]['PROPERTY_DISCOUNT_VALUE'] != NULL) { ?><span
                                            class="tur_discount">-<?=$tours[$k][0]['PROPERTY_DISCOUNT_VALUE']?>
                                            %</span>
                                        <? } ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
								<span class="tur_price"><?if(!empty($tours[$k][sortPrice($tours[$k])]['PROPERTY_DISCOUNT_VALUE'])){
                                        echo $tours[$k][sortPrice($tours[$k])]['PROPERTY_MIN_PRICE_VALUE']-(($tours[$k][sortPrice($tours[$k])]['PROPERTY_MIN_PRICE_VALUE']/100)*$tours[$k][sortPrice($tours[$k])]['PROPERTY_DISCOUNT_VALUE']);
                                    }
                                    else
                                        echo (int)($tours[$k][sortPrice($tours[$k])]['PROPERTY_MIN_PRICE_VALUE']/200)*100;
                                    ?>	Р</span>
                                        <span class="tur_date"><i class="fa fa-plane redish"></i> Дата вылета: <?=FormatDateFromDB($tours[$k][0]['PROPERTY_DATEFROM_VALUE'], "DD.MM") ?></span>
                                        <span class="tur_night"><i class="calendar1"></i> Количество ночей: <?=$tours[$k][0]['PROPERTY_DAYCOUNT_VALUE']+1 ?></span>
                                        <span class="tur_update"><i class="fa fa-refresh redish"></i> Обновлено: <?= FormatDateFromDB($tours[$k][0]['TIMESTAMP_X'], "HH:i") ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="hotdeals" data-url = "/content/tours/?id=<?=$k;?>">
                                <?foreach ($tours[$k] as $tour):?>
                                    <div>
                                        <span><?=date('d.m',strtotime($tour['PROPERTY_DATEFROM_VALUE'])) ?></span>
                                        <span><?= $tour['PROPERTY_DAYCOUNT_VALUE']+1 ?> дней</span>
                                        <span><?= round($tour['PROPERTY_MIN_PRICE_VALUE']/2,-2) ?> Р</span>
                                    </div>
                                <?endforeach?>
                            </div>
                        </div>
                        <?if( ($i - 1) % 2 == 0):?></div><?endif?>
                        <?if ($i == 28) break;?>                        
                    <?endforeach?>
                    <?if($i % 2 == 1):?></div><?endif?>
            </div>
        </div>
        </div>
    <?endif?>
    <?if(count($sections) > 28):?>
        <div class="slideTur s4">
            <h1 class="hottur">Горящие туры из <?=$departure?></h1>
            <div class="container hotturblock">
                <div class="hot-carusel2 owl-carousel">
                    <?$i=0;
                    foreach ($sections as $k => $section):
                        $i++;
                        if($i <= 28) {                            
                            continue;
                        }if($i % 2 == 0):?><div class="item s<?=$i?>"><?endif?>
                        <?
                        $bgr = NULL;
                        $arSelect = Array();
                        $arFilter = Array("IBLOCK_ID"=>21, "NAME"=>$tours[$k][0]['PROPERTY_COUNTRY_VALUE']);
                        $result = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
                        while($obj = $result->GetNextElement()) {
                            $arrReq = $obj->GetFields();
                            if(isset($arrReq["PREVIEW_PICTURE"])&&!empty($arrReq["PREVIEW_PICTURE"])){
                                $bgr=CFile::GetPath($arrReq["PREVIEW_PICTURE"]);
                            }
                            else
                                $bgr=NULL;
                            break;
                        }

                        $countryBigger = false;

                        $groups = CIBlockElement::GetElementGroups($tours[$k][0]['ID'], true);
                        while($ar_group = $groups->Fetch())
                            $ar_new_groups = $ar_group["NAME"];


                        $ar_new_groups = explode('(', $ar_new_groups)[1];
                        $ar_new_groups = explode(')', $ar_new_groups)[0];


                        if($ar_new_groups == '') {
                            $countryBigger = true;
                        }
                        ?>
                        <div class="itmbl">
                            <div class="hotblocks" <? if ($bgr != NULL) { ?>style="background-image: url(<?= $bgr ?>) !important;" <?} ?>>
                                <div class="row">
                                    <div class="col-md-6 ">
                                        <span class="tur_country"><?=$tours[$k][0]['PROPERTY_COUNTRY_VALUE']?></span>
                                        <? if(!$countryBigger){?><span class="tur_city"><?=$ar_new_groups?></span><?}?>
                                    </div>
                                    <div class="col-md-6 ">

                                        <?if($tours[$k][0]['PROPERTY_DISCOUNT_VALUE'] != NULL) { ?><span
                                            class="tur_discount">-<?=$tours[$k][0]['PROPERTY_DISCOUNT_VALUE']?>
                                            %</span>
                                        <? } ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
									<span class="tur_price"><?if(!empty($tours[$k][sortPrice($tours[$k])]['PROPERTY_DISCOUNT_VALUE'])){
                                            echo $tours[$k][sortPrice($tours[$k])]['PROPERTY_MIN_PRICE_VALUE']-(($tours[$k][sortPrice($tours[$k])]['PROPERTY_MIN_PRICE_VALUE']/100)*$tours[$k][sortPrice($tours[$k])]['PROPERTY_DISCOUNT_VALUE']);
                                        }
                                        else
                                            echo (int)($tours[$k][sortPrice($tours[$k])]['PROPERTY_MIN_PRICE_VALUE']/100)*100;
                                        ?>	Р</span>
                                        <span class="tur_date"><i class="fa fa-plane redish"></i> Дата вылета: <?=FormatDateFromDB($tours[$k][0]['PROPERTY_DATEFROM_VALUE'], "DD.MM") ?></span>
                                        <span class="tur_night"><i class="calendar1"></i> Количество ночей: <?=$tours[$k][0]['PROPERTY_DAYCOUNT_VALUE']+1 ?></span>
                                        <span class="tur_update"><i class="fa fa-refresh redish"></i> Обновлено: <?= FormatDateFromDB($tours[$k][0]['TIMESTAMP_X'], "HH:i") ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="hotdeals" data-url = "/content/tours/?id=<?=$k;?>">
                                <?foreach ($tours[$k] as $tour):?>
                                    <div>
                                        <span><?= date('d.m',strtotime($tour['PROPERTY_DATEFROM_VALUE'])) ?></span>
                                        <span><?= $tour['PROPERTY_DAYCOUNT_VALUE']+1 ?> дней</span>
                                        <span><?= round($tour['PROPERTY_MIN_PRICE_VALUE']/2,-2) ?> Р</span>
                                    </div>
                                <?endforeach?>
                            </div>
                        </div>
                        <?if( ($i - 1) % 2 == 0):?></div><?endif?>
                        <?if ($i == 36) break;?>                        
                    <?endforeach?>
                    <?if($i % 2 == 1):?></div><?endif?>
            </div>
        </div>
        </div>
    <?endif?>
    </div>
    <?
    /*
    далее мобильная версия...
    */
    ?>


    <div class="container hotturblock-mobile">
        <h1 class="hottur">Горящие туры из <?=$departure?></h1>
        <div class="hot-carusel owl-carousel">
            <? foreach ($resr as $key => $t) { ?>


                <? if ($key == 0 || $key % 2 == 0) { ?><div class="item"><? } ?>

                <div class="wraps ">
                    <?
                    $bgr = NULL;
                    $arSelect = Array();
                    $arFilter = Array("IBLOCK_ID"=>21, "NAME"=>$tours[$k][0]['PROPERTY_COUNTRY_VALUE']);
                    $result = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
                    while($obj = $result->GetNextElement()) {
                        $arrReq = $obj->GetFields();
                        if(isset($arrReq["PREVIEW_PICTURE"])&&!empty($arrReq["PREVIEW_PICTURE"])){
                            $bgr=CFile::GetPath($arrReq["PREVIEW_PICTURE"]);
                        }
                        else
                            $bgr=NULL;
                        break;
                    }

                    $countryBigger = false;
                    $groups = CIBlockElement::GetElementGroups($tours[$k][0]['ID'], true);
                    while($ar_group = $groups->Fetch())
                        $ar_new_groups = $ar_group["NAME"];


                    $ar_new_groups = explode('(', $ar_new_groups)[1];
                    $ar_new_groups = explode(')', $ar_new_groups)[0];


                    if($ar_new_groups == '') {
                        $countryBigger = true;
                    }

                    ?>
                    <div class="hotblocks" <? if ($bgr != NULL) { ?>style="background-image: url(<?= $bgr ?>) !important;" <?} ?>>
                        <div class="row">
                            <div class="col-xs-6 ">
                                <span class="tur_country"><?= $arProps[0]['COUNTRY']['VALUE'] ?></span>
                                <? if(!$countryBigger){?><span class="tur_city"><?=$ar_new_groups?></span><?}?>
                            </div>
                            <div class="col-xs-6 ">
                                <? if ($arProps[0]['DISCOUNT']['VALUE'] != NULL) { ?><span
                                    class="tur_discount">-<?= $arProps['DISCOUNT']['VALUE'] ?> %</span><? } ?>
                                <span class="tur_price"><?if(!empty($arProps['DISCOUNT']['VALUE'])){
                                        echo $arProps['MIN_PRICE']['VALUE']-(($arProps['MIN_PRICE']['VALUE']/100)*$arProps['DISCOUNT']['VALUE']);
                                    }
                                    else
                                        echo (int)($arProps['MIN_PRICE']['VALUE']/100)*100;
                                    ?> Р</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">

        						<span class="tur_date"><i
                                        class="fa fa-plane redish"></i> Дата вылета: <?= FormatDateFromDB($arProps[0]['DATEFROM']['VALUE'], "DD.MM") ?></span>
                                <span class="tur_night"><i
                                        class="calendar1"></i> Количество ночей: <?= $arProps[0]['DAYCOUNT']['VALUE']+1 ?></span>
                                <span class="tur_update"><i
                                        class="fa fa-refresh redish"></i> Обновлено: <?= FormatDateFromDB($arFieldz[0]['TIMESTAMP_X'], "HH:i") ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="hotdeals">
                        <? foreach ($arProps as $rw) { ?>
                            <div>
                                <span><?= date("d.m", strtotime($rw['DATEFROM']['VALUE'])) ?></span>
                                <span><?= $rw['DAYCOUNT']['VALUE']+1 ?> дней</span>
                                <span><?= round($rw['MIN_PRICE']['VALUE']/2,-2) ?> Р</span>
                            </div>
                        <? } ?>
                    </div>
                </div>
                <? if ($key - 1 % 2 == 0 && $key != 0) { ?></div><? } ?>
                <?
                if ($key == 7) exit;
            } ?>
        </div>
    </div>



<?endif?>
