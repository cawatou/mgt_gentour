<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
require('mailchimp/MailChimp.php');
require('mailchimp/Batch.php'); 
require('mailchimp/Webhook.php');

use \DrewM\MailChimp\MailChimp;

$APPLICATION->RestartBuffer();
if( $_REQUEST['action']=='email') {

	$MailChimp = new MailChimp('32f8f5d138827714fedc5e4f6566cc22-us16');

	$result = $MailChimp->get('lists', array("count" => 100));

	$listsArrToSub = array();

	$town =  $_REQUEST['town'];
	$email =  $_REQUEST['email'];

	foreach($result['lists'] as $list) {


		$sub = false;
		if(strpos($list['name'], '"')){
			$delimiter = '"';

		}
		else {
			$delimiter = '“';
			$sub = true;
		}

		$arr = array("city" => explode(' ', explode($delimiter, $list['name'])[1])[3], "id" => $list['id']);

		if($sub) {
			$arr['city'] = substr($arr['city'], 0, -1);
		}

		$listsArrToSub[] = $arr;
	}
	





	foreach($listsArrToSub as $array){
		if(in_array(trim($town), $array)) {
			$idToSend = $array['id'];
		}
	}


	$result = $MailChimp->post("lists/$idToSend/members", [
				'email_address' => $email,
				'status'        => 'subscribed'
			]);

	//print_r($result);

	if($result['status'] == 'subscribed'){
		echo 'Вы успешно осуществили подписку';
	}
	else if($result['title'] == 'Member Exists') {
		echo 'Ваш e-mail уже есть в нашей базе подписчиков';
	}
}
?>