# engine-sdk-freight
This package provides a freight calculation response from Engine in a faster way and can be used for developing 
internal apps.

## Installation
```bash
$ composer require betalabs/engine-sdk-freight
```

## Usage
Your internal app need a class that extends:
```php
Betalabs\Engine\Freight::class
```
And you need to implement outbound and inbound adapters, resolving these attributes in superclass.
