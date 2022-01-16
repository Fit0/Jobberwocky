# Jobberwocky API

API built with Laravel and Swagger API Development using PHP and Docker.

This API is an employment opportunities service.

First of all, once the repository is cloned we proceed to edit our .env file to indicate the credentials to access our database. We will also add a new environment variable to be able to access the endpoint to be able to obtain other external offers, for this we will use the service created with this repository https://github.com/avatureassessment/jobberwocky-extra-source 

add in the file .env:

API_EXTERNAL=http://127.0.0.1:8081

Secondly, to use Docker we use make, which we can install with these options:

Linux:
$ sudo apt install make
sudo apt install build-essential $ sudo apt install build-essential 

MacOS:
$ xcode-select --install

In order to start docker, we go to the api directory and run the following command:

make run

and to stop the service, 

make stop

When docker starts, the jobberwocky_db database is generated. The connection data to the database are:

DB_HOST=127.0.0.1
DB_PORT=36000
DB_DATABASE=jobberwocky_db
DB_USERNAME=root
DB_PASSWORD=root

*Sometimes Laravel has generated me a problem when solving the ip of the database (DB_HOST) and of the API_EXTERNAL, just change it to the ip of the machine where it is being processed.

The url to access the API's documentation is http://127.0.0.1:251/api/documentation

Next we will proceed to the creation of the tables, for this we will execute the following command from the api folder:

php artisan migrate


In this API service 3 sections have been generated:

Country
Offer
Skill

From where the different endpoints can be executed for the different actions they have: Add, Edit, Delete and Search.