<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!empty($arResult["ITEMS"])):?>
	<div class="faq">
		<div class="icon-title">Вопрос-ответ<span></span></div>
		<div class="list">
			<?foreach($arResult["ITEMS"] as $key=>$arItem):?>
				<?$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));?>
				<div class="item">
					<div class="question"><a href="#q_<?=$key;?>"  class="q_<?=$key;?>"><span></span><?=$arItem["NAME"]?></a></div>
					<div class="answer q_<?=$key;?>"><?=$arItem["PREVIEW_TEXT"]?></div>
				</div>
			<?endforeach;?>
		</div>
	</div>
<?endif;?>