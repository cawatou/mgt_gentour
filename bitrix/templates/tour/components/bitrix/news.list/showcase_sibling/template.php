<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
CModule::IncludeModule("iblock");
$arSelect = Array("ID", "NAME", "PROPERTY_siblings", "PROPERTY_city_id");
$arFilter = Array("IBLOCK_ID" => 24, "ACTIVE" => "Y", "NAME" => $_REQUEST['city_name']);
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize" => 9999), $arSelect);
while ($ob = $res->GetNextElement()) {
	$city = $ob->GetFields();
	$citys[] = $city;
	$siblings = $city['PROPERTY_SIBLINGS_VALUE'];
}

// Получаем массив id соседних городов
foreach($siblings as $sibling){
	$db_props = CIBlockElement::GetProperty(24, $sibling, Array(), Array("CODE"=>"city_id"));
	if($ar_props = $db_props->Fetch()){
		$tour_cityid[] = $ar_props['VALUE'];
	}
}

foreach($tour_cityid as $cityid){
	$rsSections = CIBlockSection::GetList(
		array("UF_MIN_PRICE" => "ASC"),
		array('IBLOCK_ID' => 20, "UF_DEPARTURE" => $cityid, "ACTIVE" => "Y"),
		false,
		array("ID", "NAME", "UF_DEPARTURE", "UF_MIN_PRICE"),
		array("nPageSize" => 8)
		);
	while ($arSection = $rsSections->Fetch()) {
		$sections[$arSection['ID']] = $arSection;
		$arSelect = Array("ID", "NAME", "PROPERTY_DATEFROM", "PROPERTY_COUNTRY", "PROPERTY_DEPARTURE", "PROPERTY_CURORT", "PROPERTY_DAYCOUNT", "PROPERTY_MIN_PRICE","TIMESTAMP_X");
		$arFilter = Array("IBLOCK_ID" => 20, "SECTION_ID" => $arSection['ID'], "ACTIVE" => "Y");
		$res = CIBlockElement::GetList(Array("PROPERTY_MIN_PRICE" => "ASC"), $arFilter, false, Array("nPageSize" => 2), $arSelect);
		while ($ob = $res->GetNextElement()) {
			$tour = $ob->GetFields();
			$tours[$arSection['ID']][] = $tour;
		}
	}
}
if($_REQUEST['dev']) echo "<pre>".print_r($sections, 1)."</pre>";
file_put_contents($_SERVER['DOCUMENT_ROOT'].'/tours.txt', print_r($tours, 1));
file_put_contents($_SERVER['DOCUMENT_ROOT'].'/sections.txt', print_r($sections, 1));
$i=0;
if(count($tours) > 0):?>
<h1 class="hottur">Горящие туры из соседних городов</h1>
<div class="container hotturblock gridviewz">
	<div class="row r25">
	<?$exeptionArrGridOne = array();
	foreach ($tours as $k => $items):
		$i++;
		if(!in_array($items[0]['PROPERTY_DEPARTURE_VALUE'], $exeptionArrGridOne)){
			$exeptionArrGridOne[] = $items[0]['PROPERTY_DEPARTURE_VALUE'];
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

			$exeptionArr = array();
			$arSelect = Array("ID","NAME", 'UF_DEPARTURE');
			$arFilter = Array("IBLOCK_ID"=>20);
			$res = CIBlockSection::GetList(array("UF_MIN_PRICE" => "ASC"), $arFilter, false,$arSelect);
			while($ob = $res->GetNextElement()){
				$arFields = $ob->GetFields();
				if(explode(' - ', $arFields['NAME'])[0] == $items[0]['PROPERTY_DEPARTURE_VALUE']){
					$id_home = $arFields['UF_DEPARTURE'];
					break;
				}
			}?>		
			<div class="col-md-3 i<?=$i?>" >
				<div class="hotblocks"  <? if ($bgr != NULL) { ?>style="background-image: url(<?= $bgr ?>) !important;" <?} ?>>
					<div class="row">
						<div class="col-md-6 ">
							<span class="tur_country"><?=$items[0]['PROPERTY_COUNTRY_VALUE']?></span>
							<span class="tur_city"><?=$items[0]['PROPERTY_CURORT_VALUE']?></span>
						</div>
						<div class="col-md-6 ">
							<!--span class="tur_discount">-25%</span-->
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<span class="tur_price"><?=round($items[0]['PROPERTY_MIN_PRICE_VALUE']/2,-2)?> Р</span>
							<span class="tur_start">из <?=$items[0]['PROPERTY_DEPARTURE_VALUE']?></span>							
							<span class="tur_date"><i class="fa fa-plane redish"></i> Дата вылета: <?=FormatDateFromDB($items[0]['PROPERTY_DATEFROM_VALUE'], "DD.MM")?></span>
							<span class="tur_night"><i class="calendar1"></i> Количество дней: <?=$items[0]['PROPERTY_DAYCOUNT_VALUE']+1?></span>
							<span class="tur_update"><i class="fa fa-refresh redish"></i> Обновлено: <?= FormatDateFromDB($items[0]['TIMESTAMP_X'], "HH:i") ?></span>
						</div>
					</div>
				</div>
				<div class="hotdeals" data-id="<?=$id_home?>" data-url = "/content/tours/?id=<?=$arFields['ID'];?>">

					<?$arSelect = Array();
					$arFilter = array(
						"IBLOCK_ID" => 20,
						"INCLUDE_SUBSECTIONS" => "Y",
						"PROPERTY_DEPARTURE" => $items[0]['PROPERTY_DEPARTURE_VALUE']
					);
					$resourse = CIBlockElement::GetList(Array("PROPERTY_MIN_PRICE" => "ASC"), $arFilter, false, Array("nPageSize"=>10000), $arSelect);
					while($fields = $resourse->GetNextElement()):
						$arFields = $fields->GetFields();
						$arProps = $fields->GetProperties();
						if($_REQUEST['dev']) echo "<pre>".print_r($arProps, 1)."</pre>";
						if(!in_array($arProps['COUNTRY']['VALUE'], $exeptionArr)):
							$exeptionArr[] = $arProps['COUNTRY']['VALUE'];?>
							<div>
								<span><?= $arProps['COUNTRY']['VALUE']?></span>
								<span><?=$arProps['DAYCOUNT']['VALUE']+1?> дней</span>
								<span class="siblings-price-forthat">от <?=round($arProps['MIN_PRICE']['VALUE']/2,-2)?> Р</span>
							</div>
							<?if(count($exeptionArr) == 8) break;
						endif;	
					endwhile?>
				</div>
			</div>
			<?if($_REQUEST['tabletOrientedCarousel'] && $i === 8){
				$i = 0;
				break;
			}
			else if($i === 8) {
				$i = 0;
				break;
			}?>
		<?}
		endforeach?>
	</div>
</div>
<div class="container hotturblock listviewz">
	<?$i=0; 
	$exeptionArrGridTwo = array();
	foreach ($tours as $k => $items): ?>
	<?$i++;
	if(!in_array($items[0]['PROPERTY_DEPARTURE_VALUE'], $exeptionArrGridTwo)){
		$exeptionArrGridTwo[] = $items[0]['PROPERTY_DEPARTURE_VALUE'];
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
		$arSelect = Array();
		$arFilter = array(
			"IBLOCK_ID" => 20,
			"INCLUDE_SUBSECTIONS" => "Y",
			"PROPERTY_DEPARTURE" => $items[0]['PROPERTY_DEPARTURE_VALUE']
		);
		$resourse = CIBlockElement::GetList(Array("PROPERTY_MIN_PRICE" => "ASC"), $arFilter, false, Array("nPageSize"=>10000), $arSelect);

		$exeptionArr = array();
		$arSelect = Array("ID","NAME", 'UF_DEPARTURE');
		$arFilter = Array("IBLOCK_ID"=>20);
		$res = CIBlockSection::GetList(array("UF_MIN_PRICE" => "ASC"), $arFilter, false,$arSelect);
		while($ob = $res->GetNextElement()){
			$arFields = $ob->GetFields();
			if(explode(' - ', $arFields['NAME'])[0] == $items[0]['PROPERTY_DEPARTURE_VALUE']){
				$id_home = $arFields['UF_DEPARTURE'];
				break;
			}
		}?>
		<div class="deal">
			<div class="hotlistview">
				<div class="row">
					<div class="col-md-2 ">
						<div class="pic"><img src="<? if ($bgr != NULL) { ?><?= $bgr ?> <?}else{ ?>/bitrix/templates/tour/images/bgrtour_03.png<? } ?>" alt=""/></div>
					</div>
					<div class="list-wrapper" data-id="<?=$id_home?>">
						<div class="hotlisted">
							<div class="col-md-3 ">
									<?= $items[0]['PROPERTY_DEPARTURE_VALUE'] ?><!-- ,
									<b><?= $items[0]['PROPERTY_CURORT_VALUE'] ?></b> -->
								</div>
								<div class="col-md-3 ">
									<i class="fa fa-plane redish"></i><b> Дата
									вылета: <?= date('d.m',strtotime($items[0]['PROPERTY_DATEFROM_VALUE'])) ?></b>
								</div>
								<div class="col-md-3 ">
									<i class="calendar2"></i><b> Количество
									дней: <?= $items[0]['PROPERTY_DAYCOUNT_VALUE']+1 ?></b>
								</div>
								<div class="col-md-3 hotrefresh ">
									<i class="fa fa-refresh redish"></i>
									Обновлено: <?= FormatDateFromDB($items[0]['TIMESTAMP_X'], "HH:i") ?>
								</div>
							</div>
							<div class="hotlistover">
								<div class="col-md-8 ">
									<?while($fields = $resourse->GetNextElement()):
									$arFields = $fields->GetFields();
									$arProps = $fields->GetProperties();
									if(!in_array($arProps['COUNTRY']['VALUE'], $exeptionArr)) {
										$exeptionArr[] = $arProps['COUNTRY']['VALUE'];?>										
										<p>
											<b><?= $arProps['COUNTRY']['VALUE']?></b>
											<?= $arProps['PROPERTY_DAYCOUNT_VALUE']+1 ?> дней
											<i>от <?=round($arProps['MIN_PRICE']['VALUE']/2,-2)?> Р</i>
										</p>
										<?
										if(count($exeptionArr) == 8) break;
									}
									endwhile?>

									<? foreach ($items as $tour): ?>
								<? endforeach ?>
							</div>
						</div>
					</div>
					<div class="col-md-2 hotprice">
						<span class="price"><? echo round($items[0]['PROPERTY_MIN_PRICE_VALUE']/2,-2);
							?> Р</span>
							<?
							if(!empty($items[0]['PROPERTY_MIN_PRICE_VALUE'])&&!empty($items[0]['PROPERTY_PRICEOLD_VALUE'])){
								$proc=round(100 - (($items[0]['PROPERTY_MIN_PRICE_VALUE']/$items[0]['PROPERTY_PRICEOLD_VALUE'])*100));
								if($proc>2){ ?><span class="tur_discount">-<?=$proc ?> %</span><?}
							}
						?>
					</div>
				</div>
			</div>
		</div>
		<?if($i == 8) break;?>
	<?}
	endforeach?>
</div>
<div class="container hotturblock-mobile" >
	<div class="hot-carusel owl-carousel">
		<?$wrappercounter = 0;
		$exeptionArrGridThree = array();
		foreach ($tours as $k => $items):?>
		<?if(!in_array($items[0]['PROPERTY_DEPARTURE_VALUE'], $exeptionArrGridThree)){
			$exeptionArrGridThree[] = $items[0]['PROPERTY_DEPARTURE_VALUE'];
				if($wrappercounter) { ?>
				<div <?=$_REQUEST['tabletOrientedCarousel']?> class="wraps " >
					<div class="hotblocks">
						<div class="row">
							<div class="col-xs-6 ">
								<span class="tur_country"><?=$items[0]['PROPERTY_COUNTRY_VALUE']?></span>
								<span class="tur_city"><?=$items[0]['PROPERTY_CURORT_VALUE']?></span>
							</div>
							<span style="margin-left: 0 !important; margin-right: 10px" class="tur_discount">-25%</span>
							<div style="clear: both" class="col-xs-6 ">
								<span class="tur_price"><?=$items[0]['PROPERTY_MIN_PRICE_VALUE']?> Р</span>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12">
								<span class="tur_date"><i class="fa fa-plane redish"></i> Дата вылета: <?=$items[0]['PROPERTY_DATEFROM_VALUE']?></span>
								<span class="tur_night"><i class="calendar1"></i> Количество дней: <?=$items[0]['PROPERTY_DAYCOUNT_VALUE']+1?></span>
								<span class="tur_update"><i class="fa fa-refresh redish"></i> Обновлено: <?=date('d.m.Y');?></span>
							</div>
						</div>
					</div>
					<div class="hotdeals" data-url="/content/tours/?id=<?=$k?>">
						<?foreach($items as $tour):?>
						<div>
							<span><?=date('d.m',strtotime($tour['PROPERTY_DATEFROM_VALUE']))?></span>
							<span><?=$tour['PROPERTY_DAYCOUNT_VALUE']+1?></span>
							<span><?=round($tour['PROPERTY_MIN_PRICE_VALUE']/2,-2)?> Р</span>
						</div>
						<?endforeach?>
					</div>
				</div>
			</div>
			<?	
			$wrappercounter--;
		}		
		else { ?>
		<div class="item">
			<div class="wraps " >
				<div class="hotblocks">
					<div class="row">
						<div class="col-xs-6 ">
							<span class="tur_country"><?=$items[0]['PROPERTY_COUNTRY_VALUE']?></span>
							<span class="tur_city"><?=$items[0]['PROPERTY_CURORT_VALUE']?></span>
						</div>
						<span style="margin-left: 0 !important; margin-right: 10px" class="tur_discount">-25%</span>
						<div style="clear: both" class="col-xs-6 ">
							<span class="tur_price"><?=$items[0]['PROPERTY_MIN_PRICE_VALUE']?> Р</span>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<span class="tur_date"><i class="fa fa-plane redish"></i> Дата вылета: <?=$items[0]['PROPERTY_DATEFROM_VALUE']?></span>
							<span class="tur_night"><i class="calendar1"></i> Количество дней: <?=$items[0]['PROPERTY_DAYCOUNT_VALUE']+1?></span>
							<span class="tur_update"><i class="fa fa-refresh redish"></i> Обновлено: <?=date('d.m.Y');?></span>
						</div>
					</div>
				</div>
				<div class="hotdeals" data-url="/content/tours/?id=<?=$k?>">
					<?foreach($items as $tour):?>
					<div>
						<span><?=date('d.m',strtotime($tour['PROPERTY_DATEFROM_VALUE']))?></span>
						<span><?=$tour['PROPERTY_DAYCOUNT_VALUE']+1?></span>
						<span><?=round($tour['PROPERTY_MIN_PRICE_VALUE']/2,-2)?> Р</span>
					</div>
					<?endforeach?>
				</div>
			</div>
			<?$wrappercounter++; }?>		
		<?}
	endforeach?>
	</div>
</div>
<?else: return 0;
endif;?>