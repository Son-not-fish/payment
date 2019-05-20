<?php
// +----------------------------------------------------------------------
// | 海豚PHP框架 [ DolphinPHP ]
// +----------------------------------------------------------------------
// | 版权所有 2016~2019 广东卓锐软件有限公司 [ http://www.zrthink.com ]
// +----------------------------------------------------------------------
// | 官方网站: http://dolphinphp.com
// +----------------------------------------------------------------------

namespace app\index\controller;

use think\App;
use think\captcha\Captcha;
use think\Db;
use think\facade\Config;
use think\facade\Cookie;
use think\facade\Request;
use think\facade\Url;
use think\facade\Validate;


/**
 * 前台首页控制器
 * @package app\index\controller
 */
class Index extends Home
{

    public function __construct(App $app = null)
    {
        parent::__construct($app);
        $lang = input('lang');
        if ($lang) {
            Cookie::set('think_var', $lang);
        }
        $lang = Cookie::get('think_var') ?? 'zh-cn';
        if ($lang) {
            Cookie::set('think_var', $lang);
        }
        $this->assign('lang', $lang);
    }

    public function index()
    {
        $slider = Db::table('db_cms_slider')->order('sort')->select();
        $this->assign('slider', $slider);
        return $this->view->fetch();
    }

    public function product()
    {
        return $this->view->fetch('product-services');
    }

    public function contact()
    {
        if ($this->request->isAjax()) {
            $data = input();
            if (!$res = captcha_check($data['dzcaptcha'])) {
                return json(['status' => 0, 'msg' => lang('Error in Verification Code')]);
            }
            $rule = [
                'dzName' => 'require',
                'dzEmail' => 'require|email',
                'Phone' => 'require',
                'organisation' => 'require',
                'dzMessage' => 'require',
                'dzcaptcha' => 'require',
            ];
            $msg = [
                'dzName.require' => lang('Please fill in your name.'),
                'dzEmail.require' => lang('Please fill in the mailbox'),
                'dzEmail.email' => lang('Error in mailbox format'),
                'Phone.require' => lang('Please fill in the contact information.'),
                'organisation.require' => lang('Please fill in the organization.'),
                'dzMessage.require' => lang('Please fill in the information.'),
                'dzcaptcha.require' => lang('Please fill in the verification code.'),
            ];
            $validate = Validate::make($rule, $msg);
            $result = $validate->check($data);
            if (!$result) {
                return json(['status' => 0, 'msg' => lang($validate->getError())]);
            }
            $data['create_time'] = time();
            $res = Db::table('db_contact')->insert($data);
            if ($res) {
                return json(['status' => 1, 'msg' => lang('success')]);
            } else {
                return json(['status' => 0, 'msg' => lang('System busy')]);
            }
        }
        return $this->view->fetch();
    }

    public function about()
    {
        return $this->view->fetch();
    }

    public function merchant()
    {
        if ($this->request->isAjax()) {
            $data = input();
            if (!$res = captcha_check($data['captcha'])) {
                return json(['status' => 0, 'msg' => lang('Error in Verification Code')]);
            }
            $data['create_time'] = time();
            $data['business_type'] = json_encode($data['business_type']);
            $data['settlement_cur'] = json_encode($data['settlement_cur']);
            $res = Db::table('db_merchant')->insert($data);
            if ($res) {
                return json(['status' => 1, 'msg' => lang('success')]);
            } else {
                return json(['status' => 0, 'msg' => lang('System busy')]);
            }
        }
        return $this->view->fetch();
    }


    public function verify()
    {
        $captcha = new Captcha(\config()['captcha']);
        return $captcha->entry();
    }
}
