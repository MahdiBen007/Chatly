# دليل تشغيل المشروع واختبار WebSockets بين مستخدمين

## 📋 الخطوات الكاملة

### 1️⃣ إعداد ملف `.env`

افتح ملف `.env` وأضف الإعدادات التالية:

```env
# Broadcasting - استخدم reverb
BROADCAST_CONNECTION=reverb

# Reverb App Settings
REVERB_APP_ID=my-app-id
REVERB_APP_KEY=my-app-key
REVERB_APP_SECRET=my-app-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

# Reverb Server Settings
REVERB_SERVER_HOST=0.0.0.0
REVERB_SERVER_PORT=8080

# Vite Settings (للواجهة الأمامية)
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

**ملاحظة:** يمكنك استخدام أي قيم للمفاتيح (REVERB_APP_ID, REVERB_APP_KEY, REVERB_APP_SECRET) - فقط تأكد من أنها متطابقة في جميع الأماكن.

---

### 2️⃣ تشغيل المشروع

لديك خياران:

#### **الطريقة الأولى: تشغيل كل شيء معاً (موصى بها)**

```bash
composer run dev
```

هذا الأمر سيشغل تلقائياً:
- ✅ Laravel Server (على المنفذ 8000)
- ✅ Queue Worker
- ✅ Reverb WebSocket Server (على المنفذ 8080)
- ✅ Vite Dev Server

#### **الطريقة الثانية: تشغيل كل خدمة بشكل منفصل**

افتح **4 نوافذ Terminal** منفصلة:

**Terminal 1 - Laravel Server:**
```bash
php artisan serve
```

**Terminal 2 - Queue Worker:**
```bash
php artisan queue:listen --tries=1
```

**Terminal 3 - Reverb WebSocket Server:**
```bash
php artisan reverb:start
```

**Terminal 4 - Vite Dev Server:**
```bash
npm run dev
```

---

### 3️⃣ التحقق من أن كل شيء يعمل

بعد التشغيل، تأكد من:

1. **Laravel Server** يعمل على: `http://localhost:8000`
2. **Reverb Server** يعمل على: `ws://localhost:8080`
3. **Vite** يعمل على: `http://localhost:5173` (أو منفذ آخر)

---

### 4️⃣ اختبار WebSockets بين مستخدمين

#### **الخطوات:**

1. **افتح متصفحين مختلفين** (أو نافذتين منفصلتين في وضع التصفح الخاص):
   - المتصفح 1: `http://localhost:8000`
   - المتصفح 2: `http://localhost:8000`

2. **سجل الدخول كمستخدمين مختلفين:**
   - في المتصفح 1: سجل دخول كمستخدم (مثلاً: user1@example.com)
   - في المتصفح 2: سجل دخول كمستخدم آخر (مثلاً: user2@example.com)

3. **افتح صفحة الدردشة:**
   - في كلا المتصفحين، اذهب إلى صفحة الدردشة (Chatly)

4. **ابدأ المحادثة:**
   - في المتصفح 1: اختر المستخدم الثاني من القائمة
   - أرسل رسالة من المتصفح 1
   - **يجب أن تظهر الرسالة فوراً في المتصفح 2** بدون تحديث الصفحة! 🎉

5. **اختبر الاتجاه المعاكس:**
   - أرسل رسالة من المتصفح 2
   - **يجب أن تظهر فوراً في المتصفح 1** 🎉

---

### 5️⃣ التحقق من عمل WebSockets

#### **من خلال Console المتصفح:**

1. افتح **Developer Tools** (F12)
2. اذهب إلى تبويب **Console**
3. يجب أن ترى رسائل مثل:
   ```
   Echo connected
   Listening for: message.sent
   ```

#### **من خلال Network Tab:**

1. افتح **Developer Tools** (F12)
2. اذهب إلى تبويب **Network**
3. ابحث عن اتصال **WebSocket** (ws://localhost:8080)
4. يجب أن يكون الحالة **101 Switching Protocols** (يعني متصل بنجاح)

---

### 6️⃣ استكشاف الأخطاء

#### **المشكلة: الرسائل لا تظهر فوراً**

**الحل:**
1. تأكد من أن Reverb Server يعمل:
   ```bash
   php artisan reverb:start
   ```
2. تحقق من ملف `.env` - تأكد من أن جميع الإعدادات صحيحة
3. افتح Console المتصفح وتحقق من وجود أخطاء
4. تأكد من أن `BROADCAST_CONNECTION=reverb` في `.env`

#### **المشكلة: خطأ في الاتصال بـ WebSocket**

**الحل:**
1. تأكد من أن Reverb Server يعمل على المنفذ 8080
2. تحقق من أن `REVERB_HOST=localhost` و `REVERB_PORT=8080` في `.env`
3. تأكد من أن `VITE_REVERB_HOST` و `VITE_REVERB_PORT` موجودة في `.env`

#### **المشكلة: Reverb Server لا يبدأ**

**الحل:**
1. تحقق من أن المنفذ 8080 غير مستخدم:
   ```bash
   # Windows
   netstat -ano | findstr :8080
   
   # إذا كان مستخدم، غير المنفذ في .env
   ```
2. تأكد من تثبيت Reverb:
   ```bash
   composer require laravel/reverb
   php artisan reverb:install
   ```

---

### 7️⃣ نصائح إضافية

1. **للتطوير:** استخدم `composer run dev` - سيشغل كل شيء تلقائياً
2. **للإنتاج:** استخدم `https` بدلاً من `http` و `wss` بدلاً من `ws`
3. **للمراقبة:** راقب Terminal الذي يعمل فيه Reverb Server لرؤية الاتصالات
4. **للتجربة:** استخدم متصفحات مختلفة (Chrome, Firefox, Edge) أو وضع التصفح الخاص

---

### 8️⃣ مثال على الإعدادات الكاملة في `.env`

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:your-app-key-here
APP_DEBUG=true
APP_URL=http://localhost

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=root
DB_PASSWORD=

# Broadcasting
BROADCAST_CONNECTION=reverb

# Reverb
REVERB_APP_ID=my-app-id
REVERB_APP_KEY=my-app-key
REVERB_APP_SECRET=my-app-secret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http
REVERB_SERVER_HOST=0.0.0.0
REVERB_SERVER_PORT=8080

# Vite
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

---

## ✅ قائمة التحقق السريعة

- [ ] ملف `.env` يحتوي على جميع إعدادات Reverb
- [ ] تم تشغيل `composer run dev` أو الخدمات بشكل منفصل
- [ ] Reverb Server يعمل على المنفذ 8080
- [ ] Laravel Server يعمل على المنفذ 8000
- [ ] تم فتح متصفحين مختلفين
- [ ] تم تسجيل الدخول كمستخدمين مختلفين
- [ ] تم فتح صفحة الدردشة في كلا المتصفحين
- [ ] تم إرسال رسالة من أحد المتصفحين
- [ ] ظهرت الرسالة فوراً في المتصفح الآخر

---

## 🎉 إذا نجح كل شيء:

ستلاحظ أن:
- ✅ الرسائل تظهر فوراً بدون تحديث الصفحة
- ✅ حالة المستخدمين (Online/Offline) تتحدث فوراً
- ✅ لا توجد أخطاء في Console المتصفح
- ✅ اتصال WebSocket نشط في Network Tab

**مبروك! WebSockets يعمل بنجاح! 🚀**

