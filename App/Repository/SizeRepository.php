<?php 

namespace App\Repository;

use App\Model\Size;
use Core\Repository\Repository;

class SizeRepository extends Repository
{
    public function getTableName(): string
    {
        return 'size';
    }

    public function getAllSize()
    {
        return $this->readAll(Size::class);
    }
}