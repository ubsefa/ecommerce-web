<?php

namespace Controllers\Admin;

class HomeController extends \Libraries\Controller
{
    public function indexAction()
    {
        $this->render("pages/admin/home.html",
            [
                "logo" => $this->model["settings"]->logo,
                "themeUrl" => GLOBAL_ADMIN_THEME_URL,
                "language" => GLOBAL_ADMIN_DEFAULT_LANGUAGE,
                "title" => $this->model["settings"]->title,
                "keywords" => $this->model["settings"]->keywords,
                "description" => $this->model["settings"]->description,
                "author" => \Helpers\I18NHelper::getAdminKeyword("author"),
            ]);
    }
}
