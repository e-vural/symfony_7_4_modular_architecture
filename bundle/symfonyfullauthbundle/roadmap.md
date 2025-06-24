Bu bundle içinde birçok şeyin birleşimi olacak. Composerdan dahil edilmeyecek. Manuel bir bundle olacak. Her projeye default versiyonu kurulup üzerinde değişiklik yapılabilecek.


1. JWT authentication https://github.com/lexik/LexikJWTAuthenticationBundle entegrasyonu
   2. Burada authentication işlemlerinin bizim kontrolümüzde olması önemli. Örneğin user auth olduktan sonra token içine istediğimiz değeri yazabilmeliyiz. Bunu daha önceki projede yapmıştık. Bunu yapmak için lexik auth tarafını overwrite etmek gerekebilir.
2. Refresh token entegrasyonu https://github.com/markitosgv/JWTRefreshTokenBundle
3. User Checker entegrasyonu https://symfony.com/doc/current/security/user_checkers.html
4. Command yazma 
5. User İşlemleri: (Bu işlemler rest api şeklinde olacak. Ama webview kısmıda olacak.)
4. Impersonate https://symfony.com/doc/current/security/impersonating_user.html
5. User İşlemleri: (Bu işlemler rest api şeklinde olacak. Ama webview kısmıda olacak.)
 - Register --> mail confirmation (https://github.com/scheb/2fa-email?tab=readme-ov-file )
 - Login --> Login link integration (https://symfony.com/doc/current/security.html#login-link)
 - Reset Password --> https://github.com/symfonycasts/reset-password-bundle
 - Change Password
6. Profile İşlemleri: (Rest api olarak yazıp. webview olacak)
- Show
- Edit
- Delete
- Image upload ( Burada image nereye yüklenecek kısmı devreye giriyor. Docker olacağı için uzak bir yere yüklenmeli. )