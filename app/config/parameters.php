<?php
$container->setParameter('secret', '1c3b83b3e7c4c9e28dd8c5d8e51e53769c690209');
$container->setParameter('locale', 'fr');

// DB
$container->setParameter('database.driver', 	getenv('SYMFONY_APPX_BD_DRIVER'));
$container->setParameter('database.host', 		getenv('SYMFONY_APPX_BD_HOST'));
$container->setParameter('database.port', 		getenv('SYMFONY_APPX_BD_PORT'));
$container->setParameter('database.name', 		getenv('SYMFONY_APPX_BD_NAME'));
$container->setParameter('database.user', 		getenv('SYMFONY_APPX_BD_USER'));
$container->setParameter('database.password', getenv('SYMFONY_APPX_BD_PASS'));

// Courriel
$container->setParameter('mailer.transport', 'gmail');
$container->setParameter('mailer_user', 		 'appxapi@gmail.com');
$container->setParameter('mailer_password',  'monpassappxapi');

// JWT
$container->setParameter('token_ttl',  86400);  // 24h=86400   7j=604800
$container->setParameter('private_key_path',  '%kernel.root_dir%/var/jwt/private.pem');
$container->setParameter('public_key_path',  '%kernel.root_dir%/var/jwt/public.pem');
$container->setParameter('pass_phrase',  '1234');

// Linux : Ajouter les commandes suivantes à votre fichier ~/.bashrc.
/*
export SYMFONY_APPX_BD_HOST="127.0.0.1"
export SYMFONY_APPX_BD_NAME="appx"
export SYMFONY_APPX_BD_USER="root"
export SYMFONY_APPX_BD_PORT="3306"
export SYMFONY_APPX_BD_PASS=""
export SYMFONY_APPX_BD_DRIVER="pdo_mysql"
*/