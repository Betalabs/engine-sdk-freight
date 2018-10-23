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
And in constructor of this class, you need to set the Outbound adapter (an AbstractTransformer)
and the Inbound adapter (an InboundRequest).