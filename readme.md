WeeK-10 Assignment:

For install-env.sh the aparmeter shoudl be passed in below order 1. AMI ID 2. Key-Name 3. Security Group 4. Launch Configuration 5. count (count cannot be less than zero) 6. Clinet Token 7. Auto scaling group name 8.Load Balancer name 9. Iam profile name

Week-12 Assignmnet:

LoginId Details for controller:
UserName: controller@iit.edu
Password: letmein

LoginID Details for Professor:
userNAme: jrh@iit.edu ( If this is not working try hajek@iit.edu)
Password: letmein

Student Login:
UserName: krose1@hawk.iit.edu
Password:letmein

A drop down is used in admin.php to change status of the upload feature. on/off should be clicked and then submit button should be clicked.

Few features have been added apart from the base requirment. 
The Upload features status ina table named credentials and have been retrived every time each page in the website is loaded, checkuploadenabled.php is used to validate the status of the upload feature.
Backup.php is used to backup the entire database and store it in the S3 bucket named databasebackup-kro
changestatus.php is used to change the status of upload features for all users (including controller himself) . 
Logout.php is used to clear session and divert to the login page
Restore.php is used to restore the entire database.
