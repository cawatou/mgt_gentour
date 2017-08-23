<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Наши сотрудники"); ?>
<style>
    body {
        background: url(/bitrix/templates/tour/images/slide7.jpg);
        background-attachment: fixed;
        background-size: cover !important;
    }

    .navbar {
        position: fixed !important;
    }

    .footer-all {
        background-color: #212121;
        padding-top: 50px;
        padding-bottom: 150px;
    }

    .employe h1 {
        margin-top: 100px;
        color: #fff;
        font-size: 30px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 25px;

    }

    .empblock {
        border-radius: 5px;
        background: rgba(255, 255, 255, .8);
        padding: 30px;
        text-align: center;
        margin-bottom: 50px;
    }

    .empblock .pic img {
        height: 150px;
    }

    .empblock .pic {
        border-radius: 50%;
        width: 150px;
        height: 150px;
        overflow: hidden;
        margin: 0 auto;
    }

    .empblock h3 {
        font-size: 20px;
        font-weight: bold;
    }

    .empblock span {
        color: #999;
        font-weight: bold;
        font-size: 11px;
    }

    .empblock .winner {
        font-size: 11px;
        padding-top: 30px;
        background: url(/bitrix/templates/tour/images/about_14.png) no-repeat center 15px;

    }

    `
    .empblock b {
        font-size: 16px;
        display: block;
    }

    .empblock p {
        font-size: 16px;
    }

    .empblock a {
        font-size: 16px;
        font-weight: bold;
        text-transform: uppercase;
        color: #fff;
        background: #db3636;
        display: inline-block;
        padding: 10px 20px;
        border-radius: 25px;
    }

    .empblock a:hover, .empblock a:focus {
        text-decoration: none;
        color: #fff;
        background: #cc6666;
    }

    /***** CSS Magic to Highlight Stars on Hover *****/

    .starq {
        float: left;
    }

    .starq:after {
        clear: both;
        content: " ";
        display: block;
    }

    .starq br {
        display: none;
    }

    .vote {
        height: 43px;
    }

    .starq > input {
        display: none;
    }

    .starq > label:before {
        margin: 5px;
        font-size: 2.2em;
        font-family: FontAwesome;
        display: inline-block;
        content: "\f005";
    }

    .starq > .half:before {
        content: "\f089";
        position: absolute;
    }

    .starq > label {
        color: #ddd;
        float: right;
        cursor: pointer;
    }

    .starq > input:checked ~ label, /* show gold star when clicked */
    .starq:not(:checked) > label:hover, /* hover current star */
    .starq:not(:checked) > label:hover ~ label {
        color: #ffc000;
    }

    /* hover previous stars in list */

    .starq > input:checked + label:hover, /* hover current star when changing rating */
    .starq > input:checked ~ label:hover,
    .starq > label:hover ~ input:checked ~ label, /* lighten current selection */
    .starq > input:checked ~ label:hover ~ label {
        color: #FFED85;
    }

    .modal-header, .modal-footer {
        border: none;
    }

    
</style>
<div class="container employe ourpeoples">
    <? if (isset($_REQUEST['ID'])) { ?>
        <div class="row ">
            <div class="col-md-12">
                <h1>Подробности о сотруднике</h1>
            </div>
        </div>
        <div class="row ">
            <? CModule::IncludeModule("iblock");
            $arSelect1 = Array("ID", "NAME", "IBLOCK_ID", "DETAIL_TEXT", "PREVIEW_PICTURE", "PROPERTY_*");
            $arFilter1 = Array("IBLOCK_ID" => 7, "ID" => IntVal($_REQUEST['ID']));
            $res1 = CIBlockElement::GetList(Array(), $arFilter1, false, Array("nPageSize" => 50), $arSelect1);

            $ob = $res1->GetNextElement();
            $arrFie = $ob->GetFields();
            $arrPro = $ob->GetProperties(); ?>
            <div class="col-md-8 col-md-offset-2 ">
                <div class="empblock">
                    <div class="pic"><img src="<?= CFile::GetPath($arrFie["PREVIEW_PICTURE"]) ?>"></div>
                    <h3><?= $arrFie['NAME'] ?></h3>
                    <span><?= $arrPro['POST']['VALUE'] ?></span>
                    <? if ($arrPro['winner']['VALUE_XML_ID'] == "5e8334d1984dd9c9791ad47abb05476f") { ?>
                        <div class="winner">Сотрудник месяца</div><? } ?>
                    <div>
                        <b>Офис:</b>
                        <p><? $arSelect1 = Array("ID", "NAME", "IBLOCK_ID", "PREVIEW_TEXT", "PREVIEW_PICTURE", "PROPERTY_*");
                            $arFilter1 = Array("IBLOCK_ID" => 16, "ID" => IntVal($arrPro['OFFICE']['VALUE']));
                            $res1 = CIBlockElement::GetList(Array(), $arFilter1, false, Array("nPageSize" => 50), $arSelect1);
                            while ($ob = $res1->GetNextElement()) {
                                $arrFiel = $ob->GetFields();
                                $arrProp = $ob->GetProperties();
                            }
                            if (isset($arrProp["office_address"]["VALUE"])) {
                                echo $arrProp["office_address"]["VALUE"];
                            } else {
                                echo 'офис не найден в базе :(';
                            } ?>
                        </p>
                    </div>
                    <div><b>Стаж работы:</b>
                        <p><?= $arrPro['staj']['VALUE'] ?><? if ($arrPro['staj']['VALUE'] == 1) echo "год";
                            if ($arrPro['staj']['VALUE'] > 1 && $arrPro['staj']['VALUE'] < 5) echo "года";
                            if ($arrPro['staj']['VALUE'] >= 5) echo "лет"; ?></p></div>
                    <div><b>О сотруднике</b>
                        <p><?= $arrFie['DETAIL_TEXT'] ?></p></div>
                    <div><b>Образование:</b>
                        <p><?= $arrPro['school']['VALUE'] ?></p></div>
                    <div><b>Посещенные страны</b>
                        <p><?= $arrPro['country']['VALUE'] ?></p></div>
                    <a href="#" class="voteemploy" data-id="<?= $arrFie['ID'] ?>" data-imya="<?= $arrFie['NAME'] ?>"
                       data-toggle="modal" data-target="#employe">Оценить работу сотрудника</a>
                </div>
            </div>
        </div>
    <? } else { ?>
        <div class="row ">
            <div class="col-md-12">
                <h1>Все сотрудники</h1>
            </div>
        </div>
        <div class="row ">
            <div class="col-md-12">
                <div class="row owl-carousel workers">
                    <? GLOBAL $emplFilter, $officeForCity;
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
                            "NEWS_COUNT" => "5",
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
        </div>
    <? } ?>
</div>
<div class="container-fluid footer-all">
    <? $APPLICATION->IncludeFile(
        $APPLICATION->GetTemplatePath("footer_inc.php"),
        Array(),
        Array("MODE" => "html")
    ); ?>
</div>
<div class="modal fade" id="employe" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content employe">
            <? $APPLICATION->IncludeComponent("bitrix:form.result.new", "vote", Array(
                    "SEF_MODE" => "N",
                    "WEB_FORM_ID" => 7,
                    "EDIT_URL" => "",
                    "CHAIN_ITEM_TEXT" => "",
                    "CHAIN_ITEM_LINK" => "",
                    "IGNORE_CUSTOM_TEMPLATE" => "Y",
                    "SUCCESS_URL" => "",
                    "USE_EXTENDED_ERRORS" => "Y",
                    "CACHE_TYPE" => "A",
                    "CACHE_TIME" => "3600",
                    "VARIABLE_ALIASES" => Array("RESULT_ID" => "RESULT_ID", "WEB_FORM_ID" => "WEB_FORM_ID")
                )
            ); ?>
        </div>
    </div>
</div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>