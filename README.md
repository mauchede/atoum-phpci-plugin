# Atoum PHPCI Plugin

Atoum PHPCI Plugin gives the possibility to run test with [Atoum](https://github.com/atoum/atoum) in [PHPCI](https://www.phptesting.org/).

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

## Contributing

1. Fork it.
2. Create your branch: `git checkout -b my-new-feature`.
3. Commit your changes: `git commit -am 'Add some feature'`.
4. Push to the branch: `git push origin my-new-feature`.
5. Submit a pull request.

## Links

* [atom](https://github.com/atoum/atoum)
* [atoum configuration file](http://docs.atoum.org/en/latest/configuration_bootstraping.html)
* [composer](https://getcomposer.org/)
* [phpci](https://www.phptesting.org/)
