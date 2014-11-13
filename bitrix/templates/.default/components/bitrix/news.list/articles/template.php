<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!empty($arResult["ITEMS"])):?>
	<div class="articles">
		<div class="icon-title">Статьи<span></span></div>
		<div class="list">
			<?foreach($arResult["ITEMS"] as $arItem):?>
				<?$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
				<div class="item">
					<div class="name"><a href="<?=$arItem["DETAIL_PAGE_URL"]?>"><span></span><?=$arItem["NAME"]?></a></div>
					<div class="announce"><?=$arItem["PREVIEW_TEXT"]?>&nbsp;<a href="<?=$arItem["DETAIL_PAGE_URL"]?>">Подробнее</a> &raquo;</div>
				</div>
			<?endforeach;?>
		</div>
	</div>
<?endif;?>