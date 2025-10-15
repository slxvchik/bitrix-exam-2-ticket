<?php

namespace Agents\Reviews;

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use CEventLog;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Entity\Query;
use Bitrix\Iblock\ElementTable;

Loc::loadMessages(__FILE__);

class ReviewAgent
{
    public static function Agent_ex_610($lastTimeExec = "")
    {
        if (Loader::includeModule("iblock")) {

            $result = static::prepareResult($lastTimeExec);

            CEventLog::Add([
                'SEVERITY' => 'INFO',
                'AUDIT_TYPE_ID' => 'ex2_610',
                'MODULE_ID' => 'iblock',
                'DESCRIPTION' => Loc::getMessage('REVIEW_AGENT_AGENT_EX_610_LOG_MESSAGE', [
                    '#LAST_TIME_EXEC#' => $lastTimeExec,
                    '#REVIEW_COUNT#' => count($result),
                ]),
            ]);
        }

        return "\\" . __METHOD__ . "(\"" . (new DateTime())->toString() . "\");";
    }

    private static function prepareResult($lastTimeExec = "")
    {
        $query = new Query(ElementTable::GetEntity());

        $query->setSelect(["ID", "TIMESTAMP_X"])
            ->setFilter([
                "IBLOCK_ID" => 5,
                ">TIMESTAMP_X" => $lastTimeExec ?: (new DateTime())->add("-1 day")
            ]);
        
        return $query->exec()->fetchAll();
    }
}