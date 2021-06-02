# Dev-transaction API-RESTFULL
Aplicação que simula transações entre usuários. Para instalar a aplicação é necessário que você tenha os pré requisitos:
- docker
- docker-compose

Para utilizar, digite o seguinte comando na pasta download do projeto:

    $ docker-compose up -d

# Rotas

### Unauthorized API's
  
  - POST /api/application/register

      Payload:
        {
            "name": "string",
            "password": "string"
        }
  
### Login
  
  - POST /api/auth/login
  
      Payload:
        {
            {
                "password": "string",
                "id": "string"
            }
        }


### Authorized API's

    - Header de exemplo
        {
            {"key":"Content-Type","value":"application/json","description":"","type":"text","enabled":true},
            {"key":"Accept","value":"application/json","description":"","type":"text","enabled":true},
            {"key":"Authorization","value":"bearer token-retornado","description":"","type":"text","enabled":true}
        }
  
      - Na chave Authorization no lugar do token-retornado deve ser inserido o token retornado na resposta da api de login. Este token deve ser enviado em todas as rotas authorized.

  - GET /api/auth/refresh

  - POST /api/auth/logout

  - POST /api/user/register

      Payload:
          {
              "name": "string",
              "password": "string",
              "email": "string",
              "document_type": "cpf" ou "cnpj",
              "cpf": "string"
          }

      O campo document_type define o tipo de usuário que será cadastrado, se for utilizado o cpf será cadastrado um usuário pessoa física e com o cnpj será cadastrado um usuário pessoa jurídica.

  - POST /api/transaction

      Payload:
          {
              "value": numeric,
              "payer_wallet_id": "string",
              "payee_wallet_id": "string"
          }






