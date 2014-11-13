<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?><?
$printed = false;
//pr($_SERVER["HTTP_REFERER"],false);
foreach ($arResult as $item): 
    ?><?
    if (strpos($_SERVER["HTTP_REFERER"], $item["PROPERTY_HTTP_REFERER_VALUE"]) !== false || $_SESSION["REFERER_ID"] == $item["ID"]) {
        $phone = $item["PROPERTY_PHONE_VALUE"];
        $_SESSION["REFERER_ID"] = $item["ID"];
        print $phone;
        $printed = true;
    } else {
        if ($item["ID"] != "25190") {
            $phone = $item["PROPERTY_PHONE_VALUE"];
            print "<span style='display:none'>";
            print $phone;
            print "</span>";
        }
    }
    ?>
<? endforeach; ?><?

$_SESSION["PROPERTY_PHONE_VALUE"]= $arResult[0]["PROPERTY_PHONE_VALUE"];

if ($printed == false) {
    print '<span class="tel">'.$arResult[0]["PROPERTY_PHONE_VALUE"].'</span>';
    
} else {
    print "<span style='display:none'>";
    print $arResult[0]["PROPERTY_PHONE_VALUE"];
    print "</span>";
   print "<style>.town-num{display:none !important;}</style>";
}
?>