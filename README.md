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
       
    

