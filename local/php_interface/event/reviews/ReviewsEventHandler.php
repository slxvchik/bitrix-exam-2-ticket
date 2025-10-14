<?php

namespace Event\Reviews;

use Bitrix\Main\Localization\Loc;

use CEventLog;

Loc::loadMessages(__FILE__);

class ReviewsEventHandler
{

    private static $oldAuthorId;

    public static function onBeforeReviewAdd(&$arFields)
    {
        if ($arFields["IBLOCK_ID"] == 5)
        {
            return self::checkPreviewText($arFields);
        }
    }
    
    public static function onBeforeReviewUpdate(&$arFields)
    {
        if ($arFields["IBLOCK_ID"] == 5 && \Bitrix\Main\Loader::includeModule("iblock"))
        {
            $oldAuthor = \CIBlockElement::GetProperty(5, $arFields["ID"], [], ["CODE" => "AUTHOR"]);
            static::$oldAuthorId = $oldAuthor->GetNext()["VALUE"];
            return self::checkPreviewText($arFields);
        }
    }

    public static function onAfterReviewUpdate(&$arFields)
    {
        if ($arFields["IBLOCK_ID"] == 5 && !isset($arFields["RESULT_MESSAGE"]) && static::$oldAuthorId !== $newAuthorId)
        {
            $newAuthor = \CIBlockElement::GetProperty(5, $arFields["ID"], [], ["CODE" => "AUTHOR"]);
            $newAuthorId = $newAuthor->GetNext()["VALUE"];
                
            CEventLog::Add([
                'SEVERITY' => 'INFO',
                'AUDIT_TYPE_ID' => 'ex2_590',
                'MODULE_ID' => 'iblock',
                'DESCRIPTION' => Loc::getMessage('REVIEWS_EVENT_HANDLER_NEW_AUTHOR', [
                    '#REVIEW_ID#' => $arFields["ID"],
                    '#OLD_AUTHOR_ID#' => static::$oldAuthorId ?: 'NULL',
                    '#NEW_AUTHOR_ID#' => $newAuthorId ?: 'NULL'
                ]),
            ]);
        }
    }

    private static function checkPreviewText(&$arFields)
    {
        if (strlen($arFields["PREVIEW_TEXT"]) <= 5)
        {
            global $APPLICATION;
            $APPLICATION->ThrowException(
                Loc::GetMessage(
                    "REVIEWS_EVENT_HANDLER_LENGTH_REVIEW_TEXT_ERROR", [
                        "#PREVIEW_TEXT_LENGTH#" => strlen($arFields["PREVIEW_TEXT"])
                    ]
                )
            );
            return false;
        }
        $arFields["PREVIEW_TEXT"] = str_replace("#del#", "", $arFields["PREVIEW_TEXT"]);
        return true;
    }

}