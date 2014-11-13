<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!empty($arResult["POINTS"])):?>
	<?$n = 0;?>
	<?foreach($arResult["POINTS"] as $arItem):?>
		<a class="bottom_filialy<?if(($n+1)%4 == 0):?> last<?endif;?><?if($arItem["SELECTED"] == 1):?> active<?endif;?>" href="<?=$arItem["LINK"]?>" title="<?=$arItem["NAME"]?>"><?=$arItem["NAME"]?></a>
		<?$n++;?>
	<?endforeach;?>
<?endif;?>