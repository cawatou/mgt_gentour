<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Поиск попутчиков");?>
<style>
	body {
		background:url(/bitrix/templates/tour/images/bgr/company_bgr.jpg);
		background-attachment:fixed;
		background-size: cover;
	}
	.navbar {
		position:fixed !important;
	}

	.footer-all {
		background-color:#212121;padding-top: 50px;
		padding-bottom: 150px;
	}
	/*******************************/
	ul.oglavl {
		padding: 0;
		margin: 0;
		list-style-type: none; 
		margin: 20px 0 0 0;
	}

	ul.oglavl li {
		border-bottom: 1px dotted #989898;
		position: relative;
		padding: 0;
		margin-bottom:5px;
	}

	ul.oglavl li span {    margin: 0;    }
	ul.oglavl li span.text, ul.oglavl li span.page {
		bottom: -5px;
	}

	ul.oglavl span.text {
		position :relative;
		margin-right:7em;
		padding-right:2px;
	}

	ul.oglavl span.page {
		position: absolute;
		right: 0;
		padding-left:2px;
	}
	/*****************************************/
	.findcompanion h1{
		margin-top:150px;
		margin-bottom:30px;
		font-size:30px;
		font-weight:bold;
		color:#fff;
		text-align:center;
	}
	.search-form {
		background:#fff;
		padding: 19px;
		border-radius: 5px 0 0 5px;
		max-width: 835px;
	}
	.search-form span{
		margin-bottom: 5px;
	}
	.search-form span{
		color:#323232;
		font-size:16px;
		font-weight:bold;
		display:block;
	}
	.search-form label{
		color:#323232;
		font-size:16px;
		margin-right: 28px;
	}

	.search-form label.checked{
		color:#db3636;
	}

	.search-form input[type=text]{
		height:30px;
	}
	.search-form input{
		font-size:16px;
		color:#323232;
	}
	.search-box .srch-btn{
		background:#db3636;
		border:0;
		border-radius:0 3px 3px 0;
		width:95px;
		height:95px;
		color:#fff;
	}
	.btn-anketa {
		text-transform:uppercase;
		font-size:16px;
		font-weight:bold;
		color:#fff;
		border-radius:5px;
		border:7px solid #c8c7c5;
		padding: 30px 23px;
		display: block;
		text-align: center;
		min-width: 211px;
		float: right;
	}
	.btn-anketa:hover {
		text-decoration:none;
		color:#fff;
	}
	.findcompanion h2 {
		margin:0;
		font-size:30px;
		color:#fff;
		font-weight:bold;
	}
	.res-title i{
		border-radius:50%;
		background:#eae4e0;
		color:#db3636;
		width:38px;
		height:38px;
		line-height: 38px;
	}
	.res-title {
		margin-top:60px;
		margin-bottom:20px;
		text-align:center;
	}
	.find-box {
		background-color:rgba(255,255,255,.8);
		border-radius:5px;
		color:#333;
		padding:25px;
	}
	.find-box img.ava{
		width:100px;
		height:100px;
		border-radius:50%;
	}
	.find-box i{
		color:#db3636;
	}
	.find-box .views{
		color:#666;
		font-size:10px;
		text-transform:uppercase;
	}
	.find-box .text{
		font-size:13px;
	}
	.find-box .page{
		font-size:13px;
		font-weight:bold;
	}
	.find-box h3{
		font-size:13px;
		font-weight:bold;
	}
	.find-box p{
		font-size:13px;
	}
	.more-companion:hover {
		cursor:pointer;
		color:#fff;
		background-color:#db3636;
		text-decoration:none;
	}
	.more-companion {
		border:1px solid #b3b3b3;
		border-radius:15px;
		color:#db3636;
		text-transform:uppercase;
		font-size:10px;
		font-weight:bold;
		padding: 9px 24px;
		display: inline-block;
	}
	.find-box p.social{
		line-height: 60px;
	}
	.find-box p.social a:hover{
		text-decoration:none;
		cursor:pointer;
	}
	.find-box p.social a{
		border-radius:50%;
		background:#fff;
		border:1px solid #ccc;
		text-align:center;
		line-height:35px;
		width:35px;
		height:35px;
	}
	.find-box p.social a.fa-vk{
		color:#507299;
	}
	.find-box p.social a.fa-facebook{
		color:#4267b2;
	}
	.actualy {
		margin: 16px 0 0px 0;
		color:#333;
		text-align:right;
	}
	.actualy span {

		color:#db3636;
	}
	.box  {
		color: #fff;
		background-color: #db3636;
		text-transform: uppercase;
		font-size: 16px;
		font-weight: bold;
		border-radius: 35px;
		display: inline-block;
		padding: 19px 35px;
	}
	.buttonblock {
		text-align:center;
		margin-top:40px;
		margin-bottom:60px;
	}
	.navg:hover {
		color:#323232;
	}
	.navg {
		color:#323232;
		font-size:14px;
		background:#fff;
		border-radius:3px;
		display:inline-block;
		width:35px;
		height:35px;
		text-align:center;
		line-height: 35px;
		margin:0 10px;
	}
	.navg.active {
		color:#fff;
		background:#db3636;
	}
	.chev i{
		color:#db3636;
	}
	.chev:first-child {
		margin-right:30px;
	}
	.chev:last-child {
		margin-left:30px;
	}
	.chev:hover {
		color:#fff;
	}
	.chev {
		font-size:11px;
		font-weight:bold;
		color:#fff;
		text-transform:uppercase;
		display:inline-block;
	}
	.navi-bottom {
		margin-bottom:40px;
		text-align:center;
	}
	.addsoc b {
		color:#db3636;
		padding-right: 10px;
	}
	.redbot:hover {
		color:#333;
		border-bottom:2px solid #fff;
		text-decoration:none;
	}
	.redbot {
		font-size:13px;
		font-weight:bold;
		color:#333;
		border-bottom:2px solid #db3636;
	}
	.loadpic {
		margin: 20px 0;
		display: inline-block;
	}
	.bigbtn {
		color: #fff;
		background: #db3636;
		padding: 12px 76px;
		font-size: 16px;
		font-weight: bold;
		text-transform: uppercase;
		border-radius: 25px;
		display: inline-block;
	}
	#results .row {
		margin-bottom:20px;
	}
	.btndiv{
		text-align: right;
		padding: 0;
		min-width: 99px;
	}
	#upload {
		display: none;
	}
	/*******************************************for mobile ******************************************/
	@media all and (max-width:1000px){
		.btndiv{
			padding: 10px;
		}
		.btn-anketa {
			font-size: 12px;
			padding: 7px 23px;
			min-width: 100%;
		}
		.search-box .srch-btn {
			width: 100%;
			height: 25px;
		}
		#results .col-xs-12 {
			margin-bottom:10px;
		}
	}
</style>
<div class="container findcompanion">
	<div class="row">
		<div class="col-md-12 col-xs-12">
			<h1>Поиск попутчика</h1>
		</div>
	</div>
	<div class="row search-box">
		<form id="comp-srch" method=post>
			<div class="poputchiki-block-wrap">
				<div class="col-md-9 col-xs-12">
					<div class="row">

						<div class="col-md-10 col-xs-12  search-form">
							<div class="row">
								<div class="col-md-4 col-xs-12">
									<span>Ваш пол</span>
									<input name="sex" value="m" type="radio" id="sexM" checked><label for="sexM" class="checked"> Мужской</label>
									<input name="sex" value="w"  type="radio" id="sexW"><label for="sexW"> Женский</label>
								</div>
								<div class="col-md-4 col-xs-12">
									<span>Интересующий пол</span>
									<input name="isex" value="m" type="radio" id="isexM"><label for="isexM"> Мужской</label>
									<input name="isex" value="w" type="radio" checked id="isexW"><label for="isexW" class="checked"> Женский</label>
								</div>
								<div class="col-md-4 col-xs-12">
									<span>Направление (место отдыха)</span>
									<input name="direction" type="text" class="form-control">
								</div>
							</div>
						</div>	
						<div class="col-md-1 col-xs-12 btndiv" >
							<button class="srch-btn"><i class="fa fa-search"></i></button>
						</div>

					</div>
				</div>
				<div class="col-md-2 col-xs-12" >
					<a href="#" class="btn-anketa"  data-toggle="modal" data-target="#addcompany">Заполнить анкету</a>
				</div>
			</div>
		</form>
	</div>
	<div class="row">
		<div class="col-md-12 col-xs-12 res-title" >
			<i class="fa fa-search " ></i>
			<h2>Результаты поиска</h2>
		</div>
	</div>
	
	<?$APPLICATION->IncludeComponent("bitrix:news.list", "companions", Array(
		"IBLOCK_TYPE"	=>	"companion",
		"IBLOCK_ID"	=>	"17",
		"NEWS_COUNT"	=>	"9",
		"SORT_BY1"	=>	"ACTIVE_FROM",
		"SORT_ORDER1"	=>	"DESC",
		"SORT_BY2"	=>	"SORT",
		"SORT_ORDER2"	=>	"ASC",
		"FILTER_NAME"	=>	"comfilter",
		"FIELD_CODE"	=>	array(
			),
		"PROPERTY_CODE"	=>	array(
			0	=>	"comp_name",
			1	=>	"com_phone",
			2	=>	"com_mail",
			3	=>	"com_sex",
			4	=>	"com_age",
			5	=>	"com_links",
			6	=>	"com_city",
			7	=>	"com_citys",
			8	=>	"com_who",
			9	=>	"COMCOUNTRY",
			10	=>	"com_flydate",
			11	=>	"com_day",
			12	=>	"com_actualy",
			13	=>	"com_coment",
			),
		"DETAIL_URL"	=>	"/content/companion/#ELEMENT_ID#/",
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
		"USE_ELEMENT_COUNTER" => "Y",
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
	<div class="modal fade" id="fullview" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">

			</div>
		</div>
	</div>
	<div class="modal fade" id="addcompany" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title" id="myModalLabel">Поиск попутчика</h4>
				</div>
				<form id="anketaComp" enctype="multipart/form-data">
					<div class="modal-body">
						<div class="row">
							<div class="col-md-6 col-xs-6">
								<div class="form-group ">
									<input name="imya" class="form-control" placeholder="Имя" />
								</div>
								<div class="form-group ">
								<input name="phone" class="phone-number-for-mask form-control" placeholder="Телефон" />
								</div>
								<div class="form-group ">
									<input name="email" class="form-control" placeholder="Почта" />
								</div>
								<div class="form-group ">
									<select name="mysex" class="form-control" >
										<option>Пол</option>
										<option>Мужской</option>
										<option>Женский</option>
									</select>
								</div>
								<div class="form-group ">
									<input name="age" class="form-control" placeholder="Возраст" />
								</div>
								<div class="form-group ">
									<input name="link[]" class="form-control" placeholder="Ссылка на социальную сеть" />
								</div>
								<div class="form-group ">
									<input name="link[]" class="form-control" placeholder="Ссылка на социальную сеть" />
								</div>
								<div class="form-group ">
									<input name="link[]" class="form-control" placeholder="Ссылка на социальную сеть" />
								</div>
								<div class="form-group ">
									<input name="link[]" class="form-control" placeholder="Ссылка на социальную сеть" />
						</div><!-- 
						<div class="form-group addsoc">
							<b>+</b><a href="#" class="redbot">Добавить ссылку на соц.сеть</a>
						</div> -->
					</div>
					<div class="col-md-6 col-xs-6">
						<div class="form-group ">
							<input name="city" class="form-control" placeholder="Город вылета" />
						</div>
						<div class="form-group ">
							<input name="citys" class="form-control" placeholder="Ближайшие города" />
						</div>
						<div class="form-group ">
							<select name="whosex" class="form-control" >
								<option>Кого ищем</option>
								<option>Мужчину</option>
								<option>Женщину</option>
							</select>
						</div>
						<div class="form-group ">
							<input name="country" class="form-control" placeholder="Страна пребывания" />
						</div>
						<div class="form-group ">
							<input name="flydate" class="form-control daterangepickerpal" placeholder="Дата вылета" />
						</div>
						<div class="form-group ">
							<input name="count" class="form-control" placeholder="Количество дней отдыха" />
						</div>
						<div class="form-group ">
							<input name="lastdate" class="form-control dtp3 dateendpop" placeholder="Дата окончания поиска попутчика" />
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 col-xs-12">
						<textarea name="more" class="form-control" placeholder="Информация о себе (не более 500 символов)" ></textarea>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 col-xs-12">
						<label for="upload">
							<input type="file" name="upload" id="upload" onchange="getName(this.value);" />
							<a class="redbot loadpic">Загрузить фотографию</a> <span id="fileformlabel"></span>
						</label>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12 col-xs-12">
						<a href="#" class="bigbtn ank">Отправить заявку</a>
					</div>
				</div>
			</div>
		</form>
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