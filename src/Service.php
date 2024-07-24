<?php

declare (strict_types=1);

namespace plugin\sign;

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
        return [
            [
                'name' => '签到管理',
                'subs' => [
                    ['name' => '用户签到记录', 'icon' => 'layui-icon layui-icon-table', 'node' => "{$code}/sign/index"],
                    ['name' => '用户签到记录', 'icon' => 'layui-icon layui-icon-table', 'node' => "{$code}/checkin/index"],
                ],
            ]
        ];
    }
}