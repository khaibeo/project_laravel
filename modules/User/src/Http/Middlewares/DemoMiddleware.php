<?php
namespace Modules\User\src\Http\Middlewares;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class DemoMiddleware{
   public function handle(Request $request, Closure $next): Response
   {
    return $next($request);
   }
}