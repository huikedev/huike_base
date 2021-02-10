<?php
return [
    // 是否安装完成
    'is_installed'=>false,
    // 是否替换Query类
    'replace_query'=>true,
    // 是否扩展基础验证规则
    'extra_validate'=>true,
    //日志记录级别，默认记录所有手动抛出异常
    'log_error_level'=>-1,
    //默认异常提示类型
    'default_error_notice_type'=>4,
    //线上环境开启调试模式
    'online_debug'=>[
        'key'=>'huike',
        'value'=>'debug'
    ],
    // 是否严格检测逻辑控制器继承BaseLogic
    'base_logic_check'=>false,
    // 分页配置
    'paginator'=>[
        'replace'=>true,
        'var_page'=>'current',
        'var_pageSize'=>'pageSize',
        'var_total'=>'total',
        'var_data'=>'list',
        'var_last'=>'last',
        'pageSize'=>10
    ],
    'token'=>[
        //同一用户允许同时在线最大客户端数量
        'max_client'=>3,
        //token名称   string
        'token_name'=>'authorization',
        //token secret token私钥,
        'token_secret'=>'huike.dev',
        //token 前缀 ,
        'token_prefix'=>'token_',
        //token加密方式
        'token_alg'=>'HS256',
        //token发布者，一般填写网站域名
        'token_iss'=>'huike.dev',
        //token默认有效期（秒），默认为8*60*60，false 为长期有效
        'token_lifetime'=>28800,
    ],
    'cors'=>[
        //允许来路
        'optionOrigin'=>['*'],             //允许域名来路,如'https://www.baidu.com'
        //请求方式
        'optionMethods'=>['*'],             //如 'GET'
        //允许请求中携带的header的name，token_name自动允许
        'optionHeaders'=>[]
    ]
];