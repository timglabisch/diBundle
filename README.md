# Tg\DiBundle

## how it works
```php
    use Tg\DiBundle\Annotation\Inject;

    class DefaultController extends Controller {


        /** @Inject */
        public function indexAction($serviceLogger) {
            return $this->render('...');
        }
    }
```

after installing the bundle you can just add the @Inject Annotation to non-service controllers and get any service based on the name.
Parameters that are not prefixed with "service" are ignored.
For example $serviceLogger looks for the service "logger" and inject it, it strips the prefix "service" and uclower the Servicename.


# Installation

add this to your composer file:

```
    "repositories": [
        { "type": "vcs", "url": "https://github.com/timglabisch/diBundle" }
    ],
    "require": {
        "tg/di-bundle": "dev-master"
    }
```

add this to your appKernel

```php
    $bundles = array(
        new Tg\DiBundle\TgDiBundle(),
    );
```