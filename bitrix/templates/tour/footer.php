<style>
    .checkme {
        width: 11px;
        display: inline-block;
        height: 11px;
        border: 1px solid #d9d9d9;
        border-radius: 3px;
        margin-right: 10px;
        cursor: pointer;
    }

    .checkeds {
        width: 11px;
        display: inline-block;
        height: 11px;
        background: #db3636;
        color: #fff;
        border-radius: 3px;
        margin-right: 10px;
        font-size: 10px;
        line-height: 11px;
        overflow: hidden;
    }

    .hidecheck {
        display: none;
    }
</style>
<?
$CITY_ID = $APPLICATION->get_cookie("CITY_ID");
if (!$USER->IsAuthorized() && empty($CITY_ID)){?>
<div class="modal fade" id="myTown" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="myModalLabel">Где вы находитесь:</h4>
			</div>
			<p>Ваш город: <b class="myTown"><?=$User_city['city']?></b></p>
			<button type="button" class="closethis  btnformmodalwhite">ОК</button>
			<button type="button" class="change btnformmodalwhite">Изменить</button>
			<div class="clearfix"></div>
		</div>
	</div>
</div>
<?}?>
<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <? $APPLICATION->IncludeComponent("bitrix:system.auth.form", "auth", Array(
                "REGISTER_URL" => "/auth/",
                "PROFILE_URL" => "/personal/profile/"
                )
                ); ?>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addrewiev" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
       <div class="modal-dialog" role="document">
          <div class="modal-content">
             <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Добавить отзыв</h4>
            </div>
            <form id="addRewv" enctype="multipart/form-data">
             <div class="modal-body">
              <? if(!in_array(1,$USER->GetUserGroupArray()) && !in_array(5,$USER->GetUserGroupArray()) && !in_array(10,$USER->GetUserGroupArray())){?>
                 <div class="row">
                   <div class="col-md-12 col-xs-12">
                      <div class="form-group ">
                         <input name="imya" class="form-control" placeholder="Имя"  required />
                     </div>
                     <div class="form-group ">
                         <input name="phone" class="phone-number-for-mask form-control" placeholder="Телефон"  required />
                     </div>
                     <div class="form-group ">
                         <input name="email" class="form-control" placeholder="Почта" required  />
                     </div>
                 </div>
             </div>
             <div class="row">
               <div class="col-md-12 col-xs-12">
                  <input type="file" name="upload" id="upload" onchange="getName(this.value);" />
                  <a href="#" class="redbot loadpic">Добавьте ваше фото кликнув сюда</a> <span id="fileformlabel"></span>
              </div>
          </div>
          <?}
          else { 
            echo 'Вы зашли как: '.$USER->GetFullName();
          }
          ?>
          <div class="row">
           <div style="position: relative;" class="col-md-12 col-xs-12">
            <h2>Укажите ваш пол</h2>
              <input type="radio" name="gender" value="woman"> Женский<br>
              <input type="radio" name="gender" value="man"> Мужской<br><br>
          </div>
      </div>
          <div class="row">
           <div style="position: relative;" class="col-md-12 col-xs-12">
              <textarea name="more" required class="form-control" style="min-height: 250px" placeholder="Ваш Отзыв (не более 500 символов)" ></textarea>
              <div class="counterSimbols">
                Осталось <span>500</span> из 500
              </div>
          </div>
      </div>
      <div class="row">
       <div class="col-md-12 col-xs-12">
          <a href="#" class="bigbtn submt">Отправить отзыв</a>
      </div>
  </div>
</div>
</form>
</div>
</div>
</div>
<div class="modal fade" id="registar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <?$APPLICATION->IncludeComponent(
               "bitrix:main.register",
               "tourreg",
               Array(
                  "SEF_MODE" => "N", 
                  "SHOW_FIELDS" => Array("NAME","LAST_NAME","EMAIL","PERSONAL_PHONE","PERSONAL_BIRTHDAY","PERSONAL_CITY","PERSONAL_PHOTO"), 
                  "REQUIRED_FIELDS" => Array("NAME","LAST_NAME","PERSONAL_CITY","EMAIL","PERSONAL_PHONE"), 
                  "AUTH" => "Y", 
                  "USE_BACKURL" => "Y", 
                  "SUCCESS_PAGE" => "/lk/", 
                  "SET_TITLE" => "N", 
                  "CACHE_TYPE" => "A", 
                  "CACHE_TIME" => "3600" 
                  )
                  );?>
              </div>
          </div>
      </div>
      <div class="modal fade" id="forgot" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <?$APPLICATION->IncludeComponent(
                  "bitrix:system.auth.forgotpasswd",
                  "",
                  Array(
                     "SHOW_ERRORS"=>"Y"
                     ) 
                     );?>
                 </div>
             </div>
         </div>
         <div class="modal fade" id="question" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <? $APPLICATION->IncludeComponent("bitrix:form.result.new", "question", Array(
                        "SEF_MODE" => "N",
                        "WEB_FORM_ID" => 2,
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
                        ); ?>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="orderfor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <? $APPLICATION->IncludeComponent("bitrix:form.result.new", "orderfor", Array(
                            "SEF_MODE" => "N",
                            "WEB_FORM_ID" => 5,
                            "EDIT_URL" => "",
                            "CHAIN_ITEM_TEXT" => "",
                            "CHAIN_ITEM_LINK" => "",
                            "IGNORE_CUSTOM_TEMPLATE" => "Y",
                            "SUCCESS_URL" => "",
                            "COMPONENT_TEMPLATE" => "orderfor",
                            "LIST_URL" => "result.php",
                            "USE_EXTENDED_ERRORS" => "Y",
                            "CACHE_TYPE" => "A",
                            "CACHE_TIME" => "3600",
                            "VARIABLE_ALIASES" => Array("RESULT_ID" => "RESULT_ID", "WEB_FORM_ID" => "WEB_FORM_ID")
                            )
                            ); ?>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="soc" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="callback" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <? $APPLICATION->IncludeComponent("bitrix:form.result.new", "question", Array(
                                    "SEF_MODE" => "N",
                                    "WEB_FORM_ID" => 3,
                                    "EDIT_URL" => "",
                                    "CHAIN_ITEM_TEXT" => "",
                                    "CHAIN_ITEM_LINK" => "",
                                    "IGNORE_CUSTOM_TEMPLATE" => "Y",
                                    "SUCCESS_URL" => "",
                                    "USE_EXTENDED_ERRORS" => "Y",
                                    "CACHE_TYPE" => "A",
                                    "CACHE_TIME" => "3600",
                                    "VARIABLE_ALIASES" => Array("RESULT_ID" => "RESULT_ID", "WEB_FORM_ID" => "WEB_FORM_ID")
                                    )
                                    ); ?>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="answer" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="showmap" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                 <div style="text-align:center;"><img src="/bitrix/templates/tour/images/89.gif" /></div>
                             </div>
                         </div>
                     </div>
                     <a href="#" id="scroller" class="fa fa-angle-up"></a>

                      
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=PT+Sans" rel="stylesheet">

  <script src="https://use.fontawesome.com/036d074a7a.js"></script>

  <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />
  
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.min.css">
  <?$APPLICATION->ShowCSS();?>
	<script>
		var officeForCity = "<?=$officeForCity?>";
		var reqID = "<?= htmlspecialcharsbx($_REQUEST['req'])?>";
		var userCity = "<?=$User_city['city']?>";
		var ajaxLicKey = "<?= md5('ajax_' . LICENSE_KEY)?>";
		var inGroup =  <? if(in_array(1,$USER->GetUserGroupArray()) || in_array(10,$USER->GetUserGroupArray())){?>"y";<?}else{?>"n";<?}?>

     <? if(in_array(5,$USER->GetUserGroupArray())) {
      echo 'var registeredOne =  true' ;
      echo ';var registeredEmail = "'.$USER->GetEmail().'"';
    } 
    else {
      echo 'var registeredOne = false';
    }?>
	</script>
	 <? if ($APPLICATION->GetCurPage(false) !== '/'): ?>
	<? endif; ?>
	<? if ($APPLICATION->GetCurPage(false) === '/'): ?>
	<script  type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<? endif; ?>

  

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script  src="<?= SITE_TEMPLATE_PATH ?>/js/jquery.mousewheel.js?<?=time()?>"></script>
<script  src="<?= SITE_TEMPLATE_PATH ?>/js/jquery.mousewheel.js?<?=time()?>"></script>
<script  type="text/javascript" src="<?= SITE_TEMPLATE_PATH ?>/js/moment-with-locales.min.js"></script>
<script  src="<?= SITE_TEMPLATE_PATH ?>/js/moment.min.js?<?=time()?>"></script>
<script  src="<?= SITE_TEMPLATE_PATH ?>/js/ru.js?<?=time()?>"></script>
<script  src="<?= SITE_TEMPLATE_PATH ?>/js/bootstrap.min.js?<?=time()?>"></script>
<script  src="<?= SITE_TEMPLATE_PATH ?>/js/dropdown.js?<?=time()?>"></script>
<script  src="<?= SITE_TEMPLATE_PATH ?>/js/jquery.ui.touch-punch.min.js?<?=time()?>"></script>
<script  src="<?= SITE_TEMPLATE_PATH ?>/js/jquery.fancybox.min.js?<?=time()?>"></script>
<script src="<?= SITE_TEMPLATE_PATH ?>/js/bootstrap-datetimepicker.js"></script>
<script src="<?= SITE_TEMPLATE_PATH ?>/js/daterangepicker.js"></script>
<script  src="<?= SITE_TEMPLATE_PATH ?>/js/jquery.formstyler.min.js?<?=time()?>"></script>
<script  src="<?= SITE_TEMPLATE_PATH ?>/js/jquery.fullPage.js?<?=time()?>"></script>
<script  src="<?= SITE_TEMPLATE_PATH ?>/js/jquery.jscrollpane.min.js?<?=time()?>"></script>
<script  src="<?= SITE_TEMPLATE_PATH ?>/js/jquery.liColl.js?<?=time()?>"></script>
<script  src="<?= SITE_TEMPLATE_PATH ?>/js/mask.js?<?=time()?>"></script>
<script  src="<?= SITE_TEMPLATE_PATH ?>/js/masonry.pkgd.min.js?<?=time()?>"></script>
<script  src="<?= SITE_TEMPLATE_PATH ?>/js/owl.carousel.min.js?<?=time()?>"></script>
<script src="<?= SITE_TEMPLATE_PATH ?>/js/jquery.fancybox.min.js"></script>
<script src="<?= SITE_TEMPLATE_PATH ?>/js/plyr.js"></script>
<script src="<?= SITE_TEMPLATE_PATH ?>/js/chart.js"></script>

<script  src="<?= SITE_TEMPLATE_PATH ?>/js/all.min.js?<?=time()?>"></script>
<script  src="<?= SITE_TEMPLATE_PATH ?>/adaptive.script.fordesktops.paladdin.js?<?=time()?>"></script>
<script  src="<?= SITE_TEMPLATE_PATH ?>/js/script.min.js?<?=time()?>"></script>

 <? /*<script src="<?= SITE_TEMPLATE_PATH ?>/js/common.min.js"></script> */ ?>

</div>
<div class="margintopieelement"></div>
</body>


</html>