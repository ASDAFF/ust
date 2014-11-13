<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?if(!empty($arResult["SECTIONS"])):?>
	<div class="bu-price">
		<div class="tabs-plate">
			<div class="tabs" id="tab_link">
				<ul>
					<li class="active_li"><a href="/catalog/bu-stroitelnaya-tehnika/">Б/У строительная техника</a></li>
					<li><a href="/catalog/bu-skladskaya-tehnika/">Б/У складская техника</a></li>
					<a id="selected" href=""></a>
					<div class="clear"></div>
				</ul>
			</div>
		</div>		
		<div class="technics-list">
			<?$flag = 0; $key_sec = 0;?>
			<?foreach($arResult["SECTIONS"] as $arSec):?>
				<div class="item<?if(($key_sec+1)%3 == 0):?> last<?endif;?>">
					<a href="<?=$arSec["SECTION_PAGE_URL"]?>" <?if($arSec["SELECTED"] == 1):?> <?$flag = 1;?> class="active" <?endif;?>><?=$arSec["NAME"]?></a>
				</div>
				<?$key_sec++;?>
			<?endforeach;?>
			<div class="item<?if(($key_sec+1)%3 == 0):?> last<?endif;?>"><a href="<?=$arParams["DEFAULT_PAGE"]?>" <?if($flag == 0):?>class="active"<?endif;?>>Показать все </a></div>
			<div class="clear"></div>
		</div>
	</div>
<?endif;?>