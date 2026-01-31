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
- ✅ View available clinic time slots for active campaigns
- ✅ Create reservations (select clinic, date, shift, cat count)
- ✅ View **reverse pickup rule** (AM drop → PM pickup, PM drop → next AM pickup)
- ✅ Modify/cancel own active reservations
- ✅ Request cage loans for colony operations
- ✅ View own cage loan history
- ✅ Update personal profile (name, phone, email, password)
- ✅ View colony details and assigned volunteers

### 🔴 Administrator
**Access Level:** Full system access

**Capabilities:**
- ✅ **Admin Dashboard** - Global KPIs, active campaigns, clinic utilization, cage availability
- ✅ **Campaign Management** 
  - Create campaigns with date ranges (fecha_inicio, fecha_fin)
  - Edit campaign details (name, dates)
  - Finalize campaigns (sets activa = 0, prevents reactivation)
  - **Business rule:** Only one active campaign allowed at a time
  - **Validation:** Cannot finalize if active bookings exist in future shifts
- ✅ **Clinic Management**
  - Register clinics with contact info and location
  - Set AM/PM capacities independently
  - Edit clinic details (name, address, phone, capacities)
  - Activate/deactivate clinics
- ✅ **Colony Management**
  - Create colonies with unique codes (ELC-XXX format)
  - Assign volunteer managers (gestor_id)
  - Edit colony information
  - Activate/deactivate colonies
- ✅ **Shift Management**
  - Create daily shifts for active campaigns
  - Bulk shift creation by date range
  - Set capacity per shift (inherits from clinic)
  - Delete unused shifts
  - Track real-time occupancy
- ✅ **Booking Management**
  - View all reservations with filters (campaign, clinic, colony, status)
  - Modify booking details
  - Cancel reservations
  - Track booking states: reservado, entregado_vet, listo_recoger, recogido, cancelado
- ✅ **User Management**
  - Full CRUD for users (volunteers and admins)
  - Assign roles (admin, voluntario)
  - Activate/deactivate accounts
  - Assign users to colonies
- ✅ **Cage Inventory**
  - Register cages by clinic, type, and internal number
  - Edit cage details (numero_interno, activo status only)
  - **Data integrity:** Cannot modify clinic_id or cage_type_id of existing cages
  - **Validation:** Cannot edit cages that are currently on loan (estado = 'prestado')
  - Track cage loans (borrower, colony, dates, return status)
  - Filter cages by type, clinic, availability
- ✅ **Statistics** - Real-time dashboards with filterable data across all modules

---

## 🗂️ Database Schema

### Tables Overview

| Table | Records | Description | Key Relationships |
|-------|---------|-------------|-------------------|
| `users` | User accounts | Volunteers & admins | → `colonies` (colony_id) |
| `colonies` | Cat colonies | TNR colony locations | → `users` (gestor_id), ← `bookings` |
| `campaigns` | Neutering campaigns | CER campaigns with date ranges | ← `shifts` |
| `clinics` | Veterinary clinics | Partner clinics | ← `shifts`, `clinic_cages`, `cages` |
| `shifts` | Daily time slots | AM/PM slots per clinic | → `clinics`, `campaigns`, ← `bookings` |
| `bookings` | Reservations | Neutering appointments | → `users`, `colonies`, `shifts` |
| `cage_types` | Cage categories | Trap types (Trampa, Drop, Transportín) | ← `cages`, `clinic_cages` |
| `cages` | Individual cages | Cage inventory by clinic | → `clinics`, `cage_types`, ← `cage_loans` |
| `clinic_cages` | Inventory summary | Total & loaned cages per clinic/type | → `clinics`, `cage_types` |
| `cage_loans` | Loan records | Borrowing history with dates | → `users`, `cages`, `colonies`, `clinics` |

### Database Features
- 🔐 **Foreign Keys** with cascading rules for referential integrity
- 🔄 **Normalized Schema** to 3NF for consistency
- 📊 **Indexed Columns** for query optimization
- 🛡️ **Constraints** - UNIQUE, NOT NULL, CHECK validations
- 🗓️ **Timestamps** - `created_at` for audit trails

### Key Field Details

**`users` table:**
- `rol` ENUM: `'admin'`, `'voluntario'`
- `activo` TINYINT(1): Account status (1 = active, 0 = inactive)
- `colony_id` FK: Optional colony assignment
- `pass` VARCHAR(255): Bcrypt hashed password

**`campaigns` table:**
- `nombre` VARCHAR(100): Campaign name
- `fecha_inicio` DATE: Campaign start date
- `fecha_fin` DATE: Campaign end date
- `activa` TINYINT(1): Active status (1 = active, 0 = finalized)
- **Business rule:** Only one campaign can have `activa = 1` at a time

**`clinics` table:**
- `nombre` VARCHAR(100): Clinic name
- `capacidad_ma` INT: Morning (mañana) capacity
- `capacidad_ta` INT: Afternoon (tarde) capacity
- `telefono` VARCHAR(30): Contact phone
- `direccion` VARCHAR(200): Physical address

**`shifts` table:**
- `clinic_id` FK → `clinics`
- `campaign_id` FK → `campaigns`
- `fecha` DATE: Shift date
- `turno` ENUM: `'M'` (mañana), `'T'` (tarde)
- `capacidad` INT: Inherited from clinic
- `ocupados` INT: Current bookings count
- UNIQUE(`clinic_id`, `campaign_id`, `fecha`, `turno`)

**`bookings` table:**
- `estado` ENUM: `'reservado'`, `'entregado_vet'`, `'listo_recoger'`, `'recogido'`, `'cancelado'`
- `fecha_drop` DATE: Drop-off date
- `turno_drop` ENUM: `'M'`, `'T'`
- `fecha_pick` DATE: Pickup date
- `turno_pick` ENUM: `'M'`, `'T'`
- `gatos_count` INT: Number of cats in booking

**`cages` table:**
- `clinic_id` FK → `clinics`: Owning clinic
- `cage_type_id` FK → `cage_types`: Cage type
- `numero_interno` VARCHAR(11): Internal identifier (e.g., "J-001")
- `activo` TINYINT(1): Active status
- UNIQUE(`clinic_id`, `numero_interno`): Prevents duplicates per clinic
- **Data integrity:** `clinic_id` and `cage_type_id` cannot be modified after creation

**`cage_loans` table:**
- `estado` ENUM: `'prestado'`, `'devuelto'`
- `fecha_prestamo` DATETIME: Loan start
- `fecha_devolucion` DATETIME: Actual return date
- `fecha_prevista_devolucion` DATETIME: Expected return
- `observaciones` VARCHAR(130): Loan notes
- `from_clinic_id` FK: Originating clinic

**`clinic_cages` table:**
- `cantidad_total` INT: Total inventory per clinic/type
- `cantidad_prestada` INT: Currently loaned cages
- Derived availability: `cantidad_total - cantidad_prestada`

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
- **PDO:** Prepauser_action.php                # User details
│   │   │   ├── volunteers_stats_action.php    # Volunteer stats
│   │   │   ├── colonies_stats_action.php      # Colony stats
│   │   │   └── colony_volunteers_action.php   # Colony volunteer list
│   │   ├── 📁 campaigns/            # Campaign management
│   │   │   ├── get_campaigns_action.php       # List campaigns
│   │   │   ├── create_campaign_action.php     # Create campaign
│   │   │   ├── update_campaigns_action.php    # Edit campaign
│   │   │   └── end_campaign_action.php        # Finalize campaign
│   │   ├── 📁 clinics/              # Clinic management
│   │   │   ├── get_clinics_action.php         # List clinics
│   │   │   ├── create_clinic_action.php       # Create clinic
│   │   │   ├── update_clinic_action.php       # Edit clinic
│   │   │   ├── activate_clinic_action.php     # Activate clinic
│   │   │   ├── deactivate_clinic_action.php   # Deactivate clinic
│   │   │   └── general_clinics_action.php     # Stats
│   │   ├── 📁 colonies/             # Colony management
│   │   │   ├── get_colony_action.php          # List colonies
│   │   │   ├── update_colony_action.php       # Edit colony
│   │   │   ├── activate_colony_action.php     # Activate colony
│   │   │   └── deactivate_colony_action.php   # Deactivate colony
│   │   ├── 📁 shifts/               # Shift management
│   │   │   ├── shifts_action.php              # List shifts
│   │   │   ├── create_shift_action.php        # Create shift(s)
│   │   │   └── delete_shift_action.php        # Delete shift
│   │   ├── 📁 jaulas/               # Cage management
│   │   │   ├── get_cages_action.php           # List cages
│   │   │   ├── create_cage_action.php         # Create cage
│   │   │   ├── update_cage_action.php         # Edit cage
│   │   │   ├── jaulas_action.php              # Cage operations
│   │   │   └── jaulas_general_action.php      # Stats
│   │   ├── 📁 bookings/             # Booking management
│   │   │   ├── user_bookings_action.php       # User's bookings
│   │   │   ├── bookings_stats_action.php      # All bookings (admin)
│   │   │   ├── new_booking_action.php         # Create booking
│   │   │   ├── cancel_booking_action.php      # Cancel booking
│   │   │   ├── update_booking_action.php      # Edit booking
│   │   │   └── available_shifts.php           # Available slots
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
│   │   │   ├── userManagement.js        # User CRUD (fetch)
│   │   │   ├── modalEditUser.js         # Edit user modal
│   │   │   ├── campaignsManagement.js   # Campaign CRUD + filters
│   │   │   ├── clinicManagement.js      # Clinic CRUD
│   │   │   ├── colonyManagement.js      # Colony filters
│   │   │   ├── shiftsManagement.js      # Shift CRUD
│   │   │   ├── cageManagement.js        # Cage CRUD + filtering
│   │   │   ├── booking.js               # Booking creation
│   │   │   ├── cancelBooking.js         # Cancel bookings
│   │   │   ├── updateBooking.js         # Edit bookings
│   │   │   ├── modalJaulas.js           # Cage modals
│   │   │   ├── modalTurnos.js           # Shift modals
│   │   │   ├── filter.js                # Generic table filtering
│   │   │   ├── validation.js            # Form validation
│   │   │   ├── close-alerts.js          # Alert auto-dismiss
│   │   │   ├── color-modes.js           # Theme management
│   │   │   └── script.js                # General utilities
│   │   ├── 📁 brand/                     # Logo files
│   │   ├── 📁 dist/css/                  # Custom styles
---

## 🎯 Key Business Rules

### Campaign Management
- ✅ **Single Active Campaign:** Only one campaign can have `activa = 1` at any time
- ✅ **No Reactivation:** Once finalized (`activa = 0`), campaigns cannot be reactivated
- ✅ **Finalization Protection:** Cannot finalize campaign if active bookings exist in future shifts
- ✅ **Date Validation:** `fecha_fin` must be >= `fecha_inicio` (client and server-side)

### Cage Management
- ✅ **Unique Internal Numbers:** `numero_interno` must be unique per clinic
- ✅ **Immutable Foreign Keys:** Cannot modify `clinic_id` or `cage_type_id` after creation
- ✅ **Loan Protection:** Cannot edit cages while they have active loans (`estado = 'prestado'`)
- ✅ **Editable Fields:** Only `numero_interno` and `activo` status can be modified

### Shift Management
- ✅ **Unique Slots:** One shift per clinic/campaign/date/turno combination
- ✅ **Capacity Inheritance:** Shifts inherit capacity from parent clinic
- ✅ **Delete Protection:** Cannot delete shifts with existing bookings

### Booking Rules
- ✅ **Reverse Pickup:** AM drop → PM pickup same day | PM drop → AM pickup next day
- ✅ **Capacity Validation:** Cannot exceed shift capacity
- ✅ **Status Workflow:** reservado → entregado_vet → listo_recoger → recogido

---

## 🚀 Installation & Setup

│   │   └── 📁 img/                       # Images
│   ├── 📁 partials/                      # Reusable components
│   │   ├── header.php
│   │   └── footer.php
│   ├── login.php                         # Login page
│   ├── registro.php                      # Registration page
│   ├── recuperar_pass.php                # Password recovery
│   ├── pass_temporal.php                 # Temp password page
│   └── about.html                        # About page
│
├── 📁 views/                        # Application views
│   ├── 📁 admin/                    # Admin-only pages
│   │   ├── adminPanel.php           # Admin dashboard
│   │   ├── campaignsAdmin.php       # Campaign management
│   │   ├── clinicsAdmin.php         # Clinic management
│   │   ├── coloniesAdmin.php        # Colony management
│   │   ├── shiftsAdmin.php          # Shift management
│   │   ├── usersAdmin.php           # User management
│   │   ├── jaulasAdmin.php          # Cage inventory
│   │   └── bookingAdmin.php         # All bookings
│   ├── panel.php                    # Volunteer dashboard
│   ├── booking.php                  # Create booking
│   ├── userBookings.php             # User's bookings
│   ├── jaulas.php                   # Cage loans (volunteer)
│   ├── listadoJaulas.php            # Cage list view
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
**Institution:** IES Severo Ochoa  
**Academic Year:** 2025-2026

### Contact
- 📧 Email: a.quivera1991@gmail.com

---

## 📜 License

This project is developed for educational purposes as part of the DAW curriculum. All rights reserved by CES Gatos Elche for production use.


---

**Last Updated:** January 29, 2026  
**Version:** 1.0.0 (Beta)  
**Database Version:** 1.0  
**Project Status:** 🚧 In Active Development (80% Complete)
