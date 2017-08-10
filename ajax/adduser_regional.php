<?require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
global $USER;
$rsUser = CUser::GetList($by, $order,
    array(
        "ID" => $USER->GetID(),
    ),
    array(
        "SELECT" => array(
            "UF_AGENT",
            "UF_CITYFLY",
            "UF_CITYCPO",
            "UF_REGIONAL_MANAGER"
        ),
    )
);
if ($arUser = $rsUser->Fetch()) {
    $user[] = $arUser;
}
echo "<pre>".print_r($user, 1)."</pre>";
die();
if(filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL)) {
    $arResult = $USER->Register($_REQUEST['email'], $_REQUEST['name'], "", $_REQUEST['pass'], $_REQUEST['confirm_pass'], $_REQUEST['email']);
    file_put_contents($_SERVER['DOCUMENT_ROOT'].'/tt.txt', print_r($arResult, 1).'fads');
    if($arResult['TYPE'] == 'ERROR') die($arResult['MESSAGE']);
    else die('done');
}else{
    die('error');
}
?>

