<?php

/**
 * https://github.com/Gregwar/Captcha
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Gregwar\Captcha\CaptchaBuilder;
use App\Http\Requests\Api\CaptchaRequest;

class CaptchasController extends Controller
{

    /**
     * 生成图片验证码
     * @param CaptchaRequest $request
     * @param CaptchaBuilder $captchaBuilder  图片验证码类库
     * @return mixed
     */
    public function store(CaptchaRequest $request, CaptchaBuilder $captchaBuilder){
        $key = 'captcha-' . str_random(15);
        $phone = $request->phone;

        // 创建验证码图片
        $captcha = $captchaBuilder->build();
        $expireAt = now()->addMinute(2);

        // 获取图片验证码文本内容
        $code = $captcha->getPhrase();
        \Cache::put($key, ['phone' => $phone, 'code' => $code], $expireAt);

        $result = [
            'captcha_key' => $key,
            'expired_at' => $expireAt->toDateTimeString(),
            // 返回图片的 base64 格式
            'captcha_image_content' => $captcha->inline()
        ];

        return $this->response->array($result)->setStatusCode(201);
    }


}
