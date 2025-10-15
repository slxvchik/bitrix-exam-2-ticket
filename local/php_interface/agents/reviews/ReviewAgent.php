<?php

namespace agents\reviews;

use Bitrix\Iblock\ElementTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Type\DateTime;

class ReviewAgent
{
    public static function Agent_ex_610($lastTimeExec = "")
    {
        if (Loader::includeModule("iblock")) {

            $result = static::prepareResult($lastTimeExec);

            \CEventLog::Add([
                'SEVERITY' => 'INFO',
                'AUDIT_TYPE_ID' => 'ex2_610',
                'MODULE_ID' => 'iblock',
                'DESCRIPTION' => Loc::getMessage('REVIEW_AGENT_AGENT_EX_610_LOG_MESSAGE', [
                    '#LAST_TIME_EXEC#' => $lastTimeExec,
                    '#REVIEW_COUNT#' => count($result),
                ]),
            ]);
        }
    }

    private static function prepareResult($lastTimeExec = "")
    {
        $query = new \Bitrix\Main\Entity\Query(ElementTable::GetEntity());

        $query->setSelect(["ID", "TIMESTAMP_X"])
            ->setFilter([
                "IBLOCK_ID" => 2,
                ">TIMESTAMP_X" => $lastTimeExec ?: (new DateTime())->add("-1 day")
            ]);
        
        return $query->exec()->fetchAll();
    }
}