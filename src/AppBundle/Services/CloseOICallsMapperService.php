<?php
/**
 * Created by PhpStorm.
 * User: marc
 * Date: 12/04/2018
 * Time: 11:48
 */

namespace AppBundle\Services;

use AppBundle\Entity\Calls;
use AppBundle\Services\Interfaces\CallsServiceInterface;

class CloseOICallsMapperService implements CallsServiceInterface
{

    const DATEFORMAT = ['date_updated','date_created'];

    /** @var Calls */
    private $calls;

    public function setCalls(array $row, array $skipKeys = []){

        $this->calls = new Calls;

        $callFunctions = get_class_methods(Calls::class);
        $setFunctions = array_values(preg_grep("/^set/", $callFunctions));
        $dataKeys =  array_keys($row);

        if(!empty($skipKeys)) {
            $dataKeys = array_values(array_filter($dataKeys, function ($values) use ($skipKeys) {
                return !in_array($values, $skipKeys);
            }));
        }

        foreach($setFunctions as $key => $value){

            $presistData = $row[$dataKeys[$key]];

            if(in_array($dataKeys[$key], self::DATEFORMAT)) {
                /** @var \DateTime $presistData */
                $presistData = new \DateTime($row[$dataKeys[$key]]);
            }

            $this->calls->$value($presistData);
        }
        return $this;
    }

    public function getCalls()
    {
        return $this->calls;

    }
}