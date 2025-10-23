# 🚀 Laravel Queue System for Assets Upload

## ✅ تم التنفيذ

### **1. Queue Job** ✓
```php
App\Jobs\ProcessAssetsUpload
```
- **Timeout:** 600 seconds (10 minutes)
- **Tries:** 3 attempts
- **Features:**
  - Background file processing
  - Progress tracking via Cache
  - Error handling & logging
  - Automatic retry on failure

---

### **2. Controller Updates** ✓
```php
LessonController::storeAssets()
```
- Stores files temporarily in `storage/app/public/temp/assets`
- Dispatches job to queue
- Returns immediately with progress tracking key

---

### **3. Progress Tracking API** ✓
```
GET /instructor/courses/lessons/upload-progress/{uploadKey}
```
**Response:**
```json
{
  "current": 3,
  "total": 5,
  "percentage": 60,
  "status": "processing",
  "data": {},
  "updated_at": "2025-10-23T17:45:00+03:00"
}
```

**Status Values:**
- `starting` - Upload starting
- `processing` - Files being processed
- `completed` - All files uploaded successfully
- `failed` - Upload failed

---

### **4. Frontend Components** ✓

#### **Progress Modal**
- Real-time progress bar
- File count tracking
- Status messages
- Auto-reload on completion

#### **JavaScript Polling**
- Checks progress every 1 second
- Updates UI dynamically
- Handles completion/failure

---

## 🎯 كيف يعمل النظام؟

### **Flow Diagram:**

```
User Submits Form
       ↓
Controller Receives Request
       ↓
Store Files in Temp Directory
       ↓
Create Lesson Record (without files)
       ↓
Dispatch Job to Queue
       ↓
Return Response Immediately ✅
       ↓
Show Progress Modal
       ↓
       ↓ (Background)
       ↓
Queue Worker Processes Job
       ↓
Move Files from Temp to Permanent
       ↓
Update Progress in Cache (every file)
       ↓
Update Lesson Record with Files
       ↓
Mark as Completed ✅
```

---

## 🔧 Setup Instructions

### **1. Configure Queue Driver**

Edit `.env`:
```env
QUEUE_CONNECTION=database
```

### **2. Run Migrations**

```bash
php artisan migrate
```

This creates:
- `jobs` table
- `failed_jobs` table

### **3. Start Queue Worker**

**Option A: Development (Manual)**
```bash
php artisan queue:work --tries=3 --timeout=600
```

**Option B: Production (Supervisor)**

Create `/etc/supervisor/conf.d/laravel-worker.conf`:
```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan queue:work --sleep=3 --tries=3 --timeout=600
autostart=true
autorestart=true
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/storage/logs/worker.log
```

**Option C: Windows (Task Scheduler)**
```powershell
# Run this in Windows Task Scheduler every minute:
cd C:\wamp64\www\administration\udemy
php artisan queue:work --stop-when-empty
```

---

## 🧪 Testing

### **1. Test Single File Upload**
- Upload 1 file (< 10MB)
- Watch progress modal
- Verify completion

### **2. Test Multiple Files**
- Upload 5-10 files
- Watch progress update
- Verify all files uploaded

### **3. Test Large Files**
- Upload file near 10MB limit
- Verify no timeout
- Check processing time

### **4. Test Error Handling**
- Upload invalid file type
- Verify error message
- Check failed_jobs table

---

## 📊 Monitoring

### **Check Queue Status**
```bash
php artisan queue:monitor
```

### **Check Failed Jobs**
```bash
php artisan queue:failed
```

### **Retry Failed Job**
```bash
php artisan queue:retry {job-id}
```

### **Retry All Failed Jobs**
```bash
php artisan queue:retry all
```

### **Clear Failed Jobs**
```bash
php artisan queue:flush
```

---

## 🗂️ File Structure

```
storage/app/public/
├── temp/
│   └── assets/              # Temporary upload directory
└── lessons/
    └── assets/              # Permanent storage
```

---

## ⚙️ Configuration

### **Queue Settings in Job**

```php
public $timeout = 600;      // 10 minutes
public $tries = 3;          // 3 attempts
```

### **Cache TTL**
```php
Cache::put($key, $data, 3600); // 1 hour
```

### **Polling Interval**
```javascript
setInterval(checkProgress, 1000); // Every 1 second
```

---

## 🐛 Troubleshooting

### **Queue Not Processing**
1. Check if worker is running: `php artisan queue:work`
2. Check `jobs` table in database
3. Check Laravel logs: `storage/logs/laravel.log`

### **Progress Not Updating**
1. Check cache driver in `.env`
2. Clear cache: `php artisan cache:clear`
3. Check browser console for errors

### **Files Not Uploading**
1. Check `storage/app/public/temp/assets` permissions
2. Verify file size limits in `php.ini`
3. Check `failed_jobs` table for errors

---

## 📝 Logs

### **Job Logs**
```
storage/logs/laravel.log
```

**Look for:**
- `Assets upload completed successfully`
- `Failed to process file`
- `Assets upload job failed`

### **Queue Worker Logs**
```
storage/logs/worker.log
```

---

## 🚀 Production Deployment

### **1. Optimize Queue**
```bash
php artisan config:cache
php artisan route:cache
php artisan queue:restart
```

### **2. Use Supervisor**
- Ensures worker always running
- Auto-restart on failure
- Multiple workers for load balancing

### **3. Monitor Performance**
- Use Laravel Horizon (optional)
- Track job processing time
- Monitor memory usage

---

## ✅ Advantages of Queue System

✅ **No Timeout Issues** - Processing happens in background  
✅ **Better UX** - User gets immediate feedback  
✅ **Progress Tracking** - Real-time upload status  
✅ **Error Handling** - Automatic retries on failure  
✅ **Scalability** - Can process multiple uploads simultaneously  
✅ **Resource Management** - Doesn't block web server

---

## 🎯 Next Steps

1. ✅ Test the upload system
2. ✅ Start queue worker
3. ✅ Monitor first uploads
4. ✅ Adjust settings if needed
5. ✅ Setup production supervisor

---

**Created:** 2025-10-23  
**Author:** Cascade AI  
**Version:** 1.0
