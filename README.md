📝 Leave Management System

A web-based Leave Management System built using PHP and MySQL, designed to streamline the leave application and approval process within an organization.

This project was developed as part of a DBMS mini project and demonstrates complete CRUD operations, role-based access control, and data visualization for employee leave tracking.

---

🚀 Features

- 👨‍💼 Admin Panel to manage leave requests (Approve/Reject)
- 👷 Employee Portal to apply for leave and view history
- 🏷️ Leave Types: Casual (CL), Earned (EL), Medical (ML), Paid (PL)
- 🗃️ Department-wise employee management
- 📊 Dashboard cards with counts of Pending, Approved, Rejected, Total
- 🔍 Filter leave requests by status
- 💾 MySQL relational database with constraints and foreign keys
- 💻 Responsive UI with Bootstrap styling

---

🧰 Tech Stack

| Layer        | Technology              |
|--------------|--------------------------|
| Frontend     | HTML, CSS, Bootstrap 5   |
| Backend      | PHP                      |
| Database     | MySQL (phpMyAdmin)       |
| Server       | XAMPP (Apache + MySQL)   |

---

📂 Folder Structure

Leave_Management_System/
├── admin_panel.php
├── apply_leave.php
├── login.php
├── logout.php
├── my_history.php
├── db.php
├── styles.css (optional)
├── database/
│ └── leave_management.sql
├── README.md


---

🛠️ Installation Guide

📍 Prerequisites:
- [XAMPP](https://www.apachefriends.org/index.html) installed and running
- PHP 7+ and MySQL (comes with XAMPP)

---

🧱 Steps to Run Locally:

1. Clone or download this repository.

2. Move the folder into:  C:\xampp\htdocs\

3. Open [phpMyAdmin](http://localhost/phpmyadmin).

4. Create a database:  leave management

5. Import the SQL file:  database/leave_management.sql

6. Start Apache and MySQL in XAMPP.

7. Access the project in your browser:  http://localhost/Leave_Management_System/


---

🔐 Login Credentials

👨‍💼 Admin Login:
- Email: `admin@example.com`
- Password: `admin123`

👷 Employee Logins:
| Name         	| Email           | Password |
|--------------	|-----------------|----------|
| emp1		| emp1@gmail.com  | emp001   |
| emp2   	| emp2@gmail.com  | emp002   |
| emp3	 	| emp3@gmail.com  | emp003   |
| emp4  	| emp4@gmail.com  | emp004   |
| emp5	 	| emp5@gmail.com  | emp005   |
| emp6		| emp6@gmail.com  | emp006   |

---

 🖼️ Screenshots

🔹 Admin Dashboard
![Admin Dashboard](images/admin_dashboard.png)

🔹 Apply Leave Page
![Apply Leave](images/apply_leave.png)

🔹 My History Page
![My History](images/my_history.png)


---

💾 Database Design (ER Model)

- users(id, name, email, password, role, department_id)
- departments(id, name)
- leave_applications(id, user_id, type, start_date, end_date, reason, status)

🧩 Relationships:
- `users.department_id → departments.id`
- `leave_applications.user_id → users.id`

---

🎯 Functional Flow
Employee logs in ➝ Applies for leave ➝ Admin reviews ➝ Approves/Rejects ➝ History updated


👤 Developed By

Sonal M Jakhar 
B.Tech in Computer Science & Engineering  
DBMS Mini Project 2025  
BMS Institute of Technology

Team Member :
Ishika Jha
B.Tech in Computer Science & Engineering  


📌 Project URL

🔗 [https://github.com/sonal-jakhar/Leave_Management_System](https://github.com/sonal-jakhar/Leave_Management_System)

