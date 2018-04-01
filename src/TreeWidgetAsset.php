<?php

namespace alexeevdv\nested\tree;

use yii\web\AssetBundle;

/**
 * Class TreeWidgetAsset
 * @package alexeevdv\nested\tree
 */
class TreeWidgetAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/alexeevdv/yii2-nested-tree/src/assets';

    /**
     * @inheritdoc
     */
    public $js = [
        'tree-widget.js',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        JquerySortableListsAsset::class,
    ];
}
