<?php


namespace huikedev\huike_base\facade;

use think\Facade;

/**
 * @see \huikedev\huike_base\debug\AppDebug
 * @mixin \huikedev\huike_base\debug\AppDebug
 * @method mixed remark( $name, $value='') static
 * @method mixed getRangeTime( $start, $end, $dec=6) static
 * @method mixed getUseTime( $dec=6) static
 * @method mixed getThroughputRate() static
 * @method mixed getRangeMem( $start, $end, $dec=2) static
 * @method mixed getMemPeak( $start, $end, $dec=2) static
 * @method mixed getFile( $detail=false) static
 */
class AppDebug extends Facade
{
    protected static function getFacadeClass()
    {
        return \huikedev\huike_base\debug\AppDebug::class;
    }
}