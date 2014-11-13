<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="filials nav-collapsed">
	<div class="title"><a href="#" class="main-link">Филиалы и Дилеры<span></span></a></div>
	<div class="collapsed active">
		<?$count = 0; $column = 0;?>
		<?$size_column = round(count($arResult["TOP"])/4);?>
		<?foreach($arResult["TOP"] as $key => $arItem):?>
			<?if(($count%$size_column == 0 || $count == 0) && $column < 4):?>
				<div class="col">
				<?$column++;?>
			<?endif;?>		
					<a href="#" title="<?=$arItem["NAME"]?>"><?=$arItem["NAME"]?></a>
			<?if((($count+1)%$size_column == 0 && $column < 4) || (!isset($arResult["TOP"][$count+1]) && $column == 4)):?>
				</div>
			<?endif;?>
			<?$count++;?>
		<?endforeach;?>
	</div>
	<div class="expanded">
		<?$count = 0; $column = 0;?>
		<?$size_column = round(count($arResult["BOTTOM"])/4);?>
		<?foreach($arResult["BOTTOM"] as $key => $arItem):?>
			<?if(($count%$size_column == 0 || $count == 0) && $column < 4):?>
				<div class="col">
				<?$column++;?>
			<?endif;?>		
			<?
			?>
					<a href="#" title="<?=$arItem["NAME"]?>"><?=$arItem["NAME"]?></a>
			<?if((($count+1)%$size_column == 0 && $column < 4) || (!isset($arResult["BOTTOM"][$count+1]) && $column == 4)):?>
				</div>
			<?endif;?>
			<?$count++;?>
		<?endforeach;?>
	</div>
</div>