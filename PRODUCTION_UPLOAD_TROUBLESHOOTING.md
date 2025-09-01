# Phase Document Upload - Production Troubleshooting Guide

## Common Production Upload Issues & Solutions

### 1. **File Upload Fails Silently**

**Symptoms:**
- Upload appears to work in development but fails in production
- No error messages shown to user
- Files don't appear in the system

**Potential Causes & Solutions:**

#### A. PHP Configuration Issues
```bash
# Check current PHP settings
php -i | grep -E "(upload_max_filesize|post_max_size|max_execution_time|memory_limit)"

# Common required settings for 50MB uploads:
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 300
max_input_time = 300
memory_limit = 256M
file_uploads = On
max_file_uploads = 20
```

#### B. Web Server Configuration

**Nginx:**
```nginx
# Add to server block in nginx.conf
client_max_body_size 50M;
client_body_timeout 300s;
```

**Apache:**
```apache
# Add to .htaccess or virtual host
LimitRequestBody 52428800
```

#### C. Storage Directory Permissions
```bash
# Set correct permissions
sudo chown -R www-data:www-data storage/
sudo chmod -R 755 storage/
sudo chmod -R 775 storage/app/public/

# Create storage symlink if missing
php artisan storage:link
```

### 2. **File Size Limits**

**Check and fix file size limits:**
```bash
# Run diagnostic command
php artisan upload:diagnose

# Check specific PHP limits
php -r "echo 'upload_max_filesize: ' . ini_get('upload_max_filesize') . PHP_EOL;"
php -r "echo 'post_max_size: ' . ini_get('post_max_size') . PHP_EOL;"
```

### 3. **Storage Disk Issues**

**Check disk space:**
```bash
df -h
du -sh storage/
```

**Test storage operations:**
```bash
# Test file creation
php artisan tinker
>>> Storage::disk('public')->put('test.txt', 'test content');
>>> Storage::disk('public')->exists('test.txt');
>>> Storage::disk('public')->delete('test.txt');
```

### 4. **Database Connection Issues**

**Check database connectivity:**
```bash
php artisan migrate:status
php artisan db:show
```

### 5. **Permission Issues**

**Check user permissions:**
```bash
# Verify web server user
ps aux | grep -E "(apache|nginx|php-fpm)"

# Check file ownership
ls -la storage/app/public/

# Fix ownership if needed
sudo chown -R www-data:www-data storage/
```

### 6. **Security Software Interference**

**Common culprits:**
- ModSecurity (Apache)
- Fail2Ban
- Firewall rules
- Antivirus software

**Check ModSecurity logs:**
```bash
tail -f /var/log/apache2/modsec_audit.log
```

### 7. **Environment-Specific Issues**

**Check environment configuration:**
```bash
# Verify environment
php artisan env

# Check Laravel configuration
php artisan config:show filesystems

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

## Debugging Steps

### Step 1: Run Diagnostic Command
```bash
php artisan upload:diagnose
```

### Step 2: Check Laravel Logs
```bash
tail -f storage/logs/laravel.log
```

### Step 3: Test Upload Manually
```bash
php artisan tinker
>>> $file = new \Illuminate\Http\UploadedFile('/path/to/test/file.pdf', 'test.pdf', 'application/pdf', null, true);
>>> $file->storeAs('phase-documents/test', 'test.pdf', 'public');
```

### Step 4: Check Browser Network Tab
- Open browser developer tools
- Go to Network tab
- Attempt upload
- Check for failed requests or error responses

### Step 5: Enable Debug Mode Temporarily
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

## Production Checklist

Before deploying to production, ensure:

- [ ] `php artisan storage:link` has been run
- [ ] Storage directories have correct permissions (755/775)
- [ ] Web server upload limits are configured
- [ ] PHP upload limits are set appropriately
- [ ] Sufficient disk space is available
- [ ] Database migrations are up to date
- [ ] Environment variables are correctly set
- [ ] SSL certificates are valid (for HTTPS uploads)

## Monitoring & Alerts

Set up monitoring for:
- Disk space usage
- Upload success/failure rates
- Error log patterns
- Performance metrics

## Emergency Fixes

### Quick Permission Fix
```bash
sudo chown -R www-data:www-data storage/
sudo chmod -R 755 storage/
sudo chmod -R 775 storage/app/public/
```

### Quick Storage Link Fix
```bash
rm -f public/storage
php artisan storage:link
```

### Quick Cache Clear
```bash
php artisan optimize:clear
```

## Contact Information

For additional support:
- Check Laravel logs: `storage/logs/laravel.log`
- Run diagnostics: `php artisan upload:diagnose`
- Review this troubleshooting guide
- Contact system administrator if issues persist