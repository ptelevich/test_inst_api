# Deployment and regulations 

###1) config/Config:

  - set your apiKey
  - set your apiSecret
  - define constant CONST_INFOLDER (relative path where project deployed)
  
###2) Instagram Developer panel

- set redirect URL in  as 'http://servername/CONST_INFOLDER/instagramCallBack.php'

###3) Composer

composer install
   
###4) PHP
- PHP 5.4 and higher (my version 5.5)
- Must exist curl library
- Must enable SPL library
- Must enable session

###5) Browser(MacOS, Linux)
- Chrome (latest)(recommended)
- Firefox (latest)
- Opera (latest)

