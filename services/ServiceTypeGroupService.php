<?php

namespace app\services;

use app\models\ServiceTypeGroup;

class ServiceTypeGroupService
{
    public function createServiceTypeGroup(int $id, string $name): ServiceTypeGroup {
        $serviceTypeGroup = new ServiceTypeGroup();
        $serviceTypeGroup->id = $id;
        $serviceTypeGroup->name = $name;
        return $serviceTypeGroup;
    }
}
