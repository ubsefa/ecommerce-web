<?php

namespace Libraries;

class Controller
{
    public $model = array();

    public function __construct()
    {
        $controller = explode("\\", get_class($this));
        $mobile = preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
        $admin = $controller[1] == "Admin" ? true : false;
        $controller = $controller[count($controller) - 1];
        $settingRepository = new \Repositories\SettingRepository;
        $this->model["settings"] = $settingRepository->getById(1);
        if ($controller != "LoginController") {
            if (in_array($controller, $admin ? USER_ADMIN_ROLES : USER_FRONT_ROLES)) {
                if (isset($_COOKIE["token"])) {
                    $token = explode("::", $_COOKIE["token"]);
                    $token = openssl_decrypt($token[0], SECURITY_CIPHER_METHOD, SECURITY_SECRET_KEY, 0, hex2bin($token[1]));
                    $token = \Helpers\JWTHelper::decode($token, SECURITY_SECRET_KEY);
                    $userRepository = new \Repositories\UserRepository;
                    $user = $userRepository->getById($token->data->id);
                    $this->model["user"] = $user;
                    $roles = $user->admin ? USER_ADMIN_ROLES : USER_FRONT_ROLES;
                    if (in_array($controller, $this->model["user"]->roles) == 0 && in_array($controller, $roles)) {
                        if ($mobile) {
                            http_response_code(401);
                            exit;
                        } else {
                            $this->redirect(GLOBAL_URL . ($admin ? "/Admin/401" : "/401"));
                        }
                    }
                } else {
                    if ($token = $this->getBearerToken()) {
                        $token = explode("::", $token);
                        $token = openssl_decrypt($token[0], SECURITY_CIPHER_METHOD, SECURITY_SECRET_KEY, 0, hex2bin($token[1]));
                        $token = \Helpers\JWTHelper::decode($token, SECURITY_SECRET_KEY);
                        $now = time();
                        if ($token->exp > $now) {
                            $userRepository = new \Repositories\UserRepository;
                            $user = $userRepository->getById($token->data->id);
                            $this->model["user"] = $user;
                            $roles = $user->admin ? USER_ADMIN_ROLES : USER_FRONT_ROLES;
                            if (in_array($controller, $this->model["user"]->roles) == 0 && in_array($controller, $roles)) {
                                if ($mobile) {
                                    http_response_code(401);
                                    exit;
                                } else {
                                    $this->redirect(GLOBAL_URL . ($admin ? "/Admin/401" : "/401"));
                                }
                            }
                        } else {
                            if ($mobile) {
                                http_response_code(401);
                                exit;
                            } else {
                                $this->redirect(GLOBAL_URL . ($admin ? "/Admin/Login" : "/Login"));
                            }
                        }
                    } else {
                        if ($mobile) {
                            http_response_code(401);
                            exit;
                        } else {
                            $this->redirect(GLOBAL_URL . ($admin ? "/Admin/Login" : "/Login"));
                        }
                    }
                }
            }
        }
    }

    private function getAuthorizationHeader()
    {
        $headers = null;
        if (isset($_SERVER["Authorization"])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER["HTTP_AUTHORIZATION"])) {
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists("apache_request_headers")) {
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_combine(array_map("ucwords", array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders["Authorization"])) {
                $headers = trim($requestHeaders["Authorization"]);
            }
        }
        return $headers;
    }

    private function getBearerToken()
    {
        $headers = getAuthorizationHeader();
        if (!empty($headers)) {
            if (preg_match("/Bearer\s(\S+)/", $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    public function render($view, $model = array())
    {
        if (GLOBAL_USE_TEMPLATE_ENGINE) {
            \Helpers\TemplateHelper::clearCache();
            \Helpers\TemplateHelper::view($view, $model);
        } else {
            if (file_exists($file = "../Application/Views/{$view}.php")) {
                extract($model);
                ob_start();
                require_once $file;
                echo ob_get_clean();
            } else {
                http_response_code(404);
                exit;
            }
        }
    }

    public function redirect($path)
    {
        header("Location: {$path}");
    }
}
