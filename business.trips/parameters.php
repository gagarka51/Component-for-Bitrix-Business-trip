<?php 

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

if (!CModule::IncludeModule('iblock'))
    return;

$arFilterIb = array("ACTIVE" => "Y");
$arSortIb = array("SORT" => "ASC");

$arIblockType = CIBlockParameters::GetIBlockTypes();

if (!empty($arCurrentValues["IBLOCK_TYPE"])) {
    $arFilterIb["TYPE"] = $arCurrentValues["IBLOCK_TYPE"];
}

$arIb = CIBlock::GetList(
	$arSortIb,
	$arFilterIb
);

while($ar_res = $arIb->fetch()) {
	$arIblock [$ar_res["ID"]] = "[" . $ar_res["ID"] . "] " . $ar_res["NAME"];
}

$arComponentParameters = array(
	"GROUPS" => array(),
	"PARAMETERS" => array(
		"IBLOCK_TYPE" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("BIS_TRIP_IBLOCK_TYPE"),
			"TYPE" => "LIST",
			"REFRESH" => "Y",
			"MULTIPLE" => "N",
			"VALUES" => $arIblockType
    	),
    	"IBLOCK_ID" => array(
			"PARENT" => "BASE",
			"NAME" => GetMessage("BIS_TRIP_IBLOCK"),
			"TYPE" => "LIST",
			"REFRESH" => "Y",
			"MULTIPLE" => "N",
			"VALUES" => $arIblock
    	),
  	),
);