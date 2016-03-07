# hydrotron

Hydrotron aids in hydrating PHP objects

## Installation

```text
composer install mrkrstphr/hydrotron
```

## Usage

When an array key exists, call one or more callbacks:

```php
$hydro = new Hydrotron(['foo' => 'bar']);
$hydro->when('foo', $callback, [$object, 'method']);
```

When an array key exists, instantiate an object, and call a series of callbacks with that object:

```php
$hydro = new Hydrotron(['foo' => 'bar', 'bizz' => 'buzz']);
$hydro->instantiateWhen('foo', MyClass::class, $callback);
```

`instantiateWhen()` uses [Instantiator](https://github.com/mrkrstphr/instantiator), which will 
analyze the classes constructor arguments and pass the values of any keys within the `Hydrotron` 
array that matches those argument names.

So if `MyClass` looked like:

```php
class MyClass {
    public function __construct($foo, $bizz) {}
}
```

`MyClass` would be instantiated with `$foo = 'bar'` and `$bizz = 'buzz'`. If an argument name is not
found within the array, `null` will be passed.

## Credits 

Hydrotron was inspired by [Keyper](https://github.com/varsitynewsnetwork/keyper).
