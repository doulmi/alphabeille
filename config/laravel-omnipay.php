<?php

return [

	// The default gateway to use
	'default' => 'alipay',

	// Add in each gateway here
	'gateways' => [
		'paypal' => [
			'driver'  => 'PayPal_Express',
			'options' => [
				'solutionType'   => '',
				'landingPage'    => '',
				'headerImageUrl' => ''
			]
		],

		'alipay' => [
			'driver' => 'Alipay_Express',
			'options' => [
				'partner' => '2088222835724472',
				'key' => '2016072001642324',
				'sellerEmail' =>'alphabeillestudio@gmail.com	',
				'returnUrl' => '',
				'notifyUrl' => ''
			]
		]
	]

];