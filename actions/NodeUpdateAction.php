<?php

/*
 * @author Maciej "Gilek" Kłak
 * @copyright Copyright &copy; 2014 Maciej "Gilek" Kłak
 * @version 1.0.0-alpha
 * @package yii2-gtreetable
 */

namespace gilek\gtreetable\actions;

use Yii;
use yii\web\HttpException;
use yii\db\Exception;
use yii\helpers\Html;
use yii\helpers\Json;

class NodeUpdateAction extends BaseAction {

    public function run($id) {
        $model = $this->getNodeById($id);
        $model->scenario = 'update';
        $model->load(Yii::$app->request->post(), '');

        if (!$model->validate()) {
            throw new HttpException(500, current(current($model->getErrors())));
        }

        try {
            if ($model->saveNode(false) === false) {
                throw new Exception(Yii::t('gtreetable', 'Update operation `{name}` failed!', ['{name}' => Html::encode((string) $model)]));
            }

            echo Json::encode([
                'id' => $model->getPrimaryKey(),
                'name' => $model->name,
                'level' => $model->level,
                'type' => $model->type
            ]);
        } catch (\Exception $e) {
            throw new HttpException(500, $e->getMessage());
        }
    }

}

?>