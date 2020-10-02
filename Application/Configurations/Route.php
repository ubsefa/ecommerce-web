<?php

define("ROUTE_ROUTES",
    array(
        array("name" => "Admin/Login", "url" => "Admin/LoginController@indexAction"),
        array("name" => "Admin", "url" => "Admin/HomeController@indexAction"),
        array("name" => "Admin/HomeController", "url" => "Admin/HomeController@indexAction"),
        array("name" => "", "url" => "Front/HomeController@indexAction"),
        array("name" => "HomeController/{id}", "url" => "Front/HomeController@indexAction"),
        array("name" => "HomeController/AddSubscriber", "url" => "Front/HomeController@addSubscriberAction")
    )
);
