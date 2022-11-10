<?php declare(strict_types=1);

namespace App\Transformer;

class Transformer
{
    public function transformCollection($objects) : array
    {
        $data = [];
        foreach ($objects as $object) {
            $data[] = $object->toArray();
        }

        return $data;
    }

    public function transformPagination($paginationObject)
    {
        return [
            'currentPageNumber' => $paginationObject->getCurrentPageNumber(),
            'totalCount' => $paginationObject->getTotalItemCount(),
            'items' => $this->transformCollection($paginationObject->getItems())

        ];
    }
}
