<?php
namespace frontend\widgets\content;

use yii\base\Widget;

class ContentWidget extends Widget
{
    public $id;
    public $title;
    public $type;

    public function init()
    {
        parent::init();
        $this->id = $this->id == null ? 0 : $this->id;
        $this->title = $this->title == null ? 'box6' : $this->title;
    }

    public function run()
    {
        $page = $this->type;
        if ($this->type == 'image') {
            $page = 'content';
        }
        return $this->render($page, ['id' => $this->id, 'title' => $this->title]);
    }
}