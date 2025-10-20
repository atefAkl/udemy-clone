# 🛠️ Console Errors Guide

## ❌ الأخطاء الشائعة وحلولها

---

## 1️⃣ TinyMCE API Key Error

### **الخطأ:**
```
❌ Fail created TinyMCE editors are configured to be read-only.
⚠️ A valid API key is required to continue using TinyMCE.
Please alert the admin to check the current API key.
```

### **السبب:**
استخدام `no-api-key` في رابط TinyMCE CDN

### **الحل:**
✅ **تم الحل!** - استبدلنا TinyMCE بـ textarea عادي

للحصول على محرر متقدم في المستقبل، اقرأ: `TINYMCE_SETUP.md`

---

## 2️⃣ Chrome Extension Error

### **الخطأ:**
```
❌ Unchecked runtime.lastError: Could not establish connection. 
   Receiving end does not exist.
```

### **السبب:**
هذا الخطأ **ليس من كودنا** - إنه من Chrome Extension مثبت في المتصفح يحاول الاتصال بشيء غير موجود.

### **الحل:**
هذا الخطأ **يمكن تجاهله** - لا يؤثر على عمل التطبيق.

إذا أردت إزالته نهائياً:

#### **الطريقة 1: تعطيل Extensions**
1. افتح Chrome Extensions: `chrome://extensions/`
2. عطّل Extensions واحد تلو الآخر
3. أعد تحميل الصفحة بعد كل تعطيل
4. عندما يختفي الخطأ، تكون قد وجدت Extension المسبب

#### **الطريقة 2: استخدام Incognito Mode**
- افتح نافذة Incognito (Ctrl+Shift+N)
- Extensions لن تعمل افتراضياً
- الخطأ لن يظهر

#### **الطريقة 3: استخدام متصفح آخر**
- جرب Firefox أو Edge
- الخطأ خاص بـ Chrome Extensions

---

## 3️⃣ CSRF Token Mismatch

### **الخطأ:**
```
❌ 419 | Page Expired
```

### **السبب:**
CSRF token منتهي أو غير موجود

### **الحل:**
```bash
php artisan cache:clear
php artisan config:clear
```

وتأكد من وجود:
```html
<meta name="csrf-token" content="{{ csrf_token() }}">
```

---

## 4️⃣ 404 Not Found - Assets

### **الخطأ:**
```
❌ GET http://localhost/css/curriculum-builder.css 404
```

### **السبب:**
ملفات CSS/JS غير موجودة أو المسار خطأ

### **الحل:**
```bash
# تأكد من وجود الملفات
ls public/css/curriculum-builder.css
ls public/js/curriculum-builder.js

# امسح الكاش
php artisan cache:clear
php artisan view:clear

# أعد تشغيل السيرفر
php artisan serve
```

---

## 5️⃣ Mixed Content (HTTP/HTTPS)

### **الخطأ:**
```
⚠️ Mixed Content: The page was loaded over HTTPS, but requested an insecure resource
```

### **السبب:**
تحميل ملفات HTTP على صفحة HTTPS

### **الحل:**
استخدم `asset()` helper:
```blade
<!-- ❌ خطأ -->
<link href="http://example.com/style.css">

<!-- ✅ صحيح -->
<link href="{{ asset('css/style.css') }}">
```

---

## 🔍 كيفية فتح Console

### **Chrome / Edge:**
- `F12` أو `Ctrl+Shift+I`
- أو: Right Click → Inspect → Console

### **Firefox:**
- `F12` أو `Ctrl+Shift+K`

### **Safari:**
- `Cmd+Option+C` (Mac)
- Enable Developer Menu first

---

## 📊 مستويات الأخطاء

| الرمز | المستوى | الوصف |
|------|---------|-------|
| ❌ | **Error** | خطأ حرج - يجب إصلاحه |
| ⚠️ | **Warning** | تحذير - يفضل إصلاحه |
| ℹ️ | **Info** | معلومة - للعلم فقط |
| 🔵 | **Log** | سجل - للتطوير |

---

## ✅ الحالة الحالية للمشروع

| المشكلة | الحالة | الملاحظات |
|---------|--------|-----------|
| TinyMCE API | ✅ **محلولة** | استبدلنا بـ textarea |
| Chrome Extension | ⚠️ **يمكن تجاهلها** | ليست من كودنا |
| CSRF Token | ✅ **تعمل** | موجودة في الكود |
| Assets Loading | ✅ **تعمل** | المسارات صحيحة |

---

## 🛡️ نصائح للتطوير

### **1. استخدم Console للـ Debugging:**
```javascript
console.log('قيمة المتغير:', variable);
console.error('خطأ حدث:', error);
console.warn('تحذير:', warning);
```

### **2. تحقق من Network Tab:**
- شاهد جميع الطلبات HTTP
- تحقق من Status Codes
- شاهد Response Data

### **3. استخدم Breakpoints:**
- Sources Tab → اختر ملف JS
- اضغط على رقم السطر لإضافة Breakpoint
- أعد تحميل الصفحة

### **4. امسح الكاش بانتظام:**
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

## 📞 الدعم

إذا واجهت خطأ جديد:
1. انسخ رسالة الخطأ الكاملة
2. تحقق من Stack Trace
3. ابحث في Google عن الخطأ
4. راجع Laravel Logs: `storage/logs/laravel.log`

---

**آخر تحديث:** 2025-10-20  
**الإصدار:** v1.0
