### - Create bundle directory and fetch codes
You need to create a directory named " **bundle** ". You will download or pull that bundle codes in this directory.

###  - Register Composer.json
Add this line in the composer.json PSR-4 autoload

    "SymfonyFullAuthBundle\\": "bundle/symfonyfullauthbundle/src"

**Sample**

    "autoload": {
        "psr-4": {
            "App\\": "src/",
            "SymfonyFullAuthBundle\\": "bundle/symfonyfullauthbundle/src"
        }
    },

### Dump Autoload
    composer dump-autoload -o

### - Register Bundle
Add this line in the bundles.php 

    SymfonyFullAuthBundle\SymfonyFullAuthBundle::class => ['all' => true]

###  - Register Routes
Add this line in the routes.yaml
    
    symfony_full_auth_bundle:
        resource: "@SymfonyFullAuthBundle/Resources/config/routing.yaml"
        prefix: "/{_locale}/api"
        defaults:
            _locale: "tr"
        requirements:
            _locale: "tr|en"


###  - doctrine.yaml Revision
**Add Bundle Entity to config/packages/doctrine.yaml.You can configure according to entity structure**

    SymfonyFullAuthBundle:
        type: attribute
        is_bundle: true
        dir: '/Entity'
        prefix: 'SymfonyFullAuthBundle\Entity'
        alias: auth

###  - Install NelmioApiDocBundle
**Exec to NelmioApiDocBundle install command on the project side**

    composer require nelmio/api-doc-bundle



###  - Install Symfony/Uid Component
**Exec to Symfony/Uid Component install command on the project side**

    composer require symfony/uid


###  - Reset Password Configuration
**Your reset_password.yaml must be like this**

    symfonycasts_reset_password:
        request_password_repository: SymfonyFullAuthBundle\Repository\ResetPassword\ResetPasswordRequestRepository
        lifetime: 3600
        throttle_limit: 3600
        enable_garbage_collection: true



###  - Refresh Token Configuration
####  1- Install Bundle 
    composer require doctrine/orm doctrine/doctrine-bundle gesdinet/jwt-refresh-token-bundle

####  2- Set Configurations
**Your gesdinet_jwt_refresh_token.yaml must be like this.**

    gesdinet_jwt_refresh_token:
        refresh_token_class: SymfonyFullAuthBundle\Entity\RefreshToken\RefreshToken
        ttl: 2592000 # 20 sn & Default : 1 ay -> 2592000 sn
        ttl_update: true
        firewall: api
        token_parameter_name: refreshToken
        return_expiration: true
        return_expiration_parameter_name: refreshTokenExpiration
        logout_firewall: refresh_token_logout
