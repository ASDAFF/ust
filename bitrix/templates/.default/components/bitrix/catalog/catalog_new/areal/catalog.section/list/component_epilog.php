<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!empty($arResult["ITEMS"])):?>
	<script type="text/javascript">
		<?foreach($arResult["ITEMS"] as $arItem):?>
			var id = "<?=$arItem["ID"]?>";
			<?if(in_array($arItem["ID"], $_SESSION["CATALOG_COMPARE_LIST"][$arParams["IBLOCK_ID"]]["ITEMS"][$arItem["IBLOCK_SECTION_ID"]])):?>
				$("#item_"+id).find("div.comparison").html('<a class="compare del" href="#" id="item_'+id+'" title="Сравнение">Убрать из сравнения</a>');
				<?if(count($_SESSION["CATALOG_COMPARE_LIST"][$arParams["IBLOCK_ID"]]["ITEMS"][$arItem["IBLOCK_SECTION_ID"]]) >= 2):?>
					$("#item_"+id).find("div.comparison").addClass("full");
					$("#item_"+id).find("div.comparison a.compare").after('<a class="go" href="/catalog/compare/" title="Перейти в список сравнения">Сравнение(<?=count($_SESSION["CATALOG_COMPARE_LIST"][$arParams["IBLOCK_ID"]]["ITEMS"][$arItem["IBLOCK_SECTION_ID"]])?>)</a>');
				<?endif;?>
			<?else:?>
				$("#item_"+id).find("div.comparison").html('<a class="compare add" href="#" id="item_'+id+'" title="Сравнение">Сравнить</a>');
			<?endif;?>			
		<?endforeach;?>
	</script>
<?endif;?>