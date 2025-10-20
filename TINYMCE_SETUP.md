# 📝 TinyMCE Setup Guide

## ⚠️ الحالة الحالية
حالياً، نستخدم **textarea عادي** لكتابة محتوى المقالات بدلاً من TinyMCE لتجنب مشكلة API Key.

---

## 🚀 كيفية تفعيل TinyMCE (اختياري)

إذا أردت استخدام محرر نصوص متقدم مع أدوات تنسيق احترافية:

### **الخطوة 1: الحصول على API Key مجاني**

1. اذهب إلى: https://www.tiny.cloud/auth/signup/
2. سجل حساب مجاني (Free Tier - حتى 1000 تحميل/شهر)
3. انسخ الـ API Key من Dashboard

### **الخطوة 2: تحديث الكود**

#### في `curriculum-builder.blade.php`:
```blade
<!-- استبدل السطر 99 -->
<!-- FROM: -->
<!-- <script src="https://cdn.tiny.cloud/1/YOUR_API_KEY/tinymce/6/tinymce.min.js"></script> -->

<!-- TO: -->
<script src="https://cdn.tiny.cloud/1/YOUR_ACTUAL_API_KEY_HERE/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
```

#### في `curriculum-builder-lessons.js`:
استبدل الأسطر 142-153 بـ:

```javascript
// Initialize TinyMCE
setTimeout(() => {
    if (typeof tinymce !== 'undefined') {
        tinymce.init({
            selector: '#' + editorId,
            height: 350,
            menubar: false,
            plugins: 'lists link image code table',
            toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright | bullist numlist | link image table | code',
            directionality: 'rtl',
            language: 'ar',
            content_style: 'body { font-family: Arial, sans-serif; font-size: 14px; direction: rtl; }',
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });
            }
        });
    }
}, 100);
```

وفي دالة `saveLesson`، استبدل الأسطر 250-263 بـ:

```javascript
const editorId = lessonEl.querySelector('.article-content-editor')?.id;
if (editorId && typeof tinymce !== 'undefined' && tinymce.get(editorId)) {
    const content = tinymce.get(editorId).getContent();
    if (!content.trim()) {
        this.showToast('الرجاء كتابة محتوى المقال', 'error');
        return;
    }
    formData.append('content', content);
} else {
    // Fallback to textarea
    const editor = lessonEl.querySelector('.article-content-editor');
    const content = editor?.value.trim();
    if (!content) {
        this.showToast('الرجاء كتابة محتوى المقال', 'error');
        return;
    }
    formData.append('content', content);
}
```

---

## ✅ مميزات TinyMCE (عند التفعيل)

- ✨ تنسيق نصوص متقدم (Bold, Italic, Underline)
- 📋 قوائم مرتبة وغير مرتبة
- 🔗 إضافة روابط وصور
- 📊 جداول
- 🎨 محاذاة النص
- 📝 عرض الكود HTML
- 🌍 دعم RTL كامل للعربية

---

## 🆓 الحد المجاني لـ TinyMCE

- **1,000 تحميل/شهر** - مجاناً
- غير محدود للمستخدمين
- جميع الميزات الأساسية
- بدون علامة مائية

---

## 📌 ملاحظات

### **الحل الحالي (textarea)**
- ✅ بسيط وسريع
- ✅ لا يحتاج API key
- ✅ يدعم HTML مباشرةً
- ❌ بدون أدوات تنسيق مرئية

### **TinyMCE (اختياري)**
- ✅ واجهة WYSIWYG احترافية
- ✅ أدوات تنسيق متقدمة
- ✅ سهل للمستخدم النهائي
- ⚠️ يحتاج API key

---

## 🔗 روابط مفيدة

- **TinyMCE Docs**: https://www.tiny.cloud/docs/
- **TinyMCE Cloud**: https://www.tiny.cloud/
- **تسجيل حساب**: https://www.tiny.cloud/auth/signup/

---

**التاريخ:** 2025-10-20  
**الإصدار:** v2.0
