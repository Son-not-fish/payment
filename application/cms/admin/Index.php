<?php
// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2019 广东卓锐软件有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------

namespace app\cms\admin;

use app\admin\controller\Admin;
use app\common\builder\ZBuilder;
use think\Db;

/**
 * 仪表盘控制器
 * @package app\cms\admin
 */
class Index extends Admin
{
    /**
     * 首页
     * @author 蔡伟明 <314013107@qq.com>
     * @return mixed
     */
    public function index()
    {
        $this->assign('document', Db::name('cms_document')->where('trash', 0)->count());
        $this->assign('column', Db::name('cms_column')->count());
        $this->assign('page', Db::name('cms_page')->count());
        $this->assign('model', Db::name('cms_model')->count());
        $this->assign('page_title', '仪表盘');
        return $this->fetch(); // 渲染模板
    }

    public function lianxi()
    {
        // 查询
        $map = $this->getMap();
        // 排序
        $order = $this->getOrder('id desc');
        // 数据列表
        $data_list = Db::table('db_contact')->where($map)->order($order)->paginate();
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['dzName', '姓名'],
                ['dzEmail', '邮件'],
                ['Phone', '电话'],
                ['dzMessage', '留言'],
                ['organisation', '组织'],
                ['create_time', '创建时间', 'datetime'],
                ['right_button', '操作', 'btn']
            ])
            ->setTableName('contact')
            ->addTopButtons('delete') // 批量添加顶部按钮
            ->addRightButtons(['delete' => ['data-tips' => '删除后无法恢复。']]) // 批量添加右侧按钮
            ->addOrder('id,title,create_time')
            ->setRowList($data_list) // 设置表格数据
            ->fetch(); // 渲染模板
    }

    public function apply()
    {
        // 查询
        $map = $this->getMap();
        // 排序
        $order = $this->getOrder('id desc');
        // 数据列表
        $data_list = Db::table('db_merchant')->where($map)->order($order)->paginate();
        // 使用ZBuilder快速创建数据表格
        return ZBuilder::make('table')
            ->addColumns([ // 批量添加数据列
                ['id', 'ID'],
                ['trading_name', '公司商业名称'],
                ['name','联系人'],
                ['position','职位'],
                ['tot_vol_RMB','每月总量'],
                ['create_time', '创建时间', 'datetime'],
                ['right_button', '操作', 'btn']
            ])
            ->addRightButton('diy',[
                'title' => '详细信息',
                'href' => url('info',['id' => '__id__'],'true'),
            ],true)
            ->setTableName('merchant')
            ->addTopButtons('delete') // 批量添加顶部按钮
            ->addRightButtons(['delete' => ['data-tips' => '删除后无法恢复。']]) // 批量添加右侧按钮
            ->addOrder('id,title,create_time')
            ->setRowList($data_list) // 设置表格数据
            ->fetch(); // 渲染模板
    }

    public function info()
    {
        $id = input('id');
        $info = Db::table('db_merchant')->where('id','=',$id)->find();
        // 使用ZBuilder快速创建数据表格
        $info['business_type'] = implode(',', json_decode($info['business_type'], true));
        $info['settlement_cur'] = implode(',', json_decode($info['settlement_cur'], true));
        return ZBuilder::make('form')
            ->addFormItems([ // 批量添加数据列
                ['static','trading_name', '公司商业名称'],
                ['static','company_name', '公司名称'],
                ['static','bus_reg_number', '商业登记号码'],
                ['static','country_incorp', '公司所在国家'],
                ['static','date_bus_est', '商业成立日期'],
                ['static','street','街'],
                ['static','city','市'],
                ['static','state','州'],
                ['static','Country','国家'],
                ['static','zipcode','邮政编码'],
                ['static','business_type','业务类型'],
                ['static','website','网站'],
                ['static','industry','行业'],
                ['static','name','联系人'],
                ['static','email','邮箱'],
                ['static','position','职位'],
                ['static','tot_vol_RMB','每月总量'],
                ['static','tot_transaction_month','每月成交总数'],
                ['static','avg_tr','平均交易金额'],
                ['static','hst_tr','最高交易金额'],
                ['static','method_cur','所需付款方式和货币'],
                ['static','settlement_cur','首选结算货币'],
                ['static','q2','是否需要技术部门接入api'],
                ['static','q3','您是否在您的网站上提供会员资格'],
                ['static','referred_by','推荐人'],
            ])
            ->addDatetime('create_time', '申请时间')
            ->hideBtn('submit')
            ->setFormData($info) // 设置表单数据
            ->fetch(); // 渲染模板

    }
}
