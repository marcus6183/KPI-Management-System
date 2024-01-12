<h1>About the Project</h1>
This is a project for COS30020 Advanced Web Development assignment.
The current version is the final submission for assignment 2 (based on assignment 1 with additional features)
It is a KPI management system that allows employers or admin to track the KPI of staffs.

<h2>Target Users</h2>

1. Admin
   - Add Staff Profile
   - Manage Staff Profile (Update staff details or Delete staff profile)
   - Update Staff KPI (Approve, Remove, Add KPI for staffs)
   - Add KPI (Add new KPI option)
   - Manage KPI (Update KPI description)
   - Has access to KPI Overview
   - Change the availability for staffs to add KPI (for approval)
2. Staffs
   - Add KPI for approval
   - Update their password

<h2>Demonstration Video Link</h2>
https://www.youtube.com/watch?v=ptQwqvzq0YA

<h2>How to setup on local PC</h2>

1. Download a zip file of the project
2. Unzip the files into <code>htdocs</code> folder in XAMPP
3. Start XAMPP with MySQL & Apache Web Server
4. In your preferred browser, go to <code>localhost/KPI-Management-System-main</code>
5. To log-in as Admin, use the following credentials:
  - Login Name: admin
  - Password: admin
6. If you want to log-in as staff:

  - When logged in as admin, head to 'Add Staff Profile', input the name of your choice, Staff ID starting with 'SS' prefix and 3-4 numbers, Email using 'swinburne.edu.my' as the domain
  - Example:
    
    - Full Name: Marcus Wong
    - Staff ID: SS002
    - Email: marcus@swinburne.edu.my

  - Click on the 'Add Staff' button

7. You may now logout and then login again using the staff profile.
   
  - The login name will be the text before '@swinburne.edu.my' when creating the staff profile. Example: 'marcus' from 'marcus@swinburne.edu.my'
  - The default password is: password123
