<?php

namespace App\Observers\Admin\Setting;

use App\Models\Admin\Setting\Link;

class LinkObserver
{
    public function saved(Link $link)
    {
        // 在保存资源链接时清空 links 相关的缓存
        \Cache::forget($link->cacheKey);
    }

}
