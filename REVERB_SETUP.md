# إعداد Laravel Reverb (WebSockets)

تم تحويل المشروع من استخدام Pusher إلى استخدام **Laravel Reverb**، وهو الحل الرسمي من Laravel للـ WebSockets ومتوافق مع Laravel 12.

## الإعدادات المطلوبة في ملف `.env`

أضف أو حدّث الإعدادات التالية في ملف `.env`:

```env
# Broadcasting
BROADCAST_CONNECTION=reverb

# Reverb App Settings
REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

# Reverb Server Settings
REVERB_SERVER_HOST=0.0.0.0
REVERB_SERVER_PORT=8080
```

## إعدادات Vite (للواجهة الأمامية)

أضف هذه الإعدادات في ملف `.env` (يتم استخدامها من قبل Vite):

```env
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

## تشغيل Reverb Server

لتشغيل خادم Reverb، استخدم الأمر التالي في terminal منفصل:

```bash
php artisan reverb:start
```

أو للتشغيل في الخلفية:

```bash
php artisan reverb:start --host=0.0.0.0 --port=8080
```

## ملاحظات مهمة

1. **يجب تشغيل Reverb Server** قبل استخدام WebSockets في التطبيق
2. Reverb Server يعمل على المنفذ `8080` افتراضياً
3. يمكنك تغيير المنفذ من خلال متغيرات البيئة
4. للتطوير المحلي، استخدم `http` و `localhost`
5. للإنتاج، استخدم `https` واسم النطاق الخاص بك

## التحقق من التثبيت

بعد إضافة الإعدادات في `.env`:

1. قم بتشغيل Reverb Server:
   ```bash
   php artisan reverb:start
   ```

2. قم بتشغيل Laravel:
   ```bash
   php artisan serve
   ```

3. افتح التطبيق في المتصفح وتحقق من عمل WebSockets

## الملفات التي تم تحديثها

- ✅ `config/broadcasting.php` - تم تعيين `reverb` كافتراضي
- ✅ `resources/js/bootstrap.js` - تم تحديث Laravel Echo لاستخدام Reverb
- ✅ تم تثبيت `laravel/reverb` عبر Composer

## الفرق بين Reverb و laravel-websockets

- **Laravel Reverb**: الحل الرسمي من Laravel، متوافق مع Laravel 12، أسهل في الإعداد
- **laravel-websockets**: غير متوافق مع Laravel 12، يحتاج إعدادات أكثر

