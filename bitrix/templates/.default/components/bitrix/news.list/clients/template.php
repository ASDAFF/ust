<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!empty($arResult["ITEMS"])):?>
	<div class="clients-page">
		<?foreach($arResult["ITEMS"] as $key => $arItem):?>
			<?$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
			<div class="image clients border_gray <?if(($key+1)%4 == 0):?>last<?endif;?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
				<table><tr><td>
					<?if(!empty($arItem["PREVIEW_PICTURE_RESIZE"]["src"])):?>
						<img src="<?=$arItem["PREVIEW_PICTURE_RESIZE"]["src"]?>" width="<?=$arItem["PREVIEW_PICTURE_RESIZE"]["width"]?>" height="<?=$arItem["PREVIEW_PICTURE_RESIZE"]["height"]?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
					<?else:?>
						<img src="<?=getImageNoPhoto(184, 143)?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
					<?endif;?>
				</td></tr></table>
				<span><?=$arItem["NAME"]?></span>
			</div>
		<?endforeach;?>
		<div class="clear"></div>
	</div>
	<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
		<?=$arResult["NAV_STRING"]?>
	<?endif;?>
<?else:?>
	<p class="errortext">К сожалению, в данном разделе нет доступной информации.</p>
<?endif;?>
