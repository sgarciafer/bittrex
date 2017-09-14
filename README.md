Bittrex Robot 
=======

This is a Brittrex exchange Robot that scan the trades and history to detect overbuy/oversell situations using last 20 average values and standard deviations.
One such situation is detected, Bittrex Robot will create a buy/sell order and then close it with a percentage of benefit.

It include a PHP class wrapper for the Bittrex altcoin trader platform API (https://bittrex.com/home/api).

Requirements
======
* You obviously need a bittrex account.
* You need to create an API key on your account settings


Installation
======
* Rename default.config.php and set the key and secret values.
* Make a CRON execute the file XXXXX.php every X seconds.

Documentation
======
TODO




