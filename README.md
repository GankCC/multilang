

# Create multi-languages website with [Laravel](https://laravel.com/), [Vue](https://vuejs.org/) and [InertiaJS](https://inertiajs.com/)

This simple example provides a guideline for creating a multi-languages website using Laravel, Vue and InertiaJS.

## Let's see the demo

First of all, clone this repository to your workspace and install dependencies.

```
git clone https://github.com/GankCC/multilang.git
cd multilang
npm install
composer install
cp .env.example .env
php artisan key:generate
npm run dev
```

Open a new tab, go to the project directory and run belowed command to start a server.
```
cd multilang
php artisan serve
```

## How it works?
Let's begin with a home page. Home.vue represent the first page of the website containg only "Hello" message at the center. On the top, there is a Header component responsible for navigation and language changing links. 

Home.vue
```vue
<template>
    <div>
        <Header />
        <div
            class="flex items-center justify-center h-screen bg-slate-900 text-gray-50"
        >
            <h1 class="text-5xl">
                {{ message[lang].hello }}
            </h1>
        </div>
    </div>
</template>

<script setup>
import Header from "@/Components/Header.vue";
import { usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const lang = computed(() => usePage().props.lang);
const message = {
    en: {
        hello: "Hello",
    },
    th: {
        hello: "สวัสดี",
    },
};
</script>

```

As you can see here, the message is stored in a variable that contains 2 languages. The message displayed at the center is determined by the "lang" props from the page. This "lang" prop is injected by HandleInertiaRequests, so that it can be accessed globally.

HandleInertiaRequests.php

```php
<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): string|null
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'lang' => app()->getLocale(),
        ]);
    }
}
```
In order to change the language, we have to create a route for handle the language.

web.php
```php
<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Home');
});

// changing language
Route::get('language/{locale}', function ($locale) {
    app()->setLocale($locale);
    session()->put('locale', $locale);

    return redirect()->back();
});
```
When user visit route, for example, "language/en", the locale session should be stored as "en". And the entire page should align with English language. However, we have to create additional middleware to set the locale for the app.

Create Localization middleware.

```
php artisan make:middleware Localization
```

Edit handle function in app/Http/Middleware/Localization.php to be as followed.

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session()->has('locale')) {
            app()->setLocale(session()->get('locale'));
        }
        return $next($request);
    }
}
```

Then, add this new middleware to Kernal.php inside the 'web' middleware group

Inside Kernal.php
```php
'web' => [
    \App\Http\Middleware\EncryptCookies::class,
    \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
    \Illuminate\Session\Middleware\StartSession::class,
    \Illuminate\View\Middleware\ShareErrorsFromSession::class,
    \App\Http\Middleware\VerifyCsrfToken::class,
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
    \App\Http\Middleware\Localization::class,
    \App\Http\Middleware\HandleInertiaRequests::class,
    \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
],
```
Please make sure that the Localization is placed above HandleInertiaRequests unless it will not working.

Finally, we create the Header component that contain links to language changing.

```vue
<template>
    <div class="flex justify-between w-full px-10 py-5 shadow-sm">
        <div>
            <h1>Company Logo</h1>
        </div>
        <ul class="flex gap-8">
            <li v-for="item in menu" key="title">
                <Link :href="item.url">{{ item[lang].title }}</Link>
            </li>
            <ul class="flex gap-1">
                <li :class="{ underline: lang == 'en' }">
                    <Link href="/language/en">EN</Link>
                </li>
                |
                <li :class="{ underline: lang == 'th' }">
                    <Link href="/language/th">TH</Link>
                </li>
            </ul>
        </ul>
    </div>
</template>
<script setup>
import { Head, Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const menu = [
    { url: "/", en: { title: "Home" }, th: { title: "หน้าแรก" } },
    { url: "/about", en: { title: "About" }, th: { title: "เกี่ยวกับเรา" } },
    { url: "/contact", en: { title: "Contact" }, th: { title: "ติดต่อเรา" } },
];

const lang = computed(() => usePage().props.lang);
</script>
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
