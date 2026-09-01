<?php

namespace App\Http\Middleware;

use Closure;
use Formatter;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\LogActivity as LogActivityModel;

class LogActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next,$subject): Response
    {
        $response = $next($request);

        $log = [];
        $log['subject'] = $subject;
        $log['url'] = $request->fullUrl();
        $log['method'] = $request->method();
        $log['agent'] = $request->header('user-agent');
        $log['respone'] = $response->status();
        $log['ip'] = $request->ip();
        if($request->has('id')){
            $log['value'] = Formatter::decrypt0($request->id);
        }
        if(auth()->guard('pemilih')->check()){
            $log['pemilih_id'] =  auth()->guard('pemilih')->user()->id;   
            LogActivityModel::create($log);
        }elseif(auth()->guard('user')->check()){
            $log['user_id'] =  auth()->guard('user')->user()->id;
            LogActivityModel::create($log);
        }else{
            $log['pemilih_id']=NULL;
            $log['user_id']=NULL;
        }

        return $response;

    }
}
