<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
?>
<style>
body {
	background:url(/bitrix/templates/tour/images/bgr/hotel_bg.jpg);
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

.overflow {
	background:rgba(255,255,255,.8);
	border-radius:5px;
	padding:40px;
}
.hotel_star {
	color:#ffc000;
	display: inline-block;
    margin: 2px 0;
	font-size:14px;
	    margin-left: 20px;
}
.hotelinfos {
	margin-top:160px;
	
}
.hotelinfos h1{
	font-size:20px;
	font-weight:bold;
	text-transform:uppercase;
	margin: 0 0 30px 0;
}
.img {
	border-radius:5px;
	overflow:hidden;
	height:257px;
	min-height: 257px;
}
.img img{
	width:100%;
	
}
.mini {
	opacity: .6;
    width: 22%;
    overflow: hidden;
    border-radius: 5px;
    float: left;
    cursor: pointer;
    margin: 10px 5px 10px 5px;
	height: 49px;
}
.mini.act,.mini:hover{
	opacity:1;
}
.mini img{
	height: 100%;
    width: 100%;
}
.showmap {
    background: #db3636;
    color: #fff;
    text-transform: uppercase;
    border-radius: 25px;
    padding: 8px 33px;
    position: absolute;
    bottom: 20px;
    right: 30px;
    font-size: 10px;
}
.nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover {
	font-size: 16px;
    font-weight: bold;
    color: #343434;
    border-top: none;
    border-left: none;
    border-right: none;
    border-bottom: 4px solid #db3636;
    background: transparent;
}

.nav-tabs>li>a, .nav-tabs>li>a:hover{
    color: #7b7977;
    font-weight: bold;
    font-size: 16px;
	
    border-top: none;
    border-left: none;
    border-right: none;
    border-bottom: 4px solid #c0bdbb;
	background:none;
}
ul.services {
	list-style:none;
	padding:0;
	font-size:16px;
	margin-top:20px;
}
.services li {
	padding:10px 0;
}
.services li .pic {
	border-radius:50%;
	background:#fff;
	padding: 25px;
    float: left;
	margin-right: 30px;
}
.infobox {
	font-size:16px;
	margin-top:20px;
}
.infobox p{
	//margin-bottom:20px;
}
.infobox a{
	color:#db3636;
	text-decoration:underline;
}
.infobox a:hover{
	text-decoration:none;
	
}
h2.rew {
	font-size:30px;
	font-weight:bold;
	color:#fff;
	text-align:center;
	padding-top:70px;
	margin:60px 0 30px 0;
}
.review {
	background:rgba(255,255,255,.8);
	border-radius:5px;
	text-align:center;
}
.review  img{
	width:100%;
}
.review .imgbox{
	width:100px;
	height:100px;
	overflow:hidden;
	border-radius:50%;
}
.review h3{
	font-size:16px;
	font-weight:bold;
}
.review span{
	font-size:10px;
}
.review i{
	color:#db3636;
}
.review p{
	line-height:22px;
	font-size:13px;
}
.rews:hover {
	color:#fff;
	text-decoration:none;
}
.rews {
	background: #db3636;
    color: #fff;
    text-transform: uppercase;
    border-radius: 25px;
    padding: 8px 33px;
	font-size:12px;
	font-weight:bold;
	    padding: 17px 54px;
		display:inline-block;
		margin: 30px 0 50px 0;
}

#add_hotel_group {
	display: inline-block;
	margin-left: 30px;
}

#add_hotel_group select {

}
</style>
<?
if(!isset($_REQUEST['ID'])) die('отель не выбран');

$arSelect = Array();
$arFieldz = Array();
$arFilter = Array("IBLOCK_ID"=>22,"PROPERTY_TOURVIZORID"=>$_REQUEST['ID']);
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
while($ob = $res->GetNextElement()){
	$arFieldz = $ob->GetFields();
	$arProps = $ob->GetProperties();
}
global $USER;
$user_id = $USER->GetID();
if(isset($user_id)) {
	$filter = Array("ID" => $user_id);
	$rsUsers = CUser::GetList($by = 'ID', $order = 'ASC', $filter, Array("SELECT" => array('UF_AGENT')));
	while ($rsUser = $rsUsers->Fetch())	{
		if($rsUser['UF_AGENT'] == 1 || $rsUser['ID'] == 1) $agent = true;
		else $agent = false;
	}
}

$star='';
define("AUTH","i@neoz.su");
define("PASS","jICPOQJ7");
if(empty($arFieldz)){
	$json = file_get_contents('http://tourvisor.ru/xml/hotel.php?authlogin='.AUTH.'&authpass='.PASS.'&hotelcode='.$_REQUEST['ID'].'&reviews=1&removetags=1&imgwidth=800&format=json');
	$arr = json_decode($json);

	$hotl['name'] = $arr->data->hotel->name;
	$hotl['pic'] = $arr->data->hotel->images->image[0];
	$hotl['description'] = $arr->data->hotel->description;
	$hotl['gallery'] ='';
	$k=0; $act=''; foreach($arr->data->hotel->images->image as $pic) {
		if($k==0) $act='act';
		$hotl['gallery'] .='<div class="mini '.$act.'" ><img src="'.$pic.'" alt="" /></div>';
	}
						
	$hotl['pic'] = $arr->data->hotel->images->image[0];
	$hotl['site'] = $arr->data->hotel->site;
	$hotl['email'] = $arr->data->hotel->email;
	$hotl['phone'] = $arr->data->hotel->phone;
	$hotl['fax'] = $arr->data->hotel->fax;
	$hotl['build'] = $arr->data->hotel->build;
	$hotl['square'] = $arr->data->hotel->square;
	$hotl['placement'] = $arr->data->hotel->placement;
	$hotl['territory'] = $arr->data->hotel->territory;
	$hotl['inroom'] = $arr->data->hotel->inroom;
	$hotl['roomtypes'] = $arr->data->hotel->roomtypes;
	$hotl['child'] = $arr->data->hotel->child;
	$hotl['meallist'] = $arr->data->hotel->meallist;
	$hotl['services'] = $arr->data->hotel->services;
	$hotl['servicefree'] = $arr->data->hotel->servicefree;
	$hotl['servicepay'] = $arr->data->hotel->servicepay;
	$hotl['beach'] = $arr->data->hotel->beach;
	
	$hotl['coord1'] =  $arr->data->hotel->coord1;
	$hotl['coord2'] =  $arr->data->hotel->coord2;

	$star_value = $arr->data->hotel->stars;
	$country = $arr->data->hotel->countrycode;
	$region = $arr->data->hotel->regioncode;
	
	for($i=1;$i<=$arr->data->hotel->stars;$i++){
		$star .='<span class="fa fa-star yellow"></span>';
	}
}
else {
	$hotl['name'] = $arFieldz["NAME"];
	$hotl['description'] = $arFieldz["DETAIL_TEXT"];
	
	$hotl['pic'] = CFile::GetPath($arProps['IMAGES']['VALUE'][0]);
	$hotl['site'] = $arProps['SITE']['VALUE'];
	$hotl['email'] = $arProps['EMAIL']['VALUE'];
	$hotl['phone'] = $arProps['PHONE']['VALUE'];
	$hotl['fax'] = $arProps['FAX']['VALUE'];
	$hotl['build'] = $arProps['BUILD']['VALUE'];
	$hotl['repair'] = $arProps['REPAIR']['VALUE'];
	$hotl['square'] = $arProps['SQUARE']['VALUE'];
	$hotl['placement'] = $arProps['PLACEMENT']['VALUE'];
	$hotl['territory'] =  $arProps['TERRITORY']['VALUE'];
	$hotl['inroom'] = $arProps['INROOM']['VALUE'];
	$hotl['roomtypes'] = $arProps['ROOMTYPES']['VALUE'];
	$hotl['child'] = $arProps['CHILD']['VALUE'];
	$hotl['meallist'] = $arProps['MEALLIST']['VALUE'];
	$hotl['services'] = $arProps['SERVICES']['VALUE'];
	$hotl['servicefree'] = $arProps['SERVICEFREE']['VALUE'];
	$hotl['servicepay'] = $arProps['SERVICEPAY']['VALUE'];
	$hotl['animation'] = $arProps['ANIMATION']['VALUE'];
	$hotl['beach'] = $arProps['BEACH']['VALUE'];

	$star_value = $arProps['STARS']['VALUE'];

	$json = file_get_contents('http://tourvisor.ru/xml/hotel.php?authlogin='.AUTH.'&authpass='.PASS.'&hotelcode='.$_REQUEST['ID'].'&reviews=1&removetags=1&imgwidth=800&format=json');
	$arr = json_decode($json);
	$star_value = $arr->data->hotel->stars;
	$country = $arr->data->hotel->countrycode;
	$region = $arr->data->hotel->regioncode;

	$k=0; $act='';
	foreach($arProps['IMAGES']['VALUE'] as $pic) {
		if($k==0) $act='act';
		$hotl['gallery'] .='<div class="mini '.$act.'" ><img src="'.CFile::GetPath($pic).'" alt="" /></div>';
	}
	
	if(is_integer($arProps['STARS']['VALUE'])){
		for($i=1;$i<=$arProps['STARS']['VALUE'];$i++){
			$star .='<span class="fa fa-star yellow"></span>';
		}
	}
	else {
		$star = $arProps['STARS']['VALUE'];
	}
	
	
	/*echo '<pre>';
var_dump($arFieldz);
var_dump($arProps);
echo '</pre>';*/
}
$APPLICATION->SetTitle("Отель",$hotl['name']);
if($_REQUEST['print']) echo "<pre>dsafdasfdasfasfasfas</pre>";
?>

<div class="container hotelinfos">
	
	<div class="overflow">
		<div class="row ">
			<div class="col-md-8">
				<h1> <?=$hotl['name']?>
				
					<div class="hotel_star">
					<?=$star?>
					</div>

					<?if($agent):?>
						<div id="add_hotel_group">
							<select>
								<option value="0">Выберите группу</option>
								<option value="1">1 группа</option>
								<option value="2">2 группа</option>
							</select>
							<button>Добавить</button>
						</div>
					<?endif?>
				</h1>
				<div class="row ">
					<div class="col-md-6">
						<div class="img"><a href="#" class="showlarge" data-toggle="modal" data-target="#largepic" ><img src="<?=$hotl['pic']?>" alt="" /></a></div>
						
						<div class="pics">
						<?=$hotl['gallery']?>
						</div>
					</div>
					<div class="col-md-6">
						<p><b>Описание отеля</b></p>
						<p><?=$hotl['description']?></p>
					</div>
				</div>
			</div>
			<div class="col-md-4">
				<?
				if(!isset($hotl['coord1'])){
					
				} else {
					$PLACEMARKS = array(
						array(
						'TEXT' => $hotl['name'], 
						'LON' => $hotl['coord2'], 
						'LAT' => $hotl['coord1']
						),
					 );
		
					$APPLICATION->IncludeComponent("bitrix:map.google.view",".default",array(
						"API_KEY" => "AIzaSyDVcwlJJsdy7gvq6LePrBSLE5UvPuIqrvg",
						"INIT_MAP_TYPE" => "MAP",
						"MAP_DATA" => serialize(
									  array(
										 'google_lat' =>  $hotl['coord1'],
										 'google_lon' =>  $hotl['coord2'],
										 'google_scale' => 10, // масштаб карты 0-20
										 'PLACEMARKS' => $PLACEMARKS
										 )
								   ),
						"MAP_WIDTH" => "100%",
						"MAP_HEIGHT" => "366px",
						"CONTROLS" => array(

							),
						"OPTIONS" => array(

								"ENABLE_DBLCLICK_ZOOM",
								"ENABLE_DRAGGING",
								"ENABLE_KEYBOARD"
							),
						"MAP_ID" => "googlemaps".$_REQUEST['ID']
						)
					);?>
				<a href="#" class="showmap">Открыть карту</a>
				<? }?>
			</div>
		</div>
		<div class="row ">
			<div class="col-md-12">
				<div class="infoabouthotel">
					<ul class="nav nav-tabs" role="tablist">
						<li role="presentation" ><a href="#home" aria-controls="home" role="tab" data-toggle="tab">Информация об отеле</a></li>
						<li role="presentation" class="active"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Услуги отеля</a></li>
					</ul>

					<div class="tab-content">
						<div role="tabpanel" class="tab-pane " id="home">
							<div class="infobox">
								<?if(!empty($hotl['site'])){?><p><b>Сайт</b> <a href="http:<?=$hotl['site']?>"><?=$hotl['site']?></a></p><?}?>
								<?if(!empty($hotl['email'])){?><p><b>E-mail</b> <a href="mailto:<?=$hotl['email']?>"><?=$hotl['email']?></a></p><?}?>
								<?if(!empty($hotl['phone'])){?><p><b>Телефон</b> <?=$hotl['phone']?></p><?}?>
								<?if(!empty($hotl['fax'])){?><p><b>Факс</b> <?=$hotl['fax']?></p><?}?>
								<?if(!empty($hotl['build'])){?><p><b>Год постройки</b> <?=$hotl['build']?></p><?}?>
								<?if(!empty($hotl['square'])){?><p><b>Площадь</b> <?=$hotl['square']?></p><?}?>
								<?if(!empty($hotl['placement'])){?><p><b>Расположение</b> <?=$hotl['placement']?></p><?}?>
							</div>
						</div>
						<div role="tabpanel" class="tab-pane active" id="profile">
							
							<div class="row ">
								<div class="col-md-6">
									<ul class="services">
									<?if(!empty($hotl['territory'])){?>
										<li>
											<div class="pic"><img src="/bitrix/templates/tour/images/franshize_12.jpg"></div>
											<p><b>Территория</b></p>
											<p><?=$hotl['territory']?></p>
											<div class="clearfix"></div>
										</li>
									<?}?>
									<?if(!empty($hotl['inroom'])){?>
										<li>
											<div class="pic"><img src="/bitrix/templates/tour/images/franshize_12.jpg"></div>
											<p><b>В комнатах</b></p>
											<p><?=$hotl['inroom']?></p>
											<div class="clearfix"></div>
										</li>
									<?}?>
									<?if(!empty($hotl['roomtypes'])){?>
										<li>
											<div class="pic"><img src="/bitrix/templates/tour/images/franshize_12.jpg"></div>
											<p><b>Тип комнат</b></p>
											<p><?=$hotl['roomtypes']?></p>
											<div class="clearfix"></div>
										</li>
									<?}?>
									<?if(!empty($hotl['child'])){?>
										<li>
											<div class="pic"><img src="/bitrix/templates/tour/images/franshize_12.jpg"></div>
											<p><b>Детям</b></p>
											<p><?=$hotl['child']?></p>
											<div class="clearfix"></div>
										</li>
									<?}?>
									<?if(!empty($hotl['meallist'])){?>
										<li>
											<div class="pic"><img src="/bitrix/templates/tour/images/franshize_12.jpg"></div>
											<p><b>Питание</b></p>
											<p><?=$hotl['meallist']?></p>
											<div class="clearfix"></div>
										</li>
									<?}?>
									</ul>
								</div>
								<div class="col-md-6">
									<ul class="services">
									<?if(!empty($hotl['services'])){?>
										<li>
											<div class="pic"><img src="/bitrix/templates/tour/images/franshize_12.jpg"></div>
											<p><b>Услуги</b></p>
											<p><?=$hotl['services']?></p>
											<div class="clearfix"></div>
										</li>
									<?}?>
									<?if(!empty($hotl['servicefree'])){?>
										<li>
											<div class="pic"><img src="/bitrix/templates/tour/images/franshize_12.jpg"></div>
											<p><b>Бесплатные услуги</b></p>
											<p><?=$hotl['servicefree']?></p>
											<div class="clearfix"></div>
										</li>
									<?}?>
									<?if(!empty($hotl['servicepay'])){?>
										<li>
											<div class="pic"><img src="/bitrix/templates/tour/images/franshize_12.jpg"></div>
											<p><b>Платные услуги</b></p>
											<p><?=$hotl['servicepay']?></p>
											<div class="clearfix"></div>
										</li>
									<?}?>
									<?if(!empty($hotl['beach'])){?>
										<li>
											<div class="pic"><img src="/bitrix/templates/tour/images/franshize_12.jpg"></div>
											<p><b>Пляж</b></p>
											<p><?=$hotl['beach']?></p>
											<div class="clearfix"></div>
										</li>
									<?}?>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row ">
		<div class="col-md-12">
			<h2 class="rew">Отзывы об отеле</h2>
		</div>
	</div>
	<div class="row ">
	<?if($arr->data->hotel->reviewscount>0){
					  foreach($arr->data->hotel->reviews->review as $key => $rew) {
						  ?>
		<div class=" col-md-3" >
			<div class="review">
				<div class="imgbox" style="background-image:url(/bitrix/templates/tour/images/user1_10.jpg);background-size:cover;"><img src="/bitrix/templates/tour/images/user1_10.jpg"></div>
				<h3><?=$rew->name?></h3>
				<span><?=$rew->reviewdate?></span>
				<i class="fa  fa-quote-left redish"></i>
				<p><?=$rew->content?></p>
			</div>
		</div>
					  <?}?>
		<?}?>
	</div>
	<div class="row ">
		<div class="col-md-12" style="text-align:Center;">
			<a class="rews" href="#">Оставить отзыв</a>
		</div>
	</div>
</div>

<div class="modal fade" id="largepic" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
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

<form id="hotel_data">
	<input type="hidden" name="torvisor_id" value="<?=htmlspecialcharsbx($_REQUEST['ID'])?>">
	<input type="hidden" name="name" value="<?=$hotl['name']?>">
	<input type="hidden" name="star" value="<?=$star_value?>">
	<input type="hidden" name="country" value="<?=$country?>">
	<input type="hidden" name="region" value="<?=$region?>">
	<input type="hidden" name="group" value="" id="hgroup">
</form>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>