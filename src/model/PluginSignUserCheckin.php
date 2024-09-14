<?php

declare (strict_types=1);

namespace plugin\sign\model;

use plugin\account\model\Abs;

/**
 * 用户签到
 * @class PluginSignUserCheckin
 * @package plugin\sign\model
 */
class PluginSignUserCheckin extends Abs
{

    /**
     * 配置存储名称
     * @var string
     */
    public static $ckcfg = 'plugin.normal.event.checkin';
}