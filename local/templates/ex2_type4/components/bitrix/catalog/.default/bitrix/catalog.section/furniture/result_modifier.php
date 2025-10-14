<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

$catalogElementIds = [];
foreach ($arResult['ITEMS'] as $key => $arItem)
{
	$arItem['PRICES']['PRICE']['PRINT_VALUE'] = number_format((float)$arItem['PRICES']['PRICE']['PRINT_VALUE'], 0, '.', ' ');
	$arItem['PRICES']['PRICE']['PRINT_VALUE'] .= ' '.$arItem['PROPERTIES']['PRICECURRENCY']['VALUE_ENUM'];

	$arResult['ITEMS'][$key] = $arItem;
	$catalogElementIds[] = $arItem['ID'];
}


// authors
$authorIds = [];
$authorsRes = CUser::GetList(
	"ID",
	"DESC",
	[
		"ACTIVE" => "Y",
		"UF_AUTHOR_STATUS" => 35
	],
);
while($author = $authorsRes->GetNext()) {
	$authorIds[] = $author["ID"];
}

// reviews
if (count($catalogElementIds) > 0 && count($authorIds) > 0) {
	$reviews = [];
	$reviewsCount = 0;
	$reviewsRes = CIBlockElement::GetList(
		[],
		[
			"IBLOCK_CODE" => "reviews",
			"ACTIVE" => "Y",
			"PROPERTY_PRODUCT" => $catalogElementIds,
			"PROPERTY_AUTHOR" => $authorIds
		],
		false,
		false,
		[
			"NAME",
			"PROPERTY_PRODUCT"
		]
	);

	while($review = $reviewsRes->GetNext()) {
		$arResult["EXTRA"][$review["PROPERTY_PRODUCT_VALUE"]][] = $review["NAME"];
		$reviewsCount++;
	}

	global $APPLICATION;
	$ex2meta = $APPLICATION->GetProperty("ex2_meta");
	if (strlen($ex2meta) > 0) {
		$APPLICATION->SetPageProperty("ex2_meta", str_replace("#count#", $reviewsCount, $ex2meta));
	}
}