<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Transformers\CategoryTransformer;


class CategoriesController extends Controller
{

    /**
     * 获取分类列表（无分页）
     * @return \Dingo\Api\Http\Response
     */
    public function index()
    {
        return $this->response->collection(Category::all(), new CategoryTransformer());
    }


}
