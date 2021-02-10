<?php


namespace huikedev\huike_base\app_const;

/**
 * Desc
 * Class AppSpecialValue
 * Full \huikedev\huike_base\app_const\AppSpecialValue
 * @package huikedev\huike_base\app_const
 */
class AppSpecialValue
{
    //永久时间戳，数据库字段需要unsigned
    const FOREVER_TIMESTAMP_UNSIGNED = 4070880000;
    //永久时间戳，数据库字段signed
    const FOREVER_TIMESTAMP_SIGNED = 2147483647;
    //最终积极状态
    const VALUE_PASSED = 9;
    //最终消极状态
    const VALUE_REJECTED = 9;
}