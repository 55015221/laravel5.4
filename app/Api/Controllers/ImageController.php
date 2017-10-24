<?php

namespace App\Api\Controllers;

use App\Models\Image;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class ImageController extends BaseController
{

    public function index(Request $request)
    {
        $title = $request->input('title');
        $description = $request->input('description');
        $dateStart = $request->input('dateStart');
        $dateEnd = $request->input('dateEnd');

        /* @var $paginate LengthAwarePaginator */
        $paginate = Image::when($title, function (Builder $query) use ($title) {
            $query->where('title', 'like', "%{$title}%");
        })->when($description, function (Builder $query) use ($description) {
            $query->where('description', 'like', "%{$description}%");
        })->when($dateStart, function (Builder $query) use ($dateStart) {
            $query->where('created_at', '>=', $dateStart);
        })->when($dateEnd, function (Builder $query) use ($dateEnd) {
            $query->where('created_at', '<=', $dateEnd);
        })
            ->paginate();
        $roles = $paginate->getCollection();

        $roles->each(function (Image &$image) {
            //格式化数据

        });

        return $this->responseData($paginate);
    }

    public function show(Request $request, $id)
    {
        dd('查看文章');
    }

    public function create()
    {
        dd('新增文章');
    }

    public function update(Request $request, $id)
    {
        dd('修改文章');
    }

}