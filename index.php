<?php
/* Cliente simples para consumo de serviços REST no projeto API.sistemas da UFRN.
 * 
 * Este cliente usa o "Light PHP wrapper for the OAuth 2.0" disponível em https://github.com/adoy/PHP-OAuth2
 */

require('Client.php');
require('GrantType/IGrantType.php');
require('GrantType/AuthorizationCode.php');

const CLIENT_ID     = 'client_id';
const CLIENT_SECRET = 'client_secret';

const REDIRECT_URI           = 'http://localhost/my-php-app';
const AUTHORIZATION_ENDPOINT = 'http://apitestes.info.ufrn.br/authz-server/oauth/authorize';
const TOKEN_ENDPOINT         = 'http://apitestes.info.ufrn.br/authz-server/oauth/token';

$client = new OAuth2\Client(CLIENT_ID, CLIENT_SECRET);

if (!isset($_GET['code']))
{
    // RECUPERANDO O "AUTHORIZATION_CODE"
    $auth_url = $client->getAuthenticationUrl(AUTHORIZATION_ENDPOINT, REDIRECT_URI);
    header('Location: ' . $auth_url);
    die('Redirect');
}
else
{
    // RECUPERANDO O "ACCESS_TOKEN"
    $params = array('code' => $_GET['code'], 'redirect_uri' => REDIRECT_URI);
    $response = $client->getAccessToken(TOKEN_ENDPOINT, 'authorization_code', $params);
	
    // INFORMANDO O "ACCESS_TOKEN" RCUPERADO AO CLIENTE
    $client->setAccessToken($response['result']['access_token']);
	
    // CONSUMINDO SERVIÇO
    $response = $client->fetch('http://apitestes.info.ufrn.br/ensino-services/services/consulta/listavinculos/usuario');

    // MOSTRANDO RETORNO DO SERVIÇO
    var_dump($response, $response['result']);
}
?>
