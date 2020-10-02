<?php

namespace Libraries;

class Application
{
    public function __construct()
    {
        if (!GLOBAL_INSTALLED) {
            $database = new Database;
            $database->query(file_get_contents("../resources/data.sql"));
            $database->execute();
            // Add User
            $user = new \Models\AdminModel;
            $user->createdAt = date("Y-m-d H:i:s");
            $user->creator = 1;
            $user->creatorIP = $_SERVER["REMOTE_ADDR"];
            $user->nameLastName = "Ümit Berkan Sefa";
            $user->userName = "ubsefa";
            $user->email = "ubsefa@gmail.com";
            $user->password = "123456";
            $user->birthDate = "1979-09-01 12:00:00";
            $user->mobile = "5308340647";
            $user->admin = "b1";
            $user->roles = join(",", array_keys(USER_ADMIN_ROLES));
            $user->active = "b1";
            $authRepository = new \Repositories\AuthRepository;
            $authRepository->signUp($user);
            // Add Settings
            $setting = new \Models\SettingModel;
            $setting->createdAt = date("Y-m-d H:i:s");
            $setting->creator = 1;
            $setting->creatorIP = $_SERVER["REMOTE_ADDR"];
            $setting->email = "ubsefa@gmail.com";
            $setting->maintenance = "b1";
            $settingRepository = new \Repositories\SettingRepository;
            $settingRepository->add($setting);
        }
        $url = (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] === "on" ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        if (isset($_GET["adminLanguage"])) {
            unset($_COOKIE["adminLanguage"]);
            setcookie("adminLanguage", "", time() - 3650, "/");
            setcookie("adminLanguage", $_GET["adminLanguage"], time() + (60 * 60 * 24 * 3650));
            header("Location: " . $url);
        }
        if (!isset($_COOKIE["adminLanguage"])) {
            setcookie("adminLanguage", GLOBAL_ADMIN_DEFAULT_LANGUAGE, time() + (60 * 60 * 24 * 3650));
            header("Location: " . $url);
        }
        if (isset($_GET["frontLanguage"])) {
            unset($_COOKIE["frontLanguage"]);
            setcookie("frontLanguage", "", time() - 3650, "/");
            setcookie("frontLanguage", $_GET["frontLanguage"], time() + (60 * 60 * 24 * 3650));
            header("Location: " . $url);
        }
        if (!isset($_COOKIE["frontLanguage"])) {
            setcookie("frontLanguage", GLOBAL_FRONT_DEFAULT_LANGUAGE, time() + (60 * 60 * 24 * 3650));
            header("Location: " . $url);
        }
    }

    public static function run()
    {
        $folder = !empty(GLOBAL_BASE_FOLDER) ? "/" . GLOBAL_BASE_FOLDER : "";
        foreach (ROUTE_ROUTES as $route) {
            \Helpers\RouteHelper::addRoute("{$folder}/{$route["name"]}", $route["url"]);
        }
    }
}
