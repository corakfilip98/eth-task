For the frontend part of the task, 
I used Portown's template for the Admin Panel (URL). 
The essential part of the task is in the files with the .php 
extension in the root directory of the project and in the 
subfolder php-includes, everything else is directories and 
files from the template.


There is one SQL file 

It has one SQL file inside which is the users table, which is used for logging in users.
U: admin 
P: admin
If you want to avoid the obligation to log in, just delete/comment the following line of code:
require("php-includes/auth.php");

From files:
index.php
results.php
transaction-details.php