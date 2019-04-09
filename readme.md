<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## License

The Laravel framework is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Install
<a>[continuar](https://medium.com/@cvallejo/sistema-de-autenticación-api-rest-con-laravel-5-6-parte-5-2725f02ab4de)<a>

## Configuracion
_Es importante que verifiques la creación del acceso personal del cliente (más info: https://laravel.com/docs/5.6/passport#client-credentials-grant-tokens) a través del comando :_
``` php artisan passport:client --personal ```

## packages
avatar    https://github.com/laravolt/avatar
passport  https://laravel.com/docs/5.8/passport

### rutas
_Crear usuario_
```
http://acl-api.test/api/auth/signup 
```

_Iniciar session_
```
http://acl-api.test/api/auth/login
```

_Cerrar session_
```
http://acl-api.test/api/auth/logout
```
_Activar cuenta_
``` http://acl-api.test/api/auth/signup/activate/tokenrecebido ```

_Restablecer contrase;a_
``` http://acl-api.test/api/password/create ```
``` http://acl-api.test/api/password/find/TOKEN //buscar el token que se le envia al usuario ```
``` http://acl-api.test/api/password/create //si el token funciona entrega los datos del usuario ```

### Pruebas con Postman
_Para la correcta utilización de postman, hay que configurar las siguientes dos cabeceras:_

```
Content-Type: application/json
X-Requested-With: XMLHttpRequest
```
_Para crear usuario_
_se envian los valores que le corespondan_
```
  "name": "valor",
  "email": "valor",
  "password": "valor",
  "password_confirmation": "valor"
```
_a la ruta:_ ``` http://acl-api.test/api/auth/signup ```

_si el registro es satisfactorio le llegara un correo el cual tendra que validar el token_
``` http://acl-api.test/api/auth/signup/activate/tokenrecibido ```
_Para iniciar session_
_se envian los valores que le corespondan_
```
  {
    "email": "valor",
    "password": "valor",
    "remember_me": boolean
  }
  ```
  _a la ruta:_ ``` http://acl-api.test/api/auth/login ```
  _si el registro es satisfactorio se responde el token de autorizacion token_
  ```
  {
    "access_token": "eyJ0eXAiOiJKV1...",
    "token_type": "Bearer",
    "expires_at": "2020-04-09 15:37:13"
  }
  ```
  _para acceder a cualquier ruta en el sistema luego de estar autenticado hay hay que agregar en postman el header_
  ``` Authorization : "Bearer '+ el token de respueste del server'" ```

  _para cerrar session tenemos que te nener el header corrrecto y accedemos a la ruta_
  ``` http://acl-api.test/api/auth/logout ```
