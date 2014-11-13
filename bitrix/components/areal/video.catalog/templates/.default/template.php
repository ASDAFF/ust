<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!empty($arResult["VIDEO"])):?>
	<div class="videos-col">
		<div class="icon-title"><span></span>Видео</div>
		<div class="list">
			<?foreach($arResult["VIDEO"] as $key => $arItem):?>
				<?if($key < 4):?>
					<div class="item-video <?if($key == 3):?> last <?endif;?>">
						<div class="video">
							<a class="video_show" href="#" id="video_<?=$arItem["ID"]?>" title="Посмотреть видео">
								<?if(!empty($arItem["PREVIEW_PICTURE"])):?>
									<img src="<?=$arItem["PREVIEW_PICTURE"]["src"]?>" width="<?=$arItem["PREVIEW_PICTURE"]["width"]?>" height="<?=$arItem["PREVIEW_PICTURE"]["height"]?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
								<?endif;?>
								<span class="icon"></span>
								<span class="video-overlay"></span>
							</a>
						</div>
						<div class="name"><a class="video_show" href="#" id="video_<?=$arItem["ID"]?>" title="Посмотреть видео"><?=$arItem["NAME"]?></a></div>
					</div>
				<?endif;?>
			<?endforeach;?>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
		<?if(count($arResult["VIDEO"]) > 4):?>
			<div class="more-videos">
				<a href="#" class="more-videos-link">Еще видео</a>
				<div class="tooltip">
					<span class="tooltip-top"></span>
					<div class="list">
						<?foreach($arResult["VIDEO"] as $key => $arItem):?>
							<?if($key >= 4):?>
								<div class="item-video <?if(($key+1)%2 == 0):?> last <?endif;?>">
									<div class="video">
										<a class="video_show" href="#" id="video_<?=$arItem["ID"]?>" title="Посмотреть видео">
											<?if(!empty($arItem["PREVIEW_PICTURE"]["src"])):?>
												<img src="<?=$arItem["PREVIEW_PICTURE"]["src"]?>" width="<?=$arItem["PREVIEW_PICTURE"]["width"]?>" height="<?=$arItem["PREVIEW_PICTURE"]["height"]?>" alt="<?=$arItem["NAME"]?>" title="<?=$arItem["NAME"]?>" />
											<?endif;?>
											<span class="frame"></span>
											<span class="icon"></span>
											<span class="video-overlay"></span>
										</a>
									</div>
									<div class="name"><a class="video_show" href="#" id="video_<?=$arItem["ID"]?>" title="Посмотреть видео"><?=$arItem["NAME"]?></a></div>
								</div>
								<?if(($key+1)%2 == 0):?> <div class="clear"></div> <?endif;?>
							<?endif;?>
						<?endforeach;?>
						<div class="clear"></div>
					</div>
				</div>
				<div class="clear"></div>
			</div>
		<?endif;?>
	</div>
	<div class="dialog" id="video_player"></div>
<?endif;?>