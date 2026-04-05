# 🏛️ University Idea Management System (COMP1640)

## 📋 Project Overview
This is a secure web-enabled role-based system designed for a large University to collect improvement ideas from staff. The system manages the entire lifecycle of an idea, from submission and peer commenting to final data archiving.

## 👥 Team Members & Roles
* **Nguyen Van Dat** - Backend Developer (Role-Based Access Control, Closure Dates Logic, Data Export CSV/ZIP).
* **[Tên Bạn 2]** - Frontend Developer (Responsive UI, Dashboard, Pagination).
* **[Tên Bạn 3]** - Database Designer & Tester (ERD, SQL Schema, Test Logs).

## 🚀 Key Technical Features
* **Role-Based Access Control (RBAC):** Custom Middleware ensures that only authorized users (Admin, QA Manager, QA Coordinator, Staff) can access specific administrative functions.
* **Academic Year Governance:** A dual-stage closure system. After the first **Closure Date**, new ideas are disabled. After the **Final Closure Date**, all commenting features are locked.
* **Interaction System:** Staff can submit ideas (with optional document uploads), comment anonymously, and give a "Thumbs Up/Down" (limited to once per idea).
* **Automated Notifications:** Email alerts are sent to QA Coordinators upon new submissions and to authors when their ideas receive comments.
* **Data Management:** High-level reporting for QA Managers, including **CSV export** for system data and **ZIP download** for all supporting documents.

## 🛠️ Tech Stack
* **Framework:** Laravel 11.x (PHP 8.2.12).
* **Database:** MySQL.
* **Frontend:** Blade Template Engine & Bootstrap (Responsive Design).
* **Tools:** XAMPP, Composer, Git/GitHub.

## 🔧 Installation & Setup
1. Clone the repository:
   ```bash
   git clone [https://github.com/vkm409147/uni-ideas-laravel.git](https://github.com/vkm409147/uni-ideas-laravel.git)
