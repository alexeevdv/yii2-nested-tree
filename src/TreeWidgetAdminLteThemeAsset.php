<?php

namespace alexeevdv\nested\tree;

use yii\web\AssetBundle;

/**
 * Class TreeWidgetAdminLteThemeAsset
 * @package alexeevdv\nested\tree
 */
class TreeWidgetAdminLteThemeAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@vendor/alexeevdv/yii2-nested-tree/src/themes';

    /**
     * @inheritdoc
     */
    public $css = [
        'admin-lte.css',
    ];
}
