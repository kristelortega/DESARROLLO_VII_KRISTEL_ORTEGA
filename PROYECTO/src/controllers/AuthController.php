<?php
require_once __DIR__ . '/../../vendor/autoload.php';

function createGoogleClient() {
    $credentials = json_decode(file_get_contents(__DIR__ . '/../../credentials.json'), true);
    $client = new Google_Client();
    $client->setApplicationName("Recetas OK");
    $client->setScopes([
        Google_Service_Oauth2::USERINFO_PROFILE,
        Google_Service_Oauth2::USERINFO_EMAIL,
        // Agrega otros scopes si necesitas acceder a más información
    ]);
    $client->setAuthConfig($credentials);
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');
    return $client;
}

function loginUrl($client) {
    return $client->createAuthUrl();
}

function handleCallback($client) {
    $authCode = $_GET['code'];
    $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
    $client->setAccessToken($accessToken);

    $oauth2 = new Google_Service_Oauth2($client);
    $userInfo = $oauth2->userinfo->get();

    // Guarda la información del usuario en la base de datos o en la sesión
    $_SESSION['user'] = [
        'google_id' => $userInfo->id,
        'email' => $userInfo->email,
        'nombre' => $userInfo->name,
    ];
}