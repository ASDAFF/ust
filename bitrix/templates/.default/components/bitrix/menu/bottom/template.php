<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!empty($arResult)):?>
	<?$count = 0;?>
	<?$size_column = intVal(count($arResult)/4);?>
	<?foreach($arResult as $key => $arItem):?>
		<?if(($count%$size_column == 0 || $count == 0) && $column < 4):?>
			<div class="col">
			<?$column++;?>
		<?endif;?>		
				<a href="<?=$arItem["LINK"]?>" title="<?=$arItem["TEXT"]?>"><?=$arItem["TEXT"]?></a>
		<?if((($count+1)%$size_column == 0 && $column < 4) || (!isset($arResult[$count+1]) && $column == 4)):?>
			</div>
		<?endif;?>
		<?$count++;?>
	<?endforeach;?>
<?endif;?>