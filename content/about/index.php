<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("О компании");
?>
<style>
body {
	background:url(/bitrix/templates/tour/images/bgr/about_bgr.jpg);
	background-attachment:fixed;
}
.navbar {
	position:fixed !important;
	background:rgba(0,0,0,0.5);
	z-index:2 !important;
}

.footer-all {
	background-color:#212121;padding-top: 50px;
    padding-bottom: 150px;
}
.about-head {
	margin-top:160px;
}
.boss  {
	padding:57px;
	background:rgba(255,255,255,.8);
	border-radius:5px;
}
.boss img {
	float:right;
}
.boss p {
	font-size:16px;
	line-height:27px;
	margin-bottom:20px;
}
.boss b {
	font-size:16px;
	line-height:27px;
	display:block;
}
.boss span {
	font-size:13px;
	font-weight:bold;
	color:#999;
}
.about-box {
	color:#fff;
	text-align:center;
	margin-top:95px;
}
.about-box h2,.advant-box h2,.awards-box h2,.employe-box h2{
	font-size:30px;
	font-weight:bold;
	margin:0 0 40px 0;
	
}
.about-box p{
	font-size:16px;
	max-width:520px;
	line-height:27px;
	
	margin: 0 auto 40px;
}
.about-box a{
	color:#fff;
	font-size:16px;
	background:#db3636;
	border-radius:25px;
	padding:10px 20px;
	display:inline-block;
}
.advant-box{
	margin-top:90px;
	color:#fff;
	text-align:center;
}
.advant-box .im{
	background:#fff;
	border-radius:50%;
	width:122px;
	height:122px;
	display:inline-block;
	line-height:122px;
}
.advant-box span{
	display:block;
	font-size:16px;
	font-weight:bold;
	margin-top:40px;
	margin-bottom:10px;
}
.advant-box p{
	font-size:16px;
	line-height:27px;
}
.awards {
	background:rgba(255,255,255,.8);
	border-radius:5px;
	padding:40px;
	
}
.awards-box, .employe-box {
	margin-top:90px;
}
.awards-box h2, .employe-box h2{
	color:#fff;
	text-align:center;
}
.awards .pic img{
	width: 100%;
}
.awards .pic{
	margin:0 29px;
	float:left;
	position:relative;
	text-align: center;
	cursor:pointer;
	width: 153px;
    height: 218px;
    overflow: hidden;
}
.over:before{
	color:#db3636;
}
.over {
	display:none;
	background: rgba(0,0,0,.6) url() no-repeat;
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    line-height: 218px;
}
.work-usr {
	background:rgba(255,255,255,.8);
	border-radius:5px;
	padding:20px;
	text-align:center;
	position:relative;
}
.work-usr img {
	width:85px;
	border-radius:50%;
}
.work-usr h3 {
	font-size:13px;
	font-weight:bold;
}
.work-usr span {
	color:#666;
	font-size:10px;
	font-weight:bold;
	text-transform:uppercase;
}
.work-usr .grant {
	padding-top:30px;
	background:url(/bitrix/templates/tour/images/about_14.png) no-repeat center 15px;
	font-size:11px;
}
.work-usr a:hover {
	text-decoration:none;
}
.work-usr a {
	color:#db3636;
	font-size:10px;
	font-weight:bold;
	border:1px solid #db3636;
	padding:10px 33px;
	border-radius:25px;
	display: inline-block;
	margin-top: 35px;
}
.work-overload {
	position:absolute;
	top:0;
	left:0;
	width:100%;
	height:100%;
	background:rgba(0,0,0,.6);
	text-align:center;
	display:none;
}
.work-overload a {
	color:#fff;
	font-size:10px;
	font-weight:bold;
	background:#db3636;
	border-radius:25px;
	text-transform:uppercase;
	padding: 10px 29px;
	display:inline-block;
	margin-top: 36%;
}
.work-box {
	margin-bottom:30px;
}
.employe-box {
	margin-bottom:125px;
}
.advant-box .im img {
	max-width: 100%;

}
/*******************************************for mobile ******************************************/
@media all and (max-width:1000px){
	.about-head {
		margin-top: 60px;
	}
	.boss {
		padding: 0;
		background: none;
		border-radius: 5px;
	}
	.boss-carousel .col-md-6{
		text-align:center;
	}
	.boss-carousel {
		padding: 15px;
		background: rgba(255,255,255,.8);
		border-radius: 5px;
	}
	.boss p {
		font-size: 9px;
		line-height: 16px;
		margin-bottom: 10px;
	}
	.boss b {
		font-size: 12px;
		line-height: 20px;
	}
	.boss span {
		font-size: 10px;
	}
	.boss-carousel img{
		width: 200px;
		    float: none;
			display: inline !important;
	}
	.about-box h2, .advant-box h2, .awards-box h2, .employe-box h2 {
		font-size: 20px;
		margin: 0 0 20px 0;
	}
	.about-box p {
		font-size: 10px;
		line-height: 19px;
		margin: 0 auto 20px;
	}
	.about-box a {
		font-size: 12px;
		padding: 7px 20px;
	}
	.advant-box {
		margin-top: 50px;
	}
	.preim-carousel img, .work-box img {
		display: inline !important;
	}
	.advant-box .im {
		width: 52px;
		height: 52px;
		line-height: 52px;
	}
	.advant-box span {
		font-size: 12px;
		margin-top: 30px;
	}
	.advant-box p {
		font-size: 10px;
		line-height: 16px;
	}
	.awards-box, .employe-box {
		margin-top: 50px;
	}
	.owl-prev {
		left: 0;
	}
	.owl-next {
		right: 0;
	}
	
	 .owl-carousel{
		 margin: 0 auto;
	 }   
}
</style>
<div class="container about-head">
	<div class="row boss">
		<div class="col-md-12">
			
			<div class="boss-carousel owl-carousel">
			<?$APPLICATION->IncludeComponent("bitrix:news.list", "about", Array(
					"IBLOCK_TYPE"	=>	"services",
					"IBLOCK_ID"	=>	"18",
					"NEWS_COUNT"	=>	"5",
					"SORT_BY1"	=>	"ACTIVE_FROM",
					"SORT_ORDER1"	=>	"DESC",
					"SORT_BY2"	=>	"SORT",
					"SORT_ORDER2"	=>	"ASC",
					"FILTER_NAME"	=>	"filter",
					"FIELD_CODE"	=>	array(
					),
					"PROPERTY_CODE"	=>	array(
						0	=>	"POST",
					),
					"DETAIL_URL"	=>	"/content/about/#ELEMENT_ID#/",
					"CACHE_TYPE"	=>	"A",
					"CACHE_TIME"	=>	"3600",
					"CACHE_FILTER"	=>	"N",
					"PREVIEW_TRUNCATE_LEN"	=>	"0",
					"ACTIVE_DATE_FORMAT"	=>	"j M Y",
					"DISPLAY_PANEL"	=>	"N",
					"SET_TITLE"	=>	"N",
					"INCLUDE_IBLOCK_INTO_CHAIN"	=>	"Y",
					"ADD_SECTIONS_CHAIN"	=>	"Y",
					"HIDE_LINK_WHEN_NO_DETAIL"	=>	"N",
					"PARENT_SECTION"	=>	"",
					"DISPLAY_TOP_PAGER"	=>	"N",
					"DISPLAY_BOTTOM_PAGER"	=>	"Y",
					"PAGER_TITLE"	=>	"Попутчики",
					"PAGER_SHOW_ALWAYS"	=>	"N",
					"PAGER_TEMPLATE"	=>	"tour",
					"PAGER_DESC_NUMBERING"	=>	"N",
					"PAGER_DESC_NUMBERING_CACHE_TIME"	=>	"36000",
					"PAGER_SHOW_ALL" => "Y",
					"DISPLAY_DATE"	=>	"Y",
					"DISPLAY_NAME"	=>	"Y",
					"DISPLAY_PICTURE"	=>	"Y",
					"DISPLAY_PREVIEW_TEXT"	=>	"Y",
					"DETAIL_FIELD_CODE" => array(
						  0 => "SHOW_COUNTER",
						  1 => "",
					   )
					)
					);?>
				
				
			</div>
		</div>
	</div>
	<div class="row about-box">
		<div class="col-md-12">
			<h2><?echo CIBlock::GetArrayByID(40, "NAME");?></h2>
			<p><?echo CIBlock::GetArrayByID(40, "DESCRIPTION");?></p>
			<a href="/search/">воспользоваться поиском</a>
		</div>
	</div>
	<div class="row advant-box">
		<div class="col-md-12">
			<h2>Наши приемущества</h2>
			<div class="row preim-carousel ">
				<?$APPLICATION->IncludeComponent("bitrix:news.list", "advantages", Array(
					"IBLOCK_TYPE"	=>	"services",
					"IBLOCK_ID"	=>	"38",
					"NEWS_COUNT"	=>	"4",
					"SORT_BY1"	=>	"ACTIVE_FROM",
					"SORT_ORDER1"	=>	"DESC",
					"SORT_BY2"	=>	"SORT",
					"SORT_ORDER2"	=>	"ASC",
					"FILTER_NAME"	=>	"filter",
					"FIELD_CODE"	=>	array(
					),
					"PROPERTY_CODE"	=>	array(
						0	=>	"name_advant",
						1	=>	"descr_advant",
					),
					"DETAIL_URL"	=>	"/content/about/#ELEMENT_ID#/",
					"CACHE_TYPE"	=>	"A",
					"CACHE_TIME"	=>	"3600",
					"CACHE_FILTER"	=>	"N",
					"PREVIEW_TRUNCATE_LEN"	=>	"0",
					"ACTIVE_DATE_FORMAT"	=>	"j M Y",
					"DISPLAY_PANEL"	=>	"N",
					"SET_TITLE"	=>	"N",
					"INCLUDE_IBLOCK_INTO_CHAIN"	=>	"Y",
					"ADD_SECTIONS_CHAIN"	=>	"Y",
					"HIDE_LINK_WHEN_NO_DETAIL"	=>	"N",
					"PARENT_SECTION"	=>	"",
					"DISPLAY_TOP_PAGER"	=>	"N",
					"DISPLAY_BOTTOM_PAGER"	=>	"N",
					"PAGER_TITLE"	=>	"Попутчики",
					"PAGER_SHOW_ALWAYS"	=>	"N",
					"PAGER_TEMPLATE"	=>	"tour",
					"PAGER_DESC_NUMBERING"	=>	"N",
					"PAGER_DESC_NUMBERING_CACHE_TIME"	=>	"36000",
					"PAGER_SHOW_ALL" => "Y",
					"DISPLAY_DATE"	=>	"Y",
					"DISPLAY_NAME"	=>	"Y",
					"DISPLAY_PICTURE"	=>	"Y",
					"DISPLAY_PREVIEW_TEXT"	=>	"Y",
					"DETAIL_FIELD_CODE" => array(
						  0 => "SHOW_COUNTER",
						  1 => "",
					   )
					)
					);?>
				
				
				
			</div>
		</div>
	</div>
	<div class="row awards-box">
		<div class="col-md-12">
			<h2>Награды</h2>
			<div class="row awards">
				<div class="col-md-12 award-carousel">
					<?$APPLICATION->IncludeComponent("bitrix:news.list", "honors", Array(
					"IBLOCK_TYPE"	=>	"services",
					"IBLOCK_ID"	=>	"19",
					"SECTION_ID"	=>	"",
					"NEWS_COUNT"	=>	"5",
					"SORT_BY1"	=>	"ACTIVE_FROM",
					"SORT_ORDER1"	=>	"DESC",
					"SORT_BY2"	=>	"SORT",
					"SORT_ORDER2"	=>	"ASC",
					"FILTER_NAME"	=>	"filter",
					"FIELD_CODE"	=>	array(
					),
					"PROPERTY_CODE"	=>	array(
						0	=>	"REAL_PICTURE",
					),
					"DETAIL_URL"	=>	"/content/honors/#ELEMENT_ID#/",
					"CACHE_TYPE"	=>	"A",
					"CACHE_TIME"	=>	"3600",
					"CACHE_FILTER"	=>	"N",
					"PREVIEW_TRUNCATE_LEN"	=>	"0",
					"ACTIVE_DATE_FORMAT"	=>	"j M Y",
					"DISPLAY_PANEL"	=>	"N",
					"SET_TITLE"	=>	"N",
					"INCLUDE_IBLOCK_INTO_CHAIN"	=>	"Y",
					"ADD_SECTIONS_CHAIN"	=>	"Y",
					"HIDE_LINK_WHEN_NO_DETAIL"	=>	"N",
					"PARENT_SECTION"	=>	"",
					"DISPLAY_TOP_PAGER"	=>	"N",
					"DISPLAY_BOTTOM_PAGER"	=>	"N",
					"PAGER_TITLE"	=>	"Попутчики",
					"PAGER_SHOW_ALWAYS"	=>	"N",
					"PAGER_TEMPLATE"	=>	"tour",
					"PAGER_DESC_NUMBERING"	=>	"N",
					"PAGER_DESC_NUMBERING_CACHE_TIME"	=>	"36000",
					"PAGER_SHOW_ALL" => "Y",
					"DISPLAY_DATE"	=>	"Y",
					"DISPLAY_NAME"	=>	"Y",
					"DISPLAY_PICTURE"	=>	"Y",
					"DISPLAY_PREVIEW_TEXT"	=>	"Y",
					"DETAIL_FIELD_CODE" => array(
						  0 => "SHOW_COUNTER",
						  1 => "",
					   )
					)
					);?>
					
					
					
				</div>
			</div>
		</div>
	</div>
	<div class="row employe-box">
		<div class="col-md-12">
			<h2>Наши сотрудники</h2>
			<?
			
			
			GLOBAL $emplFilter,$officeForCity;
						$PROPoffice = "";
						$arSelect1 = Array("ID","NAME","IBLOCK_ID");
		$arFilter1 = Array("SECTION_ID"=>IntVal($officeForCity), "IBLOCK_ID" => 16);
		$res1 = CIBlockElement::GetList(Array(), $arFilter1, false, Array("nPageSize"=>50), $arSelect1);
		while($ob = $res1->GetNextElement()){
			 $arFields2 = $ob->GetFields();
			 $PROPoffice .= $arFields2['ID']." | ";
		}
		
		$PROPoffice = substr($PROPoffice, 0, -3);
		
						$emplFilter = array(
							"PROPERTY_OFFICE" => $PROPoffice,
						);
			?>
			
			<?$APPLICATION->IncludeComponent("bitrix:news.list", "employe", Array(
					"IBLOCK_TYPE"	=>	"books",
					"IBLOCK_ID"	=>	"7",
					"NEWS_COUNT"	=>	"9",
					"SORT_BY1"	=>	"PROPERTY_winner",
					"SORT_ORDER1"	=>	"ASC",
					"SORT_BY2"	=>	"NAME",
					"SORT_ORDER2"	=>	"ASC",
					"FILTER_NAME"	=>	"emplFilter",
					"FIELD_CODE"	=>	array(
					),
					"PROPERTY_CODE"	=>	array(
						0	=>	"POST",
						1	=>	"winner",
					),
					"DETAIL_URL"	=>	"/content/employe/#ELEMENT_ID#/",
					"CACHE_TYPE"	=>	"A",
					"CACHE_TIME"	=>	"3600",
					"CACHE_FILTER"	=>	"N",
					"PREVIEW_TRUNCATE_LEN"	=>	"0",
					"ACTIVE_DATE_FORMAT"	=>	"j M Y",
					"DISPLAY_PANEL"	=>	"N",
					"SET_TITLE"	=>	"N",
					"INCLUDE_IBLOCK_INTO_CHAIN"	=>	"Y",
					"ADD_SECTIONS_CHAIN"	=>	"Y",
					"HIDE_LINK_WHEN_NO_DETAIL"	=>	"N",
					"PARENT_SECTION"	=>	"",
					"DISPLAY_TOP_PAGER"	=>	"N",
					"DISPLAY_BOTTOM_PAGER"	=>	"N",
					"PAGER_TITLE"	=>	"Попутчики",
					"PAGER_SHOW_ALWAYS"	=>	"N",
					"PAGER_TEMPLATE"	=>	"tour",
					"PAGER_DESC_NUMBERING"	=>	"N",
					"PAGER_DESC_NUMBERING_CACHE_TIME"	=>	"36000",
					"PAGER_SHOW_ALL" => "Y",
					"DISPLAY_DATE"	=>	"Y",
					"DISPLAY_NAME"	=>	"Y",
					"DISPLAY_PICTURE"	=>	"Y",
					"DISPLAY_PREVIEW_TEXT"	=>	"Y",
					"DETAIL_FIELD_CODE" => array(
						  0 => "SHOW_COUNTER",
						  1 => "",
					   )
					)
					);?>
			
				
			
		</div>
	</div>
</div>
<div class="container-fluid footer-all" >
<?$APPLICATION->IncludeFile(
			$APPLICATION->GetTemplatePath("footer_inc.php"),
			Array(),
			Array("MODE"=>"html")
		);?>

</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>