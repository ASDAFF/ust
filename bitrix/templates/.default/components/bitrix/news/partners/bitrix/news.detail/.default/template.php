<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?if(!empty($arResult["PHOTO"])):?>
	<div class="border_gray carousel brandes">
		<ul id="action_banner">
			<?foreach($arResult["PHOTO"] as $key => $photo):?>
				<li>
					<a href="#" class="image">
						<table><tr><td>
							<img src="<?=$photo["src"]?>" width="<?=$photo["width"]?>" height="<?=$photo["height"]?>" alt="<?=$photo["description"]?>" title="<?=$photo["description"]?>">
						</td></tr></table>
					</a>						
					<div class="description"><span><?=$photo["description"]?></span></div>
				</li>
			<?endforeach;?>
		</ul>
		<?if(count($arResult["PHOTO"]) > 1):?>
			<div class="nav">
				<a href="#" class="prev"></a> 
				<a href="#" class="next"></a>
				<ul class="pagging"></ul>
			</div>
		<?else:?>
			<input type="hidden" name="count_banner" value="<?=count($arResult["PHOTO"]);?>" />
		<?endif;?>
	</div>
<?endif;?>
<?if($arResult["NAV_RESULT"]):?>
	<?if($arParams["DISPLAY_TOP_PAGER"]):?><?=$arResult["NAV_STRING"]?><br /><?endif;?>
	<?echo $arResult["NAV_TEXT"];?>
	<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?><br /><?=$arResult["NAV_STRING"]?><?endif;?>
<?elseif(strlen($arResult["DETAIL_TEXT"])>0):?>
	<?echo $arResult["DETAIL_TEXT"];?>
<?else:?>
	<?echo $arResult["PREVIEW_TEXT"];?>
<?endif?>
<br />