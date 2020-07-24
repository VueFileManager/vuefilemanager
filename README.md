![logo](https://vuefilemanager.com/assets/images/vuefilemanager-horizontal-logo.svg)
# Private Cloud Storage Build on Laravel & Vue.js

## Supporting VueFileManager
Hi, we are trying make the best experience with VueFileManager. There is a lot things to do, and a lot of features we can make. 

But, it can't be done without you, development is more and more complicated and we have to hire new colleagues to help with it. There is couple way you can support us, and then, we support you with all great new features which can be. Thanks!

- [Become a backer or sponsor on Patreon](https://www.patreon.com/vuefilemanager)
- [One-time donation via PayPal](https://www.paypal.me/peterpapp)
- [Purchase Licence on CodeCanyon](https://codecanyon.net/item/vue-file-manager-with-laravel-backend/25815986)

## Contents

- [Installation](#installation)
    - [Server Requirements](#server-requirements)
    - [Installation](#installation)
    - [PHP Configuration](#php-configuration)
    - [Nginx Configuration](#nginx-configuration)
    - [Apache Configuration](#apache-configuration)
    - [Recover Failed Installation](#installation-failed)
    - [Update VueFileManager from 1.6.x to 1.7 ](#update-vuefilemanager-from-16x-to-17)
- [Payments](#payments)
    - [Get your active plans](#get-your-active-plans)
    - [Manage Failed Payments](#manage-failed-payments)
    - [Tax Rates](#tax-rates)
- [Developers](#developers)
    - [Running development environment on your localhost](#running-development-environment-on-your-localhost)
    - [Supported Storages](#supported-storages)
    - [How to Create New Language](#how-to-create-new-language)
- [Others](#others)
    - [Changelog](#changelog)
    - [GitHub Repository](#github-repository)
    - [Support](#support)
    - [Security Vulnerabilities](#security-vulnerabilities)


# Installation
## Server Requirements


**For running app make sure you have installed:**

- PHP >= 7.2.5 version
- MySQL 5.6+
- Nginx or Apache



**These PHP Extensions are required:**

- GD
- BCMath
- PDO
- Ctype
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- Tokenizer
- XML

## Installation

Copy project files to web root folder of your domain. It's mostly located in `html`, `www` or `public_html` folder name.

Then change your **public directory** for your domain. It should be changed to /public directory in the app.

Make sure `.env` file was uploaded. This type of file can be hidden in default.

When you download repository from GitHub, you have to rename your `.env.example` file to `.env`. Then run command below in your terminal to install vendors. Composer is required.
```
composer install
```

Set `755` permission (CHMOD) to these file and folders directory within all children subdirectories:

- /bootstrap/cache
- /storage
- /.env

Then open your application in web browser. If everything works fine, you will be redirect to setup wizard installation process. 

At first step you have to verify your purchase code. **Subscription service with stripe payments is available only for Extended License.**

That was the hardest part of installation proces. Please follow instructions in every step of Setup Wizard to successfully install VueFileManager.

## PHP Configuration
There are several PHP settings good to know to setup before you try upload any file. Please set these values in your php.ini, we provide minimal setup for you. When you set `-1` then you set infinity limits.

```
memory_limit = 512M
upload_max_filesize = 1024M
post_max_size = 1024M
max_file_uploads = 50
```

## Nginx Configuration
If you running VueFileManager undex Nginx, don't forget set this value in your `nginx.conf` file:
```
http {
    client_max_body_size 1024M;
}
```

And example Nginx config for your domain:
```
server {
    listen 80;
    listen [::]:80;
    
    # Log files for Debugging
    access_log /var/log/nginx/laravel-access.log;
    error_log /var/log/nginx/laravel-error.log; 
    
    # Webroot Directory for Laravel project
    root /var/www/vuefilemanager/public;
    index index.php index.html index.htm;
    
    # Your Domain Name
    server_name example.com;
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    # PHP-FPM Configuration Nginx
    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/run/php/php7.3-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## Apache Configuration
Make sure you have enabled mod_rewrite. There is an example config for running VueFileManager under apache:

```
<VirtualHost example.com:80>
    DocumentRoot /var/www/vuefilemanager/public
    ServerName example.com

    <Directory "/var/www/vuefilemanager/public">
        AllowOverride All
        allow from all
        Options +Indexes
        Require all granted
    </Directory>
    
    RewriteEngine on
    RewriteCond %{SERVER_NAME} =example.com
    RewriteRule ^ https://%{SERVER_NAME}%{REQUEST_URI} [END,NE,R=permanent]
</VirtualHost>
```

## Installation Failed

What to do when installation fail and you can't continue, at first, try to fix issue why installation fail. Probably missing PHP extension or permissions wasn't set correctly.

At worst scenarios, to reset Setup Wizard, delete all tables in your previously created database, delete content of `/storage/framework/cache`. Then replace content in your `.env` file from `.env.example` file. 

After these steps, installation will be reseted.

## Update VueFileManager from 1.6.x to 1.7
`Don't forget create backup of your database and storage before make any changes in your production application.`

For those, who purchase extended licence, place these lines at the end of your `/.env` file:
```
CASHIER_LOGGER=stack
CASHIER_CURRENCY=
STRIPE_KEY=
STRIPE_SECRET=
STRIPE_WEBHOOK_SECRET=
CASHIER_PAYMENT_NOTIFICATION=App\Notifications\ConfirmPayment
```

Then follow this steps:

- Make sure you have PHP >= 7.2.5 version
- Make a backup of the .env config file located on your server.
- Upload and replace all the files on your server with what's inside the app folder.
- Restore your `.env` config file on your server.
- Go to https://your-domain.com/upgrade and follow the setup wizard instructions.

# Payments
VueFileManager is packed with **Stripe** payment options. To configure Stripe, you will be asked in Setup Wizard to set up. Or, if you skip this installation process, you will find stripe set up in you admin `Dashboard / Settings / Payments`.

## Get your active plans
Would you like to get your subscription plans for your custom front-end page? Create GET request and get all your active plans:
```
GET /api/public/pricing
```

## Manage Failed Payments
VueFileManager manage failed payments with additional email notification. But, there is more you can do for better User Experience. There is some additionals option in Stripe, look on [prevent failed payments](https://dashboard.stripe.com/settings/billing/automatic).

## Tax Rates
You are able to manage tax rates. When adding a new tax rate, if no Region is specified, the tax rate will apply to everyone. Add a [ISO 3166-1 alpha-2 country code](https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2#Officially_assigned_code_elements) to the Region field if you wish to apply taxes per country.

Just log in to your stripe dashboard, and you will find taxes under `Dashboard / Products / Tax Rates`.

# Developers
## Running development environment on your localhost

When you download repository from GitHub, you have to rename your `.env.example` file to `.env`. Then run command below in your terminal to install vendors. Composer is required.
```
composer install
```

Set your `APP_ENV` to local mode, in default, it's in production mode.
```
APP_ENV=local
```

Also, to debug application, set `APP_DEBUG` on true:
```
APP_DEBUG=true
```

To start server on your localhost, run command below. Then go to your generated localhost URL by terminal, and follow Setup Wizard steps to configure VueFileManager.
```
php artisan serve
```

To develop your Vue front-end, you have to install npm modules by this command:
```
npm install
```

To compiles and hot-reloads for front-end development. Then run this command:
```
npm run hot
```

To compiles for production build, run this command
```
npm run prod
```

## Supported Storages
VueFileManager support these storages for your files:

- [Amazon Web Services S3](https://aws.amazon.com/s3/)
- [Digital Ocean Spaces](https://www.digitalocean.com/products/spaces/)
- [Object Cloud Storage by Wasabi](https://wasabi.com/)
- [Backblaze B2 Cloud Storage](https://www.backblaze.com/b2/cloud-storage.html)
- Your local disk

In case of installation process, you will be able to set storage driver and credentials. After this, you can change your credentials later in `/.env` file.

To set or change your storage driver, you have to edit `FILESYSTEM_DRIVER` in your `/.env` file. Supported drivers are `s3`, `spaces`, `wasabi`,`backblaze` or `local`:
```
FILESYSTEM_DRIVER=local
```
Then you can find corresponding credentials options for your storage driver like key, secret, region in `/.env` file.


## How to Create New Language
VueFileManager front-end support i18n standard for localization. This mean, you can translate app to any language

### How to create translation for Vue Front-End
Go to `/resources/js/i18n/lang` And make copy of `en.json` and rename it to your local name (eg: Slovak language has 'sk' shortcut, it means `sk.json`). If you have created your copy, then feel free to translate this file.

Open `/resources/js/i18n/index.js` import your new language and assign it to languages object:

```
import Vue from 'vue';
import VueI18n from 'vue-i18n';

import en from './lang/en.json'
import sk from './lang/sk.json'

Vue.use(VueI18n);

const i18n = new VueI18n({
    locale: config.locale,
    messages: Object.assign({
        en,
        sk
    }),
});

export default i18n;
```
After this, you have to compile language to your application code via this command in your terminal:
```
npm run prod
```

### How to Create Translation for Laravel Back-End
Go to `/resources/lang/` And make copy of `en` folder and rename it to your local name (eg: Slovak language has 'sk' shortcut, it means `sk`). If you have created your copy, then feel free to translate this file.

### Set locale
To set your locale in app, go to `/config/app.php` and set your locale string in `locale` option:

```
'locale' => 'YOUR_LOCALE',
```

**Small hint:** We use for translating localizations this awesome software, [check it](https://www.codeandweb.com/babeledit). 

# Others
## Changelog

Refer to the [Changelog](https://vuefilemanager.com/changelog) for a full history of the project.

## GitHub Repository
[Join our GitHub repository](https://vuefilemanager.com/github-access) to submit your issues or suggestions, track VueFileManager progress and get new updates as fast as possible.

## Support

The following support channels are available at your fingertips:

- [CodeCanyon support message](https://codecanyon.net/item/vue-file-manager-with-laravel-backend/25815986/support)
- [GitHub repository](https://vuefilemanager.com/github-access)

## Security Vulnerabilities

If you discover a security vulnerability within this project, please send an e-mail to [peterpapp@makingcg.com](peterpapp@makingcg.com). All security vulnerabilities will be promptly addressed.



