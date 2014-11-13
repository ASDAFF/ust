<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!empty($arResult["SECTIONS"])):?>
	<div class="activities">
		<hr />
		<h2 class="red">Направления нашей деятельности:</h2>
		<div class="list">
			<?foreach($arResult["SECTIONS"] as $key => $arSection):?>
				<div class="item <?if(($key+1)%5 == 0):?>last<?endif;?>">
					<a href="<?=$arSection["URL"]?>" class="image" title="<?=$arSection["NAME"]?>">
						<div class="img">
							<table><tr><td>
								<img src="<?=$arSection["PICTURE"]["src"]?>" width="<?=$arSection["PICTURE"]["width"]?>" height="<?=$arSection["PICTURE"]["height"]?>" alt="<?=$arSection["NAME"]?>" title="<?=$arSection["NAME"]?>" />
							</td></tr></table>
						</div>
						<div class="name">
							<table><tr><td><?=$arSection["NAME"]?></td></tr></table>
						</div>
						<div class="clear"></div>
					</a>
				</div>
			<?endforeach;?>
			<div class="clear"></div>
		</div>
	</div>
<?endif;?>