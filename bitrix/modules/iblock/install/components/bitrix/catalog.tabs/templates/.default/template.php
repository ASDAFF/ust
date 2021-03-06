<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
$content = "";
$activeTabId = "";
$jsObjName = "catalogTabs_".$arResult["ID"];
?>
<div class="bx-catalog-tab-section-container"<?=isset($arResult["WIDTH"]) ? ' style="width: '.$arResult["WIDTH"].'px;"' : ''?>>
	<ul class="bx-catalog-tab-list" style="left: 0px;">
		<?foreach ($arParams["DATA"] as $tabId => $arTab):?>
			<?$id = $arResult["ID"].$tabId;
			if(isset($arTab["ACTIVE"]) && $arTab["ACTIVE"] == "Y")
				$tabActive = true;
			else
				$tabActive = false;
			?>
			<?if(isset($arTab["NAME"]) && isset($arTab["CONTENT"])):?>
				<li <?=($tabActive ? 'class="active" ' : '')?>id="<?=$id?>" onclick="<?=$jsObjName?>.onTabClick(this);">
					<a href="javascript:void(0);"><span><?=$arTab["NAME"]?></span></a>
				</li>

				<?if($tabActive || $activeTabId == "")
					$activeTabId = $id;
				?>
				<?$content .= '<div id="'.$id.'_cont"'.(!isset($arTab["ACTIVE"]) || $arTab["ACTIVE"] != "Y" ? ' class="tab-off"' : '').'>'.$arTab["CONTENT"].'</div>';?>
			<?endif;?>
		<?endforeach;?>
	</ul>
	<div class="bx-catalog-tab-body-container">
		<div class="container">
			<?=$content?>
		</div>
	</div>
</div>

<script type="text/javascript">
	var JCCatalogTabsParams = {
		activeTabId: "<?=$activeTabId?>",
		tabsContId: "<?=CUtil::JSEscape($arResult["ID"])?>",
	};

	var <?=$jsObjName?> = new JCCatalogTabs(JCCatalogTabsParams);

	BX.ready(function(){
		var tab = BX(JCCatalogTabsParams.activeTabId);

		if(tab)
			<?=$jsObjName?>.setTabActive(tab);
	});
</script>