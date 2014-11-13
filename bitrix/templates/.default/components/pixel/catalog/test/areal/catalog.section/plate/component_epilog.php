<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!empty($arResult["ITEMS"])):?>
	<script type="text/javascript">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			var id = "<?=$arItem["ID"]?>";
			<?if(in_array($arItem["ID"], $_SESSION["CATALOG_COMPARE_LIST"][$arParams["IBLOCK_ID"]]["ITEMS"])):?>
				$("#item_"+id).find("div.comparison").html('<a class="compare" href="/catalog/compare/" title="Перейти в список сравнения">В списке сравнения</a>');
			<?else:?>
				$("#item_"+id).find("div.comparison").html('<a class="compare add" href="#" id="item_'+id+'" title="Сравнить">Сравнить</a>');
			<?endif;?>			
		<?endforeach;?>
	</script>
<?endif;?>