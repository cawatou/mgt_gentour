<?require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');
CModule::IncludeModule("iblock");
$arSelect = Array("ID", "NAME");
$arFilter = Array("IBLOCK_ID" => 16, "SECTION_ID" => $_REQUEST['id']);
$res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>9999), $arSelect);
while($ob = $res->GetNextElement()){
	$arFields = $ob->GetFields();
	$office[$arFields["ID"]] = $arFields["NAME"];
}
echo json_encode($office);