<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Image;
use App\Handlers\ImageUploadHandler;
use App\Transformers\ImageTransformer;
use App\Http\Requests\Api\ImageRequest;

class ImagesController extends Controller
{

    /**
     * 上传图片资源
     *
     * @param ImageRequest $request 图片验证规则
     * @param ImageUploadHandler $uploader 图片上传助手函数
     * @param Image $image eloquent 实例
     * @return \Dingo\Api\Http\Response
     */
    public function store(ImageRequest $request, ImageUploadHandler $uploader, Image $image)
    {
        // $this->user() 等同于\Auth::guard('api')->user()
        // 见 Dingo\Api\Routing\Helpers 这个 trait
        $user = $this->user();

        // 限制图片尺寸
        $size = $request->type == 'avatar' ? 362 : 1024;
        // str_plural 获取英语单词的复数形式
        $result = $uploader->save($request->image, str_plural($request->type), $user->id, $size);

        $image->path = $result['path'];
        $image->type = $request->type;
        $image->user_id = $user->id;
        $image->save();

        return $this->response->item($image, new ImageTransformer())->setStatusCode(201);
    }



}
