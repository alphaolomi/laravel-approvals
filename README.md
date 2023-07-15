# Laravel Approvals

[![Latest Version on Packagist](https://img.shields.io/packagist/v/alphaolomi/laravel-approvals.svg?style=flat-square)](https://packagist.org/packages/alphaolomi/laravel-approvals)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/alphaolomi/laravel-approvals/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/alphaolomi/laravel-approvals/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/alphaolomi/laravel-approvals/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/alphaolomi/laravel-approvals/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/alphaolomi/laravel-approvals.svg?style=flat-square)](https://packagist.org/packages/alphaolomi/laravel-approvals)

## Installation

You can install the package via composer:

```bash
composer require alphaolomi/laravel-approvals
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-approvals-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-approvals-config"
```


## Usage

```php
$project = Project::find(1);

// Approve a project
$project->approve('project-submitted', auth()->user());

// Check if a project is approved
$project->isApproved('project-submitted'); // true
$project->isApproved('project-submitted', auth()->user()); // true

// Check if a project is approved by a specific user
$project->isApproved('project-submitted', User::find(2)); // false

$admin = User::find(2);

// Now approve the project by an admin
$project->approve('project-approved', $admin);

// Check if a project is approved
$project->isApproved('project-approved'); // true
```

Using the facade

```php
use Alphaolomi\LaravelApprovals\Facades\Approvals;

$project = Project::find(1);

// Approve a project
Approvals::approve($project, 'project-submitted', auth()->user());

// Get all approvals for a project
$allProjectApprovals = Approvals::all($project);

// Get all approvals 
$allApprovals = Approvals::allApprovals();
```

## Testing

Project is tested with [pest](https://pestphp.com/).

```bash
composer test
```

## Versioning

Project follows [RomVer](https://github.com/romversioning/romver) for versioning. For the versions available, see the [tags on this repository](../../tags).

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Alpha Olomi](https://github.com/alphaolomi)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
