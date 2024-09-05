<?php

namespace plugin\sign\controller\api\auth;

use plugin\account\controller\api\Auth;
use think\exception\HttpResponseException;
use plugin\sign\service\SignService;

/**
 * 用户签到
 */
class Sign extends Auth
{

    /**
     * 签到
     * @return void
     */
    public function sign()
    {
        try {
            $this->success('签到成功',SignService::in($this->unid));
        } catch (HttpResponseException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->error($exception->getMessage(), [], $exception->getCode());
        }
    }

    /**
     * 获取今天的奖励
     * @return void
     */
    public function today()
    {
        try {
            $data = SignService::todayReward($this->unid);
            $this->success('获取成功',$data);
        } catch (HttpResponseException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->error($exception->getMessage(), [], $exception->getCode());
        }
    }

    /**
     * 获取明天天的奖励
     * @return void
     */
    public function tomorrow()
    {
        try {
            $data = SignService::tomorrowReward($this->unid);
            $this->success('获取成功',$data);
        } catch (HttpResponseException $exception) {
            throw $exception;
        } catch (\Exception $exception) {
            $this->error($exception->getMessage(), [], $exception->getCode());
        }
    }
}