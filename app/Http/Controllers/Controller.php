<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // Add PHP console logger
    public function console_log($data) {
        echo '<script>';
        echo 'console.log('.json_encode($data).')';
        echo '</script>';
    }
    public function console_log_off($data) {
        // This is just an easy way to turn hide the console messages
    }
}
