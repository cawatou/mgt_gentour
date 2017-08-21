<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Статьи");

if (isset($_REQUEST['ID'])) {?>
    <style>
        body {
            background: url(/bitrix/templates/tour/images/bgr/articles_bgr.jpg);
            background-size: cover;
            background-attachment: fixed;
        }

        .navbar {
            position: fixed !important;
        }

        .footer-all {
            background-color: #212121;
            padding-top: 50px;
            padding-bottom: 150px;
        }

        /************/
        .last-art {
            margin-top: 140px;
        }

        .last-art div {
            text-align: center;
        }

        .last-art h1 {
            font-size: 30px;
            color: #fff;
            font-weight: bold;
            margin-bottom: 40px;
        }

        .last-art i {
            border-radius: 50%;
            background: #eae4e0;
            color: #db3636;
            width: 38px;
            height: 38px;
            line-height: 38px;
        }

        .art-box .pic {
            overflow: hidden;
            height: 200px;
            border-radius: 5px 5px 0 0;
        }

        .contents {
            padding: 30px;
        }

        .contents h3 {
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 10px;
            line-height: 25px;
        }

        .contents span {
            font-size: 10px;
            color: #666;
            font-weight: bold;
            display: block;
            padding: 0 0 10px 0;
        }

        .contents p {
            font-size: 13px;
            color: #333;
            text-align: justify;
        }

        .contents a:hover {
            cursor: pointer;
            color: #fff;
            background-color: #db3636;
            text-decoration: none;
        }

        .contents a {
            border: 1px solid #b3b3b3;
            border-radius: 15px;
            color: #db3636;
            text-transform: uppercase;
            font-size: 10px;
            font-weight: bold;
            padding: 9px 24px;
            display: inline-block;
        }

        .art-box {
            background-color: #fff;
            border-radius: 5px;
        }

        .art-row {
            margin-bottom: 30px;
        }

        .lastart-carousel {
            margin-bottom: 30px;
        }

        /***********************************************************************/
        .full-news h3 {
            font-size: 30px;
            font-weight: bold;
            margin: 0 0 25px 0;
            line-height: 40px;
        }

        .full-news .date_create {
            font-size: 14px;
            font-weight: bold;
            color: #7e7e7e;
            display: block;
            margin-bottom: 30px;
        }

        .full-news p {
            font-size: 18px;
            line-height: 31px;
        }

        .full-news {
            background: rgba(255, 255, 255, .8);
            border-radius: 5px;
            padding: 40px;
            color: #333;
        }

        .detail_picture {
            float: left;
            padding: 20px 20px 20px 0
        }

        .art-detail {
            margin-top: 140px;
        }

        /*******************************************for mobile ******************************************/
        @media all and (max-width: 1000px) {
            .art-detail {
                margin-top: 60px;
            }

            .art-box {
                margin-bottom: 10px;
            }

            .full-news {
                padding: 20px;
            }

            .full-news h3 {
                font-size: 20px;
                margin: 0 0 25px 0;
                line-height: 25px;
            }

            .full-news p {
                font-size: 12px;
                line-height: 20px;
            }

            .owl-next {
                right: 0;
            }

            .owl-prev {
                left: 0;
            }
        }
    </style>
    <div class="container art-detail">
        <? $APPLICATION->IncludeComponent("bitrix:news.detail", "tour", Array(
                "DISPLAY_DATE" => "Y",
                "DISPLAY_NAME" => "Y",
                "DISPLAY_PICTURE" => "Y",
                "DISPLAY_PREVIEW_TEXT" => "Y",
                "USE_SHARE" => "Y",
                "SHARE_HIDE" => "N",
                "SHARE_TEMPLATE" => "",
                "SHARE_HANDLERS" => array("delicious"),
                "SHARE_SHORTEN_URL_LOGIN" => "",
                "SHARE_SHORTEN_URL_KEY" => "",
                "AJAX_MODE" => "Y",
                "IBLOCK_TYPE" => "articles",
                "IBLOCK_ID" => "2",
                "ELEMENT_ID" => $_REQUEST["ID"],
                "ELEMENT_CODE" => "",
                "CHECK_DATES" => "Y",
                "FIELD_CODE" => Array("ID"),
                "PROPERTY_CODE" => Array("DESCRIPTION"),
                "IBLOCK_URL" => "/content/articles/",
                "DETAIL_URL" => "",
                "SET_TITLE" => "Y",
                "SET_CANONICAL_URL" => "Y",
                "SET_BROWSER_TITLE" => "Y",
                "BROWSER_TITLE" => "-",
                "SET_META_KEYWORDS" => "Y",
                "META_KEYWORDS" => "-",
                "SET_META_DESCRIPTION" => "Y",
                "META_DESCRIPTION" => "-",
                "SET_STATUS_404" => "Y",
                "SET_LAST_MODIFIED" => "Y",
                "INCLUDE_IBLOCK_INTO_CHAIN" => "Y",
                "ADD_SECTIONS_CHAIN" => "Y",
                "ADD_ELEMENT_CHAIN" => "N",
                "ACTIVE_DATE_FORMAT" => "d.m.Y",
                "USE_PERMISSIONS" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "3600",
                "CACHE_GROUPS" => "Y",
                "DISPLAY_TOP_PAGER" => "Y",
                "DISPLAY_BOTTOM_PAGER" => "Y",
                "PAGER_TITLE" => "Страница",
                "PAGER_TEMPLATE" => "",
                "PAGER_SHOW_ALL" => "Y",
                "PAGER_BASE_LINK_ENABLE" => "Y",
                "SHOW_404" => "Y",
                "MESSAGE_404" => "",
                "STRICT_SECTION_CHECK" => "Y",
                "PAGER_BASE_LINK" => "",
                "PAGER_PARAMS_NAME" => "arrPager",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "AJAX_OPTION_HISTORY" => "N"
            )
        ); ?>

        <div class="row last-art">
            <div class="col-md-12">
                <i class="fa fa-newspaper-o"></i>
                <h1>Последние статьи</h1>
            </div>
        </div><?
        GLOBAL $filterArt;
        $filterArt = array(
            "PROPERTY_TOPNEWS_VALUE" => "Нет"
        ); ?>
        <div class="lastart-carousel owl-carousel">
            <? $APPLICATION->IncludeComponent("bitrix:news.list", "tnewslast", Array(
                    "IBLOCK_TYPE" => "articles",
                    "IBLOCK_ID" => "2",
                    "NEWS_COUNT" => "6",
                    "SORT_BY1" => "ACTIVE_FROM",
                    "SORT_ORDER1" => "DESC",
                    "SORT_BY2" => "SORT",
                    "SORT_ORDER2" => "ASC",
                    "FILTER_NAME" => "filterArt",
                    "FIELD_CODE" => array(),
                    "PROPERTY_CODE" => array(),
                    "DETAIL_URL" => "/content/articles/#ELEMENT_ID#/",
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
                    "PAGER_TITLE" => "Статьи",
                    "PAGER_SHOW_ALWAYS" => "Y",
                    "PAGER_TEMPLATE" => "tour",
                    "PAGER_DESC_NUMBERING" => "N",
                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                    "PAGER_SHOW_ALL" => "Y",
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
    <div class="modal fade " id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" data-dismiss="modal">
            <div class="modal-content">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal"><span
                            aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <img src="" class="imagepreview" style="width: 100%;">
                </div>
            </div>
        </div>
    </div>
<?}else{?>
    <style>
        body {
            background: url(/bitrix/templates/tour/images/bgr/article_bgr.jpg);
            background-attachment: fixed;
        }

        .navbar {
            position: fixed !important;
        }

        .footer-all {
            background-color: #212121;
            padding-top: 50px;
            padding-bottom: 150px;
        }

        /************/
        .last-art {
            margin-top: 140px;
        }

        .last-art div {
            text-align: center;
        }

        .last-art h1 {
            font-size: 30px;
            color: #fff;
            font-weight: bold;
            margin-bottom: 40px;
        }

        .last-art i {
            border-radius: 50%;
            background: #eae4e0;
            color: #db3636;
            width: 38px;
            height: 38px;
            line-height: 38px;
        }

        .art-box .pic {
            overflow: hidden;
            height: 200px;
            border-radius: 5px 5px 0 0;
        }

        .contents {
            padding: 30px;
        }

        .contents h3 {
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 10px;
            line-height: 25px;
        }

        .contents span {
            font-size: 10px;
            color: #666;
            font-weight: bold;
            display: block;
            padding: 0 0 10px 0;
        }

        .contents p {
            font-size: 13px;
            color: #333;
            text-align: justify;
        }

        .contents a:hover {
            cursor: pointer;
            color: #fff;
            background-color: #db3636;
            text-decoration: none;
        }

        .contents a {
            border: 1px solid #b3b3b3;
            border-radius: 15px;
            color: #db3636;
            text-transform: uppercase;
            font-size: 10px;
            font-weight: bold;
            padding: 9px 24px;
            display: inline-block;
        }

        .art-box {
            background-color: #fff;
            border-radius: 5px;
        }

        .art-row {
            margin-bottom: 30px;
        }

        /****************************navigator ********************/
        .navi-bottom {
            margin-bottom: 40px;
            text-align: center;
        }

        .navg:hover {
            color: #323232;
        }

        .navg {
            color: #323232;
            font-size: 14px;
            background: #fff;
            border-radius: 3px;
            display: inline-block;
            width: 35px;
            height: 35px;
            text-align: center;
            line-height: 35px;
            margin: 0 10px;
        }

        .navg.active {
            color: #fff;
            background: #db3636;
        }

        .chev i {
            color: #db3636;
        }

        .chev:first-child {
            margin-right: 30px;
        }

        .chev:last-child {
            margin-left: 30px;
        }

        .chev:hover {
            color: #fff;
        }

        .chev {
            font-size: 11px;
            font-weight: bold;
            color: #fff;
            text-transform: uppercase;
            display: inline-block;
        }

        .navi-bottom {
            margin-bottom: 40px;
            text-align: center;
        }

        /*******************************************for mobile ******************************************/
        @media all and (max-width: 1000px) {
            .art-box {
                margin-bottom: 10px;
            }
        }
    </style>
    <div class="container testimonalcontainer">
        <div class="row last-art">
            <div class="col-md-12">
                <i class="fa fa-newspaper-o"></i>
                <h1>Отзывы о нас</h1>
            </div>
        </div>
        <div class="row">
            <a href="#" class="reviewlink" data-toggle="modal" data-target="#addrewiev">оставить отзыв</a>
        </div>
        <?
        GLOBAL $filterArt;
        $filterArt = array(
            "PROPERTY_TOPNEWS_VALUE" => "Нет"
        ); ?>
        <? $APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "testimonalpage",
            array(
                "IBLOCK_TYPE" => "services",
                "IBLOCK_ID" => "12",
                "NEWS_COUNT" => "9",
                "SORT_BY1" => "ACTIVE_FROM",
                "SORT_ORDER1" => "DESC",
                "SORT_BY2" => "",
                "SORT_ORDER2" => "",
                "FILTER_NAME" => "filterArt",
                "FIELD_CODE" => array(
                    0 => "",
                    1 => "",
                ),
                "PROPERTY_CODE" => array(
                    0 => "",
                    1 => "",
                ),
                "DETAIL_URL" => "/content/articles/#ELEMENT_ID#/",
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
                "DISPLAY_BOTTOM_PAGER" => "Y",
                "PAGER_TITLE" => "Статьи",
                "PAGER_SHOW_ALWAYS" => "Y",
                "PAGER_TEMPLATE" => "tour",
                "PAGER_DESC_NUMBERING" => "N",
                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                "PAGER_SHOW_ALL" => "Y",
                "DISPLAY_DATE" => "Y",
                "DISPLAY_NAME" => "Y",
                "DISPLAY_PICTURE" => "Y",
                "DISPLAY_PREVIEW_TEXT" => "Y",
                "DETAIL_FIELD_CODE" => array(
                    0 => "SHOW_COUNTER",
                    1 => "",
                ),
                "COMPONENT_TEMPLATE" => "testimonalpage",
                "CHECK_DATES" => "Y",
                "AJAX_MODE" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "AJAX_OPTION_HISTORY" => "N",
                "AJAX_OPTION_ADDITIONAL" => "",
                "CACHE_GROUPS" => "Y",
                "SET_BROWSER_TITLE" => "Y",
                "SET_META_KEYWORDS" => "Y",
                "SET_META_DESCRIPTION" => "Y",
                "SET_LAST_MODIFIED" => "N",
                "PARENT_SECTION_CODE" => "",
                "INCLUDE_SUBSECTIONS" => "Y",
                "PAGER_BASE_LINK_ENABLE" => "N",
                "SET_STATUS_404" => "N",
                "SHOW_404" => "N",
                "MESSAGE_404" => ""
            ),
            false
        ); ?>


    </div>
<?}?>
    <div class="container-fluid footer-all">
        <? $APPLICATION->IncludeFile(
            $APPLICATION->GetTemplatePath("footer_inc.php"),
            Array(),
            Array("MODE" => "html")
        ); ?>
    </div>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>