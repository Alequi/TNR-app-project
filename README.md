# 📘 **README.md — CES Gatos Elche Reservation System**

##  Project Description
This project is a web application designed to manage **neutering campaign reservations** and **trap/cage loan control** for CES Gatos Elche, an organization dedicated to the care and TNR (Trap–Neuter–Return) of community cats in the city of Elche.

The system centralizes and digitizes key operational processes:

- Annual neutering campaigns  
- Booking veterinary clinic appointments  
- Managing feline colonies  
- Registering and coordinating volunteers  
- Tracking cage and trap loans  
- Monitoring clinic capacity and daily workload  

The application includes two main user roles: **Volunteer** and **Administrator**, each with specific permissions and functionalities.

---

##  Main Objectives
- Simplify and automate the **reservation process** for neutering campaigns.  
- Provide efficient management of **clinics, time slots, and campaign dates**.  
- Track **physical cages** and maintain all loan records.  
- Offer administrators a centralized platform to control users, colonies, bookings, and resources.  
- Improve volunteer coordination and minimize manual errors.

---

##  User Roles

###  Volunteer
A user assigned to a specific TNR colony.

**Capabilities:**
- Log in and access a personal dashboard  
- View available clinic time slots  
- Create reservations (clinic, date, time slot, number of cats)  
- Automatically view the **reverse pickup rule** (morning → afternoon, afternoon → next morning)  
- Modify or cancel active reservations  
- View and manage **their own** cage loans  
- View personal loan history  
- Edit personal profile (name, phone, email, password)

---

###  Administrator
Full access to the entire system.

**Capabilities:**
- Access a full admin dashboard with global statistics  
- Create, update, or deactivate campaigns  
- Manage clinics and capacities  
- Manage time slots (morning/afternoon)  
- View all reservations in the system  
- Modify or cancel reservations  
- Manage colonies (create, edit, assign colony managers)  
- Manage users (roles, activation, colony assignment)  
- Manage cage inventory:
  - Add, edit, disable cages  
  - Loan cages to any volunteer  
  - Register returns  
  - Record incidents  
  - View complete loan history

---

##  Main Modules of the Project

### 1. **Authentication**
- Login  
- User registration  
- Password recovery  
- Role-based session control  

### 2. **Volunteer Panel**
- Personal dashboard  
- Reservation list  
- New reservation form  
- Slot availability viewer  
- Cage loan management  
- Profile management  

### 3. **Admin Panel**
- Main dashboard with campaign status and statistics  
- Access to full management modules  

### 4. **Campaign Management**
- Create/edit campaigns  
- Activate/deactivate campaigns  
- Link campaigns with clinics and time slots  

### 5. **Clinic Management**
- Register/edit clinics  
- Set capacity for each time slot  
- View occupancy  

### 6. **Time Slot Management**
- CRUD for shifts (morning/afternoon)  
- Real-time capacity control  

### 7. **Reservation Management**
- Create/modify/cancel reservations  
- Automatic reverse pickup calculation  
- Global booking list with filters  

### 8. **Cage Management**
- Full cage inventory (`cages`)  
- Track loaned cages (`cage_loans`)  
- Record returns and incidents  
- Full history view  

### 9. **Colony Management**
- Register colonies  
- Assign colony managers  
- Manage volunteer–colony relationships  

### 10. **User Management**
- Add/edit users  
- Assign roles  
- Activate/deactivate accounts  
- Assign colonies  

---

##  Technologies Used

- **Frontend:** Bootstrap + Vanilla JavaScript   
- **Backend:** PHP  
- **Database:** MySQL / MariaDB  
- **Architecture:** MVC-style separation (controllers, models, views)  
- **Development environment:** XAMPP/WAMP  
- **Version control:** Git + GitHub  
- **Responsive design:** CSS Grid & Flexbox  
- **Documentation:** UML diagrams and SQL schema  

---

##  Database Structure (Summary)

| Table | Description |
|-------|-------------|
| `campaigns` | Neutering campaigns |
| `clinics` | Veterinary clinics |
| `cage_types` | Types of cages/traps |
| `cages` | Individual cages |
| `clinic_cages` | Cage stock per clinic |
| `colonies` | Cat colonies |
| `users` | Volunteers & admins |
| `shifts` | Time slots (morning/afternoon) |
| `bookings` | Reservation records |
| `cage_loans` | Cage loan records |

The entire schema uses **foreign keys**, **cascading rules**, and **normalization** for consistency.

---

## Project Status
- ✔ Database design finalized  
- ✔ Entity relationship model and FKs implemented  
- ✔  Full screenflow and UX/UI structure completed  
- ⬜ Phase 2: Building frontend views  
- ⬜ Phase 3: Implementing PHP backend logic  

---

## 📝 Author
Developed by **Alejandro Quiera**, 2nd year DAW student, as part of the **Intermodular Project**.
