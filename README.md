# Creditsafe API
```
composer require synergitech/creditsafe-connect
```

## Usage

### Setting up Client
```php
$config = [
    'username'  =>  'username',
    'password'  =>  'password'
];

$creditsafe = new \SynergiTech\Creditsafe\Client($config);
```

### Access countries and their codes
```php
$creditsafe->countries()->access();
```

### Search criteria using country code
```php

$creditsafe->companies()->searchCriteria(['countries' => 'GB']);


```
### Company search pagination
```php
$search = $creditsafe->companies()->search(['countries' => 'GB', 'name' => 'GOOGLE UK LIMITED']);
$search->setPageSize(100);
foreach ($search as $result) {
    $company = $result->get();
}
```

### Get company report
```php
$creditsafe->companies()->get('GB001-0-03977902');
```

## Running tests
```
vendor/bin/phpunit tests
```
