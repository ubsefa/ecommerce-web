<?php

namespace Controllers\Admin;

class LoginController extends \Libraries\Controller
{
    public function indexAction()
    {
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);
        $validation = new \Libraries\Validation;
        $email = '';
        $validation->name('email')->value($data["email"] || $_POST["email"])->pattern('email', 'Hatalı e-posta!')->required('Boş bırakamazsını!');
        if ($validation->isSuccess()) {
            echo "Validation ok!";
        } else {
            var_dump($validation->getErrors());
        }
        $this->render("pages/admin/signin.html",
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

    public function signInAction()
    {
        $response = new \Models\ResponseModel;
        $authRepository = new \Repositories\AuthRepository;
        $json = file_get_contents("php://input");
        $data = json_decode($json, true);
        $result = $authRepository->signIn($data["userName"], $data["password"]);
        if ($result) {
            $response->success = true;
            $tokenId = \Helpers\JWTHelper::urlSafeB64Encode(SECURITY_SECRET_IV);
            $issuedAt = time();
            $notBefore = $issuedAt + 10;
            $expire = $notBefore + (isset($data["remember"]) && $data["remember"] == 1 ? (60 * 60 * 24 * 3650) : (60 * 60 * 24));
            $serverName = GLOBAL_URL;
            $result->token = array(
                "iat" => $issuedAt,
                "jti" => $tokenId,
                "iss" => $serverName,
                "nbf" => $notBefore,
                "exp" => $expire,
                "data" => array(
                    "id" => $result->id,
                ),
            );
            $response->result = openssl_encrypt(\Helpers\JWTHelper::encode($result->token, SECURITY_SECRET_KEY), SECURITY_CIPHER_METHOD, SECURITY_SECRET_KEY, 0, \Helpers\JWTHelper::urlSafeB64Decode($tokenId)) . "::" . bin2hex(\Helpers\JWTHelper::urlSafeB64Decode($tokenId));
            if (isset($_COOKIE["token"])) {
                unset($_COOKIE["token"]);
                setcookie("token", "", time() - (60 * 60 * 24), "/");
            }
            setcookie("token", $response->result, $expire, "/");
            unset($response->message);
        } else {
            $response->success = false;
            $response->message = \Helpers\I18NHelper::getAdminKeyword("incorrectUserNameOrPassword");
        }
        http_response_code(200);
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
    }
}
