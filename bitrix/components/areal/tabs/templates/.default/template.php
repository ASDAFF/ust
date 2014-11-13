<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(count($arResult["TABS"])):?>
	<div class="tabs-plate">
		<div class="tabs" id="tabs">
			<ul>
				<?foreach($arResult["TABS"] as $key => $tab):?>
					<li><a href="#<?=$tab["CODE"]?>" <?if($key == 0):?>class="active"<?endif;?>><?=$tab["NAME"]?></a></li>
				<?endforeach;?>
				<a id="selected"></a>
				<div class="clear"></div>
			</ul>
			<?foreach($arResult["TABS"] as $key => $tab):?>
				<div class="page" id="<?=$tab["CODE"]?>">
					<?if(!empty($tab["PREVIEW_PICTURE"]["src"])):?>
						<img class="tabs" src="<?=$tab["PREVIEW_PICTURE"]["src"]?>" width="<?=$tab["PREVIEW_PICTURE"]["width"]?>" height="<?=$tab["PREVIEW_PICTURE"]["height"]?>" alt="<?=$tab["NAME"]?>" title="<?=$tab["NAME"]?>" />
					<?endif;?>
					<?if($tab["PREVIEW_TEXT_TYPE"] == "text"):?>
						<p><?=$tab["PREVIEW_TEXT"]?></p>
					<?else:?>
						<?=$tab["PREVIEW_TEXT"]?>
					<?endif;?>
				</div>
			<?endforeach;?>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>
<?endif;?>