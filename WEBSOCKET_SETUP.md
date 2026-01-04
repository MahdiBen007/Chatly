# إعداد WebSocket للمشروع

تم إعداد WebSocket بنجاح في المشروع. اتبع الخطوات التالية لإكمال التكوين:

## 1. إعداد متغيرات البيئة (.env)

أضف المتغيرات التالية إلى ملف `.env`:

### إذا كنت تستخدم Pusher (الخدمة المستضافة):
```env
BROADCAST_CONNECTION=pusher

PUSHER_APP_ID=your-app-id
PUSHER_APP_KEY=your-app-key
PUSHER_APP_SECRET=your-app-secret
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

### إذا كنت تستخدم Soketi (محلي/مستضاف ذاتيًا):
```env
BROADCAST_CONNECTION=pusher

PUSHER_APP_ID=app-id
PUSHER_APP_KEY=app-key
PUSHER_APP_SECRET=app-secret
PUSHER_HOST=127.0.0.1
PUSHER_PORT=6001
PUSHER_SCHEME=http

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

## 2. تثبيت وتشغيل Soketi (اختياري - للاستخدام المحلي)

إذا كنت تريد استخدام Soketi محليًا:

```bash
npm install -g @soketi/soketi
soketi start
```

## 3. بناء الأصول

بعد إضافة المتغيرات، قم ببناء الأصول:

```bash
npm run build
```

أو للتطوير:

```bash
npm run dev
```

## 4. التأكد من أن Broadcasting يعمل

تأكد من أن Laravel يمكنه الوصول إلى خادم WebSocket. يمكنك اختبار الاتصال من خلال:

1. فتح تطبيق الدردشة في متصفحين مختلفين
2. تسجيل الدخول كمستخدمين مختلفين
3. إرسال رسالة من أحد المستخدمين
4. يجب أن تظهر الرسالة فورًا في المتصفح الآخر

## الملفات المعدلة

- `app/Events/MessageSent.php` - حدث البث
- `app/Models/Message.php` - نموذج الرسالة مع العلاقات
- `app/Http/Controllers/ChatlyController.php` - إرسال الأحداث
- `routes/channels.php` - تفويض القنوات
- `bootstrap/app.php` - تسجيل ملف القنوات
- `resources/js/bootstrap.js` - إعداد Laravel Echo
- `resources/views/components/chat-area.blade.php` - الاستماع للأحداث

## ملاحظات

- يتم بث الرسائل على قنوات خاصة لكل مستخدم
- الرسائل تظهر فورًا للمستقبل دون الحاجة لتحديث الصفحة
- يتم حفظ جميع الرسائل في قاعدة البيانات

