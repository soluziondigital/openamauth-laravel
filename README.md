# OpenAM Authentication 

This is a provider for adding a OpenAM driver to your authentication system in Laravel 5

## Installation

To install the package run the following composer command 

``` bash
composer require maenbn/openamauth
```

You will also need to register the service provider by going into `config/app.php` and add the following to the `providers` key:
```php
Maenbn\OpenAmAuthLaravel\Providers\OpenAmServiceProvider::class,
```

## Configuration

You'll need to configure the package for your OpenAM server. First publish the vendor assets:

```bash
$ php artisan vendor:publish
```
which will create a `config/openam.php` file in your app where you can modify it to reflect 
your OpenAM server.

Finally make sure to change the value for the `driver` key to `openam` in `config/auth.php`.

### Eloquent model
There is also an option to use an Eloquent model as the user object for OpenAM authentication. This is useful if 
you want to authenticate against OpenAM but want to control authorisation within Laravel e.g. using 
[Entrust](https://github.com/Zizaco/entrust) package. 
 
Ideally the default ```App\User``` class found in a new install of Laravel is perfect for this. Modify the
`eloquentModel` key to refer to the Eloquent class you want and the `eloquentUid` key to store the OpenAM uid into your
user table column in the `config/openam.php` file e.g.

```php
'eloquentModel' => App\User::class,

'eloquentUidName' => 'username',
```

Finally, modify your Eloquent model to use the OpenAM `Authenicatable` trait and extend off the Laravel `Model` class
instead of the `Authenticable` class like below:

```php
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;
use Maenbn\OpenAmAuthLaravel\Authenticatable

class User extends Model
{
    use Notifiable, Authenticatable;
    ..........
```

## Middleware

If you require your app to set a cookie to hold the OpenAM token, you can utilise the middleware available in this 
package. Add it to your `app/Http/Kernel.php` as a middleware group:

```php
protected $middlewareGroups = [
    ...............
    \Maenbn\OpenAmAuthLaravel\Middleware\SetOpenAmCookie::class,
];
```

You have to also make sure you add your OpenAM cookie name into the `except` array found in the middleware 
`app/Http/Middleware/EncryptCookies.php` so the token value isn't encrypted as it will need to be validated during 
authentication attempts.

You can either hard code it or do the following in `app/Http/Middleware/EncryptCookies.php` making sure you 
import the `Closure` class into the middleware:

```php
namespace app\Http\Middleware;

use Closure;
use Illuminate\Cookie\Middleware\EncryptCookies as BaseEncrypter;

class EncryptCookies extends BaseEncrypter
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * @var array
     */
    protected $except = [
    ];

    public function handle($request, Closure $next)
    {
        $this->except[] = config('openam.cookieName');
        return parent::handle($request, $next);
    }
}
```

##Usage
Now your Auth driver is using OpenAM you will be able to use the Laravel's `Auth` class to authenticate users.

###Examples

```php
//Authenticating using the OpenAM TokenID from a cookie
Auth::attempt();
	
//Authenticating using user input
$input = Input::only('username', 'password');
Auth::attempt($input);

//Retrieving the OpenAM attributes of a logged in user
$user = Auth::user();
```