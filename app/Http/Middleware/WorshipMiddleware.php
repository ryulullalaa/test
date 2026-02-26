<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class WorshipMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $grade_id = User::findOrFail(Auth::user()->id)->member->grade_id;
        $permit_grade_id = [
            4,  // 총괄관리자
            6,  // 임원단총괄
            7,  // 임원단서기
            8,  // 교구임원단
            10,  // 팀장
            11, // 그룹장
        ];

        if (!in_array($grade_id, $permit_grade_id)) {
            return redirect('/')->with('error', '접근 권한이 없습니다.');
        }

        return $next($request);
    }
}
