<?php


declare (strict_types=1);

namespace plugin\sign\controller\api\auth;

use plugin\payment\service\BalanceService;
use plugin\payment\service\IntegralService;
use plugin\account\controller\api\Auth;
use plugin\sign\model\AccountUserCheckin;
use think\admin\extend\CodeExtend;
use think\admin\helper\QueryHelper;
use think\exception\HttpResponseException;

/**
 * 用户签到接口
 * @class Checkin
 * @package plugin\sign\controller\api\auth
 */
class Checkin extends Auth
{

    /**
     * 当前日期
     * @var string
     */
    protected $today = '';

    /**
     * 控制器初始化
     * @return void
     */
    protected function initialize()
    {
        parent::initialize();
        $this->today = date('Y-m-d');
    }

    /**
     * 创建签到活动
     * @return void
     */
    public function add()
    {
        try {
            $conf = sysdata(AccountUserCheckin::$ckcfg);
            if (empty($conf['status'])) $this->error('活动未开始！');
            $last = AccountUserCheckin::mk()->where(['unid' => $this->unid])->order('id desc')->findOrEmpty();
            if ($last->isExists() && $last->getAttr('date') === $this->today) $this->success('已签到！', $last->toArray());
            // 计算连续天数
            $yesterday = date('Y-m-d', strtotime('-1day', strtotime($this->today)));
            if ($last->isEmpty() || ($last->isExists() && $last->getAttr('date') !== $yesterday)) {
                $timed = $times = 1;
            } else {
                $times = $last->getAttr('times') + 1;
                $timed = $times % $conf['days'];
                if ($timed <= 0) $timed = intval($conf['days']);
            }
            // 写入签到数据
            $item = $conf['items'][$timed - 1] ?? [];
            ($checkin = AccountUserCheckin::mk())->save([
                'unid'     => $this->unid,
                'date'     => $this->today,
                'times'    => $times,
                'timed'    => $timed,
                'balance'  => $item['balance'] ?? 0,
                'integral' => $item['integral'] ?? 0,
            ]);
            // 发放余额及积分奖励
            [$balance, $integral] = [floatval($checkin->getAttr('balance')), floatval($checkin->getAttr('integral'))];
            if ($balance > 0 || $integral > 0) $this->app->db->transaction(function () use ($checkin, $balance, $integral) {
                $code = CodeExtend::uniqidNumber(16, 'CK');
                $balance > 0 && BalanceService::create($this->unid, $code, '签到奖励余额', $balance, '通过签到活动获得的奖励', true);
                $integral > 0 && IntegralService::create($this->unid, $code, '签到奖励积分', $integral, '通过签到活动获得的奖励', true);
            });
            $this->success('签到成功！', $checkin->refresh()->toArray());
        } catch (HttpResponseException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->error($exception->getMessage());
        }
    }

    /**
     * 获取签到记录
     * @return void
     */
    public function get()
    {
        $data = $this->_vali(['date.default' => '']);
        if (empty($data['date'])) $data['date'] = date('Y-m');
        $date = date('Y-m', strtotime($data['date']));
        AccountUserCheckin::mQuery(null, function (QueryHelper $query) use ($date) {
            $query->where(['unid' => $this->unid])->whereLike('create_time', "{$date}%");
            $this->success('获取签到记录！', $query->order('id desc')->page(false, false, false, 90));
        });
    }

    /**
     * 数据列表处理
     * @param array $data
     * @param array $result
     * @return void
     * @throws \think\admin\Exception
     */
    protected function _page_filter(array &$data, array &$result)
    {
        $conf = sysdata(AccountUserCheckin::$ckcfg);
        $result['date'] = $this->today;
        $result['tips'] = str2arr($conf['tips'], "\n");
    }

    /**
     * 获取签到配置
     * @return void
     * @throws \think\admin\Exception
     */
    public function config()
    {
        $data = sysdata(AccountUserCheckin::$ckcfg);
        unset($data['days'], $data['items']);
        $this->success('获取签到配置', $data);
    }
}