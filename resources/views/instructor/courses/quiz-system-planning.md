# تخطيط نظام الكويزات - نهج شامل ومرن

## ✅ التقدم الحالي (تم إنجازه)

### المرحلة 1: قاعدة البيانات ✅
- [x] إنشاء جدول `quizzes`
- [x] إنشاء جدول `quiz_questions`
- [x] إنشاء جدول `quiz_answers`
- [x] إنشاء جدول `lesson_assignments`
- [x] إنشاء جدول `lesson_files`
- [x] تشغيل جميع الـ Migrations

### المرحلة 2: Models ✅
- [x] إنشاء `Quiz` Model مع العلاقات
- [x] إنشاء `QuizQuestion` Model مع العلاقات
- [x] إنشاء `QuizAnswer` Model مع العلاقات
- [x] إنشاء `LessonAssignment` Model
- [x] إنشاء `LessonFile` Model
- [x] تحديث `Lesson` Model (إضافة علاقات files و assignments)
- [x] تحديث `Section` Model (إضافة علاقة quizzes)

### المرحلة 3: تنظيف الكود ✅
- [x] حذف Assets Lesson Modal
- [x] حذف Assets من dropdown menu
- [x] حذف JavaScript الخاص بـ Assets

### المرحلة 4: تحديث Video/Article Modals ✅
- [x] إضافة قسم Downloadable Files لـ Video Modal
- [x] إضافة قسم Assignments لـ Video Modal
- [x] إضافة قسم Downloadable Files لـ Article Modal
- [x] إضافة قسم Assignments لـ Article Modal
- [x] JavaScript للـ File Preview (مع أيقونات حسب نوع الملف)
- [x] JavaScript لإضافة/حذف Assignments ديناميكياً

### المرحلة 5: تحديث عرض الدروس ✅
- [x] إضافة Badge لعدد الواجبات
- [x] إضافة Badge لعدد الملفات
- [x] تصميم جذاب مع ألوان مميزة

---

## 🎯 الاستراتيجية المقترحة

### **1. نظام ثنائي للكويزات**
```
Quiz Types:
├── Section Quiz (كويز الوحدة)
│   └── مرتبط بـ Lesson داخل Section معين
├── Course Quiz (كويز المنهج)
│   ├── Mid-term (نصف نهائي)
│   └── Final (نهائي)
```

### **2. طريقتان للإنشاء**

#### **أ) Quiz Builder (تفاعلي)** 
✅ **مميزات:**
- مرونة كاملة في التصميم
- إضافة أنواع أسئلة متعددة
- تعديل فوري
- إضافة صور/ميديا للأسئلة

#### **ب) Import من CSV/Excel**
✅ **مميزات:**
- سرعة في إنشاء كويزات كبيرة
- سهل للأساتذة غير التقنيين
- إعادة استخدام كويزات جاهزة

---

## 📊 هيكل قاعدة البيانات المقترح

```sql
-- جدول الكويزات الرئيسي
quizzes
├── id
├── title (عنوان الكويز)
├── description
├── quiz_type (section/midterm/final)
├── course_id (مرتبط بالكورس)
├── section_id (null إذا كان final/midterm)
├── lesson_id (null إذا كان final/midterm)
├── duration_minutes (وقت الكويز)
├── pass_percentage (نسبة النجاح)
├── randomize_questions (خلط الأسئلة)
├── show_results (عرض النتائج فوراً)
└── max_attempts (عدد المحاولات)

-- جدول الأسئلة
quiz_questions
├── id
├── quiz_id
├── question_text
├── question_type (multiple_choice/true_false/fill_blank/essay)
├── points (درجة السؤال)
├── order
└── image_path (صورة توضيحية)

-- جدول الإجابات
quiz_answers
├── id
├── question_id
├── answer_text
├── is_correct
└── order
```

---

## 🛠️ خطة التنفيذ المقترحة

### **المرحلة 1: البنية الأساسية** (يومين)
- [ ] إنشاء Migration للجداول
- [ ] إنشاء Models (Quiz, QuizQuestion, QuizAnswer)
- [ ] إنشاء العلاقات بين الجداول

### **المرحلة 2: Quiz Builder Modal** (3-4 أيام)
- [ ] مودال إنشاء كويز جديد
- [ ] اختيار نوع الكويز (Section/Midterm/Final)
- [ ] إضافة الأسئلة تفاعلياً
- [ ] أنواع الأسئلة:
  - Multiple Choice
  - True/False
  - Fill in the Blank
  - Essay (اختياري)

### **المرحلة 3: CSV/Excel Import** (يومين)
- [ ] واجهة رفع ملف
- [ ] Parser للـ CSV/Excel
- [ ] Template جاهز للتحميل
- [ ] Validation للبيانات

### **المرحلة 4: ربط بالدروس** (يوم واحد)
- [ ] إضافة خيار "Quiz" عند إنشاء درس جديد
- [ ] اختيار كويز موجود أو إنشاء جديد
- [ ] عرض الكويز في صفحة الدرس

---

## 💡 التصميم المقترح للمودال

```blade
<!-- Modal Structure -->
<div class="modal" id="addQuizModal">
    <!-- Header -->
    <div class="modal-header">
        <h3>إضافة كويز جديد</h3>
        <span class="close">&times;</span>
    </div>
    
    <!-- Tabs -->
    <ul class="nav nav-tabs">
        <li><a href="#builder">بناء تفاعلي</a></li>
        <li><a href="#import">استيراد من ملف</a></li>
    </ul>
    
    <!-- Tab 1: Quiz Builder -->
    <div id="builder" class="tab-content">
        <!-- معلومات أساسية -->
        <input name="title" placeholder="عنوان الكويز">
        <select name="quiz_type">
            <option value="section">كويز الوحدة</option>
            <option value="midterm">نصف نهائي</option>
            <option value="final">نهائي</option>
        </select>
        
        <!-- إضافة الأسئلة -->
        <div id="questions-container">
            <!-- يتم إضافة الأسئلة ديناميكياً -->
        </div>
        
        <button id="add-question">+ إضافة سؤال</button>
    </div>
    
    <!-- Tab 2: Import -->
    <div id="import" class="tab-content">
        <div class="upload-area">
            <i class="icon-upload"></i>
            <p>اسحب الملف هنا أو اضغط للتحميل</p>
            <input type="file" accept=".csv,.xlsx">
        </div>
        <a href="/templates/quiz-template.csv" download>
            📥 تحميل قالب CSV
        </a>
    </div>
</div>
```

---

## 📋 قالب CSV المقترح

```csv
Question Type,Question Text,Answer 1,Answer 2,Answer 3,Answer 4,Correct Answer,Points
multiple_choice,ما هي عاصمة مصر؟,القاهرة,الإسكندرية,أسوان,الأقصر,1,5
true_false,الأرض مسطحة,صح,خطأ,,,2,3
fill_blank,عاصمة فرنسا هي _____,باريس,,,,1,4
```

---

## 🎨 الخصائص الإضافية المقترحة

1. **بنك الأسئلة**: حفظ أسئلة لإعادة استخدامها
2. **Randomization**: خلط الأسئلة والإجابات
3. **Timer**: مؤقت للكويز
4. **Auto-save**: حفظ تلقائي أثناء الإنشاء
5. **Preview Mode**: معاينة الكويز قبل النشر
6. **Analytics**: إحصائيات عن أداء الطلاب

---

## 🚀 ملاحظات التنفيذ

- استخدام AJAX لإضافة الأسئلة بدون إعادة تحميل الصفحة
- Validation قوي على جانب الخادم والعميل
- دعم الصور في الأسئلة
- نظام الدرجات مرن (يمكن تخصيص درجة كل سؤال)
- إمكانية إعادة ترتيب الأسئلة بـ Drag & Drop
