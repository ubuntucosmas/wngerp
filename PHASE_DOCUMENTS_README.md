# Phase Document Upload System

## Overview
This system allows Project Officers (POs), Project Managers (PMs), and Design team members to upload and manage documents for specific project phases, with automatic file type detection and organized storage.

## Features

### 🔧 **Automatic File Type Detection**
The system automatically detects and categorizes uploaded files:
- **Images**: JPG, JPEG, PNG, GIF, BMP, SVG, WebP
- **Documents**: PDF, DOC, DOCX, TXT, RTF
- **Spreadsheets**: XLS, XLSX, CSV
- **Presentations**: PPT, PPTX
- **CAD/Design**: DWG, DXF, AI, PSD, Sketch
- **Archives**: ZIP, RAR, 7Z
- **Media**: MP4, AVI, MOV (video), MP3, WAV (audio)

### 👥 **Role-Based Access Control**
- **Admin & Super-Admin**: Can upload to any phase and delete any documents
- **Project Manager (PM)**: Can upload to all phases and delete any documents
- **Project Officer (PO)**: Can upload to most phases
- **Design Team**: Can upload to Design & Concept Development phase
- **All Users**: Can delete their own uploaded documents

### 📁 **Organized Storage**
Files are stored in: `storage/app/public/phase-documents/{project_id}/{phase_id}/`

### 🎯 **Phase-Specific Upload**
Currently focused on **Design & Concept Development** phase, but extensible to all phases.

## Database Schema

### `phase_documents` Table
```sql
- id (Primary Key)
- project_phase_id (Foreign Key to project_phases)
- project_id (Foreign Key to projects)
- phase_name (String)
- original_filename (String)
- stored_filename (String - UUID)
- file_path (String)
- file_size (BigInteger - bytes)
- mime_type (String)
- file_extension (String)
- uploaded_by (Foreign Key to users)
- description (Text - Optional)
- document_type (String - Auto-detected)
- is_active (Boolean - Default: true)
- created_at, updated_at (Timestamps)
```

## Routes

### Document Management Routes
```php
// All routes are prefixed with: projects/{project}/phases/{phase}/documents/
GET    /                    # List documents
GET    /create             # Upload form
POST   /                   # Store documents
GET    /{document}         # View document details
GET    /{document}/download # Download document
DELETE /{document}         # Delete document
GET    /bulk-download      # Download all as ZIP
GET    /ajax/documents     # AJAX endpoint for dynamic loading
```

## Models

### PhaseDocument Model
- **Relationships**: Belongs to Project, ProjectPhase, User (uploader)
- **Attributes**: 
  - `file_size_human` - Human readable file size
  - `document_type` - Auto-detected file type
  - `icon_class` - CSS class for file type icon
  - `file_url` - Public URL to file
- **Methods**:
  - `fileExists()` - Check if file exists in storage
  - `scopeForPhase()` - Filter by phase name
  - `scopeActive()` - Filter active documents

### ProjectPhase Model (Extended)
- **New Relationships**:
  - `documents()` - All documents for this phase
  - `activeDocuments()` - Only active documents

### Project Model (Extended)
- **New Relationships**:
  - `phaseDocuments()` - All phase documents for project
  - `documentsForPhase($phaseName)` - Documents for specific phase

## Controllers

### PhaseDocumentController
- **index()** - Display documents for a phase
- **create()** - Show upload form
- **store()** - Handle file uploads (supports multiple files)
- **show()** - Display document details
- **download()** - Download individual document
- **destroy()** - Delete document
- **bulkDownload()** - Create ZIP of all documents
- **getDocuments()** - AJAX endpoint for document list

## Views

### Main Views
- `resources/views/projects/phases/documents/index.blade.php` - Document management interface

### Integration Points
- **Design & Concept Page**: Added "Phase Documents" card
- **Main Project Files Page**: Added document count indicator and quick access button

## File Upload Features

### Drag & Drop Interface
- Visual drag-and-drop zone
- File preview before upload
- Progress indicators
- Multiple file selection

### File Validation
- Maximum file size: 50MB per file
- Maximum files per upload: 10
- File type validation based on extension
- MIME type verification

### Storage Management
- UUID-based file naming to prevent conflicts
- Automatic directory creation
- File cleanup on document deletion
- Organized by project and phase

## Security Features

### Access Control
- Route-level middleware for role checking
- Phase-specific upload permissions
- User can only delete their own documents (unless admin/PM)
- File type restrictions

### File Security
- Files stored outside web root
- Controlled download through application
- MIME type validation
- File extension verification

## Usage Examples

### Accessing Phase Documents
1. Navigate to Project → Files & Phases
2. Find "Design & Concept Development" phase
3. Click the folder icon or "Open" → "Phase Documents"

### Uploading Documents
1. Click "Upload Documents" button
2. Drag files or click "Browse Files"
3. Add optional description
4. Click "Upload Documents"

### Managing Documents
- **View**: Click eye icon on document card
- **Download**: Click download icon or use "Download All" for ZIP
- **Delete**: Click trash icon (only your own documents)

## File Type Icons
The system uses Font Awesome icons based on file type:
- 📄 PDF files: `fas fa-file-pdf text-danger`
- 🖼️ Images: `fas fa-image text-success`
- 📝 Documents: `fas fa-file-word text-primary`
- 📊 Spreadsheets: `fas fa-file-excel text-success`
- 📽️ Presentations: `fas fa-file-powerpoint text-warning`
- 🎨 Design files: `fas fa-palette text-purple`
- 📐 CAD files: `fas fa-drafting-compass text-info`
- 📦 Archives: `fas fa-file-archive text-secondary`
- 🎥 Videos: `fas fa-file-video text-info`
- 🎵 Audio: `fas fa-file-audio text-warning`

## Installation Steps

1. **Run Migration**:
   ```bash
   php artisan migrate
   ```

2. **Create Storage Directory**:
   ```bash
   mkdir -p storage/app/public/phase-documents
   chmod 755 storage/app/public/phase-documents
   ```

3. **Link Storage** (if not already done):
   ```bash
   php artisan storage:link
   ```

4. **Set Permissions**:
   Ensure the web server can write to `storage/app/public/phase-documents`

## Configuration

### File Upload Limits
Modify in `PhaseDocumentController`:
```php
'files.*' => 'required|file|max:50240', // 50MB max per file
'files' => 'required|array|min:1|max:10', // Max 10 files
```

### Allowed File Types
Modify `isAllowedFileType()` method in controller to add/remove file types.

### Role Permissions
Current role permissions (modify `checkPhaseUploadPermission()` method to adjust):

#### Full Access Roles
- **Admin & Super-Admin**: Full access to all phases and all documents (upload, view, delete any)

#### Management Roles  
- **Project Manager (PM)**: Full access to all phases and can delete any documents

#### Phase-Specific Permissions
- **Project Officer (PO)**: Can upload to most phases (Client Engagement, Material List, Budget, Handover, Archival)
- **Design Team**: Can upload to Design & Concept Development phase
- **Production Team**: Can upload to Production phase
- **Logistics Team**: Can upload to Logistics and Set Down & Return phases
- **Setup Team**: Can upload to Event Setup & Execution phase

#### Universal Permissions
- **All Users**: Can delete their own uploaded documents regardless of role

## Troubleshooting

### Common Issues
1. **Upload fails**: Check file size limits and permissions
2. **Files not found**: Verify storage link and directory permissions
3. **Access denied**: Check user roles and phase permissions
4. **Slow uploads**: Consider increasing PHP upload limits

### PHP Configuration
May need to adjust in `php.ini`:
```ini
upload_max_filesize = 50M
post_max_size = 500M
max_execution_time = 300
```

## Future Enhancements

### Planned Features
- [ ] File versioning system
- [ ] Document approval workflow
- [ ] Advanced search and filtering
- [ ] Document templates
- [ ] Integration with external storage (AWS S3)
- [ ] Document preview functionality
- [ ] Batch operations (move, copy, etc.)
- [ ] Document sharing with external clients
- [ ] Automated document organization
- [ ] Document expiration and archival

### Extension to Other Phases
The system is designed to be easily extended to other project phases:
- Client Engagement & Briefing
- Project Material List
- Budget & Quotation
- Production
- Logistics
- Event Setup & Execution
- Client Handover
- Set Down & Return
- Archival & Reporting

## Support
For issues or questions about the Phase Document Upload System, contact the development team or refer to the project documentation.