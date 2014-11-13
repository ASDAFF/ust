<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<div class="title">Выберите ближайший город:</div>
<?if(!empty($arResult["TOWNS"])):?>
	<div class="cities">
		<?foreach($arResult["TOWNS"] as $town):?>
			<div class="town"><a href="#" class="select_town <?if($town["SELECTED"]):?>active<?endif;?>" id="town_<?=$town["ID"]?>"><?=$town["NAME"]?></a></div>
		<?endforeach;?>
		<div class="clear"></div>
		<div class="select-city">
			<p>Или укажите в поле:</p>
			<div class="input_autocomplete autocomplete">
				<form name="town" method="post" action="<?=$APPLICATION->GetCurPage()?>">
					<input type="text" class="autocomplete town" name="select_town" value="" />
					<div class="b-places"></div>
				</form>
			</div>
		</div>
		<div class="clear"></div>
	</div>
<?endif;?>