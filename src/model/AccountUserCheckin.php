<?php


declare (strict_types=1);

namespace plugin\sign\model;

use plugin\account\model\Abs;

/**
 * 用户签到
 * @class AccountSign
 * @package plugin\sign\model
 */
class AccountUserCheckin extends Abs
{

    /**
     * 配置存储名称
     * @var string
     */
    public static $ckcfg = 'plugin.normal.event.checkin';
}