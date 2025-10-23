# PHP Upload & Timeout Settings

## ⚠️ Problem: Internal Server Error on File Upload

When uploading large files or multiple files, you may encounter:
- **500 Internal Server Error**
- **Request Timeout**
- **Maximum execution time exceeded**

---

## ✅ Solution 1: Update `.htaccess` (Already Done ✓)

The `.htaccess` file in `public/` folder has been updated with:

```apache
php_value upload_max_filesize 100M
php_value post_max_size 100M
php_value max_execution_time 300
php_value max_input_time 300
php_value memory_limit 256M
```

---

## ✅ Solution 2: Update WAMP `php.ini`

### Steps:

1. **Open WAMP Tray Icon** → Left Click → **PHP** → **php.ini**

2. **Find and update these lines:**

```ini
upload_max_filesize = 100M
post_max_size = 100M
max_execution_time = 300
max_input_time = 300
memory_limit = 256M
max_file_uploads = 20
```

3. **Save the file**

4. **Restart Apache:**
   - WAMP Tray Icon → **Apache** → **Restart All Services**

---

## ✅ Solution 3: Check Apache Timeout (if needed)

If still getting timeout errors:

1. **Open Apache `httpd.conf`:**
   - WAMP Tray Icon → **Apache** → **httpd.conf**

2. **Add/Update:**

```apache
Timeout 300
```

3. **Restart Apache**

---

## 🧪 Test Upload Settings

After making changes, test by uploading:
- **Single large file** (> 10MB)
- **Multiple files** (5-10 files)

---

## 📋 Current Settings Summary

| Setting | Value | Purpose |
|---------|-------|---------|
| `upload_max_filesize` | 100M | Maximum size per file |
| `post_max_size` | 100M | Maximum POST request size |
| `max_execution_time` | 300s (5 min) | Script execution timeout |
| `max_input_time` | 300s (5 min) | Input parsing timeout |
| `memory_limit` | 256M | Memory limit per request |
| `max_file_uploads` | 20 | Maximum files per request |

---

## 🔍 Troubleshooting

### Check current PHP settings:

```php
<?php
phpinfo();
```

Create this file in `public/info.php` and visit: `http://localhost/info.php`

Look for:
- **upload_max_filesize**
- **post_max_size**
- **max_execution_time**

### Check Laravel logs:

```bash
storage/logs/laravel.log
```

---

## ⚡ Quick Fix Checklist

- [x] Updated `.htaccess` with timeout settings
- [ ] Updated `php.ini` with upload limits
- [ ] Restarted Apache
- [ ] Cleared Laravel cache (`php artisan config:clear`)
- [ ] Tested file upload

---

**Last Updated:** 2025-10-23
