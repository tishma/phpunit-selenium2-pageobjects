# PHPUnit Selenium2 PageObjects

[![Build Status @ Travis CI](https://travis-ci.org/ravage84/phpunit-selenium2-pageobjects.svg?branch=master)](http://travis-ci.org/ravage84/phpunit-selenium2-pageobjects)

PageObjects is the idea of representing a webpage's services through an object, abstracting away the guts of Selenium and the page's structure.

You can (and should) read all about PageObjects here: http://code.google.com/p/selenium/wiki/PageObjects

Additionally, this repository comes prepared with an example application and functional test: https://github.com/nationalfield/phpunit-selenium-pageobjects/tree/master/Example

## Why
**You should apply good programming practices to testing. Tests are code. Bad code can contribute considerably to your technical debt. Tests should be DRY.**

# Installation
If you use Composer, simply add a dependency on ``ravage84/phpunit-selenium2-pageobjects``
to your project's ``composer.json``.

````
{
    "require-dev": {
        "phpunit/phpunit": "3.7.*"
    }
}
````

## Requirements
- Composer
- PHP 5.3
- PHPUnit Selenium 1.3.x

# Behavior
## Getters and Setters
You should define a getter and setter for each field for usage outside of your functional test. Never manually access the map, locators, or map keys outside of the PageObject.

## Map
The map is defined using CSS selectors.

```php
<?php
protected $map = array(
    'first_name' = '#account_fname',
    'last_name' = '#account_lname',
);
```

When you instantiate a PageObject, it calls `assertMapConditions` which automatically loops through all `$map` elements and asserts their presence.

> **Note:** Another method exists, `assertPreConditions`, and is executed before `assertMapConditions`. Implementing this method allows you to execute methods like `waitForElementPresent`.

## Using The Map

To use a mapped element, simply append `ByMap` to the end of the method name you want to utilize. For example:

```php
<?php
$this->typeByMap('first_name', 'Graham');
$this->typeByMap('last_name', 'Christensen');
```
