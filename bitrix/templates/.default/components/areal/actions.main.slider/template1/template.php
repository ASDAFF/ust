<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(count($arResult["ACTIONS"]) > 0):?>
	<div class="actions-slider">
		<div class="wrapper">
			<div class="list" id="actions_main">
				<ul>
					<?foreach($arResult["ACTIONS"] as $action):?>
						<li>
							<a href="<?=$action["DETAIL_PAGE_URL"]?>" title="category" class="image border_gray">
								<table><tr><td>
								<?if(!empty($action["PREVIEW_PICTURE"]["src"])):?>
									<img src="<?=$action["PREVIEW_PICTURE"]["src"]?>" width="<?=$action["PREVIEW_PICTURE"]["width"]?>" height="<?=$action["PREVIEW_PICTURE"]["height"]?>" alt="<?=$action["NAME"]?>" title="<?=$action["NAME"]?>">
								<?else:?>
									<img src="<?=getImageNoPhoto(252, 128)?>" alt="<?=$action["NAME"]?>" title="<?=$action["NAME"]?>">
								<?endif;?>
								</td></tr></table>
								<div class="info-overlay"></div>
								<div class="info">
									<span class="title"><?=$action["NAME"]?></span>
									<span class="descr">
										<?if(!empty($action["PREVIEW_TEXT"])):?>
											<?if($action["PREVIEW_TEXT_TYPE"] == "text"):?>
												<?=$action["PREVIEW_TEXT"]?>
											<?elseif($action["PREVIEW_TEXT_TYPE"] == "html"):?>
												<?=strip_tags($action["PREVIEW_TEXT"])?>
											<?endif;?>
										<?endif;?>
									</span>
								</div>
							</a>
						</li>
					<?endforeach;?>
					<div class="clear"></div>
				</ul>
			</div>
			<div class="jcarousel-prev"></div>
			<div class="jcarousel-next"></div>
		</div>
	</div>
<?endif;?>