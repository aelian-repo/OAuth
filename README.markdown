# CakePHP OAuth2 Server Plugin

Plugin do CakePHP que implementa autenticação por OAuth2, construido com base na lib [oauth2-php library][1]

## Ambiente
[CakePHP 2.x](http://cakephp.org/)

## Instalação

### Baixe o Plugin

Clone o projeto na pasta `/cake/plugins`. 

```
cd ~/cake/plugins
git clone git@github.com:aelian-repo/OAuth.git OAuth
```

### Popule o database

Rode o `Config/Schema/schema.sql` no database do app para criar as tabelas necessárias.

### Carregue no App

Carregue o plugin no bootstrap do app

```PHP
CakePlugin::loadAll(array(
	'OAuth' => array('routes' => true)
));
```

E inclua o componente no Controller que utilizará o OAuth:

```PHP
public $components = array(
    /**
     * substituir userModel pelo model que vai identificar o usuário no app. Ex: Empresa, Orgao, Singular...
     */
    'OAuth.OAuth' => array('authenticate' => array('userModel' => 'Singular')), 
);
```

## Como funciona

### Crie Client para obter autorização

Para criar um Client automaticamente, basta acessar a URL:
* `/[APP]/api/v1/oauth/add/25`

Retorna:
```PHP
Array(
    [Client] => Array(
        [redirect_uri] => localhost
        [user_id] => 25
        [client_id] => NWQ2OTI4ODBkYTMyNDI4
        [client_secret] => ad5981ca4fd6dea30e9dbef521a2ef5decb373fe
    )
)
```

Ou chamar o método do Component no seu Controller:

```PHP
$client = $this->OAuth->Client->add('http://localhost');
```

Ao criar o Client, é gerado o *client_id* e *client_secret* que deverão ser fornecidos ao App / Serviço que solicitará a autenticação.

### Faça a autenticação

1. Faça um GET para receber o código de Autorização
 * `/[APP]/api/v1/oauth/authorize?response_type=code&client_id=xxxxxxx`

Retorna:
```PHP
Array(
    [code] => 26f48d0bef98313499af7b527866621457bf4d8c
)
```
 
2. Faça um GET para receber o Access Token
 * `/[APP]/api/v1/oauth/token?grant_type=authorization_code&code=26f48d0bef98313499af7b527866621457bf4d8c&client_id=xxxxxxxxx&client_secret=xxxxxxxx`

Retorna:
```PHP
Array(
    [access_token] => 7b7148a96e09c549e6838e62e57f527289c3af5d
    [expires_in] => 60
    [token_type] => bearer
    [refresh_token] => 516dd97b520eeb6c6261c5a087403b41430b649a
)
```

### Acesse o Recurso desejado

Faça o seu GET ou POST para o recurso desejado utilizando o Access Token adquirido na autenticação
 * `/[APP]/api/v1/meu_recurso?access_token=7b7148a96e09c549e6838e62e57f527289c3af5d`

## Exemplos

Exemplos de como criar suas *Routes* do REST Server:
[Config/routes.php](https://github.com/aelian-repo/OAuth/blob/master/Config/routes.php)

Exemplo comentado de REST Server:
[Controller/ServerController.php](https://github.com/aelian-repo/OAuth/blob/master/Controller/ServerController.php)

Exemplo comentado de REST Client:
[Controller/ClientController.php](https://github.com/aelian-repo/OAuth/blob/master/Controller/ClientController.php)

### Testes para demonstrar funcionamento:

Chame a URL para simular a ação desejada:

 * `/[APP]/api/v1/client/index`
 * `/[APP]/api/v1/client/view/1`
 * `/[APP]/api/v1/client/edit/1`
 * `/[APP]/api/v1/client/delete/1`

## Para se aprofundar

Documentação adicional sobre OAuth2:
* [Google](https://developers.google.com/accounts/docs/OAuth2) 
* [Facebook](http://developers.facebook.com/docs/authentication/) 
* [in the official spec](http://tools.ietf.org/html/draft-ietf-oauth-v2-23)
* [Authorization Code Grant](http://tools.ietf.org/html/draft-ietf-oauth-v2-23#section-4.1)
* [Refresh Token Grant](http://tools.ietf.org/html/draft-ietf-oauth-v2-23#section-6)
* [Resource Owner Password Credentials Grant](http://tools.ietf.org/html/draft-ietf-oauth-v2-23#section-4.3) 

[1]: https://github.com/quizlet/oauth2-php

O projeto que originou esse plugin esta matido agora em https://github.com/uafrica/oauth-server.