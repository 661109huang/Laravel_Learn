<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpFoundation\Response;

use App\Traits\ApiResponseTrait;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

use Illuminate\Auth\AuthenticationException;

class Handler extends ExceptionHandler
{
    use ApiResponseTrait; // 使用特徵，類似將Trait撰寫的方法貼到這個類別中
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * 攔截例外
     */
    public function render($request, Throwable $exception)
    {
        // dd($exception);

        if ($request->expectsJson()) {
            // 1.Model 找不到資源
            if ($exception instanceof ModelNotFoundException) {
                // 呼叫 errorResponse 方法 (特徵撰寫的方法)
                return $this->errorResponse(
                    '找不到資源',
                    Response::HTTP_NOT_FOUND
                );
            }
            // 2.網址輸入錯誤
            if ($exception instanceof NotFoundHttpException) {
                return $this->errorResponse(
                    '無法找到此網址',
                    Response::HTTP_NOT_FOUND
                );
            }
            // 3.網址不允許該請求動詞
            if ($exception instanceof MethodNotAllowedException) {
                return $this->errorResponse(
                    $exception->getMessage(), // 回傳例外內的訊息
                    Response::HTTP_METHOD_NOT_ALLOWED
                );
            }
        }

        // 執行父類別render的程式
        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        // 客戶端請求JSON格式
        if ($request->expectsJson()) {
            return $this->errorResponse(
                $exception->getMessage(),
                Response::HTTP_UNAUTHORIZED
            );
        } else {
            // 客戶端非請求JSON格式轉回登入頁面
            return redirect()->guest($exception->redirectTo() ?? route('login'));
        }
    }
}
