<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="filials nav-collapsed">
    
        <? //print_r($arResult); ?>
    
    <div class="title"><a href="#" class="main-link">Филиалы <span></span></a></div>
    <div class="collapsed active">
        <? $count = 0;
        $column = 0; ?>
        <? $size_column = round(count($arResult["TOP"]) / 4); ?>
        <? foreach ($arResult["TOP"] as $key => $arItem): ?>
                <? if (($count % $size_column == 0 || $count == 0) && $column < 4): ?>
                <div class="col">
                    <? $column++; ?>
                <? endif; ?>	
                <? if ($arItem["TYPE_LINK_ID"] != 171567): ?>
                    <a href="/filialy/<?= $arItem["CODE"]; ?>/"   title="<?= $arItem["NAME"] ?>"><?= $arItem["NAME"] ?></a>
                <? else: ?>
                    <a class="diler" href="/dilery-skladskoj-tehniki/#element_<?= $arItem["CODE"]; ?>"   title="<?= $arItem["NAME"] ?>"><?= $arItem["NAME"] ?></a>
    <? endif; ?>


            <? if ((($count + 1) % $size_column == 0 && $column < 4) || (!isset($arResult["TOP"][$count + 1]) && $column == 4)): ?>
                </div>
            <? endif; ?>
            <? $count++; ?>
<? endforeach; ?>
    </div>
    <div class="expanded">
        <? $count = 0;
        $column = 0; ?>
        <? $size_column = round(count($arResult["BOTTOM"]) / 4); ?>
            <? foreach ($arResult["BOTTOM"] as $key => $arItem): ?>
			<?
				// Убираем Петрозаводск
				$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				if ($arItem["NAME"] == "Петрозаводск" && strpos($actual_link, "forestry.u-st.ru") != false) continue;
				?>
                <? if (($count % $size_column == 0 || $count == 0) && $column < 4): ?>
                <div class="col">
                    <? $column++; ?>
                <? endif; ?>		

                <? if ($arItem["TYPE_LINK_ID"] != 171567): ?>
                    <a href="/filialy/<?= $arItem["CODE"]; ?>/"   title="<?= $arItem["NAME"] ?>"><?= $arItem["NAME"] ?></a>
                <? else: ?>
                    <a class="diler"  href="/dilery-skladskoj-tehniki/#element_<?= $arItem["CODE"]; ?>"   title="<?= $arItem["NAME"] ?>"><?= $arItem["NAME"] ?></a>
            <? endif; ?>
            <? if ((($count + 1) % $size_column == 0 && $column < 4) || (!isset($arResult["BOTTOM"][$count + 1]) && $column == 4)): ?>
                </div>
            <? endif; ?>
    <? $count++; ?>
<? endforeach; ?>
    </div>
</div>