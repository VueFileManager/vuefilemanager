![logo](https://vuefilemanager.com/assets/images/vuefilemanager-horizontal-logo.svg)
# Private Cloud Storage Build on Laravel & Vue.js

## Contents

- [Installation](#installation)
    - [Server Requirements](#server-requirements)
    - [Installation](#installation)
    - [PHP Configuration](#php-configuration)
    - [Chunk Upload](#chunk-upload)
    - [Upgrade Guide](#upgrade-guide)
        - [Common Instructions](#common-instructions)
        - [Update from 1.7.10 to 1.7.11](#update-from-1710-to-1711)
        - [Update from 1.7.8 to 1.7.9](#update-from-178-to-179)
        - [Update from 1.7.x to 1.7.8](#update-from-17x-to-178)
        - [Update from 1.6.x to 1.7](#update-from-16x-to-17)
    - [Nginx Configuration](#nginx-configuration)
    - [Apache Configuration](#apache-configuration)
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
- SQLite
- Ctype
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- Tokenizer
- XML
- Exif

## Installation

#### 1. Upload files on your server
Upload project files to web root folder of your domain. It's mostly located in `html`, `www` or `public_html` folder name.

#### 2. Configure your web root folder
Configure your web server's document root to point to the public directory of the files you previously uploaded. For example, if you've uploaded the files in `html` folder, your domain root directory should be changed to `html/project_files/public` folder or anything else where domain root is in project `/public` directory. 

Please don't try go to `yourdomain.com/public` URL address, you will have issue to verify your purchase code, this is not correct domain root setup, you must do this in your webhosting settings panel.

![Domain Root](https://vuefilemanager.com/assets/images/domain-root.jpg)

#### 3. Check your .env file
Make sure `.env` file was uploaded. This type of file can be hidden in default.

#### 3.1 When you install from GitHub
When you download repository from GitHub, you have to rename your `.env.example` file to `.env`. Then run command below in your terminal to install vendors. Composer is required.
```
composer install
```

#### 4. Set write permissions
Set `755` permission (CHMOD) to these file and folders directory within all children subdirectories:

- /bootstrap/cache
- /storage
- /.env

#### 5. Open your application in your web browser
Then open your application in web browser. If everything works fine, you will be redirect to setup wizard installation process. 

At first step you have to verify your purchase code. **Subscription service with stripe payments is available only for Extended License.** If you can't verify your purchase code, check, if you did previously steps correctly.

#### 6. Follow setup wizard steps

That was the hardest part of installation proces. Please follow instructions in every step of Setup Wizard to successfully install VueFileManager.

#### 7. Set up Cron

Add the following Cron entry to your server. Just update your php path (if it's different) and project path:
```
* * * * *  /usr/local/bin/php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1

```

## PHP Configuration
There are several PHP settings good to know to setup before you try upload any file. Please set these values in your php.ini, we provide minimal setup for you. When you set `-1` then you set infinity limits.

```
memory_limit = 512M
upload_max_filesize = 128M
post_max_size = 128M
max_file_uploads = 50
max_execution_time = 3600
```

## Chunk & Multipart Upload
VueFileManager in default supporting chunk upload. Default chunk upload size is `128MB`. If you wish change this default value, go to your `.env` and change `CHUNK_SIZE` attribute.

When you use external storage, and upload large files, to prevent failing upload process make sure you have enough space in your application space and set higher `max_execution_time` in your php.ini to move your files to external storage. 

## Upgrade Guide

### Common Instructions
`Don't forget create backup of your database before make any changes in your production application. If you serve your files in local storage driver pay attention and don't delete your /storage folder!`

These instructions is applicable for all updates. Please follow this step:

- Just rewrite all project files with new excluded `/.env` file and `/storage` folder. These items must be preserved!

### Update from 1.7.10 to 1.7.11
Before upload new files to your hosting, log in to VueFileManager as Admin. After uploading new files on your webhosting, visit this url `your-domain.com/service/upgrade-database` for upgrading your database.

### Update from 1.7.8 to 1.7.9
After rewrited old files with new files, log in as admin to the app and go to `your-domain.com/service/upgrade-database`. This will upgrade your database on the background.

Add the following Cron entry to your server. Just update your php path (if it's different) and project path:
```
* * * * *  /usr/local/bin/php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1

```

### Update from 1.7.x to 1.7.8
For those who have installed VueFileManager via git or any other repository synchronization tool, dont't forget after updated code run `composer update` command to update your vendors.

### Update from 1.6.x to 1.7

For those, who purchase extended licence, place these lines at the end of your `/.env` file:
```
CASHIER_LOGGER=stack
CASHIER_CURRENCY=
STRIPE_KEY=
STRIPE_SECRET=
STRIPE_WEBHOOK_SECRET=
CASHIER_PAYMENT_NOTIFICATION=App\Notifications\ConfirmPayment
```

Then go to https://your-domain.com/upgrade and follow the setup wizard instructions.

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

After successfully installation via Setup Wizard, stop your artisan server, clear config cache and run your artisan server again:
```
php artisan config:clear
php artisan serve
```
*After any change in your .env you have to restart your artisan server to reload your config cache.*

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

## Supporting VueFileManager
Hi, we are trying make the best experience with VueFileManager. There is a lot things to do, and a lot of features we can make. 

But, it can't be done without you, development is more and more complicated and we have to hire new colleagues to help with it. There is couple way you can support us, and then, we support you with all great new features which can be. Thanks you for participating on this awesome software!

- [Buy me a Coffe](https://www.buymeacoffee.com/pepe)
- [One-time donation via PayPal](https://www.paypal.me/peterpapp)

## Security Vulnerabilities

If you discover a security vulnerability within this project, please send an e-mail to [peterpapp@makingcg.com](peterpapp@makingcg.com). All security vulnerabilities will be promptly addressed.



