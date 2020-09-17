<?php

namespace App\Http\Transformers;


abstract class Transformer
{

    public function transformCollection(array $items)
    {
        $itemsArray = [];

        foreach ($items as $item) {

            if ($this->transform($item)) {
                $item_transofrmer = $this->transform($item);
                if(isset($item_transofrmer)) {
                    $itemsArray[] = $item_transofrmer;
                }
            }
        }
        return $itemsArray;
    }

    public abstract function transform($item);


}
