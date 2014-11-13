<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?if(!empty($arResult["SECTIONS"])):?>
	<div class="news-filter">
		<ul class="sections">
			<li><a href="<?=$APPLICATION->GetCurPageParam("", array("type"), false)?>" <?if(!$_REQUEST["type"]):?>class="active"<?endif;?> title="Все типы">Все</a></li>
			<?foreach($arResult["SECTIONS"] as $sect):?>
				<li><a href="<?=$APPLICATION->GetCurPageParam("type=".$sect["ID"], array("type"), false)?>" <?if($_REQUEST["type"] == $sect["ID"]):?>class="active"<?endif;?> title="<?=$sect["NAME"]?>"><?=$sect["NAME"]?></a></li>
			<?endforeach;?>
			<div class="clear"></div>
		</ul>
	</div>
<?endif;?>