<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = array(
    "NAME" => "Служебные поездки",
    "DESCRIPTION" => "Выводит информацию о созданной поездке",
    "CACHE_PATH" => "Y",
    "COMPLEX" => "N",
    "PATH" => array(                                      
        "ID" => "sharkova_components",                                
        "NAME" => "Шаркова С.И.",                           
        "CHILD" => array(
            "ID" => "business.trips",
            "NAME" => "Служебные поездки"
        )
    )
);