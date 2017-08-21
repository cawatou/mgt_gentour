<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Франшиза");?>
<style>
	body {
		background:url(/bitrix/templates/tour/images/bgr/franchize_bg.jpg);
		background-attachment:fixed;
		background-size:cover;
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
	.link-room {
		background-color:#212121;
		line-height:70px;
		color:#fff;
	}
	.franchblog {
		margin-top:100px;
	}
	.franchblog h1{
		margin:40px 0;
		color:#fff;
		font-size:30px;
		text-align:center;
		font-weight:bold;
	}
	.franchorder {
		border:10px solid #db3636;
		border-radius:10px;
		background:rgba(255,255,255,.85);
		padding:30px;
	}
	.franchorder h2 {
		font-size:30px;
		font-weight:bold;
		margin-top:0;
	}
	.franchorder label {
		font-size:13px;
		color:#999;
	}
	.franchorder a {
		font-size:13px;
		font-weight:bold;
		background:#db3636;
		display:block;
		border-radius:25px;
		line-height:44px;
		text-transform:uppercase;
		color:#fff;
		text-align:center;
	}
	.nagrada-carousel  {
		background:rgba(255,255,255,.85);
		border-radius:5px;
		padding:40px 60px;
	}
	.nagrada-carousel h4 {
		font-size:20px;
		font-weight:bold;
	}
	.nagrada-carousel p {
		font-size:16px;
		line-height:27px;
	}
	.hideover {
		display:none;
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
		background: rgba(0,0,0,.8);
		color: #db3636;
		text-align: center;
		line-height: 153px;
		font-size: 3em;
		padding-top: 50%;
	}
	.pictur {
		width:107px;
		height:153px;
		overflow:hidden;
		position:relative;
		float:left;
		margin: 5px 4px 5px 0;
	}
	.pictur img{

		height:100%;
	}
	.advant {
		text-align:center;
		color:#fff;
	}
	.advant .circle{
		text-align:center;
		display:block;
		width:125px;
		height:125px;
		line-height:125px;
		background:#fff;
		border-radius:50%;
		margin:0 auto 30px;
	}
	.video h3 {
		font-size:20px;
		font-weight:bold;
		color:#fff;
		text-align:center;
	}
	.video {
		border-radius:5px;
		margin-top:30px;
		margin-bottom:30px;
	}

	.box h4 span{
		color:#db3636;
	}
	.box h4{
		font-size:24px;
		font-weight:bold;
		line-height:35px;
		text-align:left;
		margin:0;
	}
	.box {
		width:50%;
		float:left;
		background:#fff;
		padding:30px 0 30px 30px;
		border-radius: 0 5px 5px 0;
	}
	.box.calc {
		background:rgba(255,255,255,.8);
		padding-left:40px;
		padding-right:40px;
		border-radius:5px 0 0 5px;
	}
	.box.calc h4{
		font-size:16px;
		font-weight:bold;

	}
	.box.calc label{
		font-size:13px;
		color:#999;
		font-weight:normal;
	}
	canvas {
		margin: 40px 0 15px 0;
	}
	.buble {
		background: #fff;
		border-radius: 50%;
		width: 145px;
		color: #db3636;
		font-size: 20px;
		line-height: 145px;
		text-align: center;    
		display: inline-block;
	}
	.finan {
		font-size:20px;
		color:#fff;
		text-align: center;
		margin:0 0 80px 0;
	}
	.link-room a.what{
		color:#fff;
		border-bottom:2px solid #db3636;
		font-weight:bold;
		font-size:16px;
		text-transform:uppercase;
		margin-left: 20px;
	}
	.link-room a.linkmenu{
		color:#fff;
		font-size:20px;
		font-weight:bold;
		border-radius:25px;
		border:1px solid #fff;
		padding: 5px 15px;

	}
	.whyme {
		background:rgba(255,255,255,.8);
		border-radius:5px;
		padding:45px;
		margin:40px 0;
	}
	.whyme h4 {
		font-size:30px;
		font-weight:bold;
		margin-top:0;
	}
	.whyme p {
		font-size:20px;
		line-height:34px;
	}
	.calcs {
		margin-bottom:80px;
	}
</style>
<? // --------Получаем слайдер--------

$arSelect = Array("ID", "NAME", "PROPERTY_*", "IBLOCK_ID", "PREVIEW_TEXT");
$arFilter = array(
	"IBLOCK_ID" => 31

	);
$sliderObj = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);

// --------Получаем преимущества--------

$arSelect = Array("ID", "NAME", "PREVIEW_PICTURE", "PREVIEW_TEXT");
$arFilter = array(
	"IBLOCK_ID" => 32

	);
$advantagesObj = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>4), $arSelect);

// --------Получаем видео для франшизы--------

$arSelect = Array("ID", "NAME", "PROPERTY_*", "IBLOCK_ID");
$arFilter = array(
	"IBLOCK_ID" => 33
	);
$videoObj = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>3), $arSelect);

// --------Получаем финансовые показатели--------

$arSelect = Array("ID", "NAME", "PROPERTY_*", "IBLOCK_ID");
$arFilter = array(
	"IBLOCK_ID" => 34

	);
$financialObj = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>4), $arSelect);

// --------Получаем контент для блока "Почему мы"--------

$arSelect = Array("ID", "NAME", "PREVIEW_PICTURE", "PREVIEW_TEXT");
$arFilter = array(
	"IBLOCK_ID" => 35

	);
$whyWeObj = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>1), $arSelect);
?>

<div class="container franchblog">
	<div class="row ">
		<div class="col-md-12">
			<h1>Франшиза</h1>
		</div>
	</div>
	<div class="row row-flex row-flex-wrap">
		<div class="col-md-9">
			<div class="nagrada-carousel owl-carousel">
				<?while($ob = $sliderObj->GetNextElement()){ 
					$arFields = $ob->GetFields();  
					$arProps = $ob->GetProperties();
					$arFile = array();
					foreach($arProps['PICS']['VALUE'] as $file_id) {
						$arFile[] = CFile::GetFileArray($file_id);
						$arFile['SRC'];
					}?>
					<div class="item">
						<div class="row ">
							<div class="col-md-8">
								<h4><?=$arFields['NAME']?></h4>
								<p><?=$arFields['PREVIEW_TEXT']?></p>
							</div>
							<div class="col-md-4">
								<?foreach($arFile as $file_src){?>
									<div class="pictur">
										<img src="<?=$file_src['SRC']?>" />
										<div class="hideover">
											<i class="fa fa-search"></i>
										</div>
									</div>
									<?}?>
								</div>
							</div>
						</div>
					<?}?>
				</div>
			</div>
			<div class="col-md-3">
				<div class="franchorder">
					<h2>Заявка</h2>
					<form class="franchise">
						<div class="form-group ">
							<label for="iname">Имя</label>
							<input id="iname" class="form-control" name="name"  />
						</div>
						<div class="form-group ">
							<label for="phone">Телефон</label>
							<input id="phone" class="form-control" name="phone" placeholder="+7(___)___-__-__" />
						</div>
						<button type="submit" class=" btnformmodalred">отправить заявку</button>
					</form>
				</div>
			</div>
		</div>
		<div class="row ">
			<div class="col-md-12">
				<h1>Наши приемущества</h1>
			</div>
		</div>
		<div class="row advant">
			<?while($ob = $advantagesObj->GetNextElement()){ 
				$arFields = $ob->GetFields();  
				$arProps = $ob->GetProperties();?>
				<div class="col-md-3">
					<div class="circle"> 
						&nbsp;<img src="<?=CFile::GetPath($arFields["PREVIEW_PICTURE"])?>"> 
					</div>
					<b><?=$arFields['NAME']?></b>
					<p><?=$arFields['PREVIEW_TEXT']?></p>
				</div>
			<?}?>
		</div>
		<link rel="stylesheet" href="/video/plyr.css">
		<div class="row video">
			<?while($ob = $videoObj->GetNextElement()){ 
				$arFields = $ob->GetFields();  
				$arProps = $ob->GetProperties();?>
				<div class="col-md-4">
					<h3 class="rew"><?=$arFields['NAME']?></h3>
					<video controls="controls" width="100%" poster="<?=CFile::GetPath($arProps['PREVIEW_IMAGE_FOR_VIDEO']['VALUE'])?>" crossorigin>
						<source src="<?=CFile::GetPath($arProps['VIDEOMP4']['VALUE'])?>" type='video/mp4;'>
						<source src="<?=CFile::GetPath($arProps['VIDEOWEBM']['VALUE'])?>" type='video/webm;'>
						<source src="<?=CFile::GetPath($arProps['VIDEOOGV']['VALUE'])?>" type='video/ogv;'>
							Видео не поддерживается вашим браузером.
						</video>
					</div>
				<? }?>
				</div>
				<div class="row ">
					<div class=" col-md-12" >
						<h1>Калькулятор франшизы</h1>
					</div>
				</div>
				<div class="row calcs">
					<div class=" col-md-12" >
						<div id="calculation_franchize" class="box calc">
							<div class="form-group ">
								<label for="citizens_amount">Население города</label>
								<select id="citizens_amount" class="form-control"  >
									<option value="0">0-10 тыс.</option>
									<option value="1">11 - 30 тыс</option>
									<option value="2">31 - 50 тыс</option>
									<option value="3">51 - 100 тыс</option>
									<option value="4">101 - 300 тыс</option>
									<option value="5">301 - 500 тыс</option>
									<option value="6">501 тыс - 1 млн</option>
									<option value="7">1-3 млн</option>
									<option value="8">3 млн и более</option>
								</select>
							</div>
							<!-- <div class="form-group ">
								<label for="cntcity">Название города</label>
								<input id="cntcity" class="form-control"  />
							</div> -->
							<h4>Объем продаж туров за последние 3 месяца</h4>
							<div class="form-group ">
								<label for="fmonth">Первый месяц</label>
								<input id="fmonth" class="form-control" placeholder="Количество (шт.)" />
							</div>
							<div class="form-group ">
								<label for="nmonth">Второй месяц</label>
								<input id="nmonth" class="form-control"  placeholder="Количество (шт.)" />
							</div>
							<div class="form-group ">
								<label for="lmonth">Прошлый месяц</label>
								<input id="lmonth" class="form-control" placeholder="Количество (шт.)"  />
							</div>
							<div class="form-group ">
								<input id="submitchart" type="submit" class="btn btn-danger" value="Рассчитать на графике"  />
							</div>
						</div>
						<div class="box">
							<h4>Объем ваших продаж под брендом<br> <span>&laquo;Мой Горящий Тур&raquo;</span></h4>

							<canvas id="chart" width="300"></canvas>
						</div>
					</div>
				</div>
				<div class="row ">
					<div class=" col-md-12" >
						<h1>Финансовые показатели</h1>
					</div>
				</div>
				<div class="row finan">	
				<?while($ob = $financialObj->GetNextElement()){ 
				$arFields = $ob->GetFields();  
				$arProps = $ob->GetProperties();?>
					<div class=" col-md-3" >
						<div class="buble">
							<b><?=$arProps['VALUE_IN_CIRCLE']['VALUE']?></b>
						</div>
						<span><?=$arFields['NAME']?></span>
					</div>	
				<? }?>
				</div>
				<div class="row whyme">
					<div class="col-md-12">
						<div class="row ">
						<?while($ob = $whyWeObj->GetNextElement()){ 
						$arFields = $ob->GetFields();  
						$arProps = $ob->GetProperties();?>
							<div class="col-md-7">
								<h4><?=$arFields['NAME']?></h4>
								<p><?=$arFields['PREVIEW_TEXT']?></p>
							</div>
							<div class="col-md-5">
								<img src="<?=CFile::GetPath($arFields['PREVIEW_PICTURE'])?>" width="100%"/>
							</div>
						<? }?>
						</div>
					</div>
				</div>
			</div>
			<div class="container-fluid link-room" >
				<div class="container">
					<div class="row ">
						<div class="col-md-12">
							<a href="skype:franchise_154?call" class="linkmenu">позвонить по Skype</a>
							<a href="#" class="linkmenu"  data-toggle="modal" data-target="#question_franchise">Задать вопрос</a>
							<a href="#" class="linkmenu" data-toggle="modal" data-target="#franchform">Заявка на презентацию франшизы онлайн</a>
							<a href="#" class="what">Что такое франшиза?</a>
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade" id="franchform" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<? $APPLICATION->IncludeComponent("bitrix:form.result.new", "question", Array(
							"SEF_MODE" => "N",
							"WEB_FORM_ID" => 10,
							"EDIT_URL" => "",
							"CHAIN_ITEM_TEXT" => "",
							"CHAIN_ITEM_LINK" => "",
							"IGNORE_CUSTOM_TEMPLATE" => "Y",
							"SUCCESS_URL" => "",
							"COMPONENT_TEMPLATE" => "question",
							"LIST_URL" => "result.php",
							"USE_EXTENDED_ERRORS" => "Y",
							"CACHE_TYPE" => "A",
							"CACHE_TIME" => "3600",
							"VARIABLE_ALIASES" => Array("RESULT_ID" => "RESULT_ID", "WEB_FORM_ID" => "WEB_FORM_ID")
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