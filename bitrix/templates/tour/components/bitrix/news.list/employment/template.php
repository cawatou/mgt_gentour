<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?foreach($arResult["ITEMS"] as $arItem):?>
<div class="item fr">
	<div class="<?if($arItem["PROPERTIES"]['winner']['VALUE_XML_ID']=="5e8334d1984dd9c9791ad47abb05476f"){?>winnerinner<?}?> area">
		<div class="imgbox2" style="background-image:url(<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>);background-size:cover;"><img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>"></div>
		<h3><?echo $arItem["NAME"]?></h3>
		<span><?echo $arItem["PROPERTIES"]["POST"]["VALUE"]?></span>
		<?if($arItem["PROPERTIES"]['winner']['VALUE_XML_ID']=="5e8334d1984dd9c9791ad47abb05476f"){?> <div class="winner">Сотрудник месяца</div><?}?>
		<div class="awrap"><a href="/content/employe/<?echo $arItem["ID"]?>/">Оценить работу сотрудника</a></div>
	</div>
</div>
<?endforeach;?>
