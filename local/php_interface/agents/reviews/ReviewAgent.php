<?php

namespace Agents\Reviews;

\Bitrix\Main\Localization\Loc::loadMessages(__FILE__);

class ReviewAgent
{
    public static function Agent_ex_610($lastTimeExec = "")
    {
        if (\Bitrix\Main\Loader::includeModule("iblock")) {

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

        return "\\" . __METHOD__ . "(\"" . (new \Bitrix\Main\Type\DateTime())->toString() . "\");";
    }

    private static function prepareResult($lastTimeExec = "")
    {
        $query = new \Bitrix\Main\Entity\Query(\Bitrix\Iblock\ElementTable::GetEntity());

        $query->setSelect(["ID", "TIMESTAMP_X"])
            ->setFilter([
                "IBLOCK_ID" => 5,
                ">TIMESTAMP_X" => $lastTimeExec ?: (new \Bitrix\Main\Type\DateTime())->add("-1 day")
            ]);
        
        return $query->exec()->fetchAll();
    }
}