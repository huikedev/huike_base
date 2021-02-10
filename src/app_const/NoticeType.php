<?php


namespace huikedev\huike_base\app_const;

/**
 * Desc
 * Class NoticeType
 * Full \huikedev\huike_base\app_const\NoticeType
 * @package huikedev\huike_base\app_const
 */
class NoticeType
{
    //
    const SILENT = 0;
    const MESSAGE_WARN=1;
    const MESSAGE_ERROR=2;
    const MESSAGE_SUCCESS=3;
    const NOTIFICATION_WARN=4;
    const NOTIFICATION_ERROR=5;
    const NOTIFICATION_SUCCESS=6;
    const DIALOG_WARN=7;
    const DIALOG_ERROR=8;
    const DIALOG_SUCCESS=9;
    const PAGE_WARN = 10;
    const PAGE_ERROR=11;
    const PAGE_SUCCESS = 12;

    const ALL=[
        'SILENT',
        'MESSAGE_WARN',
        'MESSAGE_ERROR',
        'MESSAGE_SUCCESS',
        'NOTIFICATION_WARN',
        'NOTIFICATION_ERROR',
        'NOTIFICATION_SUCCESS',
        'DIALOG_WARN',
        'DIALOG_ERROR',
        'DIALOG_SUCCESS',
        'PAGE_WARN',
        'PAGE_ERROR',
        'PAGE_SUCCESS'
    ];

    const ALL_TEXT=[
        '静默模式',
        'Message.Waring',
        'Message.Error',
        'Message.Success',
        'Notification.Waring',
        'Notification.Error',
        'Notification.Success',
        'Dialog.Waring',
        'Dialog.Error',
        'Dialog.Success',
        'Page.Waring',
        'Page.Error',
        'Page.Success',
    ];

}