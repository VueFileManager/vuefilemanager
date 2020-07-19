# Private Cloud Storage Build on Laravel & Vue.js

## Contents

- [Server Requirements](#server-requirements)
- [Installation](#installation)
- [Installation Failed](#installation-failed)
- [Update VueFileManager](#update-vuefilemanager)
- [For Developers](#for-developers)
- [Supported Storages](#supported-storages)
- [How to Create New Language](#how-to-create-new-language)
- [Changelog](#changelog)
- [GitHub Repository](#github-repository)
- [Support](#support)
- [Security Vulnerabilities](#security-vulnerabilities)

## Server Requirements


** For running app make sure you have installed:**

- PHP >= 7.2.5 version
- MySQL 5.6+
- Nginx or Apache



** These PHP Extensions are required:**

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

Set `755` permission (CHMOD) to these file and folders directory within all children subdirectories:

- /bootstrap/cache
- /storage
- /.env

Then open your application in web browser. If everything works fine, you will be redirect to setup wizard installation process. 

At first step you have to verify your purchase code. **Subscription service with stripe payments is available only for Extended License.**

That was the hardest part of installation proces. Please follow instructions in every step of Setup Wizard to successfully install VueFileManager.

## Installation Failed

What to do when installation fail and you can't continue, at first, try to fix issue why installation fail. Probably missing PHP extension or permissions wasn't set correctly.

At worst scenarios, to reset Setup Wizard, delete all tables in your previously created database, delete content of `/storage/framework/cache`. Then replace content in your `.env` file from `.env.example` file. 

After these steps, installation will be reseted.

## Update VueFileManager
Don't forget create backup of your database and storage before make any changes in your production application.

- Make a backup of the .env config file located on your server.
- Upload and replace all the files on your server with what's inside the app folder.
- Restore your `.env` config file on your server.
- Go to https://your-domain.com/upgrade and follow the setup wizard instructions.

## For Developers
Installation process on your localhost is the same. But, there are some good hints.

After successfully installation with setup wizard, you have to set your `APP_ENV` to local mode, in default, it's in production mode.
```
APP_ENV=local
```

Also, to debug application, set `APP_DEBUG` on true:
```
APP_DEBUG=true
```

To start server on your localhost, run this command
```
php artisan serve
```

To compiles and hot-reloads for development. Then run this command:
```
npm run hot
```

To compiles for production, run this command
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



