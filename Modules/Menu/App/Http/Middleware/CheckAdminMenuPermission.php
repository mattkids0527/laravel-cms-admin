<?php

namespace Modules\Menu\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Menu\App\Services\AdminMenuService;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminMenuPermission
{
    public function __construct(private AdminMenuService $menuService) {}

    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('admin')->user();

        if (! $user) {
            return $next($request);
        }

        $routeName = $request->route()?->getName();

        if ($routeName && ! $this->menuService->canAccess($user, $routeName)) {
            abort(403, '您沒有權限存取此頁面。');
        }

        return $next($request);
    }
}
