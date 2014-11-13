<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>	
 <div class="scroll-pane horizontal-only">
<?
$count = 0;
function WL_Show_Current_Prop($curProp)
{
	if ($curProp["MULTIPLE"] == "Y")
	{
		echo '<tr><td class="name">' . $curProp["NAME"] . '</td><td class="value">'; 
		$i = 0;
		foreach ($curProp["VALUE"] as $curVal)
		{
			if ($i != 0) echo ', ';
			echo $curVal;
			$i++;
		}
		echo '</td></tr>';
	}
		else echo '<tr><td class="name">' . $curProp["NAME"] . '</td><td class="value">' . $curProp["VALUE"] . '</td></tr>';
}

function WL_Show_Props($arFields, $free_props = false)
{
	if ($free_props == false)
	{
		$count = 0;
		foreach ($arFields["GROUPS"] as $curGroup)
		{
			echo '<div class="group group_'.$count.'">';
			echo  '<strong class="group_title">' . $curGroup["NAME"] . '</strong>';
			echo '<table>';
			foreach ($curGroup["BOUND_PROPERTIES"] as $curProp)
			{
				WL_Show_Current_Prop($curProp);
			}
			echo '</table></div>';
			
			$count++;
		}
	}	
	else
	{
		echo  '<br />';
		echo '<ul>';
		foreach ($arFields["FREE_PROPS"] as $curProp)
		{
			WL_Show_Current_Prop($curProp);
		}
		echo '</ul>';
	}
	$_SESSION['group_count'] = $count;
	return true;
	
}

// Âûâîäèì ñâîéñòâà

echo '<div class="prop_div">';

if ($arParams["FREE_PROPS"] == "before")
	WL_Show_Props($arResult, true);

WL_Show_Props($arResult, false);	
	
if ($arParams["FREE_PROPS"] == "after")
	WL_Show_Props($arResult, true);	
	
echo '</div>';

?>

</div>
            <? if($_SESSION['group_count'] > 2): 
			?>
            <div class="hided show_chars">Показать все характеристики</div>
            
            <? endif;?>
