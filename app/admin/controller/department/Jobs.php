<?php

namespace app\admin\controller\department;

use app\common\controller\Backend;

/**
 * 岗位
 *
 */
class Jobs extends Backend
{
    /**
     * Jobs模型对象
     * @var object
     * @phpstan-var \app\admin\model\department\Jobs
     */
    protected object $model;

    protected string|array $quickSearchField = ['name', 'code'];

    protected string|array $defaultSortField = 'weigh,desc';

    protected string|array $preExcludeFields = ['createtime', 'updatetime'];

    public function initialize(): void
    {
        parent::initialize();
        $this->model = new \app\admin\model\department\Jobs;
    }

}