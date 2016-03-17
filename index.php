<?php
/* Cliente simples para consumo de serviços REST no projeto API.sistemas da UFRN.
 * 
 * Este cliente usa o "Light PHP wrapper for the OAuth 2.0" disponível em https://github.com/adoy/PHP-OAuth2
 */

require('Client.php');
require('GrantType/IGrantType.php');
require('GrantType/AuthorizationCode.php');

const CLIENT_ID     = 'SEU_CLIENT_ID';
const CLIENT_SECRET = 'SEU_CLIENT_SECRET';

const REDIRECT_URI           = 'URL_DE_RETORNO';
const AUTHORIZATION_ENDPOINT = 'URL_DE_AUTORIZACAO_DO_OAUTH';
const TOKEN_ENDPOINT         = 'URL_DE_RECUPERACAO_DE_TOKEN_DO_OAUTH';

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
    $response = $client->fetch('URL_DE_SERVICO_REST');

    // MOSTRANDO RETORNO DO SERVIÇO
    var_dump($response, $response['result']);
}
?>
