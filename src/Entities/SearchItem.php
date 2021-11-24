<?php

namespace OSS\SDK\Entities;


class SearchItem
{
    protected $item = [];

    public function __construct($id)
    {
        $this->item['id'] = $id;
    }

    public function setTitle($title)
    {
        $this->item['title'] = $title;
    }

    public function setDescription($description)
    {
        $this->item['description'] = $description;
    }

    public function setImage($image)
    {
        $this->item['image'] = $image;
    }

    public function setUrl($url)
    {
        $this->item['url'] = $url;
    }

    public function setData($data)
    {
        $this->item['data'] = $data;
    }

    public function toArray()
    {
      return array_merge($this->item, [
        'data' => json_encode($this->item['data'] ?? [])
      ]);
    }
}
