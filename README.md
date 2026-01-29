# � **CES Gatos Elche - TNR Management System**

## 📋 Project Overview

**CES Gatos Elche Reservation System** is a comprehensive web application designed to manage **neutering campaign reservations (CER - Captura, Esterilización, Retorno)** and **trap/cage loan control** for CES Gatos Elche, an animal welfare organization dedicated to the TNR (Trap–Neuter–Return) program for community cats in Elche, Spain.

### Core Features
The system centralizes and automates critical operational workflows:

- 📅 **Campaign Management** - Annual neutering campaigns with date tracking
- 🏥 **Clinic Coordination** - Multi-clinic booking system with capacity control
- 🗺️ **Colony Management** - Feline colony registration and volunteer assignment
- 👥 **User Administration** - Volunteer coordination and role-based access
- 📦 **Cage Inventory** - Real-time tracking of cage/trap loans and returns
- 📊 **Statistics Dashboard** - Live monitoring of operations and resources

### User Roles
- **👤 Volunteers** - Colony managers with booking and cage loan capabilities
- **🔑 Administrators** - Full system access with advanced management tools
- **🏥 Clinic Staff** - (Future) Appointment and treatment tracking

---

## 🎯 Project Objectives

### Primary Goals
1. **Streamline Reservation Process** - Eliminate manual booking coordination
2. **Resource Optimization** - Maximize clinic capacity utilization
3. **Cage Tracking** - Maintain accurate inventory and loan records
4. **Centralized Management** - Single platform for all TNR operations
5. **Volunteer Coordination** - Simplify communication and task assignment
6. **Data Integrity** - Reduce errors through validation and automation

### Business Impact
- ⏱️ **Time Savings** - Reduce administrative workload by 70%
- 📈 **Increased Capacity** - Better clinic utilization and scheduling
- 🔍 **Transparency** - Real-time visibility of all operations
- 📊 **Data-Driven Decisions** - Analytics for resource planning

---

## 👥 User Roles & Permissions

### 🟢 Volunteer (Colony Manager)
**Access Level:** Limited to own colony data

**Capabilities:**
- ✅ Personal dashboard with colony statistics
- ✅ View available clinic time slots
- ✅ Create reservations (clinic, date, shift, cat count)
- ✅ View **reverse pickup rule** (AM drop → PM pickup, PM drop → next AM pickup)
- ✅ Modify/cancel own active reservations
- ✅ Request cage loans for colony
- ✅ View own cage loan history
- ✅ Update personal profile (name, phone, email, password)

### 🔴 Administrator
**Access Level:** Full system access

**Capabilities:**
- ✅ Admin dashboard with global statistics and KPIs
- ✅ **Campaign Management** - Create, edit, activate/deactivate campaigns
- ✅ **Clinic Management** - Register clinics, set capacities (morning/afternoon)
- ✅ **Shift Management** - Generate and manage daily time slots
- ✅ **Booking Management** - View, modify, cancel all reservations
- ✅ **Colony Management** - Create colonies, assign volunteers as managers
- ✅ **User Management** - Full CRUD with role assignment and activation
- ✅ **Cage Inventory** - Add/edit cages, track loans, record returns
- ✅ **Statistics** - Real-time dashboards with filterable data
- ✅ **Security** - Protected routes with session validation

---

## 🗂️ Database Schema

### Tables Overview

| Table | Records | Description | Key Relationships |
|-------|---------|-------------|-------------------|
| `users` | User accounts | Volunteers & admins | → `colonies` (colony_id) |
| `colonies` | Cat colonies | TNR colony locations | → `users` (gestor_id) |
| `campaigns` | Neutering campaigns | Annual CER campaigns | ← `shifts` |
| `clinics` | Veterinary clinics | Partner clinics | ← `shifts`, `clinic_cages` |
| `shifts` | Daily time slots | Morning/Afternoon slots | → `clinics`, `campaigns` |
| `bookings` | Reservations | Neutering appointments | → `users`, `colonies`, `shifts` |
| `cage_types` | Cage categories | Trap types (3 types) | ← `cages` |
| `cages` | Individual cages | Cage inventory | → `clinics`, `cage_types` |
| `clinic_cages` | Inventory tracking | Stock per clinic/type | → `clinics`, `cage_types` |
| `cage_loans` | Loan records | Borrowing history | → `users`, `cages`, `colonies` |

### Database Features
- 🔐 **Foreign Keys** with cascading rules for referential integrity
- 🔄 **Normalized Schema** to 3NF for consistency
- 📊 **Indexed Columns** for query optimization
- 🛡️ **Constraints** - UNIQUE, NOT NULL, CHECK validations
- 🗓️ **Timestamps** - `created_at` for audit trails

### Key Field Details

**`users` table:**
- `rol` ENUM: `'admin'`, `'gestor'`, `'voluntario'`
- `activo` BOOLEAN: Account status
- `colony_id` FK: Optional colony assignment

**`bookings` table:**
- `estado` ENUM: `'reservado'`, `'en_clinica'`, `'completado'`, `'cancelado'`
- `fecha_drop/pick` DATE: Drop-off and pickup dates
- `turno_drop/pick` ENUM: `'M'` (morning), `'T'` (afternoon)

**`cage_loans` table:**
- `estado` ENUM: `'prestado'`, `'devuelto'`, `'extraviado'`
- `fecha_prestamo/devolucion` DATE: Loan lifecycle
- `observaciones` TEXT: Incident notes

**`clinic_cages` table:**
- `cantidad_total` INT: Total inventory
- `cantidad_prestada` INT: Currently loaned out
- Auto-calculated availability

---

## 🛠️ Technology Stack

### Frontend
- **Framework:** Bootstrap 5.3.0
- **Icons:** Bootstrap Icons 1.11.0
- **JavaScript:** Vanilla ES6+ (no frameworks)
- **AJAX:** Fetch API with JSON
- **Modals:** Bootstrap modal system
- **Forms:** HTML5 validation + custom validation

### Backend
- **Language:** PHP 8.0.30
- **Database:** MariaDB 10.4.32
- **PDO:** Prepared statements for security
- **Sessions:** Server-side session management
- **Architecture:** MVC-inspired separation

### Development Environment
- **Stack:** XAMPP/WAMP
- **Server:** Apache
- **Database:** phpMyAdmin
- **Version Control:** Git + GitHub
- **Editor:** VS Code (recommended)

### Security
- 🔒 **Password Hashing:** `PASSWORD_BCRYPT`
- 🛡️ **SQL Injection Protection:** PDO prepared statements
- 🚪 **Session Validation:** Role-based access control
- ✅ **Input Sanitization:** `filter_var()` and validation
- 🔐 **CSRF Protection:** (Planned)

---

## 📂 Project Structure

```
TNR-app-project/
│
├── 📁 app/                          # Backend logic
│   ├── 📁 actions/                  # API endpoints (JSON responses)
│   │   ├── 📁 auth/                 # Authentication
│   │   │   ├── login_action.php
│   │   │   ├── register_action.php
│   │   │   ├── logout_action.php
│   │   │   ├── recuperar_action.php
│   │   │   └── change_password_action.php
│   │   ├── 📁 user/                 # User management
│   │   │   ├── get_users_action.php           # List all users
│   │   │   ├── create_user_action.php         # Create user (admin)
│   │   │   ├── update_user_action.php         # Edit user (admin)
│   │   │   ├── update_profile_action.php      # Self-edit profile
│   │   │   ├── volunteers_stats_action.php    # Stats
│   │   │   └── colonies_stats_action.php      # Stats
│   │   ├── 📁 jaulas/               # Cage management
│   │   │   ├── get_cages_action.php           # List cages
│   │   │   ├── create_cage_action.php         # Create cage
│   │   │   ├── jaulas_action.php              # Cage operations
│   │   │   └── jaulas_general_action.php      # Stats
│   │   ├── 📁 bookings/             # Booking management
│   │   │   ├── user_bookings_action.php       # User's bookings
│   │   │   ├── bookings_stats_action.php      # All bookings (admin)
│   │   │   ├── new_booking_action.php         # Create booking
│   │   │   ├── cancel_booking_action.php      # Cancel booking
│   │   │   ├── update_booking_action.php      # Edit booking
│   │   │   └── available_shifts.php           # Available slots
│   │   ├── 📁 clinics/              # Clinic management
│   │   │   └── general_clinics_action.php     # Stats
│   │   ├── campaign_stats_action.php          # Campaign stats
│   │   └── validaciones.php                   # Shared validations
│   │
│   └── 📁 helpers/                  # Utility functions
│       └── auth.php                 # Auth helpers (admin(), login())
│
├── 📁 config/                       # Configuration
│   └── conexion.php                 # Database connection (PDO)
│
├── 📁 public/                       # Public assets
│   ├── 📁 assets/
│   │   ├── 📁 js/                   # JavaScript modules
│   │   │   ├── userManagement.js   # User CRUD (fetch)
│   │   │   ├── modalEditUser.js    # Edit modal population
│   │   │   ├── cageManagement.js   # Cage CRUD + filtering
│   │   │   ├── booking.js          # Booking creation
│   │   │   ├── cancelBooking.js    # Cancel bookings
│   │   │   ├── updateBooking.js    # Edit bookings
│   │   │   ├── filter.js           # Table filtering
│   │   │   └── validation.js       # Form validation
│   │   ├── 📁 brand/                # Logo files
│   │   └── 📁 dist/css/             # Custom styles
│   ├── 📁 img/                      # Images
│   ├── 📁 partials/                 # Reusable components
│   │   ├── header.php
│   │   └── footer.php
│   ├── login.php                    # Login page
│   ├── registro.php                 # Registration page
│   ├── recuperar_pass.php           # Password recovery
│   └── about.html                   # About page
│
├── 📁 views/                        # Application views
│   ├── 📁 admin/                    # Admin-only pages
│   │   ├── adminPanel.php           # Admin dashboard
│   │   ├── usersAdmin.php           # User management
│   │   ├── jaulasAdmin.php          # Cage management
│   │   └── bookingAdmin.php         # All bookings
│   ├── panel.php                    # Volunteer dashboard
│   ├── booking.php                  # Create booking
│   ├── userBookings.php             # User's bookings
│   ├── jaulas.php                   # Cage loans (volunteer)
│   ├── userProfile.php              # Profile edit
│   └── userColony.php               # Colony details
│
├── 📄 index.html                    # Landing page
├── 📄 reservas_db.sql               # Database schema + sample data
└── 📄 README.md                     # This file
```

---

## 🚀 Installation & Setup

### Prerequisites
- PHP 8.0+
- MariaDB/MySQL 10.4+
- Apache Server (via XAMPP/WAMP)
- Git (optional)

### Step 1: Clone Repository
```bash
git clone https://github.com/your-username/TNR-app-project.git
cd TNR-app-project
```

### Step 2: Database Setup
1. Start XAMPP/WAMP and launch **phpMyAdmin**
2. Import the database:
   - Open `reservas_db.sql` in phpMyAdmin
   - Click **Import** and select the file
   - Database `ces_reservas` will be created automatically

### Step 3: Configure Database Connection
Edit `config/conexion.php`:
```php
$host = 'localhost';
$port = '3308';  // Change if needed (default: 3306)
$db = 'ces_reservas';
$user = 'root';
$password = '';  // Your MySQL password
```

### Step 4: Move to Server Directory
```bash
# For XAMPP
mv TNR-app-project C:/xampp/htdocs/

# For WAMP
mv TNR-app-project C:/wamp64/www/
```

### Step 5: Access Application
Open browser and navigate to:
```
http://localhost/TNR-app-project/
```

### Default Credentials
**Admin Account:**
- Email: `admin@ces.test`
- Password: `00000`

**Volunteer Account:**
- Email: `vol1@ces.test`
- Password: `0000`

### Step 6: Test Installation
1. Login with admin credentials
2. Check admin dashboard loads
3. Navigate to Users section
4. Verify database connection works

---

## 📊 Current Implementation Status

### ✅ Fully Implemented Modules

#### 1. Authentication System (100%)
- ✅ User login with role-based redirection
- ✅ User registration with email validation
- ✅ Password recovery (email pending)
- ✅ Session management with security helpers
- ✅ Role verification: `admin()`, `login()`, `isLoggedIn()`
- ✅ Logout functionality

**Files:**
- `app/actions/auth/login_action.php`
- `app/actions/auth/register_action.php`
- `app/actions/auth/logout_action.php`
- `app/helpers/auth.php`

#### 2. Admin Dashboard (100%)
- ✅ Campaign statistics (active campaign display)
- ✅ Real-time clinic capacity and occupancy
- ✅ Volunteer count and colony statistics
- ✅ Booking counters (today's reservations)
- ✅ Monthly neutering stats
- ✅ Protected admin-only routes
- ✅ Upcoming reservations table (7 days)

**Files:**
- `views/admin/adminPanel.php`
- `app/actions/campaign_stats_action.php`
- `app/actions/clinics/general_clinics_action.php`
- `app/actions/jaulas/jaulas_general_action.php`

#### 3. User Management (100%)
- ✅ User listing with LEFT JOIN aggregations
  - Active bookings count
  - Borrowed cages count
- ✅ Create new users (admin)
  - Role assignment: admin/gestor/voluntario
  - Optional colony assignment
  - Email uniqueness validation
  - Password minimum 4 characters
- ✅ Edit users (admin)
  - Optional password change
  - Update all fields
  - Activate/deactivate accounts
- ✅ AJAX-based CRUD with JSON responses
- ✅ Bootstrap modals for forms
- ✅ Real-time form validation

**Files:**
- `views/admin/usersAdmin.php`
- `app/actions/user/get_users_action.php`
- `app/actions/user/create_user_action.php`
- `app/actions/user/update_user_action.php`
- `public/assets/js/userManagement.js`
- `public/assets/js/modalEditUser.js`

#### 4. Cage Management (100%)
- ✅ Complete cage inventory system
- ✅ CRUD operations for cages
  - Create cage with clinic assignment
  - Cage type selection
  - Internal numbering
- ✅ Advanced filtering
  - By cage type
  - By clinic
  - By availability status
- ✅ Transactional integrity
  - Updates `clinic_cages` table
  - Prevents duplicate cage numbers per clinic
- ✅ Real-time availability tracking
- ✅ Bootstrap modals for forms

**Files:**
- `views/admin/jaulasAdmin.php`
- `app/actions/jaulas/get_cages_action.php`
- `app/actions/jaulas/create_cage_action.php`
- `public/assets/js/cageManagement.js`

#### 5. Booking Management (80%)
- ✅ View all reservations (admin)
- ✅ Booking statistics
  - Pending count
  - In clinic count
  - Completed count
  - Total cats processed
- ✅ Update bookings (admin)
- ✅ Cancel bookings
- ✅ Integration with shifts and clinics
- ⬜ Create new booking (volunteer) - In progress
- ⬜ Reverse pickup rule automation - Pending

**Files:**
- `views/admin/bookingAdmin.php`
- `app/actions/bookings/bookings_stats_action.php`
- `app/actions/bookings/update_booking_action.php`
- `app/actions/bookings/cancel_booking_action.php`
- `public/assets/js/updateBooking.js`
- `public/assets/js/cancelBooking.js`

#### 6. Security Implementation (100%)
- ✅ All admin pages protected with `admin()` check
  - `adminPanel.php`
  - `usersAdmin.php`
  - `jaulasAdmin.php`
  - `bookingAdmin.php`
- ✅ All admin actions protected
  - User CRUD endpoints
  - Cage CRUD endpoints
  - Booking management endpoints
  - Statistics endpoints
- ✅ Session validation on all protected routes
- ✅ SQL injection prevention (prepared statements)
- ✅ Password hashing with `PASSWORD_BCRYPT`
- ✅ Email uniqueness validation
- ✅ Input sanitization with `filter_var()`
- ✅ Session status checks before `session_start()`

**Security Features:**
```php
// Session check before starting
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Role verification
function admin() {
    login();  // Ensure authenticated
    if ($_SESSION['rol'] !== 'admin') {
        header("Location: ../../public/login.php");
        exit();
    }
}

// Prepared statements
$stmt = $con->prepare("SELECT * FROM users WHERE email = :email");
$stmt->execute([':email' => $email]);

// Password hashing
$password_hash = password_hash($password, PASSWORD_BCRYPT);
```

### 🚧 Partially Implemented

#### 7. Volunteer Features (30%)
- ✅ Volunteer views created
  - `panel.php` - Dashboard
  - `booking.php` - Create booking
  - `userBookings.php` - View bookings
  - `jaulas.php` - Cage loans
  - `userProfile.php` - Profile edit
- ⬜ Backend actions for volunteers
- ⬜ Booking creation workflow
- ⬜ Profile update functionality
- ⬜ Cage loan request system

**Pending Files:**
- `app/actions/bookings/new_booking_action.php` (exists but needs testing)
- `app/actions/user/update_profile_action.php` (exists but needs implementation)

### ⬜ Not Started

#### 8. Campaign Management (0%)
- ⬜ Campaign CRUD interface
- ⬜ Activate/deactivate campaigns
- ⬜ Date range validation
- ⬜ Link campaigns with clinics

#### 9. Clinic Management (0%)
- ⬜ Clinic CRUD interface
- ⬜ Capacity configuration
- ⬜ Contact information management

#### 10. Colony Management (0%)
- ⬜ Colony CRUD interface
- ⬜ Assign colony managers
- ⬜ Volunteer-colony relationships

#### 11. Shift Management (0%)
- ⬜ Generate daily shifts
- ⬜ Capacity control per shift
- ⬜ Bulk shift creation

#### 12. Advanced Features (0%)
- ⬜ Reverse pickup rule automation
- ⬜ Cage loan incident tracking
- ⬜ Email notifications (SMTP)
- ⬜ PDF/Excel export
- ⬜ Reporting dashboards
- ⬜ Mobile responsive optimization

---

## 🔌 API Endpoints

### Authentication
| Endpoint | Method | Auth | Description |
|----------|--------|------|-------------|
| `/app/actions/auth/login_action.php` | POST | None | User login |
| `/app/actions/auth/register_action.php` | POST | None | User registration |
| `/app/actions/auth/logout_action.php` | GET | Session | Logout |

### User Management (Admin Only)
| Endpoint | Method | Auth | Description |
|----------|--------|------|-------------|
| `/app/actions/user/get_users_action.php` | GET | Admin | List all users + stats |
| `/app/actions/user/create_user_action.php` | POST | Admin | Create user |
| `/app/actions/user/update_user_action.php` | POST | Admin | Update user |

**Request/Response Example:**
```json
// POST /app/actions/user/create_user_action.php
{
  "nombre": "Juan",
  "apellido": "Pérez",
  "email": "juan@example.com",
  "password": "SecurePass123",
  "telefono": "666777888",
  "rol": "voluntario",
  "colony_id": "5"  // Optional
}

// Response
{
  "success": true,
  "message": "Usuario creado exitosamente."
}
```

### Cage Management (Admin Only)
| Endpoint | Method | Auth | Description |
|----------|--------|------|-------------|
| `/app/actions/jaulas/get_cages_action.php` | GET | Admin | List all cages |
| `/app/actions/jaulas/create_cage_action.php` | POST | Admin | Create cage |

### Bookings
| Endpoint | Method | Auth | Description |
|----------|--------|------|-------------|
| `/app/actions/bookings/bookings_stats_action.php` | GET | Admin | All bookings |
| `/app/actions/bookings/update_booking_action.php` | POST | Admin | Update booking |
| `/app/actions/bookings/cancel_booking_action.php` | POST | User | Cancel booking |

---

## 💡 Code Quality & Best Practices

### PHP Standards
```php
// ✅ Always check session status
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ✅ Use prepared statements
$stmt = $con->prepare("INSERT INTO users (email) VALUES (:email)");
$stmt->execute([':email' => $email]);

// ✅ Validate all inputs
$email = filter_var($data['email'], FILTER_VALIDATE_EMAIL);

// ✅ Handle optional fields
$colony_id = isset($data['colony_id']) && $data['colony_id'] !== '' 
    ? filter_var($data['colony_id'], FILTER_VALIDATE_INT) 
    : null;

// ✅ Use transactions for multi-table operations
$con->beginTransaction();
try {
    // Multiple queries
    $con->commit();
} catch (PDOException $e) {
    $con->rollBack();
    throw $e;
}

// ✅ JSON responses for AJAX
header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'OK']);
exit;
```

### JavaScript Standards
```javascript
// ✅ Use Fetch API with async/await
const response = await fetch('endpoint.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(formData)
});
const result = await response.json();

// ✅ Handle errors gracefully
try {
    // API call
} catch (error) {
    alert('Error: ' + error.message);
    console.error('Error:', error);
}

// ✅ Close Bootstrap modals properly
const modalInstance = bootstrap.Modal.getInstance(modal);
modalInstance.hide();
location.reload();  // Refresh to show $_SESSION message
```

---

## 🐛 Known Issues & Limitations

### Current Limitations
1. **Email Notifications** - Not implemented (password recovery shows message only)
2. **CSRF Protection** - Not implemented
3. **Mobile Optimization** - Partial responsive design
4. **File Uploads** - No file upload for user avatars or cage photos
5. **Audit Logs** - No activity tracking
6. **API Rate Limiting** - Not implemented
7. **Input Validation** - Client-side only (needs server-side enhancement)

### Browser Compatibility
- ✅ Chrome 90+
- ✅ Firefox 88+
- ✅ Edge 90+
- ⚠️ Safari (partial testing)
- ❌ IE11 (not supported)

---

## 🗺️ Roadmap

### Phase 1: Core Completion (Q1 2026)
- [ ] Campaign CRUD
- [ ] Clinic CRUD  
- [ ] Colony CRUD
- [ ] Shift generation system
- [ ] Volunteer booking workflow
- [ ] Profile management (volunteer)

### Phase 2: Advanced Features (Q2 2026)
- [ ] Reverse pickup automation
- [ ] Cage loan workflow for volunteers
- [ ] Email notification system (SMTP)
- [ ] PDF export (bookings, cage loans)
- [ ] Advanced filtering and search

### Phase 3: Optimization (Q3 2026)
- [ ] Mobile app (PWA)
- [ ] API documentation (Swagger)
- [ ] Performance optimization
- [ ] Comprehensive testing suite
- [ ] Security audit

---

## 👨‍💻 Development Guidelines

### Adding New Features
1. Create database migrations if needed
2. Implement backend action in `app/actions/`
3. Add auth protection (`admin()` or `login()`)
4. Create/update view in `views/`
5. Add JavaScript in `public/assets/js/`
6. Test thoroughly with real data
7. Update this README

### Code Review Checklist
- [ ] Prepared statements for all queries
- [ ] Input validation and sanitization
- [ ] Error handling with try-catch
- [ ] Session authentication
- [ ] JSON responses for AJAX
- [ ] No SQL injection vulnerabilities
- [ ] No XSS vulnerabilities
- [ ] Responsive design tested

---

## 📝 Author & Contact

**Developed by:** Alejandro Quiera  
**Role:** 2nd Year DAW Student (Desarrollo de Aplicaciones Web)  
**Project Type:** Intermodular Project (Proyecto Intermodular)  
**Institution:** [Your School Name]  
**Academic Year:** 2025-2026

### Contact
- 📧 Email: a.quivera1991@gmail.com
- 💼 GitHub: [Your GitHub Profile]
- 🌐 Organization: CES Gatos Elche

---

## 📜 License

This project is developed for educational purposes as part of the DAW curriculum. All rights reserved by CES Gatos Elche for production use.

---

## 🙏 Acknowledgments

- **CES Gatos Elche** - For providing real-world requirements and use cases
- **Bootstrap Team** - For the excellent UI framework
- **DAW Faculty** - For guidance and support throughout development
- **Open Source Community** - For tools and inspiration

---

**Last Updated:** January 29, 2026  
**Version:** 1.0.0 (Beta)  
**Database Version:** 1.0  
**Project Status:** 🚧 In Active Development (65% Complete)
