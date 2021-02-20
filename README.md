[![Build Status](https://travis-ci.com/cowanr/wp-crispy-enigma.svg?branch=master)](https://travis-ci.com/cowanr/wp-crispy-enigma)

# wp-crispy-enigma

Ryan Cowan - SENG6245001201930 (SENG 6245, Software Construction, Spring 2019

Presentation - https://youtu.be/noLsMcfZLtE

## Resources

The following resources were the technical documentation for the technologies I use in the demo. In all cases they were used for installation of the technologies and as a resource to check what I was learning from other resources. Also used for debugging issues that arose from the demo.

* Varying-Vagrant-Vagrants - https://github.com/Varying-Vagrant-Vagrants/VVV - Local
environment ready made for WordPress Testing.
* PHPUnit - https://phpunit.de/ - Unit testing framework for PHP language
* WP-CLI - https://wp-cli.org/ - WordPress command line interface.
* Brain Monkey - https://brain-wp.github.io/BrainMonkey/

A mock WP library that allowed the tests to be run without a WordPress installation. I choose it for the ease of use and its human readable syntax. It was the best mock library I found to be able to test the actions and filters that plugins use heavily to modify WordPress behavior.

* WordPress Plugin Developer Handbook - https://developer.wordpress.org/plugins/
* WordPress Plugin Unit Tests - https://make.wordpress.org/cli/handbook/plugin-unit-tests/

The following were articles used as a resource for the demo.

### Mock Library for unit tests in WordPress

https://swas.io/blog/wordpress-plugin-unit-test-with-brainmonkey/

Describes how to create and run unit tests with a mock library. I used it as a starting point for my own unit test with the brain monkey mock library. 

### CI Integration for WordPress Plugins and Automated testing

https://torquemag.io/2018/06/advanced-oop-for-wordpress-part-6-continuous-integration-for-
wordpress-plugins/

Describes how to use Travis CI for WordPress Plugins as well as more advanced techniques then most blog posts on the subject. Used it to familiarize myself with how he automated unit and integrations tests with Travis CI.

### Unit Testing WordPress Plugins with PHPUnit

https://premium.wpmudev.org/blog/unit-testing-wordpress-plugins-phpunit/
https://premium.wpmudev.org/blog/vvv-wordpress-development/

Introductory how-to articles. Was a good initial exposure to unit testing. Ended up using the Vagrant environment shown in this article for my local environment.

## Slides

### WordPress

One slide to introduce WordPress and specifically what a plugin is in WordPress. Will also mention the importance of actions and filters since that was one of the factors in deciding on the mock testing library.

### PHPUnit

One slide to introduce PHPUnit. Show some example code and point out the similarities to JUnit.

### WordPress PHPUnit Tests

One slide to introduce the WordPress Testing library built on top of phpunit.

### WP-CLI

One slide to introduce WP CLI and to briefly cover the wp scaffolding command.

### Brain Monkey

One to introduce the concept of a mock library and a brief overview of the Brain Monkey.

## Demo

* Briefly walk through how I configured my local environment.

* Highlight the functionality that the plugin added to WordPress.

* Describe the unit tests configuration files created by wp cli

* Provide an overview of the unit tests

* Provide an overview of the integration tests

* Successfully run unit and integration tests and show a successful build in Travis CI.


