<?php

// Global Definitions
define("GLOBAL_APP_NAME", "E-Commerce");
define("GLOBAL_BASE_FOLDER", "ecommerce-web");
define("GLOBAL_URL", "http://192.168.64.2" . (!empty(GLOBAL_BASE_FOLDER) ? "/" . GLOBAL_BASE_FOLDER : ""));
define("GLOBAL_ADMIN_THEME_URL", GLOBAL_URL . "/theme/admin");
define("GLOBAL_FRONT_THEME_URL", GLOBAL_URL . "/theme/front");
define("GLOBAL_ADMIN_DEFAULT_LANGUAGE", "tr");
define("GLOBAL_FRONT_DEFAULT_LANGUAGE", "tr");
define("GLOBAL_USE_TEMPLATE_ENGINE", true);
define("GLOBAL_INSTALLED", true);
