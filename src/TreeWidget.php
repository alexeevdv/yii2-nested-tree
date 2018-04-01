<?php

namespace alexeevdv\nested\tree;

use creocoder\nestedsets\NestedSetsBehavior;
use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\db\ActiveRecord;
use yii\helpers\Url;

/**
 * Class MenuTreeWidget
 * @package backend\widgets
 */
class TreeWidget extends Widget
{
    /**
     * @var ActiveRecord
     */
    public $root;

    /**
     * @var string|array
     */
    public $orderAction = ['order'];

    /**
     * @var string
     */
    public $theme = TreeWidgetAdminLteThemeAsset::class;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->root === null) {
            throw new InvalidConfigException('`root` attribute is required.');
        }

        if ($this->getNestedSetsBehavior($this->root) === null) {
            throw new InvalidConfigException('`root` model should have NestedSetsBehavior');
        }

        // TODO ensure that root element has zero depth
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->getView()->registerAssetBundle(TreeWidgetAsset::class);
        $this->getView()->registerAssetBundle($this->theme);

        $widgetId = $this->getId();

        ?>
        <ul id="<?= $widgetId ?>" class="treeWidgetContainer">
            <?php foreach ($this->root->children(1)->all() as $child) : ?>
                <?= $this->renderItem($child) ?>
            <?php endforeach; ?>
        </ul>
        <?php

        $orderAction = Url::to($this->orderAction);
        $this->getView()->registerJs("$('#$widgetId').treeWidget({orderAction: $orderAction});");
    }

    /**
     * @param ActiveRecord $item
     */
    protected function renderItem($item)
    {
        ?>
        <li class="sortableListsOpen" data-id="<?= $item->primaryKey ?>" class="treeWidgetElement">
            <div><?= $item->title ?> <?= $item->primaryKey?></div>
            <?php
            $children = $item->children(1)->all();
            if ($children) {
                ?>
                <ul>
                    <?php
                    foreach ($children as $child) {
                        $this->renderItem($child);
                    }
                    ?>
                </ul>
                <?php
            }
            ?>
        </li>
        <?php
    }

    /**
     * @param ActiveRecord $item
     * @return ActiveRecord[]
     */
    protected function getChildren($item)
    {
        $modelClass = get_class($item);
        $behavior = $this->getNestedSetsBehavior($item);

        return $modelClass::find()
            ->andWhere([
                $behavior->treeAttribute => $item->{$behavior->treeAttribute},
                $behavior->depthAttribute => $item->{$behavior->depthAttribute} + 1,
            ])
            ->andWhere(['>', $behavior->leftAttribute, $item->{$behavior->leftAttribute}])
            ->andWhere(['<', $behavior->rightAttribute, $item->{$behavior->rightAttribute}])
            ->orderBy([$behavior->leftAttribute => SORT_ASC])
            ->all()
        ;
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
