DROP TABLE IF EXISTS leave_applications;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS departments;

-- Create departments table
CREATE TABLE departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE
);

-- Create users table with department_id
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    role ENUM('admin', 'employee') NOT NULL,
    department_id INT,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL
);

-- Insert sample departments
INSERT INTO departments (name) VALUES
('Computer Science'),
('Human Resources'),
('Finance'),
('Sales'),
('Marketing'),
('Operations');

-- 5. Insert admin + employees
INSERT INTO users (name, email, password, role, department_id) VALUES
('Admin', 'admin@example.com', 'admin123', 'admin', NULL),
('emp1', 'emp1@gmail.com', 'emp001', 'employee', 1),
('emp2', 'emp2@gmail.com', 'emp002', 'employee', 2),
('emp3', 'emp3@gmail.com', 'emp003', 'employee', 3),
('emp4', 'emp4@gmail.com', 'emp004', 'employee', 4),
('emp5', 'emp5@gmail.com', 'emp005', 'employee', 5),
('emp6', 'emp6@gmail.com', 'emp006', 'employee', 6);

CREATE TABLE leave_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type ENUM('CL', 'EL', 'ML', 'PL') NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    reason TEXT NOT NULL,
    status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);