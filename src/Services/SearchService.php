<?php

namespace OSS\SDK\Services;

use Exception;
use OSS\SDK\Entities\SearchItem;
use OSS\SDK\Libs\Client;

class SearchService
{

    protected $batchSize = 100;
    protected $saveItems = [];
    protected $deleteItems = [];
    protected $saveCount = 0;
    protected $deleteCount = 0;

    protected $commitItems = [
      'save' => [],
      'delete' => [],
    ];
    protected $_deleteItems = [];

    public function _transform($op)
    {
      if ($op === 'save' && count($this->saveItems)) {
        $this->commitItems['save'][] = $this->saveItems;
        $this->saveItems = [];
      } else if ($op === 'delete' && count($this->deleteItems)) {
        $this->commitItems['delete'][] = $this->deleteItems;
        $this->deleteItems = [];
      }
    }

    public function save(SearchItem $item)
    {
        $this->saveItems[] = $item->toArray();
        if (count($this->saveItems) > $this->batchSize) {
            $this->commit();
        }
    }

    public function delete(SearchItem $item)
    {
        $this->deleteItems[] = $item->toArray();
        if (count($this->deleteItems) > $this->batchSize) {
            $this->commit();
        }
    }

    public function commit()
    {
        $this->_transform('save');
        $this->_transform('delete');
        $client = app(Client::class);
        try {
            foreach ($this->commitItems['save'] as $items) {
               $this->saveCount += $client->post('app/search/save', ['items' => json_encode($items)]);
            }
            foreach ($this->commitItems['delete'] as $items) {
                $this->deleteCount += $client->post('app/search/delete', ['items' => json_encode($items)]);
            }
        } catch (Exception $e) {
            logger($e);
        }
        // 清除内存
        $this->commitItems = [
          'save' => [],
          'delete' => [],
        ];
        return [
          'saveCount' => $this->saveCount,
          'deleteCount' => $this->deleteCount,
        ];
    }

}
