<?php

use think\migration\Migrator;

class InstallSign extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->_create_plugin_sign_user_checkin();
    }

    /**
     * 业务-活动-签到
     * @class PluginSignUserCheckin
     * @table plugin_sign_user_checkin
     * @return void
     */
    private function _create_plugin_sign_user_checkin()
    {

        // 当前数据表
        $table = 'plugin_sign_user_checkin';

        // 存在则跳过
        if ($this->hasTable($table)) return;

        // 创建数据表
        $this->table($table, [
            'engine' => 'InnoDB', 'collation' => 'utf8mb4_general_ci', 'comment' => '业务-活动-签到',
        ])
            ->addColumn('unid', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '用户UNID'])
            ->addColumn('times', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '连续天数'])
            ->addColumn('timed', 'biginteger', ['limit' => 20, 'default' => 0, 'null' => true, 'comment' => '奖励天数'])
            ->addColumn('date', 'string', ['limit' => 20, 'default' => '', 'null' => true, 'comment' => '签到日期'])
            ->addColumn('balance', 'decimal', ['precision' => 20, 'scale' => 2, 'default' => '0.00', 'null' => true, 'comment' => '赠送余额'])
            ->addColumn('integral', 'decimal', ['precision' => 20, 'scale' => 2, 'default' => '0.00', 'null' => true, 'comment' => '赠送积分'])
            ->addColumn('status', 'integer', ['limit' => 1, 'default' => 1, 'null' => true, 'comment' => '生效状态(0未生效,1已生效)'])
            ->addColumn('deleted', 'integer', ['limit' => 1, 'default' => 0, 'null' => true, 'comment' => '删除状态(0未删除,1已删除)'])
            ->addColumn('create_time', 'datetime', ['default' => NULL, 'null' => true, 'comment' => '创建时间'])
            ->addColumn('update_time', 'datetime', ['default' => NULL, 'null' => true, 'comment' => '更新时间'])
            ->addIndex('unid', ['name' => 'i0faf876c0_unid'])
            ->addIndex('date', ['name' => 'i0faf876c0_date'])
            ->addIndex('status', ['name' => 'i0faf876c0_status'])
            ->addIndex('deleted', ['name' => 'i0faf876c0_deleted'])
            ->addIndex('create_time', ['name' => 'i0faf876c0_create_time'])
            ->create();

        // 修改主键长度
        $this->table($table)->changeColumn('id', 'integer', ['limit' => 11, 'identity' => true]);
    }
}
