<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Subscription;
use App\UserSubscription;
use Illuminate\Support\Facades\Session;
use Omnipay\Omnipay;

class AlipayController extends Controller
{
    public function pay($id) {
        $gateway = Omnipay::create('Alipay_Express');
        $gateway->setPartner('2088222835724472');  //合作者id
        $gateway->setKey('7ibfr0bnv8ymljkvrao2dsu7mgqj5ui3');   //支付key
        $gateway->setSellerEmail('alphabeillestudio@gmail.com');  //收款账户email
        $gateway->setReturnUrl(url('alipay/result'));  //返回url， 用户支付后会跳转到这个地址， 可以定义支付成功或者支付失败等页面返回增加用户体验
        $gateway->setNotifyUrl(url('alipay/result'));   //通知url，每次支付完成后， 支付宝服务器会向这个地址发请求，返回支付状态

        $menu = Subscription::findOrFail($id);
        $options = [
            'out_trade_no' => date('YmdHis') . mt_rand(1000,9999),
            'subject' => 'Alphabeille: ' . trans('labels.' . $menu->name),
            'total_fee' => $menu->price,
        ];

        $response = $gateway->purchase($options)->send();
        $response->redirect();
    }

    public function result()
    {
        $gateway = Omnipay::create('Alipay_Express');
        $gateway->setPartner('2088222835724472');  //合作者id
        $gateway->setKey('7ibfr0bnv8ymljkvrao2dsu7mgqj5ui3');   //支付key
        $gateway->setSellerEmail('alphabeillestudio@gmail.com');  //收款账户e

        $options = [
            'request_params'=> $_REQUEST,
        ];

        $response = $gateway->completePurchase($options)->send();

        if ($response->isSuccessful() && $response->isTradeStatusOk()) {
            UserSubscription::create([

            ]);
            return redirect('/');
        } else {
            Session::flash('payFailed', 'payFailed');
            return redirect('/menus');
        }
    }

    public function buy($id)
    {
        //调用支付网关， 这一步其实就是配置参数，可以写成通用函数
        $gateway = Omnipay::create('Alipay_Express');  //创建网关类
        $gateway->setPartner('');  //合作者id
        $gateway->setKey('');   //支付key
        $gateway->setSellerEmail('alphabeillestudio@gmail.com');
        //收款账户email
        $gateway->setReturnUrl('http://www.example.com/return');  //返回url， 用户支付后会跳转到这个地址， 可以定义支付成功或者支付失败等页面返回增加用户体验
        $gateway->setNotifyUrl('http://www.example.com/notify');   //通知url，每次支付完成后， 支付宝服务器会向这个地址发请求，返回支付状态

//设置订单
        $options = [
            'out_trade_no' => date('YmdHis') . mt_rand(1000, 9999),  //你自己网站的唯一订单号
            'subject' => 'test', //订单标题
            'total_fee' => '0.01', //订单价格
            //这里也可以带上其他参数，支付完之后支付宝会返回该参数和对应的值，不过设置了之后后面处理支付状态需要多一步操作。我一般会设置'paytype' => 'alipay',
        ];

//跳转支付
        $response = $gateway->purchase($options)->send();
        $response->redirect();
    }
}
