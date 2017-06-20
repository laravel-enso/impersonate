# Impersonate
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/fdc8f68f71064cd0b811462ef097879d)](https://www.codacy.com/app/laravel-enso/Impersonate?utm_source=github.com&utm_medium=referral&utm_content=laravel-enso/Impersonate&utm_campaign=badger)
[![StyleCI](https://styleci.io/repos/94622194/shield?branch=master)](https://styleci.io/repos/94622194)
[![Total Downloads](https://poser.pugx.org/laravel-enso/impersonate/downloads)](https://packagist.org/packages/laravel-enso/impersonate)
[![Latest Stable Version](https://poser.pugx.org/laravel-enso/impersonate/version)](https://packagist.org/packages/laravel-enso/impersonate)

Middleware for impersonating users.

### Installation Steps

1. Add `LaravelEnso\Impersonate\ImpersonateServiceProvider::class` to your providers list in config/app.php.

2. Use the `Model\Impersonate` trait in your User model

3. Use the `Controller\Impersonate` trait in your UserController model

4. Add in the `$routeMiddleware` array from App\Http\Kernel.php the "impersonate" middleware.

```
	protected $routeMiddleware = [
        'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
        ...
		'impersonate' => \LaravelEnso\Impersonate\app\Http\Middleware\Impersonate::class,
		...
	]
```

5. Add the following routes in `web.php`

```
Route::get('users/{user}/impersonate', 'UserController@impersonate')->name('impersonate');
Route::get('users/stopImpersonating', 'UserController@stopImpersonating')->name('stopImpersonating');
```
6. Apply the `impersonate` middleware in `web.php` file on the desired routes.

### Note

The laravel-enso/core package comes with this middleware included

### Contributions

... are welcome