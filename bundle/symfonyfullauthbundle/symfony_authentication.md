## JSON_LOGIN, LEXIK, REFRESH TOKEN BAĞLANTISI

```
    login:
      pattern: ^/api/login
      stateless: true
      json_login:
        check_path: /api/login_check
        success_handler: lexik_jwt_authentication.handler.authentication_success
        failure_handler: lexik_jwt_authentication.handler.authentication_failure
```

- Buradaki herşey symfony kendi döngüsüne aittir. Lexik ile alakalı bir durum değildir. Login authentication işlemi vendordaki symfony componentlerinde yapılmaktadır. 
- Burada lexik kendi success handler ve failure servislerini dahil etmiştir. Symfony check_path işlemerini yapar ve duruma göre success veya failure servislerini çağırır.Lexik event ile JWT token oluşturup geriye dönmektedir.
- Refresh token işlemlerini yapan bundle ise lexik ile entegre çalışacak şekilde yapılmıştır. Lexik olmadan çalışmaz. **AuthenticationSuccessEvent.php**  eventini araya girip manupile etmekte ve kendi refresh token değerini dönen response içine eklemektedir.
- Bu alanlar karışıktır. Anlamak için vendor dosyalarına bakmak gerekir. Ancak ana döngü ve kullanması basittir.

## JWT AUTHENTICATION