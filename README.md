# Atoum PHPCI Plugin

Atoum PHPCI Plugin gives the possibility to run test with [Atoum](https://github.com/atoum/atoum) in [PHPCI](https://www.phptesting.org/).

⚠️ This project is no longer maintained. ⚠️

## Installation

This plugin can be installed via [composer](https://getcomposer.org/):

```bash
composer require mauchede/atoum-phpci-plugin
```

## Usage

This plugin takes the following options:
* `config` (string, optional): Allows you to pass the [atoum configuration file](http://docs.atoum.org/en/latest/configuration_bootstraping.html). The default value is `./atoum.php`.

An example of `phpci.yml` with this plugin:

```yml
test:
    \Mauchede\PHPCI\Plugin\Atoum:
        config: atoum.config.file
```

## Links

* [atom](https://github.com/atoum/atoum)
* [atoum configuration file](http://docs.atoum.org/en/latest/configuration_bootstraping.html)
* [composer](https://getcomposer.org/)
* [phpci](https://www.phptesting.org/)
