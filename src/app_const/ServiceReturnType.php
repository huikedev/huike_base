<?php


namespace huikedev\huike_base\app_const;


use think\Collection;
use think\Model;
use think\Paginator;

class ServiceReturnType
{
    const ALL = [
        'mixed',
        'bool',
        'int',
        'float',
        'array',
        'array_object',
        'string',
        'paginator',
        'self',
        'model',
        'collection',
        'object'
    ];

    const ALL_TEXT = [
        'mixed'=>'任意（mixed）',
        'bool'=>'布尔（bool）',
        'int'=>'整型（int）',
        'float'=>'浮点（mixed）',
        'array'=>'数组（array）',
        'array_object'=>'数组对象（array_object）',
        'string'=>'字符串（string）',
        'paginator'=>'分页（paginator）',
        'self'=>'当前类（self）',
        'model'=>'模型（model）',
        'collection'=>'数据集（collection）',
        'object'=>'对象（object）'
    ];

    const PHP_DEFAULT = [
        'mixed',
        'bool',
        'int',
        'float',
        'array',
        'string',
        'self',
        'object'
    ];

    const TP_DEFAULT = [
        'paginator'=>Paginator::class,
        'model'=>Model::class,
        'collection'=>Collection::class
    ];
}