<?php
namespace Models;

require_once __DIR__ . '/../../vendor/autoload.php';

use Exception;
use Google_Client;
use Google_Service_Oauth2;

class Auth{
    private $client;

    public function __construct()
    {
        $this->client = new Google_Client();
    }

    /**
     * @throws \Google\Exception
     */
    function createGoogleClient() {
        $credentialsPath = __DIR__ . '/../../credentials.json';
        if (!file_exists($credentialsPath)) {
            throw new Exception("El archivo credentials.json no se encuentra en la ruta esperada.");
        }

        $credentials = json_decode(file_get_contents($credentialsPath), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error al decodificar credentials.json: " . json_last_error_msg());
        }

        $this->client->setApplicationName("Recipe Search");
        $this->client->setScopes([
            Google_Service_Oauth2::USERINFO_PROFILE,
            Google_Service_Oauth2::USERINFO_EMAIL,
        ]);
        $this->client->setAuthConfig($credentials);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');
        return $this->client;
    }

    function loginUrl() {
        return $this->client->createAuthUrl();
    }

    /**
     * @throws \Google\Service\Exception
     */
    function handleCallback() {
        if (!isset($_GET['code'])) {
            throw new Exception("El código de autenticación no está presente en la URL.");
        }

        $authCode = $_GET['code'];
        $accessToken = $this->client->fetchAccessTokenWithAuthCode($authCode);

        if (isset($accessToken['error'])) {
            throw new Exception("Error al obtener el token de acceso: " . $accessToken['error']);
        }

        $this->client->setAccessToken($accessToken);
        $oauth2 = new Google_Service_Oauth2($this->client);
        $userInfo = $oauth2->userinfo->get();

        //se guarda la sesion
        $_SESSION['user'] = [
            'google_id' => $userInfo->id,
            'email' => $userInfo->email,
            'nombre' => $userInfo->name,
            'picture' => $userInfo->picture
        ];
    }

}