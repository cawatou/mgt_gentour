<?

if(isset($_REQUEST['RESULT_ID'])&&$_REQUEST['formresult']=='addok'){
	echo '<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel"></h4>
      </div>';
	if($_REQUEST['WEB_FORM_ID']=='2')
		echo 
	 '<div class="thnx"><h1>Спасибо, ваш вопрос отправлен!</h1><p>Вопрос публикуется вместе с ответом на него</p></div>';
	
	if($_REQUEST['WEB_FORM_ID']=='3')
		echo
	 '<div class="thnx"><h1>Спасибо, ваша заявка принята!</h1><p>В течении 5 минут с вами свяжется оператор</p></div>';
	
	if($_REQUEST['WEB_FORM_ID']=='4')
		echo
	 '<div class="thnx"><h1>Спасибо, ваша заявка принята!</h1><p>Подписка успешно осуществлена</p></div>';
	
	if($_REQUEST['WEB_FORM_ID']=='5')
		echo
	 '<div class="thnx"><h1>Спасибо, ваша заявка принята!</h1><p>С вами свяжется оператор</p></div>';
	
	if($_REQUEST['WEB_FORM_ID']=='6')
		echo
	 '<div class="thnx"><h1>Спасибо, ваша заявка принята!</h1></div>';
	if($_REQUEST['WEB_FORM_ID']=='7')
		echo
	 '<div class="thnx"><h1>Спасибо, ваша оценка принята!</h1></div>';
	if($_REQUEST['WEB_FORM_ID']=='9')
		echo
	 '<div class="thnx"><h1>Подписка оформлена!</h1><p>По всем вопросам обращаться на e-mail</p></div>';
}
?>