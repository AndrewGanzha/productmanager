# Настройка API Token аутентификации с Laravel Sanctum

## Что было сделано

### Backend (Laravel)

1. **Модель User** ([backend/app/app/Models/User.php](backend/app/app/Models/User.php:8))
   - Добавлен трейт `HasApiTokens` для работы с токенами

2. **AuthController** ([backend/app/app/Http/Controllers/AuthController.php](backend/app/app/Http/Controllers/AuthController.php))
   - `register()` - регистрация нового пользователя с выдачей токена
   - `login()` - вход с выдачей токена
   - `logout()` - удаление текущего токена
   - `user()` - получение данных текущего пользователя

3. **API Routes** ([backend/app/routes/api.php](backend/app/routes/api.php))
   - Публичные роуты:
     - `POST /api/auth/register` - регистрация
     - `POST /api/auth/login` - вход
   - Защищенные роуты (требуют токен):
     - `POST /api/auth/logout` - выход
     - `GET /api/user` - получить данные пользователя

### Frontend (Vue.js)

1. **Token Storage** ([frontend/app/src/utils/tokenStorage.ts](frontend/app/src/utils/tokenStorage.ts))
   - Утилита для работы с токенами в localStorage
   - Методы: `getToken()`, `setToken()`, `removeToken()`, `hasToken()`

2. **Auth API** ([frontend/app/src/api/auth/auth.ts](frontend/app/src/api/auth/auth.ts))
   - `register()` - регистрация с сохранением токена
   - `login()` - вход с сохранением токена
   - `logout()` - выход с удалением токена
   - `getUser()` - получение данных пользователя

3. **Axios Interceptors** ([frontend/app/src/plugins/axios.ts](frontend/app/src/plugins/axios.ts))
   - Request interceptor: автоматически добавляет токен в заголовок `Authorization: Bearer {token}`
   - Response interceptor: при 401 ошибке автоматически удаляет токен

4. **User Store** ([frontend/app/src/stores/user.ts](frontend/app/src/stores/user.ts))
   - Добавлен метод `clearUser()` для очистки данных пользователя
   - Добавлен computed `isAuthenticated` для проверки авторизации

5. **UI Компоненты**
   - [LoginForm.vue](frontend/app/src/components/Auth/LoginForm.vue) - форма входа
   - [RegisterForm.vue](frontend/app/src/components/Auth/RegisterForm.vue) - форма регистрации
   - Обновлен [UserProfile.vue](frontend/app/src/components/Main/UserProfile.vue) для корректного выхода

## Как использовать

### 1. Регистрация нового пользователя

```typescript
import { register } from '@/api/auth/auth';
import { useUserStore } from '@/stores/user';

const userStore = useUserStore();

const data = {
  name: 'Иван Иванов',
  email: 'ivan@example.com',
  password: 'password123',
  password_confirmation: 'password123'
};

try {
  const user = await register(data);
  userStore.setUser(user);
  // Токен автоматически сохранен в localStorage
} catch (error) {
  console.error('Registration error:', error);
}
```

### 2. Вход пользователя

```typescript
import { login } from '@/api/auth/auth';
import { useUserStore } from '@/stores/user';

const userStore = useUserStore();

try {
  const user = await login('ivan@example.com', 'password123');
  userStore.setUser(user);
  // Токен автоматически сохранен в localStorage
} catch (error) {
  console.error('Login error:', error);
}
```

### 3. Выход пользователя

```typescript
import { logout } from '@/api/auth/auth';
import { useUserStore } from '@/stores/user';

const userStore = useUserStore();

try {
  await logout();
  userStore.clearUser();
  // Токен автоматически удален из localStorage
} catch (error) {
  console.error('Logout error:', error);
}
```

### 4. Получение данных текущего пользователя

```typescript
import { getUser } from '@/api/auth/auth';
import { useUserStore } from '@/stores/user';

const userStore = useUserStore();

// При загрузке приложения
const user = await getUser();
if (user) {
  userStore.setUser(user);
}
```

## Защита роутов

Все запросы к API автоматически включают токен в заголовке `Authorization: Bearer {token}`.

На backend защищенные роуты используют middleware `auth:sanctum`:

```php
Route::middleware('auth:sanctum')->group(function () {
    Route::get('user', [AuthController::class, 'user']);
    Route::post('auth/logout', [AuthController::class, 'logout']);
});
```

## Автоматическая обработка ошибок

Если токен истек или невалиден (401 ошибка), axios автоматически:
- Удаляет токен из localStorage
- Можно добавить редирект на страницу логина (раскомментировать в [axios.ts](frontend/app/src/plugins/axios.ts:36))

## Миграция с Cookie-based на Token-based

Основные изменения:
1. ✅ Убрана зависимость от CSRF cookies
2. ✅ Убран `withCredentials = true`
3. ✅ Токены теперь хранятся в localStorage
4. ✅ Токены автоматически добавляются к каждому запросу
5. ✅ Backend возвращает токен при регистрации и входе

## Безопасность

- Токены хранятся в localStorage (можно использовать httpOnly cookies для дополнительной защиты от XSS)
- Все запросы идут через HTTPS в продакшене
- Токены можно отозвать на backend через `$user->tokens()->delete()`
- Можно настроить срок действия токенов в `config/sanctum.php` (параметр `expiration`)

## Дополнительные возможности

### Множественные токены для разных устройств

```php
// При логине можно указать имя устройства
$token = $user->createToken('mobile-app')->plainTextToken;
$token = $user->createToken('web-browser')->plainTextToken;

// Удалить токены определенного устройства
$user->tokens()->where('name', 'mobile-app')->delete();

// Удалить все токены пользователя
$user->tokens()->delete();
```

### Abilities (разрешения для токенов)

```php
// Создать токен с определенными правами
$token = $user->createToken('api-token', ['posts:read', 'posts:write'])->plainTextToken;

// Проверить права в контроллере
if ($request->user()->tokenCan('posts:write')) {
    // Разрешено
}
```

## Тестирование

1. Зарегистрируйте нового пользователя через API или форму регистрации
2. Проверьте, что токен сохранен в localStorage (DevTools → Application → Local Storage)
3. Сделайте запрос к защищенному роуту `/api/user`
4. Проверьте, что токен передается в заголовке (DevTools → Network → Headers)
5. Выполните logout и убедитесь, что токен удален

## Troubleshooting

**Проблема**: 401 Unauthenticated на защищенных роутах

**Решение**:
- Проверьте, что токен есть в localStorage
- Проверьте, что токен добавляется в заголовок Authorization
- Убедитесь, что в модели User есть трейт `HasApiTokens`
- Проверьте, что Sanctum установлен: `composer show laravel/sanctum`

**Проблема**: CORS ошибки

**Решение**:
- Настройте CORS в `config/cors.php`
- Убедитесь, что `VITE_BACKEND_URL` правильно настроен в `.env`
