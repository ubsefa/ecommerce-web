<?php

namespace Helpers;

class I18NHelper
{
    public static function getAdminLanguages()
    {
        $languages = array();
        foreach (FileHelper::getFiles("../Application/Languages/Admin", "json") as $file) {
            array_push($languages, array("name" => str_replace(".json", "", $file), "logo" => json_decode(file_get_contents("../Application/Languages/Admin/" . $file), true)["logo"], "value" => json_decode(file_get_contents("../Application/Languages/Admin/" . $file), true)["language"]));
        }
        return $languages;
    }

    public static function getFrontLanguages()
    {
        $languages = array();
        foreach (FileHelper::getFiles("../Application/Languages/Front", "json") as $file) {
            array_push($languages, array("name" => str_replace(".json", "", $file), "logo" => json_decode(file_get_contents("../Application/Languages/Front/" . $file), true)["logo"], "value" => json_decode(file_get_contents("../Application/Languages/Front/" . $file), true)["language"]));
        }
        return $languages;
    }

    public static function getAdminKeyword($keyword)
    {
        $keywords = json_decode(file_get_contents("../Application/Languages/Admin/" . (isset($_COOKIE["frontLanguage"]) ? $_COOKIE["frontLanguage"] : GLOBAL_ADMIN_DEFAULT_LANGUAGE) . ".json"), true);
        if (isset($keywords["keywords"][$keyword])) {
            return $keywords["keywords"][$keyword];
        }
        return $keyword;
    }

    public static function getFrontKeyword($keyword)
    {
        $keywords = json_decode(file_get_contents("../Application/Languages/Front/" . (isset($_COOKIE["frontLanguage"]) ? $_COOKIE["frontLanguage"] : GLOBAL_FRONT_DEFAULT_LANGUAGE) . ".json"), true);
        if (isset($keywords["keywords"][$keyword])) {
            return $keywords["keywords"][$keyword];
        }
        return $keyword;
    }
}
