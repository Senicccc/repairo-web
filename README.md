#  Repairo Gadget and Moobile Phone Repair Management System

Repairo is a web-based application built with **Laravel**, designed to streamline and enhance the operations of mobile phone repair shops.  
The system provides complete management of customers, repair workflows, technician processes, payments, and loyalty points.

---

## 👥 Group Project Developers

|| Name                          || Student ID    || 
||-------------------------------||---------------||
|| **Nicholas Jeremy Hendrajaya**|| 2310631170109 ||
|| **Nur Muhammad**              || 2310631170145 ||

---

## 📌 Table of Contents
- [Tech Stack](#-tech-stack)
- [Key Features](#-key-features)
- [User Roles](#-user-roles)
- [Service Workflow](#-service-workflow)
- [Project Versions](#-project-versions)
- [Installation (Development Mode)](#️-installation-development-mode)
- [Production Build](#-production-build)

---

## 🧰 Tech Stack

Repairo is built using:

- **Laravel** — Backend framework (MVC architecture)  
- **Laravel Breeze** — Authentication scaffolding  
- **Tailwind CSS** — Utility-first styling framework  
- **MySQL** — Relational database  
- **Laravel Migrations** — Database schema management  
- **Vite** — Asset bundler & build tool  
- *(Optional)* **Flowbite** — Tailwind-based UI components  

---

## ✨ Key Features

### 🔹 Customer Management
- Customer CRUD  
- Repair history  
- Loyalty point tracking  

### 🔹 Repair Management
- Device information  
- Damage diagnosis  
- Repair status tracking  
- Cost & time estimation  
- Spare part management  

### 🔹 Technician Workflow
- Claim unassigned repair jobs  
- Perform diagnosis  
- Update repair progress  
- Finalize repair tasks  

### 🔹 Payment & Invoice
- Payment processing  
- Printable invoices  
- Automatic loyalty point generation  

### 🔹 Authentication & RBAC
- Login/Register  
- Roles: Admin, Cashier, Technician, Customer  
- Role-restricted access  

---

## 👥 User Roles

### 1️⃣ Customer (User)
- View repair status  
- View & redeem loyalty points  
- Access personal dashboard  

### 2️⃣ Admin
- Full access to all modules  
- Complete CRUD controls  
- System-wide monitoring  

### 3️⃣ Cashier
- Create repair entries  
- Create customer accounts  
- Process payments  
- Print invoices  
- Assist in redeeming points  

### 4️⃣ Technician
- Claim repair jobs  
- Provide diagnosis  
- Add required spare parts  
- Update repair flow:  
  `On Progress → Waiting Sparepart → Testing → Completed`  
- Finish the repair  

---

## 🔄 Service Workflow

### **1. Repair Intake (Cashier)**
1. Customer arrives requesting repair service  
2. Cashier checks customer account:
   - If none → create a new account  
3. Cashier creates a repair entry  
4. The system generates a tracking number  
5. Customer waits for the service progress  

---

### **2. Technician Workflow**
1. Technician claims a repair job  
2. Performs damage diagnosis  
3. Inputs the following:
   - Diagnosis result  
   - Required spare parts  
   - Cost estimation  
   - Estimated repair time  
4. Updates repair status accordingly  
5. Finalizes work with **Finish Repair**  

---

### **3. Payment Process**
1. Customer returns once the repair status is **Completed**  
2. Cashier opens the repair entry → selects **Payment**  
3. Customer pays (cash/transfer/etc.)  
4. Cashier prints the invoice  
5. Customer receives loyalty points  

---

## 🗂️ Project Versions

| Branch | Description | Status |
|--------|-------------|--------|
| **main** | Development version — active feature updates | 🔧 Active Development |
| **final** / **build** | Production-ready compiled version | 🚀 Stable Release |

---

## ▶️ Installation (Development Mode)

Clone the repository:

```bash
git clone <repository-url>
cd repairo
Install dependencies:

composer install
npm install


Setup environment variables:

cp .env.example .env
php artisan key:generate


Run migrations:

php artisan migrate


Run development servers:

npm run dev
php artisan serve

🚀 Production Build
npm run build
php artisan optimize
