<?require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->RestartBuffer();

CModule::IncludeModule("iblock");
CModule::IncludeModule("form");
CModule::IncludeModule("main");

if (isset($_REQUEST['city'])) {?>
    <div class="container ourpeoples">
        <div class="row">
            <div class="col-md-10 col-xs-12 tt">
                <div class="row owl-carousel workers">
                    <?$officeForCity = $_REQUEST['city'];
                    $PROPoffice = "";
                    $arSelect1 = Array("ID", "NAME", "IBLOCK_ID");
                    $arFilter1 = Array("SECTION_ID" => IntVal($officeForCity), "IBLOCK_ID" => 16);

                    $res1 = CIBlockElement::GetList(Array(), $arFilter1, false, Array("nPageSize" => 50), $arSelect1);

                    while ($ob = $res1->GetNextElement()) {
                        $arFields2 = $ob->GetFields();
                        $PROPoffice .= "" . $arFields2['ID'] . " | ";
                    }

                    $PROPoffice = substr($PROPoffice, 0, -3);

                    $emplFilter = array(
                        "?PROPERTY_OFFICE" => $PROPoffice,
                    );
                    $APPLICATION->IncludeComponent("bitrix:news.list", "employment", Array(
                            "IBLOCK_TYPE" => "books",
                            "IBLOCK_ID" => "7",
                            "NEWS_COUNT" => "50",
                            "SORT_BY1" => "PROPERTY_winner",
                            "SORT_ORDER1" => "ASC",
                            "SORT_BY2" => "NAME",
                            "SORT_ORDER2" => "ASC",
                            "FILTER_NAME" => "emplFilter",
                            "FIELD_CODE" => array(),
                            "PROPERTY_CODE" => array(
                                0 => "NAME",
                                1 => "",
                            ),
                            "DETAIL_URL" => "/content/employe/#ELEMENT_ID#/",
                            "CACHE_TYPE" => "A",
                            "CACHE_TIME" => "3600",
                            "CACHE_FILTER" => "N",
                            "PREVIEW_TRUNCATE_LEN" => "0",
                            "ACTIVE_DATE_FORMAT" => "j M Y",
                            "DISPLAY_PANEL" => "N",
                            "SET_TITLE" => "N",
                            "INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
                            "ADD_SECTIONS_CHAIN" => "Y",
                            "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                            "PARENT_SECTION" => "",
                            "DISPLAY_TOP_PAGER" => "N",
                            "DISPLAY_BOTTOM_PAGER" => "N",
                            "PAGER_TITLE" => "Попутчики",
                            "PAGER_SHOW_ALWAYS" => "N",
                            "PAGER_TEMPLATE" => "",
                            "PAGER_DESC_NUMBERING" => "N",
                            "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                            "PAGER_SHOW_ALL" => "N",
                            "DISPLAY_DATE" => "Y",
                            "DISPLAY_NAME" => "Y",
                            "DISPLAY_PICTURE" => "Y",
                            "DISPLAY_PREVIEW_TEXT" => "Y",
                            "DETAIL_FIELD_CODE" => array(
                                0 => "SHOW_COUNTER",
                                1 => "",
                            )
                        )
                    ); ?>
                </div>
            </div>
            <div class="magic">
                <div class="addme">
                    <?
                    $TVID = $_REQUEST['tvcity'];
                    $theNameOfTheTown = '';
                    $tempArr = array();
                    $resultTempArr = array();

                    $arSelect = Array("ID", "NAME", "PREVIEW_TEXT", "IBLOCK_ID", "PROPERTY_*");
                    $arFilter = Array("IBLOCK_ID" => 24, "PROPERTY_city_id" => $TVID);
                    $res = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter, false, Array("nPageSize" => 1), $arSelect);

                    if ($ob = $res->GetNextElement()) {
                        $arFields = $ob->GetFields();
                        $arProps = $ob->GetProperties();
                        $theNameOfTheTown = $arFields['NAME'];
                        $temp = $arProps['siblings']['VALUE'];

                        foreach ($temp as $key => $val) {
                            $arSelect4 = Array("ID", "NAME");
                            $arFilter4 = Array("IBLOCK_ID" => 24, "ID" => $val);
                            $res4 = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter4, false, Array("nPageSize" => 1), $arSelect4);

                            if ($ob4 = $res4->GetNextElement()) {
                                $arFields4 = $ob4->GetFields();
                                $tempArr[] = $arFields4['NAME'];
                            }
                        }
                        foreach ($tempArr as $key => $val) {
                            $arSelect5 = Array("ID", "NAME");
                            $arFilter5 = Array("IBLOCK_ID" => 16, "NAME" => $val);
                            $res5 = CIBlockSection::GetList(Array('SORT' => 'ASC'), $arFilter5, false, Array("nPageSize" => 1), $arSelect5);

                            if ($ob5 = $res5->GetNextElement()) {
                                $arFields5 = $ob5->GetFields();

                                $resultTempArr[] = $arFields5['ID'];
                            }
                        }
                    }

                    foreach ($resultTempArr as $val) {
                        $officeForCity .= ' | ' . $val;
                    }

                    $arSelect = Array("ID", "NAME", "PREVIEW_TEXT", "IBLOCK_ID", "PROPERTY_*");
                    $arFilter = Array("IBLOCK_ID" => 11, "PROPERTY_city_id" => $TVID);

                    $res1 = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter, false, Array("nPageSize" => 1), $arSelect);

                    while ($ob = $res1->GetNextElement()) {
                        $arFields2 = $ob->GetFields();
                        // print_r($arFields2);
                        $arProps = $ob->GetProperties();
                    }

                    $arSelect1 = Array("ID", "NAME", "PREVIEW_TEXT", "IBLOCK_ID", "PROPERTY_*");
                    $arFilter1 = Array("IBLOCK_ID" => 11, "PROPERTY_CITY" => $officeForCity);
                    $res1 = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter1, false, Array("nPageSize" => 1), $arSelect1);

                    $arr = array();
                    $i = 0;
                    while ($ob = $res1->GetNextElement()) {
                        $arFields2 = $ob->GetFields();
                        // print_r($arFields2);
                        $arProps = $ob->GetProperties();?>
                        <h3><?= $arFields2['NAME'] ?><!--  (<?= $theNameOfTheTown ?>) --></h3>
                        <p><?= $arFields2['PREVIEW_TEXT'] ?></p>
                        <div class="awrap"><a href="/content/job/">Хочу стать сотрудником</a></div>
                    <?}?>
                </div>
            </div>
        </div>
    </div>
    <a href="/content/employe/" class="reviewlink">все сотрудники</a>
<?}?>