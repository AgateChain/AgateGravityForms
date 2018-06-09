# Using the Agate plugin for Gravity Forms

## Prerequisites

* Last Version Tested: 1.8

You must have a Agate API Key to use this plugin. It's free visit [here](http://www.agate.services/registration-form/) .


## Server Requirements

* [Wordpress](https://wordpress.org/about/requirements/) >= 4.6 (Older versions may work, but we do not test against those)
* [GravityForms](http://www.gravityhelp.com/) >= 2.0.6
* [mcrypt](http://us2.php.net/mcrypt)
* [OpenSSL](http://us2.php.net/openssl) Must be compiled with PHP
* PHP >= 5.4

## Installation

**From Downloadable Archive:**

Clone the repository and make zip file of the "src" folder. Once this is done, you can just
go to Wordpress's Adminstration Panels > Plugins > Add New > Upload Plugin, select the zip file and click Install Now.
After the plugin is installed, click on Activate.

**WARNING:** It is good practice to backup your database before installing plugins. Please make sure you create backups.

**NOTE:** Your Maximum File Upload Size located inside your php.ini may prevent you from uploading the plugin if it is less than 2MB. If this is the case just extract the contents of zip file into your Wordpress's wp-content/plugins folder.

## Configuration

Configuration can be done using the Administrator section of Wordpress.
Once Logged in, you will find the configuration settings under **Forms > Settings > Agate Payments**.
Alternatively, you can also get to the configuration settings via Plugins and clicking the Settings link for this plugin.

**Jeeb Settings**

1. Get the API Key from your Agate merchant account.
2. Paste the API Key ID string that you created in API Key field.
3. Set the Redirect Url as you wish to redirect the customer after the payment.

Save your changes and you're good to go!

## Usage

Once enabled, your customers will be able to pay with Agate. Once
they checkout they are redirected to a full screen Agate invoice to pay for
the order.

As a merchant, the orders in your Gravity Forms store can be treated as any other
order. You may need to adjust the Invoice Settings depending on your order
fulfillment.
