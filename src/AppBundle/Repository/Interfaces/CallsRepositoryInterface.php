<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 12/04/2018
 * Time: 12:20
 */

namespace AppBundle\Repository\Interfaces;


use Doctrine\Common\Collections\Collection;

/**
 * Interface CallsRepositoryInterface
 * @package AppBundle\Repository\Interfaces
 */
interface CallsRepositoryInterface
{
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null);
}