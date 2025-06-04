<?php

namespace app\admin\model;

use think\Model;

/**
 * Bill
 */
class Bill extends Model
{
    // 表名
    protected $name = 'bill';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = true;


    public function getAmountAttr($value): ?float
    {
        return is_null($value) ? null : (float)$value;
    }
}