<?php 
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<form method="get">
	<input type="time" name="time">
	<input type="submit" value="Найти">
</form>

<?php foreach($arResult["ITEMS"] as $key => $item) { ?>
	<form method="post">
		<p>Модель: <?=$item["Модель"] ?></p>
		<p>Водитель: <?=$item["Водитель"] ?></p>
		<p>Занятость: <?=$item["Занятость"] ?></p>
		<p>Категория: <?=$item["Категория"] ?></p>
		<p>Начало поездки: <?=$item["Начало поездки"] ?></p>
		<p>Окончание поездки: <?=$item["Окончание поездки"] ?></p>
		<input type="hidden" name="id_car" value="<?=$item["ID"] ?>">
		<input type="submit" name="select_car" value="Выбрать">
	</form>
	<br>
<?php } ?>
