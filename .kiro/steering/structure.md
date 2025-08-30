# Project Structure

## Laravel Application Structure

### Core Directories
- `app/` - Application logic
- `config/` - Configuration files
- `database/` - Migrations, seeders, factories
- `resources/` - Views, assets, language files
- `routes/` - Route definitions
- `storage/` - File storage and logs
- `tests/` - Test files

### App Directory Organization
```
app/
├── Console/Commands/     # Artisan commands
├── Exports/             # Excel export classes
├── Http/
│   ├── Controllers/     # Request handlers
│   ├── Middleware/      # HTTP middleware
│   └── Requests/        # Form request validation
├── Imports/             # Excel import classes
├── Models/              # Eloquent models
├── Notifications/       # Email/SMS notifications
├── Policies/            # Authorization policies
├── Providers/           # Service providers
├── Services/            # Business logic services
└── View/Components/     # Blade components
```

### Key Model Categories
- **Core Entities**: Project, Enquiry, User, Client
- **Project Management**: ProjectPhase, ProjectBudget, Deliverable
- **Inventory**: Inventory, MaterialList, Checkouts, ReturnItem
- **Documents**: PhaseDocument, CloseOutReport, HandoverReport
- **Financial**: Quote, QuoteLineItem, BudgetItem

### View Structure
```
resources/views/
├── layouts/             # Master layouts
├── projects/            # Project-related views
│   ├── phases/         # Phase management
│   ├── files/          # File management
│   └── close-out-report/ # Reports
├── enquiries/           # Enquiry management
├── inventory/           # Inventory views
└── components/          # Reusable components
```

### Route Organization
- Web routes in `routes/web.php`
- API routes in `routes/api.php` (if applicable)
- Resource controllers for CRUD operations
- Nested routes for related resources (e.g., project phases)

### Policy Structure
- One policy per model for authorization
- Methods: view, create, update, delete, plus custom actions
- Role-based permissions using Spatie Laravel Permission