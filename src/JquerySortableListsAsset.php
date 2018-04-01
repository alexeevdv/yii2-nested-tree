<?php

namespace alexeevdv\nested\tree;

use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * Class JquerySortableListsAsset
 * @package alexeevdv\nested\tree
 */
class JquerySortableListsAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@bower/jquery-sortable-lists';

    /**
     * @inheritdoc
     */
    public $js = [
        'jquery-sortable-lists-mobile.js',
    ];

    /**
     * @inheritdoc
     */
    public $css = [
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        JqueryAsset::class,
    ];
}
