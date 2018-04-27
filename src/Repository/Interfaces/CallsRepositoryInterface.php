<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 12/04/2018
 * Time: 12:20
 */

namespace App\Repository\Interfaces;

/**
 * Interface CallsRepositoryInterface
 * @package App\Repository\Interfaces
 */
interface CallsRepositoryInterface
{
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);
}