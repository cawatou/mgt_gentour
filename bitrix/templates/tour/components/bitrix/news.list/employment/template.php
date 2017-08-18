<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?foreach($arResult["ITEMS"] as $arItem):
	$vote = $arItem['PROPERTIES']['vote']['VALUE'];
	$vote_count = $arItem['PROPERTIES']['vote_count']['VALUE'];
	$grade = $vote / $vote_count;?>
	<div class="item">
		<div class="<?if($arItem["PROPERTIES"]['winner']['VALUE_XML_ID']=="5e8334d1984dd9c9791ad47abb05476f"){?>winnerinner<?}?> area">
			<div class="imgbox2" style="background-image:url(<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>);background-size:cover;">
				<img src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>">
			</div>
			<h3><?echo $arItem["NAME"]?></h3>
			<span><?echo $arItem["PROPERTIES"]["POST"]["VALUE"]?></span>
			<?if($arItem["PROPERTIES"]['winner']['VALUE_XML_ID']=="5e8334d1984dd9c9791ad47abb05476f"):?> 
				<div class="winner">Сотрудник месяца</div>
			<?endif?>
			<p><?=($grade > 0) ? "Оценка = ".$grade : ""?></p>
			<div class="awrap">
				<a href="/content/employe/<?echo $arItem["ID"]?>/">Оценить работу сотрудника</a>
			</div>
		</div>
	</div>
<?endforeach;?>
