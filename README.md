BASIC API IMPLEMENTATION FOR STAFF RECORDS



INSTALLATION INSTRUCTIONS
REQUIREMENTS : Existing MYSQL instance, composer and PHP 8.1 or later
1) Clone the repository in a directory in local machine
2) Open a terminal and go to the directory where the repository was cloned. Run 'composer install' (This will install necessary modules)
3) In your MySQL instance create an empty DB.
4) Edit the .env file to connect to your newly created DB.
5) Start Symfony , Run 'symfony server:start' in the terminal
6) Create an empty "staff" table in MySQL using doctrine. Go to your directory and run 'php bin/console doctrine:migrations:migrate' .
7) Create few records for testing using 'fixtures.sql'
8) Run a basic unit testing script (PHPUnit) by running 'php bin/phpunit' . This should test basic add, edit, delete, get rooutes
9) A Basic OpenAPI Spec (Swagger Doc) should be available in http://localhost:8000/api/doc
10) Test the routes in postman or and endopint testing tool.




Due to the time constraints , I have listed a couple of TODOs that should be the next steps


a) authentication - passwords are currently not being hashed(with salt) , HTTPS(TLS/SSL) should be implemented

b) input sanitization - there are some validation of input in place but each fields should have a thorough sanitization for security and integrity of the data. An example is that email is not being validated if its in a valid email address format.

c) Some of the sanitization are still in controller, it has been suggested in the code that it should be isolated (loosely couple) .

d) OpenAPI Doc should be applied to attributes/annotation in the controllers or even the models(entity)

e) Enum implementation in the DB fields should be implemented in doctrine entities, the codes have notes on references.

f) Test scripts should also evaluate instances of exceptions like wrong parameter, invalid json content and the likes.


IMPORTANT CODE FILES: 
/src/Controller/APIController.php
/src/Entity/Staff.php
/src/Repository/StaffRepository.php
/tests/ApiTest.php
