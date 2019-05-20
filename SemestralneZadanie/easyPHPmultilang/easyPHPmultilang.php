<?php

include_once dirname(__FILE__) . '/config.php';
include_once dirname(__FILE__) . '/functions.php';

class DarsioLang {

    protected $languages = NULL;
    protected $flip_languages = NULL;
    public $default_language = NULL;
    public $current_language = NULL;
    public $url_variable = "language";

    public function setLanguagesList($array) {
        try {

            // Check language uniqueness
            $unique = array_unique($array);
            if ($unique != $array) {
                throw new Exception("Languages List is not unique, please retry");
            }

            $this->languages = $array;
            $this->default_language = $this->languages[0];
            $this->current_language = $this->languages[0];
            $this->flip_languages = array_flip($this->languages);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString() . "<br>" . $exc->getMessage();
        }
    }

    public function setDefaultLanguage($language_id) {
        try {

            // Check if Languages List is not empty
            if (!$this->languages) {
                throw new Exception("Please set languages list (setLanguagesList) before exceeding");
            }

            // Check if default language is on the languages list
            if (!in_array($language_id, $this->languages)) {
                throw new Exception("$language_id code is not in language list");
            } else {
                $this->default_language = $language_id;
                $this->current_language = $language_id;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString() . "<br>" . $exc->getMessage();
        }
    }

    public function setCurrentLanguage($language_id) {
        try {

            // Ask to set default language
            if (!$this->default_language) {
                throw new Exception("Please set default language (setDefaultLanguage) before exceeding");
            }

            // Set current language, if not in list, will set the default language
            if (in_array($language_id, $this->languages)) {
                $this->current_language = $language_id;
            } else {
                $this->current_language = $this->default_language;
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString() . "<br>" . $exc->getMessage();
        }
    }

    public function printLanguageToggle($language_id) {
        try {
            // Check if language_id language is on the languages list
            if (!in_array($language_id, $this->languages)) {
                throw new Exception("$language_id code is not in language list");
            } else if ($this->default_language == $language_id) {
                echo removeqsvar(curPageURL(),  $this->url_variable);
            } else if (count($_GET)-isset($_GET[$this->url_variable])>0){
                echo removeqsvar(curPageURL(),  $this->url_variable) . "&{$this->url_variable}={$language_id}";
            } else {
                echo "?{$this->url_variable}={$language_id}";
            }
        } catch (Exception $exc) {
            echo $exc->getTraceAsString() . "<br>" . $exc->getMessage();
        }
    }

    public function printURLFront() {
        if ($this->current_language == $this->default_language) {
            echo "";
        } else {
            echo "?{$this->url_variable}={$this->current_language}";
        }
    }

    public function printURLMiddle() {
        if ($this->current_language == $this->default_language) {
            echo "";
        } else {
            echo "&{$this->url_variable}={$this->current_language}";
        }
    }

    public function label($array) {
        return $array[$this->flip_languages[$this->current_language]];
    }

    public function printLabel($array) {
        echo $this->label($array);
    }

    public function _($array) {
        echo $this->label($array);
    }

    public function statLanguages() {
        echo "<pre>";
        print_r(["languages" => $this->languages, "flip_languages" => $this->flip_languages, "default_language" => $this->default_language, "current_language" => $this->current_language]);
        echo "</pre>";
    }

}
