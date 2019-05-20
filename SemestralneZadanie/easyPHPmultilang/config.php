<?php

define("DEBUG_MODE", TRUE);

$lang = new DarsioLang();
$lang->setLanguagesList(["sk", "en"]);
$lang->setDefaultLanguage("sk");

if (!$_GET[$lang->url_variable]) {
    $lang->setCurrentLanguage($lang->default_language);
} else {
    $lang->setCurrentLanguage($_GET[$lang->url_variable]);
}