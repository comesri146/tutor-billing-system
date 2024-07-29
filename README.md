# tutor-billing-system
This is tuition billing system .it is used to add student details and invoice for all branches.
  # There are two users:
 
        Admin/ master.
        Branch admin

deployed link to project: [visite the webiste](https://tuitionmanagetharun.000webhostapp.com/master_login.php)

## Deployed platform and credentials:

   website link for login : [login with 000webhost](https://in.000webhost.com/cpanel-login)
     
     Login details
              email:   tharunvikas55@gmail.com
              password: Tharun#2003
After successfull login you will see this page :

![image](https://github.com/user-attachments/assets/899d58cc-007f-472e-91dc-8491df1c7f8b)


**Add new master follow the step**

  ![image](https://github.com/user-attachments/assets/6e796438-b51a-4052-9b15-837fbaa1be63)

  click manage button in above window image.

  ![image](https://github.com/user-attachments/assets/acfe13d5-4378-4b72-9091-2bd55bec4418)
  

  ### scroll down the page  you will see the Databases section   under that click the MySQL Databases  you will redirect to below page:
  

  ![image](https://github.com/user-attachments/assets/43780b6e-19dd-4bf7-937e-d4ae941a581f)


  ### In above page click the PhpMyAdmin and you need to login into database with below provided

      username: id21968246_sriram
      Password: Tuitionbilling@1

      
  ![image](https://github.com/user-attachments/assets/81128eed-ecf4-4a7f-9808-3195f5e454b1)


**click the MASTER table in id21968246_tuitionbilling database.**

![image](https://github.com/user-attachments/assets/164756fa-d004-4591-8d30-e7cb2e53b4b6)


**Click the INSERT button in top  of the page to add new master**

![image](https://github.com/user-attachments/assets/9731d750-188e-4634-b582-aea2248952a5)

**Add the new master by adding  username and passowrd  choose function as MD5 for encryption of password you entered   and clcik Go**

![image](https://github.com/user-attachments/assets/b9c2ef76-f844-4d0b-b307-c3e3415247da)

**Again Do not  click the go button at bottom of the page  if you click it then it make duplicate of the username and password . so go to master table after above step**

![image](https://github.com/user-attachments/assets/36c52c27-dc53-45d5-a079-405ce34ada2b)

**You can see the added new master username and password**

![image](https://github.com/user-attachments/assets/133a38f5-3661-4daa-9850-2d6a0060a30b)


## Download and add dompdf  from below link in project:

[Dompdf download]( https://github.com/dompdf/dompdf/releases)
## Project work flow:

  - Login as master/ admin for tuition billing system  with lgoin credentials below:
       username: master1
       password: master123
     if you need to add more admin add in **tuitiondata.sql** file.
  - After succesfully logged as admin/master  you will redirect to admin /master dashboard shown below.
    
       ![image](https://github.com/user-attachments/assets/c14a74b6-944c-461b-b71b-bc9ade5aa736)

    - In master/admin dashboard you can perform operations that included:
                  
               - Add  subjects
               - Add branch
               - Add Admin for particular branch with username and password.
               - view all branch invoice with search option are enabled.
               - view subject for all branches.
               - view total student count in all branches.
      
    - After Successfully logged as branch admin for particular branch you will redirect to branch Admin dashboard shown below:
      
         ![image](https://github.com/user-attachments/assets/99dead0d-5e96-4d1f-8807-9e2a035e6ba2)
      
    - In Branch admin dashboard you can perform operations that included:

                - Add student details.
                - Genreate the invoice for student for selected subjects in that branch.
                - View the invoice for that branch
                - pay the due amount of student.
                - Reverse invoice the student details.

   ## Database schema:
     - master
     - branches
     - branch admins
     - branch students
     - invoices
     - subjects
     - subject branches
       
    

