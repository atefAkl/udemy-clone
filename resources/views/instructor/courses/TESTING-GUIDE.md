# 🧪 دليل الاختبار - نظام الدروس والملفات والواجبات

## ✅ التحديثات المكتملة

### 1. قاعدة البيانات ✅
- ✅ جدول `lesson_files`
- ✅ جدول `lesson_assignments`
- ✅ جدول `quizzes` (جاهز للاستخدام لاحقاً)

### 2. Models ✅
- ✅ `LessonFile` Model مع العلاقات
- ✅ `LessonAssignment` Model مع العلاقات
- ✅ تحديث `Lesson` Model (علاقات files & assignments)

### 3. Controllers ✅
- ✅ تحديث `storeVideo()` - معالجة الملفات والواجبات
- ✅ تحديث `storeArticle()` - معالجة الملفات والواجبات
- ✅ تحديث `edit()` - eager loading للعلاقات

### 4. Views ✅
- ✅ Video Modal - قسم Files & Assignments
- ✅ Article Modal - قسم Files & Assignments
- ✅ عرض الدروس - Badges للملفات والواجبات

---

## 📋 خطوات الاختبار

### **الخطوة 1: التحضير**
```bash
# تأكد من تشغيل السيرفر
php artisan serve

# افتح المتصفح على
http://localhost:8000
```

### **الخطوة 2: الوصول لصفحة تعديل الكورس**
1. سجل دخول كـ Instructor
2. اذهب إلى "My Courses"
3. اختر كورس موجود أو أنشئ كورس جديد
4. اضغط على "Edit Course"
5. اذهب لتبويب "Curriculum"

### **الخطوة 3: اختبار إضافة Section**
1. اضغط على "+ Add Section"
2. أدخل:
   - Title: "Introduction to Programming"
   - Description: "Basic concepts"
3. احفظ
4. ✅ تأكد من ظهور Section جديد

### **الخطوة 4: اختبار Video Lesson مع Files & Assignments**

#### إضافة درس فيديو:
1. اضغط على "Add Lesson" → "Video Lesson"
2. املأ البيانات:
   ```
   Title: "Variables in Python"
   Description: "Learn about variables"
   Video Source: Upload (أو URL)
   ```

#### إضافة ملفات:
3. في قسم "Downloadable Files":
   - اختر ملف PDF (مثلاً: code-examples.pdf)
   - اختر ملف ZIP (مثلاً: source-code.zip)
   - ✅ شاهد المعاينة مع الأيقونات الملونة

#### إضافة واجبات:
4. في قسم "Assignments":
   - اضغط "+ Add Assignment"
   - املأ:
     ```
     Title: "Variables Practice"
     Description: "Complete the exercises"
     Due Days: 7
     Max Score: 100
     ```
   - ✅ يمكنك إضافة أكثر من واجب
   - ✅ يمكنك حذف واجب بالضغط على ×

5. احفظ الدرس

### **الخطوة 5: التحقق من النتيجة**

#### في صفحة Curriculum:
✅ يجب أن ترى:
- **الدرس الجديد** في الـ Section
- **Badge أصفر** مع عدد الواجبات (مثال: "2 Assignments")
- **Badge أخضر** مع عدد الملفات (مثال: "2 Files")

#### تحقق من قاعدة البيانات:
```sql
-- تحقق من الدرس
SELECT * FROM lessons WHERE title = 'Variables in Python';

-- تحقق من الملفات
SELECT * FROM lesson_files WHERE lesson_id = [LESSON_ID];

-- تحقق من الواجبات
SELECT * FROM lesson_assignments WHERE lesson_id = [LESSON_ID];
```

### **الخطوة 6: اختبار Article Lesson**

1. اضغط على "Add Lesson" → "Article Lesson"
2. املأ البيانات:
   ```
   Title: "Python Best Practices"
   Article Body: (أدخل نص مع تنسيق في TinyMCE)
   ```
3. اختر Poster (صورة أو رابط)
4. أضف ملفات قابلة للتحميل
5. أضف واجب أو أكثر
6. احفظ

✅ تأكد من:
- ظهور الدرس
- Badges للملفات والواجبات
- البيانات محفوظة في قاعدة البيانات

---

## 🐛 الأخطاء المحتملة وحلولها

### خطأ: "The lesson files field must be a file"
**الحل:**
- تأكد من أن الملفات المرفوعة صحيحة
- حجم الملف لا يتجاوز 10MB
- نوع الملف مدعوم (PDF, DOC, ZIP, etc.)

### خطأ: "Column 'assignments' does not exist"
**الحل:**
```bash
php artisan migrate:fresh
# أو
php artisan migrate
```

### الملفات لا تظهر في المعاينة
**الحل:**
- افتح Console في المتصفح (F12)
- تحقق من أخطاء JavaScript
- تأكد من أن file input له الـ ID الصحيح

### Badges لا تظهر
**الحل:**
- تأكد من eager loading في Controller:
```php
$course->load(['sections.lessons.assignments', 'sections.lessons.files']);
```

---

## 📊 نقاط الاختبار الحرجة

### ✅ يجب أن يعمل:
- [ ] رفع ملفات متعددة في نفس الوقت
- [ ] معاينة الملفات مع الأيقونات الصحيحة
- [ ] إضافة/حذف واجبات ديناميكياً
- [ ] حفظ البيانات في قاعدة البيانات
- [ ] عرض Badges للملفات والواجبات
- [ ] TinyMCE يعمل في Article Modal

### ⚠️ تحقق من:
- حجم الملفات (max 10MB)
- أنواع الملفات المسموحة
- validation في Form Requests
- رسائل النجاح/الخطأ

---

## 📸 Screenshots للمقارنة

### قبل إضافة الدرس:
```
Section: Introduction
└── (No lessons)
```

### بعد إضافة درس مع ملفات وواجبات:
```
Section: Introduction
└── Video: Variables in Python
    ├── 📝 2 Assignments (Badge أصفر)
    └── 📥 2 Files (Badge أخضر)
```

---

## 🎯 الخطوة التالية بعد الاختبار

إذا نجح كل شيء:
✅ **جاهز لبناء Quiz Modal**

إذا وجدت مشاكل:
❌ سجل الأخطاء وسنصلحها معاً

---

## 📝 ملاحظات

- الملفات تُحفظ في: `storage/app/public/lessons/files/`
- الفيديوهات تُحفظ في: `storage/app/public/lessons/videos/`
- Posters تُحفظ في: `storage/app/public/lessons/posters/`

تأكد من تشغيل:
```bash
php artisan storage:link
```

---

**تاريخ الإنشاء:** 2025-10-26
**الإصدار:** 1.0
**الحالة:** جاهز للاختبار ✅
