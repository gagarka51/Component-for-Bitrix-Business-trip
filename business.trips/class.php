<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Loader;
use Bitrix\Iblock;

class CIBlockElems extends CBitrixComponent
{

    public function onIncludeComponentLang()
    {
        Loc::loadMessages(__FILE__);
    }
    
    protected function checkModules()
    {
        if (!Loader::includeModule('iblock')){
        	ShowError("Модуль iblock(«Информационные блоки») не подключен");
    	}
    }

    public function onPrepareComponentParams($arParams)
    {
    	if (!isset($arParams["IBLOCK_TYPE"])) {
			ShowError("Тип инфоблока не выбран");
    	}
    	if (!isset($arParams["IBLOCK_ID"])) {
			ShowError("Инфоблок не был выбран");
    	}

        return $arParams;
    }

    public function getResult()
    {
        if(CModule::IncludeModule('iblock')) {
            $arIbEl = CIBlockElement::GetList(
                ["SORT" => "ASC"],
                ["IBLOCK_ID" => $arParams["IBLOCK_ID"]],
                false,
                false,
                ["ID", "IBLOCK_SECTION_ID"]
            );
            
            while($ob = $arIbEl->GetNextElement()) {
                $arIdEl[] = $ob->GetFields();
            }
            
            $arSect = GetIBlockSectionList($this->arParams["IBLOCK_ID"]);

            while($res = $arSect->GetNext())
                $arSection [$res["ID"]] = $res["NAME"];

            foreach ($arIdEl as $el) {
                $arPr = CIBlockElement::GetProperty($this->arParams["IBLOCK_ID"], $el["ID"]);
                while ($prop_res = $arPr->GetNext()) {
                    if ($prop_res["PROPERTY_TYPE"] == "L") {
                        $arProps[$el["ID"]][$prop_res["NAME"]] = $prop_res["VALUE_ENUM"];
                    } else {
                        $arProps[$el["ID"]][$prop_res["NAME"]] = $prop_res["VALUE"];
                    }
                    $arProps[$el["ID"]]["ID"] = $el["ID"];
                } 

                foreach ($arSection as $key => $value) {
                    if ($el["IBLOCK_SECTION_ID"] == $key) {
                        $arProps[$el["ID"]]["Категория"] = $value;
                    }    
                }
            }
                
            if ($_GET["time"] != "") {
                $dateTrip = date('d.m.Y') . " " . $_GET["time"];
                foreach ($arProps as $key => $props) {
                    if ($props["Начало поездки"] >= $dateTrip && $props["Занятость"] == "Свободна" && $props["Начало поездки"] > date('d.m.Y H:i:s')) {
                        $arResult["ITEMS"][] = $props;
                    }
                }
            }

        }

        return $arResult;
    }

    public function executeComponent()
    {
        global $USER;
    	if (!$this->checkModules()) {
            // Проверка права на изменение элемента (изменение свойства "Занятость" машины)
            $usRight = CIBlockElementRights::UserHasRightTo($this->arParams["IBLOCK_ID"], $_POST["id_car"], "element_edit");
            
            if ($_POST["id_car"] != "" && $_POST["select_car"] == "Выбрать") {
                if ($usRight === false) {
                    ShowError("Вы не можете выбрать машину данной категории");
                } else {
                    $arItems = $this->getResult();

                    foreach ($arItems["ITEMS"] as $car) {
                        if ($_POST["id_car"] == $car["ID"]) {
                            $arValues = [
                                "model" => $car["Модель"],
                                "driver" => $car["Водитель"],
                                "busyness" => 2, // 2-е значение списка "В работе"
                                "start_trip" => $car["Начало поездки"],
                                "end_trip" => $car["Окончание поездки"],
                                "user_car_id" => $USER->GetID() // Символьный код свойства ИБ
                            ];
                        }
                    }
                    
                    if (!empty($arValues)) {
                        CIBlockElement::SetPropertyValues(
                            $_POST["id_car"], 
                            $this->arParams["IBLOCK_ID"], 
                            $arValues
                        );
                    }   
                }
            }
            
    		if (isset($this->arParams["IBLOCK_ID"])) {
                $this->arResult = $this->getResult(); 
    		}
    	}

        $this->IncludeComponentTemplate();
    }
}
