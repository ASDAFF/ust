<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>	
<?
function WL_Show_Current_Prop($curProp)
{
	if ($curProp["MULTIPLE"] == "Y")
	{
		echo '<li>' . $curProp["NAME"] . ": <span class='value'>"; 
		$i = 0;
		foreach ($curProp["VALUE"] as $curVal)
		{
			if ($i != 0) echo ', ';
			echo $curVal;
			$i++;
		}
		echo '</span></li>';
	}
		else echo '<li>' . $curProp["NAME"] . ": <span class='value'>" . $curProp["VALUE"] . '</span></li>';
}

function WL_Show_Props($arFields, $free_props = false)
{
	if ($free_props == false)
	{
		foreach ($arFields["GROUPS"] as $curGroup)
		{
			echo  '<div class="group_title">' . $curGroup["NAME"] . '</div>';
			echo '<ul>';
			foreach ($curGroup["BOUND_PROPERTIES"] as $curProp)
			{
				WL_Show_Current_Prop($curProp);
			}
			echo '</ul>';
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
	
	return true;
}

// Выводим свойства

echo '<div class="prop_div">';

if ($arParams["FREE_PROPS"] == "before")
	WL_Show_Props($arResult, true);

WL_Show_Props($arResult, false);	
	
if ($arParams["FREE_PROPS"] == "after")
	WL_Show_Props($arResult, true);	
	
echo '</div>';
?>


