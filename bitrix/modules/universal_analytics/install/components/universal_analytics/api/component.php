<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
// echo '<pre>'; print_r($arParams); echo '</pre>';
if (CModule::IncludeModule("iblock"))
{
    // здесь необходимо использовать функции модуля "Информационные блоки"
    print "1";
    if (CModule::IncludeModule("universal_analytics"))
    {

        try
        {
            Universal_Analytics::test();
        }
        catch (Exception $e)
        {
            echo 'Выброшено исключение: ', $e->getMessage(), "\n";
        }

        print "2";
    } 
}
?>