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

##  Project Status

### ✅ Completed Modules

#### 1. **Authentication System**
- ✔ User login with role-based redirection
- ✔ User registration with email validation
- ✔ Password recovery system
- ✔ Session management with `auth.php` helper
- ✔ Role verification (`admin()`, `login()`, `isLoggedIn()`)

#### 2. **Admin Panel**
- ✔ Dashboard with campaign statistics
- ✔ Real-time clinic capacity and occupancy
- ✔ Volunteer, colony, and booking counters
- ✔ Protected admin-only routes

#### 3. **User Management (Admin)**
- ✔ Complete CRUD operations
- ✔ User listing with aggregated data (active bookings, borrowed cages)
- ✔ Create new users with role assignment
- ✔ Edit users (optional password change, colony assignment)
- ✔ Activate/deactivate users
- ✔ Filter and search capabilities
- ✔ AJAX-based operations with JSON responses

#### 4. **Cage Management (Admin)**
- ✔ Complete cage inventory system
- ✔ CRUD operations for cages
- ✔ Cage types and clinic assignments
- ✔ Advanced filtering (type, clinic, availability)
- ✔ Transactional integrity with `clinic_cages` table
- ✔ Real-time availability tracking

#### 5. **Booking Management**
- ✔ View all reservations (admin)
- ✔ Booking statistics (pending, in clinic, completed)
- ✔ Update and cancel bookings
- ✔ Integration with shifts and clinics

#### 6. **Security Implementation**
- ✔ All admin pages protected with `admin()` check
- ✔ All admin actions protected against unauthorized access
- ✔ Statistics endpoints secured
- ✔ Session validation on all protected routes
- ✔ SQL injection prevention with prepared statements
- ✔ Password hashing with `PASSWORD_BCRYPT`
- ✔ Email uniqueness validation

### 🚧 In Progress / Pending

#### Phase 1: Core Features (Remaining)
- ⬜ Campaign CRUD (admin)
- ⬜ Clinic CRUD (admin)
- ⬜ Colony CRUD (admin)
- ⬜ Shift/turn management (admin)

#### Phase 2: Volunteer Features
- ⬜ Volunteer dashboard
- ⬜ Create new bookings (volunteer)
- ⬜ View own bookings (volunteer)
- ⬜ Cancel own bookings (volunteer)
- ⬜ Cage loan requests (volunteer)
- ⬜ View own cage loans (volunteer)
- ⬜ Profile management (volunteer)

#### Phase 3: Advanced Features
- ⬜ Reverse pickup rule automation
- ⬜ Cage loan incident tracking
- ⬜ Email notifications
- ⬜ Reporting and analytics
- ⬜ Export functionality (PDF/Excel)

---

##  Technical Implementation Details

### Architecture
- **Pattern:** MVC-inspired structure
- **Frontend:** Bootstrap 5.3.0 + Vanilla JavaScript (ES6+)
- **Backend:** PHP 8.0+ with PDO
- **AJAX:** Fetch API with JSON request/response
- **Modals:** Bootstrap modals for forms
- **State Management:** Server-side sessions

### Code Quality Standards
- ✔ Prepared statements for all database queries
- ✔ Transaction support for multi-table operations
- ✔ Proper error handling with try-catch blocks
- ✔ JSON responses for AJAX endpoints
- ✔ Session status checks before `session_start()`
- ✔ Input validation and sanitization
- ✔ Optional field handling (colony_id, password updates)

### File Structure
```
TNR-app-project/
├── app/
│   ├── actions/          # Backend endpoints
│   │   ├── auth/         # Authentication actions
│   │   ├── bookings/     # Booking management
│   │   ├── clinics/      # Clinic operations
│   │   ├── jaulas/       # Cage management
│   │   └── user/         # User operations
│   └── helpers/
│       └── auth.php      # Authentication helper
├── config/
│   └── conexion.php      # Database connection
├── public/
│   ├── assets/
│   │   └── js/           # JavaScript modules
│   └── partials/         # Reusable components
└── views/
    ├── admin/            # Admin pages
    └── ...               # Other views
```

---

## 📝 Author
Developed by **Alejandro Quiera**, 2nd year DAW student, as part of the **Intermodular Project**.

**Last Updated:** January 2026
