<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

if(empty($arResult["BRAND_BLOCKS"])) // no data? - good bye!
	return;

if (!function_exists('getBrandblockPopupHtml'))
{
	function getBrandblockPopupHtml($text)
	{
		return '<span class="bx_popup"><span class="arrow"></span><span class="text">'.$text.'</span></span>';
	}
}

$strObName = "bxIblockBrand".rand();
$mouseEvents = 'onmouseover="'.$strObName.'.itemOver(this);" onmouseout="'.$strObName.'.itemOut(this)"';
?>
<div class="bx_item_detail_inc_two">
		<?
		foreach ($arResult["BRAND_BLOCKS"] as $blockId => $arBB)
		{
			$html = '';

			if($arBB['TYPE'] == 'ONLY_PIC')
			{
				$html .= '<img src="'.htmlspecialcharsbx($arBB['PICT']['SRC']).'"';

				if(strlen($arBB['NAME']) > 0)
					$html .= ' alt="'.htmlspecialcharsbx($arBB['NAME']).'"  title="'.htmlspecialcharsbx($arBB['NAME']).'"';

				$html .= '>';

				if(strlen($arBB['LINK']) > 0)
					$html = '<a href="'.htmlspecialcharsbx($arBB['LINK']).'">'.PHP_EOL.
					$html.PHP_EOL.
					'</a>';

				if(strlen($arBB['FULL_DESCRIPTION']) > 0)
					$html .= getBrandblockPopupHtml($arBB['FULL_DESCRIPTION']);

				$html = '<div class="bx_item_detail_inc_one_container" '.$mouseEvents.'>'.PHP_EOL.
					$html.PHP_EOL.
					'</div>';
			}
			else
			{
				if(strlen($arBB['FULL_DESCRIPTION']) > 0)
					$html .= getBrandblockPopupHtml($arBB['FULL_DESCRIPTION']);

				if(strlen($arBB['DESCRIPTION']) > 0)
					$html .= htmlspecialcharsbx($arBB['DESCRIPTION']);

				if($arBB['PICT'] != false && strlen($arBB['PICT']['SRC']) > 0)
				{
					$html = ' class="bx_item_vidget icon" style="background-image:url('.$arBB['PICT']['SRC'].');" '.$mouseEvents.'>'.
						$html;
				}
				else
				{
					$html = ' class="bx_item_vidget" '.$mouseEvents.'>'.
						$html;
				}

				if(strlen($arBB['LINK']) > 0)
					$html = '<a href="'.htmlspecialcharsbx($arBB['LINK']).'"'.$html.'</a>';
				else
					$html = '<span'.$html.'</span>';
			}

			echo $html;
		}
		?>
</div>
<script type="text/javascript">
	var <?=$strObName;?>;
	BX.ready( function(){
		if(typeof window["JCIblockBrands"] != 'function') //if cached by upper components, etc.
		{
			BX.loadScript("<?=$templateFolder.'/script.js'?>", function(){ <?=$strObName;?> = new JCIblockBrands; });
			BX.loadCSS("<?=$templateFolder.'/style.css'?>");
		}
		else
		{
			<?=$strObName;?> = new JCIblockBrands;
		}
	});
</script>