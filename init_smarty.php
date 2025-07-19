<?php
require_once __DIR__ . '/libs/smarty-master/smarty-master/libs/Smarty.class.php';

// Création de l'objetSmarty :
use Smarty\Smarty;
$smarty = new Smarty();

$smarty->setTemplateDir(__DIR__ . '/template');
$smarty->setCompileDir(__DIR__ . '/templates_c/');
$smarty->setCacheDir(__DIR__ . '/cache/');
$smarty->setConfigDir(__DIR__ . '/configs/');

// Facultatif : désactiver le cache
$smarty->caching = Smarty::CACHING_OFF;

