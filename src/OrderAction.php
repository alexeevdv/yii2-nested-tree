<?php

namespace alexeevdv\nested\tree;

use creocoder\nestedsets\NestedSetsBehavior;
use Yii;
use yii\base\Action;
use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * Class SortAction
 * @package alexeevdv\nested\tree
 */
class OrderAction extends Action
{
    /**
     * @var string
     */
    public $modelClass;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->modelClass === null) {
            throw new InvalidConfigException('`modelClass` is required.');
        }

        // todo ensure behavior
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $modelClass = $this->modelClass;

        $post = Yii::$app->request->post();
        $id = ArrayHelper::getValue($post, 'id');
        $previousId = ArrayHelper::getValue($post, 'prev');
        $nextId = ArrayHelper::getValue($post, 'next');
        $parentId = ArrayHelper::getValue($post, 'parent');

        $item = $modelClass::findOne($id);
        $behavior = $this->getNestedSetsBehavior($item);

        if ($nextId) {
            $oldNextId = $modelClass::find()
                ->andWhere([
                    $behavior->treeAttribute => $item->{$behavior->treeAttribute},
                    $behavior->depthAttribute => $item->{$behavior->depthAttribute},
                ])
                ->andWhere(['>', $behavior->leftAttribute, $item->{$behavior->leftAttribute}])
                ->orderBy([$behavior->leftAttribute => SORT_ASC])
                ->select('id')
                ->scalar();
            if ($oldNextId != $nextId) {
                $newNext = $modelClass::findOne($nextId);
                $item->insertBefore($newNext);
                return;
            }
        }

        if ($previousId) {
            $oldPrevId = $modelClass::find()
                ->andWhere([
                    $behavior->treeAttribute => $item->{$behavior->treeAttribute},
                    $behavior->depthAttribute => $item->{$behavior->depthAttribute},
                ])
                ->andWhere(['<', $behavior->rightAttribute, $item->{$behavior->rightAttribute}])
                ->orderBy([$behavior->rightAttribute => SORT_DESC])
                ->select('id')
                ->scalar();
            if ($oldPrevId  != $previousId) {
                $newPrev = $modelClass::findOne($previousId);
                $item->insertAfter($newPrev);
                return;
            }
        }

        $item->appendTo($modelClass::findOne($parentId));
    }

    /**
     * @param ActiveRecord $model
     * @return NestedSetsBehavior
     */
    protected function getNestedSetsBehavior($model)
    {
        foreach ($model->getBehaviors() as $behavior) {
            if ($behavior instanceof NestedSetsBehavior) {
                return $behavior;
            }
        }
        return null;
    }
}
