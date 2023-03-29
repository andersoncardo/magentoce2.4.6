This is a brief guide on how to install the cardoso/module-customer-products package using Composer

## Prerequisites
Before starting the installation process, make sure you have the following prerequisites installed:

PHP version 7.3 or later
Composer

## Installation

Open your terminal or command prompt.

Navigate to your project's root directory.

Run the following command to install the package:

```bash
composer require cardoso/module-customer-products
```
## Configuration
After installing the package, you will need to enable it in your Magento 2 project by running the following command:
```bash
php bin/magento module:enable Cardoso_CustomerProducts
```
After enabling the module, you will need to run the following command to update your project's dependencies:
After installing the package, you will need to enable it in your Magento 2 project by running the following command:
```bash
php bin/magento setup:upgrade
```


## Usage

The cardoso/module-customer-products package allows customers to view a list of their products filtered by price range. To access this list, customers can log in to their account and navigate to the "Products" section.


## License

[MIT](https://choosealicense.com/licenses/mit/)