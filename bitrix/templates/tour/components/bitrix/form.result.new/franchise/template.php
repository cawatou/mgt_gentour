<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<?if ($arResult["isFormErrors"] == "Y"):?><p class="bg-danger"><?=$arResult["FORM_ERRORS_TEXT"];?></p><?endif;?>

<?if ($arResult["isFormNote"] != "Y")
{
?>
<?=$arResult["FORM_HEADER"]?>

<?
if ($arResult["isFormDescription"] == "Y" || $arResult["isFormTitle"] == "Y" || $arResult["isFormImage"] == "Y")
{
?>
<style>
	#employe .jq-selectbox{
		display: none !important;
	}
</style>
<?
/***********************************************************************************
					form header
***********************************************************************************/
 	if ($arResult["isFormImage"] == "Y")
	{
	?>
	<a href="<?=$arResult["FORM_IMAGE"]["URL"]?>" target="_blank" alt="<?=GetMessage("FORM_ENLARGE")?>"><img src="<?=$arResult["FORM_IMAGE"]["URL"]?>" <?if($arResult["FORM_IMAGE"]["WIDTH"] > 300):?>width="300"<?elseif($arResult["FORM_IMAGE"]["HEIGHT"] > 200):?>height="200"<?else:?><?=$arResult["FORM_IMAGE"]["ATTR"]?><?endif;?> hspace="3" vscape="3" border="0" /></a>
	<?//=$arResult["FORM_IMAGE"]["HTML_CODE"]?>
	<?
	} //endif
	?>

			
		
	<?
} // endif
	?>

<?
/***********************************************************************************
						form questions
***********************************************************************************/
?>
 <div>
	<?
	foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion)
	{
		
	?><div class="form-group <?if($arQuestion["CAPTION"]=="star")echo "vote";?>">
		<? if($arQuestion["CAPTION"]=="star"){
			echo '<div class="starq">';
		} ?>
			<?if (is_array($arResult["FORM_ERRORS"]) && array_key_exists($FIELD_SID, $arResult['FORM_ERRORS'])):?>
			<P class="bg-danger" title="<?=$arResult["FORM_ERRORS"][$FIELD_SID]?>"></p>
			<?endif;?>
			<?=$arQuestion["HTML_CODE"]?>
		<? if($arQuestion["CAPTION"]=="star"){
			echo '</div>';
		} ?>
		</div>
	<?
	} //endwhile
	?>
</div>
<?
if($arResult["isUseCaptcha"] == "Y")
{
?>
		
		
<?
} // isUseCaptcha
?>
	<div>
		<button type="submit" class=" btnformmodalred">ОТПРАВИТЬ ЗАЯВКУ</button>
	</div>
	

<?=$arResult["FORM_FOOTER"]?>
<?
} //endif (isFormNote)
?>