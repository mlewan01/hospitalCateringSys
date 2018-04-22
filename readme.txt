full address of my aplication's home page:
http://artemlux.com/nhs/index.php

in install folder there is "catering.sql" script which you can use to create corect tables in database of your choice. 

In folder "includes" there is located "config.php" file. You should edit this file to provide correct credentials to be able to connect with your database. 
There is also a locale variable. Uncomment the language you want your application to be.

in order to enable development helpful/additional output go to "config.php" and change value of global variable "DEV" to true. To disable change it to false.

Interface of the application is localised. Currently supports two language: Polish and English. In order to change this application language go to "config.php" 
and comment the other LANGUAGE global variables and uncomment the the one related to the language preferred.More languages can be added by adding 
compatible language file in to folder lang.


application tested successfully on: 
both Windows and Linux with:
- MySQL 5.5.27, 
- Apache 2.4.3, 
- PHP 7.1

 