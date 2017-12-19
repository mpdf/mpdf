<?php
/**
 * Created by PhpStorm.
 * User: ghans
 * Date: 19/12/17
 * Time: 10:05 AM
 */

namespace Mpdf\Providers;


use Illuminate\Support\Facades\Facade;

class PDFFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'mpdf';
    }
}