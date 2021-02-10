<?php


namespace huikedev\huike_base\app_const\response;

/**
 * Desc
 * Class AppResponseType
 * Full \huikedev\huike_base\app_const\response\AppResponseType
 * @package huikedev\huike_base\app_const\response
 */
class AppResponseType
{
    const DOWNLOAD = 'download';
    const DEFAULT = 'json';
    const JSON = 'json';
    const HTML = 'html';
    const REDIRECT = 'redirect';
    const DATA_STREAM = 'data_stream';
    const JSONP = 'jsonp';
    const IMAGE = 'image';
    const ALL_TEXT=[
        'NONE', // 占位
        'JSON',
        'HTML',
        'JSONP',
        '图片',
        '数据流',
        '下载',
        '跳转',
    ];

    const NAMES = [
        'none',
        'json',
        'html',
        'jsonp',
        'image',
        'data_stream',
        'download',
        'redirect'
    ];


}