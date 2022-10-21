<p align="center">
  <img src="https://raw.githubusercontent.com/antony382/roles-and-permission/master/public/images/logo.png" style="width: 15% !important;max-width: 20% !important;">
</p>

[![Latest Stable Version](http://poser.pugx.org/process-drive/laravel-cloud-translation/v)](https://packagist.org/packages/process-drive/laravel-cloud-translation) [![Total Downloads](http://poser.pugx.org/process-drive/laravel-cloud-translation/downloads)](https://packagist.org/packages/process-drive/laravel-cloud-translation) [![Latest Unstable Version](http://poser.pugx.org/process-drive/laravel-cloud-translation/v/unstable)](https://packagist.org/packages/process-drive/laravel-cloud-translation) [![License](http://poser.pugx.org/process-drive/laravel-cloud-translation/license)](https://packagist.org/packages/process-drive/laravel-cloud-translation) [![PHP Version Require](http://poser.pugx.org/process-drive/laravel-cloud-translation/require/php)](https://packagist.org/packages/process-drive/laravel-cloud-translation)


ProcessDrive laravel cloud translation
=============================================
  This pacakage is used for store your locale file in database and use. Then you can directly update and store languages. if you wants to create new language you can directly mention this package. it will translate and store the values.


Installation
============


Run this command in your terminal

```
composer require process-drive/laravel-cloud-translation
```



After Installation
==================



To set service provider to config/app.php file
```
'providers' => [
        ProcessDrive\LaravelCloudTranslation\CloudTranslationServiceProvider::class,
    ]
```
If you not added in job table in your project. you run this below command or refer this link : "https://laravel.com/docs/9.x/queues"



```
php artisan queue:table
```

Mention .env:
```
QUEUE_CONNECTION=database
```


Run the migration



```
php artisan migrate
````



Run this command in your terminal



```
php artisan trans:db
```
Then:
```
php artisan serve
```



```
Go to this link: "http://127.0.0.1:8000/translation/index"
```




License
=======
MIT





has context menu
