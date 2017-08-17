<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->RestartBuffer();

//if(isset($_REQUEST['direction'])&&!empty($_REQUEST['direction'])){
	$you = $_REQUEST['sex'];
	if($you=="m") $you='Мужчину'; else $you='Женщину';
	$find = $_REQUEST['isex'];
	if($find=="m") $find = 'Мужской'; else $find='Женский';
	CModule::IncludeModule("iblock");
	 $k=0;
	$arSelect = Array();
	$arFilter = Array("IBLOCK_ID"=>17, "PROPERTY_COMCOUNTRY"=>$_REQUEST['direction'],"PROPERTY_com_sex_VALUE"=>$find, "PROPERTY_com_who_VALUE"=>$you, "ACTIVE"=>"Y");
	$res = CIBlockElement::GetList(Array("NAME"=>"ASC"), $arFilter, false,Array("nPageSize"=>10000),$arSelect);
	while($ob = $res->GetNextElement())
	{
		$arProps = $ob->GetProperties();
		$arFields = $ob->GetFields();
		
		$brg = CFile::GetPath($arFields["PREVIEW_PICTURE"]);
		if(empty($brg)) {
			$zx=($arProps["com_sex"]["VALUE"]=='Женский')?"5":"3";
			$brg="/bitrix/templates/tour/images/man-n-girl_0".$zx.".png";
	
		}
?>
		<? if($k==0||$k%3==0){?><div class="row"><?}?><? $k++; ?>


		<?
		if($arFields['SHOW_COUNTER'] == ''){
			$c = 0;
		}
		else {
			$c = $arFields['SHOW_COUNTER'];
		}

		?>
		<div class="col-md-4 col-xs-12">
			<div class="find-box" data-id="<?=$arFields['ID']?>">
				<div style="text-align:center;">
					<p><img src="<?=$brg?>" class="ava"></p>
					<p><i class="fa fa-eye"></i></p>
					<p><span class="views"><?=$c?>  просмотров</span></p>
				</div>
				<ul class="oglavl">
					<li>
						<span class="text">Имя</span>
						<span class="page"><?=$c?></span>
					</li>
					<li>
						<span class="text">Телефон</span>
						<span class="page"><?=$arProps["com_phone"]["VALUE"]?></span>
					</li>
					<li>
						<span class="text">Почта</span>
						<span class="page"><?=$arProps["com_mail"]["VALUE"]?></span>
					</li>
					<li>
						<span class="text">Пол</span>
						<span class="page"><?=$arProps["com_sex"]["VALUE"]?></span>
					</li>
					<li>
						<span class="text">Город вылета</span>
						<span class="page"><?=$arProps["com_city"]["VALUE"]?></span>
					</li>
					<li>
						<span class="text">Ближайшие города</span>
						<span class="page"><?=$arProps["com_citys"]["VALUE"]?></span>
					</li>
					<li>
						<span class="text">Интересующийся пол</span>
						<span class="page"><?=$arProps["com_who"]["VALUE"]?></span>
					</li>
					<li>
						<span class="text">Возраст</span>
						<span class="page"><?=$arProps["com_age"]["VALUE"]?></span>
					</li>
					<li>
						<span class="text">Страна</span>
						<span class="page"><?=$arProps["COMCOUNTRY"]["VALUE"]?></span>
					</li>
					<li>
						<span class="text">Дата вылета</span>
						<span class="page"><?=$arProps["com_flydate"]["VALUE"]?></span>
					</li>
					<li>
						<span class="text">Дней отдыха</span>
						<span class="page"><?=$arProps["com_day"]["VALUE"]?> дней</span>
					</li>
				</ul>
				<h3>Информация о себе:</h3>
				<p><?=$arFields["PREVIEW_TEXT"]?></p>
				<a href="#" class="more-companion"   >подробнее</a>
				<div class="row">
					<div class="col-md-6 col-xs-4">
						<p class="social">
							<?if(count($arProps["com_links"]["VALUE"])!=0){
								foreach($arProps["com_links"]["VALUE"] as $l){
									
									if(substr_count($l,"vk.com")) echo '<a class="fa fa-vk " href="'.$l.'"></a>';
									if(substr_count($l,"fb.com")) echo '<a class="fa fa-facebook " href="'.$l.'"></a>';
									
								}
							}?>
							
						</p>
					</div>
					<div class="col-md-6 col-xs-8">
						<p class="actualy">
							<b>Поиск актуален до:<br><span ><?=$arProps["com_actualy"]["VALUE"]?></span></b>
						</p>
					</div>
				</div>
			</div>
		</div>
<?if($k%3==0){?></div><?}
	}
	
	
//}
?>