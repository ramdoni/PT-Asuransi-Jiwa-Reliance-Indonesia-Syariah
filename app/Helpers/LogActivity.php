<?php
namespace App\Helpers;
use Request;
use App\Models\LogActivity as LogActivityModel;

class LogActivity
{
    public static function add($subject="")
    {
    	$log = [];
    	$log['subject'] = $subject="" ? Request::route()->getName() : $subject;
    	$log['url'] = Request::fullUrl();
    	$log['method'] = Request::method();
    	$log['ip'] = Request::ip();
		$log['var'] = Request::method()=='POST' ? json_encode(Request::post()) : '';
    	$log['agent'] = Request::header('user-agent');
    	$log['user_id'] = auth()->check() ? auth()->user()->id : 1;
    	LogActivityModel::create($log);
    }
	
    public static function logActivityLists()
    {
    	return LogActivityModel::latest()->get();
    }
}