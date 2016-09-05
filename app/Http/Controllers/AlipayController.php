<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Subscription;
use App\User;
use App\UserSubscription;
use Bican\Roles\Models\Role;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Omnipay\Omnipay;

class AlipayController extends Controller
{
    public function pay($id) {
        $gateway = Omnipay::create('Alipay_Express');
        $gateway->setPartner(env('ALIPAY_PID'));  //合作者id
        $gateway->setKey(env('ALIPAY_SECRET'));   //支付key
        $gateway->setSellerEmail(env('ALIPAY_SELLER'));  //收款账户email
        $gateway->setReturnUrl(url('alipay/result'));  //返回url， 用户支付后会跳转到这个地址， 可以定义支付成功或者支付失败等页面返回增加用户体验
        $gateway->setNotifyUrl(url('alipay/result'));   //通知url，每次支付完成后， 支付宝服务器会向这个地址发请求，返回支付状态

        $menu = Subscription::findOrFail($id);
        $options = [
            'out_trade_no' => date('YmdHis') . mt_rand(1000,9999),
            'subject' => 'Alphabeille: ' . trans('labels.' . $menu->name),
            'body' => \Auth::user()->id . '_' . $id,
            'total_fee' => $menu->price,
        ];

        $response = $gateway->purchase($options)->send();
        $response->redirect();
    }

    public function result()
    {
        if($this->isFromAlipay(Input::get('notify_id'))) {
            $gateway = Omnipay::create('Alipay_Express');
            $gateway->setPartner(env('ALIPAY_PID'));  //合作者id
            $gateway->setKey(env('ALIPAY_SECRET'));   //支付key
            $gateway->setSellerEmail(env('ALIPAY_SELLER'));  //收款账户

            $options = [
                'request_params' => $_REQUEST,
            ];

            $response = $gateway->completePurchase($options)->send();

            if ($response->isSuccessful() && $response->isTradeStatusOk()) {
                $bodies = explode('_', Input::get('body'));

                $subscription = Subscription::find($bodies[1]);

                DB::transaction(function () use ($bodies, $subscription) {
                    UserSubscription::create([
                        'user_id' => $bodies[0],
                        'subscription_id' => $bodies[1],
                        'expire_at' => Carbon::now()->addMonth($subscription->duration),
                        'price' => $subscription->price
                    ]);

                    $user = User::findOrFail($bodies[0]);
                    $role = Role::findOrFail(3);
                    $user->attachRole($role);
                    $user->save();
                });
                return redirect('/');
            }
        }
        Session::flash('payFailed', 'payFailed');
        return redirect('menus');
    }

    protected function isFromAlipay($notifyId)
    {
        $url = 'https://mapi.alipay.com/gateway.do?service=notify_verify&partner=' . trim(env('ALIPAY_PID')) . '&notify_id=' . $notifyId;
        $response = $this->httpGet($url);
        return (bool) preg_match("/true$/i",$response);
    }

    protected function httpGet($url)
    {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HEADER, 0 ); // 过滤HTTP头
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);// 显示输出结果
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);//SSL证书认证
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);//严格认证
        curl_setopt($curl, CURLOPT_CAINFO, public_path() . '/cacert.pem');//证书地址
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
}
