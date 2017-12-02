# Listo

[![GitHub license](https://img.shields.io/github/license/mashape/apistatus.svg)](LICENSE.md)

Extends the Ligero CRUD micro-framework for Laravel, adding dynamic contextual UI controls supporting complex search architectures.

Quick-Links:

- [Installation](#installation)
- [Overview](#overview)
    - [Architecture](#architecture)
    - [Getting Started](#getting-started)
    - [Basic Steps Outlined](#basic-steps-outlined)
    - [Live Demo](#live-demo)
- [Class Types](#class-types)
    - [Config (PublisherConfigInterface)](#config-publisherconfiginterface)
    - [Request (PublisherRequestInterface)](#request-publisherrequestinterface)
    - [Publisher (PublisherInterface)](#publisher-publisherinterface)
    - [Repository (PublisherRepositoryInterface)](#repository-publisherrepositoryinterface)
    - [Model (PresentableInterface)](#model-presentableinterface)
    - [Presenter (PresenterInterface)](#presenter-presenterinterface)
    - [Context (ContextInterface)](#context-contextinterface)
- [Customization](#customization)
    - [Publishing the Package Files](#publishing-the-package-files)
    - [Extending or Decorating Base Classes](#extending-or-decorating-base-classes)
    - [Namespace for Custom Classes](#namespace-for-custom-classes)
- [Tests](#tests)
- [License](#license)
- [Contributing](#contributing)

## Installation

Via Composer:

```bash
$ composer require viewflex/listo
```

After installing, add the `ListoServiceProvider` to the list of service providers in Laravel's `config/app.php` file.

```php
Viewflex\Listo\ListoServiceProvider::class,
```

## Overview

### Architecture

This package extends or decorates base classes in the `viewflex/ligero` package, following the same strategy pattern. See the [Ligero documentation](https://github.com/viewflex/ligero-docs) to understand this before you continue.

### Getting Started

This package provides several ways to create new domain Publishers, depending on the complexity of the requirements for a given domain. See the [Basic Usage](#basic-usage) and [Advanced Usage](#advanced-usage) sections below, for examples of creating and using Publishers.

A good place to start understanding any codebase is through the interfaces. In this package nearly every class implements an interface, providing a clear map of core functionality, and a decoupling of code that allows extension, decoration, or replacement of any class, without side-effects.

### Basic Steps Outlined

- Specify configuration, validation rules, and other attributes of the Config, Request, and Repository components. You can define all these via setters at runtime, or extend the base classes.
- Create a new Publisher instance with the configured components. This can be in a controller, or a Context - basically any class that uses the `HasPublisher` trait.
- Use methods on the Publisher to perform CRUD operations and generate dynamic navigation and query controls. Extend the Publisher if necessary to modify or add functionality.

There are multiple ways to implement domains - the demo illustrates implementing a Publisher in a UI controller. This and other implementations are explained in detail in the following sections.

### Live Demo

![demo screenshot](https://raw.githubusercontent.com/viewflex/listo-docs/master/img/screenshots/results-list-view.png)

The Items domain is a working demo provided in this package; you can try it out using the [routes](#creating-a-publisher-ui-controller) listed below. The demo implements a very simple CRUD UI in plain HTML with Bootstrap.css, but of course the generated API data sent to the views could be presented using any front-end framework desired.

Listo provides a complete separation of presentation and application logic, and outputs results with all necessary data elements for dynamic UI components, pre-packaged in a standardized format. Use the 'Items > Display as JSON' menu command to see what the raw data looks like.

#### Using the Demo as a Template

See the [Publishing the Package Files](#publishing-the-package-files) subsection below to learn how to quickly scaffold new domains by publishing (copying) the demo and customizing it to suit the requirements.

#### Demo API Endpoints

Besides the UI controller used in the demo, there is also a ready-made Context and API controller serving the API routes for the same Items domain. The package `ContextApiController` can be used to serve any number of domain Contexts via route parameter keyed to the configured `$contexts` array.


## Class Types

Understanding these class types, and the different tasks they perform, is the key to making productive use of this package. The way that they come together to get the job done provides great power and flexibility with minimal development effort.

This package includes several live examples, demonstrating a few of the ways in which a `Publisher` object can be created and used. The easiest way to get started using this package for your own projects would be to copy one of these implementations, changing the namespaces and class names.

The namespace `Viewflex\Listo\Publish` corresponds to the `publish/viewflex/listo` directory in your Laravel project root. When you use `php artisan publish`, [as detailed below](#publishing-the-package-files), this directory will be created if not already existing.

### Config `PublisherConfigInterface`

This class is where you configure the operation of the other class types. It provides getters, setters, and helper methods used throughout the `Publisher`, `PublisherApi` and `BasePublisherRepository` classes. These settings allow complete control over generation of search results and UI components. Extend the `BasePublisherConfig` class and override attributes to suit the domain.

This package's `Config/listo.php` file provides defaults for the global settings in `BasePublisherConfig`, and can be published (via artisan command) to the Laravel `config` directory for customization (see the [Customization](#customization)  section below).

Config attributes can also be overridden using setter methods, so if you only need to override a few values, you can use the base Config class instead of creating an extended Config class. These are the methods of `PublisherConfigInterface`:

#### Domain Configuration

These configuration settings are specific to the given domain, getting their minimal defaults from the `BasePublisherConfig` class.

##### Domain, Resource Namespaces, Translation

Specify the domain name used in messages and labels ('Items' in the demo) - this is also used (lower-cased) as part of the the domain view prefix. For multiple-word domain names, name should be in `StudlyCaps` case (no spaces).

Specify the resource namespace used to locate published resources in the application's `resources/lang/vendor/[namespace]` and `resources/views/vendor/[namespace]` directories. The package default is 'listo', but you can set a custom namespace for domain resources - useful if there are many, or if non-canonical names result in naming conflicts between domains.

Specify a translation filename ('items' in the demo) to get translations from a specific file in the configured resource namespace. The `ls()` method is a wrapper for Laravel's `trans()` helper function (or `trans_choice()`, if a count is supplied for inflection), enhancing flexibility of localization by allowing use of a custom namespace and file.


##### Query Menus

Detailed configuration of query UI controls.

```php
getQueryMenusConfig()
setQueryMenusConfig($query_menus_config)
```

##### Sort Menu

Detailed configuration of sort menu UI control.

```php
getSortMenuConfig()
setSortMenuConfig($sort_menu_config)
```


### Request `PublisherRequestInterface`

This class represents the Laravel/Symfony Request class, which contains the inputs and validation rules. Extend the `BasePublisherRequest` class and override validation rules to suit the domain.

As with the Config class, setter methods exist to customize attributes of the Request class, allowing use of the base Request class directly, without creating an extended Request class. These are the methods of `PublisherRequestInterface`:

#### Rules for GET and POST Validations

```php
rules()
getRules()
setRules($rules)
setRule($name, $value)
getPostRules()
setPostRules($post_rules)
setPostRule($name, $value)
```

#### Raw and Filtered Inputs

```php
getQueryInputs()
getQueryInput($key = '')
getInputs()
setInputs($inputs = [])
mergeInputs($inputs = [])
cleanInput($param = '')
```

### Publisher `PublisherInterface`

#### Raw Data

Get results, data for dynamic UI controls, and full info on query.

```php
getQueryMenus()
getSortMenu()
```

#### Multi-Record List Actions

The 'action' and 'items' query parameters can be used to perform actions on a selection of records. The package supports the actions 'clone' and 'delete'. To add new actions, extend the `BasePublisherRepository`, adding new cases to the `action()` method.

```php
action($inputs = null)
```

Separate from this functionality, using 'select_all' as the 'action' parameter in a query will select all item checkboxes in the results display, via the `$form_item_checked` attribute for each row of results data passed to the views. This is useful when creating a pure HTML front end, where JavaScript can't be used to manipulate UI elements.

### Repository `PublisherRepositoryInterface`

Changes...

These are the methods of the `PublisherRepositoryInterface`:


#### Menu Building

```php
distinctColumn()
```

#### Multi-Record List Actions

Added actions:

	-- Sort Higher
	-- Sort Lower
	-- Show
	-- Hide

### Context `ContextInterface`

A Context class encapsulates a domain Publisher and it's dependencies, and can be used to make a particular representation of the domain's internal functionality available to the wider application. This optional class facilitates creation of an application service layer, providing a path toward implementing complex multi-domain processes.

Similar to a controller, a Context includes methods for querying, storing, updating and deleting records, plus corresponding methods for doing the same not only for one domain, but possibly (if the domain is an aggregate root) for several at once, within a transaction (if saving data).

See the section on [Advanced Usage](#advanced-usage) below to learn more about Contexts. These are the methods of the `ContextInterface`:

#### Publisher Query Actions

```php
find($id, $native = true)
findBy($inputs = [], $native = true)
store($inputs)
update($inputs)
delete($id)
```

#### Context Query Actions

```php
findContext($id, $native = true)
findContextBy($inputs = [], $native = true)
storeContext($inputs)
updateContext($inputs)
deleteContext($id)
```

#### Validation

```php
inputsAreValid($inputs = [])
```

#### Utility

```php
onFailure($msg = '', $data = [])
failureMessage($operation = '', $inputs = [])
formatDomainContext($context, $name = null)
contextResponse($success, $msg, $data)
responseSuccess($response = [])
responseMessage($response = [])
responseData($response = [])
log($message = '')
```

#### Aggregate Context Methods

The `AggregateContextInterface` extends the `ContextInterface`, specifying additional methods to support rich Contexts.

```php
getContext($root, $native = true)
contextInputsAreValid($inputs)
```

## Customization

This package comes with a demo domain, 'Items', that provides examples of publishing a domain with both a UI controller and an API controller. This demo can be used without using `php artisan vendor:publish` to copy the files for modification, but to either play around with the demo 'Items' domain or create new domains for your application, just use the `publish` command with the `listo` tag (as described below).

Copy and rename the demo files you need and change the class names, to implement Publishers for custom domains. Copy and rename the resource files (views and lang), and customize as needed.


### Publishing the Package Files

The package service provider configures `artisan` to publish specific file groups with tags. There are several option available in this package.

#### Routes

Run this command to publish the `routes.php` file to the project's `publish/viewflex/listo` directory for customization:

```bash
php artisan vendor:publish  --tag='listo-routes'
```

#### Config

Run this command to publish the `listo.php` config file to the project's `config` directory for customization:

```bash
php artisan vendor:publish  --tag='listo-config'
```

#### Resources

Run this command to publish the blade templates for the demo UI, and lang files for package messages and UI strings:

```bash
php artisan vendor:publish  --tag='listo-resources'
```

#### Routes, Demo Migration and Seeder

Run this command to install the migration and seeder for the 'Items' demo domain:

```bash
php artisan vendor:publish  --tag='listo-data'
```

After publishing the demo migration and seeder, run the migration:

```bash
php artisan migrate
```

Then run the seeder:

```bash
php artisan db:seed --class="ListoSeeder"
```

#### Routes, Config, Resources, Demo Migration and Seeder

Use this command to publish config, demo views, and lang files for modification. The demo migration and seeder are also copied to their proper directories:

```bash
php artisan vendor:publish  --tag='listo'
```

### Extending or Decorating Base Classes

Listo's architecture is based on distinct pattern of class types, each defined by an interface; since classes relate to each other as abstract types, you can easily substitute your own custom classes, provided that they implement the same interfaces. In fact, Listo follows the same architecture as the Ligero package that it extends.

### Namespace for Custom Classes

The `Viewflex\Listo\Publish` namespace, corresponding to the `publish/viewflex/listo` directory, is recognized by the package, and is intended for organization of your custom classes. The Items demo classes will be published (copied) to this directory for customization.

## Tests

The phpunit tests can be run in the usual way, as described in the [Test Documentation](https://github.com/viewflex/listo-docs/blob/master/TESTS.md).

## License

This software is offered for use under the [MIT License](https://github.com/viewflex/listo/blob/master/LICENSE.md).

## Contributing

Please see the [Contributing Guide](https://github.com/viewflex/listo-docs/blob/master/CONTRIBUTING.md) to learn more about the project goals and how you can help.

