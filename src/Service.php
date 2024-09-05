<?php

declare (strict_types=1);

namespace plugin\sign;

use plugin\account\Service as AccountService;
use think\admin\Plugin;

/**
 * 组件注册服务
 * @class Service
 * @package app\sign
 */
class Service extends Plugin
{
    /**
     * 定义插件名称
     * @var string
     */
    protected $appName = '签到管理';

    /**
     * 定义安装包名
     * @var string
     */
    protected $package = 'xiaochao/plugs-sign';


    /**
     * 签到模块菜单配置
     * @return array[]
     */
    public static function menu(): array
    {
        // 设置插件菜单
        $code = app(static::class)->appCode;
        // 设置插件菜单
        return array_merge(AccountService::menu(), [
            [
                'name' => '签到管理',
                'subs' => [
                    ['name' => '签到参数配置', 'icon' => 'layui-icon layui-icon-table', 'node' => "{$code}/checkin/config"],
                    ['name' => '签到记录管理', 'icon' => 'layui-icon layui-icon-table', 'node' => "{$code}/checkin/index"],
                ],
            ]
        ]);
    }
}