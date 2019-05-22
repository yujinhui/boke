<?php
	return [
		//应用ID,您的APPID。
		'app_id' => "2016092700609204",
		'seller_id' => '2088102177524452',

		//商户私钥
		'merchant_private_key' => "MIIEpQIBAAKCAQEAtUAs1Axvmf5ENL8o6XvZfDifd4K7xzMZNkzVs7IbtlwH6RCaBSONKRqK0viv3wHhGnh8jz301zKYAunWqimdUDKCN3zHpaxZs1FjWNdKrCp2qy4B/LMcs/FE4DxhFjAgLVJmQDegIBM16qXBlKS9X46gPlQhjUsZkBx1ob8EzMfKxKEfBZNspwuwtT69O+ZLEzFc2ze8Am4nsQH9xGZA5pLJewhoCuhuqbIVibLPg7skP667vI4P9derdup8rqC+a3jVI2FrHEpR+vYLBGIjMEQ9EdeJlS/8md8NtqLGywMCoqRY4ad2dAsjVvlJlLwYWQhKsbnTOW9SoXwVvGI28QIDAQABAoIBAAmtSndrzo2POL8EMb/9f8gVEdaEoVfqT39JrOMyMC6VTUrROxfpJQE7zIulAWbLp+QM6MGREkBnEByftXlSTXbZq4Ag5lvfDs73GnHE1NTKU5miFDMUsQSebgn2z0wq95zT2Ge3UJJF1EwZ23L/NKkkaUPKpBvyFYRPMKyZET2Jmh8kizPh4nyBMOiQKLFKjcygwr3Han0q8jrmh3mTURPZ3985AW4idM2WksK41M0GnRV0tFpnJjNmzsMCt2Bos5W7M71Qc/zqTDDcCexYQe/q1vKkDiu3LVoQUafRccotZq5iXSPZ+VZHwllW47MfK8F9FtjC8IW8QZxNzEeWdMECgYEA63Rs7zfhBTn8MN9aJTHz6QAczWrnpZNi0PiYo05eH3IZyvhlZUSLaAwyJFHkXVpPscFmpQ0NAycmmzNg1d9AtHG6jEfgCA0ys0luZSCMTKfr/qHBhI1qIluHhItUiUd/iS/A3x2lzJyjJe1Jvn9sY42EJ1cAysZwpBsr7CCVZdkCgYEAxRDxEf6jFfddGIvYpkXZku9DI3uRjLJlcoinOPHEVVDYa4Rm0oy3+ROht+i/CxWims0q6QYFI72SGnJmk+y1RnNIrQ3T93s1GN2OwsL4t2zZ+R3ZhumtVnU9JOy+SF6gBMSur6vtYh9Rs7rOj9aL2xWkc7L5Urv0j1DfqsmestkCgYEA4/p2/6k7V7QtW6Tnw9v6L0DMoplAcjGjHHOYV4cp24i/MKgShVs5ICWu5zvjwgAP6i05FdbtIoBmASBeQrdID2+PEQUHBsTAspgHLCO3tQFin8o/dfj3Kw00ykGeOM3hzxNNzLsILnX8Al0fajQ23q3Hp8+2FKDPsBYfESL7hQECgYEArFrn7UwfiSvDIZ/WSX6YD7nxp4wAveSdc7HvR4+0nsXJsyem4omusksoSvhmdqRihi/hUtMwFZVMvpLGAqSp20cjDjAk7rO0ud21acQq0gqDDi28PhIMx6xcYPoqZpNUMzdubTjastIfnClLVmRYB7KDFao4A9Ndzyb1qKMUiOkCgYEAuFei09w7tPOC18ao4yNa4rSXJuYXBFcjVT3yV+iz/AO7OfhvFIMkblWKjIMr88udoVpbPbboYcLZP80qYR5Om9OXF8DtKz07gEcACIUJM/oqZCR+tC0OIgZATEupf1AwUynrh+Vc0k1kAzoP5+PRdpvtperhidhv9+qkeEL8LMM=",
		
		//异步通知地址
		'notify_url' => "http://39.105.155.250/pay/alipay",
		
		//同步跳转
		'return_url' => "http://39.105.155.250/pay/success",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAuOXuNThGTZXFZ3HgZq9yuOouSAmK5KXoaAz9/pHycfFEQWrxztSVkUbxCn8Iocyu0iODVbag7j80Xh5XzCm3oe6s1ITXMW9ns8ak/ur7BZkxRUOcXMCPJOHYrv8GPL5S5Wsovp+HjBsUNHCrnN5H2IB0CtNMlZPqDJ2PhoOHlnSMM504dEuTxUIHNO65RpBhIKDW9062eJ/gKEVAI/X3daKZtAa6IXK6vL0caFRDRRW/KNMSHg34QaSCBKPTTVPvTzRiZ9D2FHg/12JwluavKjqn/78ucVwqYtnOkZjj/LHGEGpwRKPnzOYhkQukfRfSBU+RaoFUe5Q0/Pt0CJVVNQIDAQAB",
		

	];