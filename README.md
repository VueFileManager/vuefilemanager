### Documentation
[Read online documentation](https://vuefilemanager.com/docs/)

### Demo & dev preview links
* For visit demo version click here [demo.vuefilemanager.com](https://demo.vuefilemanager.com/) 
* For visit dev version click here [dev.vuefilemanager.com](https://dev.vuefilemanager.com/) (It's auto deployed dev branch. Can be unstable and not ready for production)

### Installation setup

Run these commands to install vendors:
```
composer install
```
```
npm install
```

Setup your database in .env and run this command:
```
php artisan setup:prod
```

It automatically:
* Migrate database
* Generate Application key
* Create Passport Encryption keys
* Create Password grant client
* Create Personal access client

Then, copy generated password grant client `Client ID`, `Client secret` and paste it to .env files here:
```
PASSPORT_CLIENT_ID=<your_passport_client_id>
PASSPORT_CLIENT_SECRET=<your_passport_client_secret>
```
For sending forgoten password request via email, fill your mail driver in .env

### Run Application
To start server on your localhost, run this command
```
php artisan serve
```

To compiles and hot-reloads for development, run this command
```
npm run hot
```

To compiles for production, run this command
```
npm run prod
```
That's all, happy coding! :tada: :tada: :tada:
