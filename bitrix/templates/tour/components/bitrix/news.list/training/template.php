<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="blockbgr tacher">
	<h2><?=$arResult['NAME']?></h2>
	<div class="img"><?=CFile::ShowImage($arResult['SECTION']['PATH'][0]['PICTURE'], 0, 0, "", "", false)?></div>
	<h3><?=$arResult['SECTION']['PATH'][0]['NAME']?></h3>
	<p><?=$arResult['SECTION']['PATH'][0]['DESCRIPTION']?></p>
	<hr>
	<div class="teach-carusel  owl-carousel">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			<div class="blockwork" onclick='window.location="/content/teach/#pro<?=$arItem['CODE']?>"' style="background:url('<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>')">
			<h4><?=$arItem["NAME"]?></h4>
			<p><?=$arItem["PREVIEW_TEXT"]?></p>
		</div>
		<?endforeach;?>
	</div>
	<?//if($_REQUEST['dev']) echo "<pre>".print_r($arResult["ITEMS"], 1)."</pre>";?>
</div>

