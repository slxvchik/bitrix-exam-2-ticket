<?php

namespace Event\Index;

use Bitrix\Main\Loader;
use CUser;
use CIBlockElement;

class IndexEventHandler
{
    public static function BeforeIndexReviewHandler($arFields)
    {
        if (Loader::includeModule("iblock") && $arFields["MODULE_ID"] == "iblock" && $arFields["PARAM2"] == 5)
        {
            $propertyAuthor = CIBlockElement::GetProperty(
                $arFields["PARAM2"],
                $arFields["ITEM_ID"],
                array(),
                array("CODE" => "AUTHOR")
            )->Fetch();
            
            if ($propertyAuthor && $propertyAuthor["VALUE"])
            {
                $user = CUser::GetByID($propertyAuthor["VALUE"])->Fetch();
                
                if ($user && $user["LOGIN"])
                {
                    $arFields["TITLE"] .= " " . $user["LOGIN"];
                }
            }
        }
        return $arFields;
    }
}