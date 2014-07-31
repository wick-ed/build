# Version 0.1.12

## Features

* Make PHPDocumentor target dir configurable with phpdoc-target.dir
* Optimize phpcpd-exclude.dir by add new phpcpd-additional.args initialized with phpcpd-exclude.dir instead

## Bugfixes

* None

# Version 0.1.11

## Features

* Remove composer-install target as dependency from run-test target

## Bugfixes

* None

# Version 0.1.10

## Features

* None

## Bugfixes

* Bugfix missing = for phpcpd-exclude.dir in commong.default.properties

# Version 0.1.9

## Features

* Make exclude directories for tools configurable

## Bugfixes

* None

# Version 0.1.8

## Features

* Make source directory, to be parsed by tools, configurable

## Bugfixes

* None

# Version 0.1.7

## Features

* Ignore composer vendor directory when running tools

## Bugfixes

* None

# Version 0.1.6

## Features

* Switch to composer version of phploc, phpmd, phpcs, phpcpd, phpdocumentor

## Bugfixes

* None

# Version 0.1.5

## Features

* Rename os.family property from unix to linux (also default)

## Bugfixes

* None

# Version 0.1.4

## Features

* Add new properties for var/tmp, deploy + webapps directories

## Bugfixes

* None

# Version 0.1.3

## Features

* None

## Bugfixes

* Set correct deploy.dir in common.mac.properties
* Remove static app/code from deploy.dir in deploy target in common.xml

# Version 0.1.2

## Features

* Allow coding standard definition by build property coding.standard

## Bugfixes

* None

# Version 0.1.1

## Bugfixes

* Add basedir + bootstrap when calling phpunit
* Remove release.version property from common.default.properties

## Features

* None

# Version 0.1.0

## Bugfixes

* None

## Features

* Initial Release