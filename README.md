## phpbips


This repository is a readonly mirror of [digitaloversight/phpbips](https://sslcube.com/digitaloversight/phpbip/)


### Introduction


The phpbips library is an experimental php implementation of [Bitcoin bips](https://github.com/bitcoin/bips) articles. 

The EccBase class provides general configuration for the bips usage. While default is configured for the Bitcoin architecture, EccBase can be configured for other crypto currency or networks.
 

### Require


GMP php extension, make sure you built php with --with-gmp.

Composer will get phpecc/phpecc library from @mdanter



### Credits 


The [phpecc library](https://github.com/phpecc/phpecc) php integration with original work by @mdanter 

The [python ecdsa](https://github.com/warner/python-ecdsa) from @warner. 

The original [bip 0032 article](https://github.com/bitcoin/bips/blob/master/bip-0032.mediawiki).

[Python version](https://github.com/lyndsysimon/bip32utils) of bip32 by @lyndsysimon

[Base58 PHP library](https://github.com/stephen-hill/base58php) inspiration by @stephen-hill

