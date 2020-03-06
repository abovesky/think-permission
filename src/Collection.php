<?php

namespace think\permission;

use think\helper\Arr;
use think\Collection as BaseCollection;

class Collection extends BaseCollection
{
    /**
     * 将多维集合转为一维
     * @param $depth
     * @return static
     */
    public function flatten($depth = INF)
    {
        return new static(Arr::flatten($this->items, $depth));
    }

}