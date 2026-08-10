<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public const SUPPORTED = [
        'id' => ['name' => 'Indonesian', 'native' => 'Bahasa Indonesia', 'dir' => 'ltr'],
        'en' => ['name' => 'English', 'native' => 'English', 'dir' => 'ltr'],
        'zh' => ['name' => 'Chinese', 'native' => '中文', 'dir' => 'ltr'],
        'ja' => ['name' => 'Japanese', 'native' => '日本語', 'dir' => 'ltr'],
        'ko' => ['name' => 'Korean', 'native' => '한국어', 'dir' => 'ltr'],
        'ar' => ['name' => 'Arabic', 'native' => 'العربية', 'dir' => 'rtl'],
        'es' => ['name' => 'Spanish', 'native' => 'Español', 'dir' => 'ltr'],
        'fr' => ['name' => 'French', 'native' => 'Français', 'dir' => 'ltr'],
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('locale', config('app.locale', 'id'));

        if (! array_key_exists($locale, self::SUPPORTED)) {
            $locale = 'id';
        }

        App::setLocale($locale);
        $dir = self::SUPPORTED[$locale]['dir'];

        View::share('appLocale', $locale);
        View::share('appDir', $dir);

        $response = $next($request);

        if (method_exists($response, 'header')) {
            $response->headers->set('Content-Language', $locale);
        }

        return $response;
    }
}
