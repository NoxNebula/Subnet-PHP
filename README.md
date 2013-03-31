Subnet-PHP
==========

A PHP-Class wich calculates subnetmasks and compares it with IPs

I just needed to check if IPs match one or more given subnetmasks, so this class was created.

I'm not 100% sure that this will work 100% correctly but all my tests passed.

Example
-------

This example is my case for that I needed this class

```php
<?php
require 'Subnet.class.php';

$GitHubHooks = [
	'207.97.227.253/32',
	'50.57.128.197/32',
	'108.171.174.178/32',
	'50.57.231.61/32',
	'204.232.175.64/27',
	'192.30.252.0/22'
];

$IPs = new Subnet($GitHubHooks);

if($IPs->Compare('204.232.175.75')) {
	echo '??? Profit!';
}
```
