<?require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
global $USER;
$rsUser = CUser::GetList($by, $order, array("ID" => $USER->GetID()),array(
        "SELECT" => array(
            "UF_REGIONAL_MANAGER"
        ),
    )
);
if ($arUser = $rsUser->Fetch()) $user = $arUser;
if ($user['UF_REGIONAL_MANAGER'] != 1) exit();
$city = $_REQUEST['regional_city'];
$office = $_REQUEST['regional_office'];

if(filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL)) {
    COption::SetOptionString("main","captcha_registration","N");
    $arResult = $USER->Register($_REQUEST['login'], false, false, $_REQUEST['pass'], $_REQUEST['confirm_pass'], $_REQUEST['email']);
    COption::SetOptionString("main","captcha_registration","Y");
    if($arResult['TYPE'] == 'ERROR'){     
        die($arResult['MESSAGE']);  
    }else{
        $user = new CUser;
        $fields = Array(
            "ACTIVE" => "Y",
            "UF_CITYCPO" => array($office),
            "UF_CITYFLY" => array($city),
        );
        $user->Update($arResult['ID'], $fields);
        die('done');
    }
}else{
    die('error');
}
?>

