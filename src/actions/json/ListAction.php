<?php
/**
 * Created by PhpStorm.
 * User: Nadzif Glovory
 * Date: 11/14/2019
 * Time: 12:54 AM
 */

namespace nadzif\base\actions\json;


use yii\base\Action;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

class ListAction extends Action
{
    public $queryParamKey = 'search';
    public $limit         = 10;

    public $activeRecordClass;
    public $idAttribute;
    public $textAttribute;

    public $condition = [];

    public function init()
    {
        parent::init();
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    }

    public function run()
    {
        $q = \Yii::$app->request->get($this->queryParamKey);

        $out = ['results' => ['id' => '', 'text' => '']];

        /** @var ActiveRecord $activeRecord */
        $activeRecord  = new $this->activeRecordClass;
        $textAttribute = $this->textAttribute;

        if (!is_null($q)) {
            /** @var ActiveQuery $query */
            $query = $activeRecord::find()
                ->select([$this->idAttribute, $textAttribute . ' AS text'])
                ->where(['like', $textAttribute, $q])
                ->asArray()
                ->limit($this->limit);

            if ($this->condition) {
                $query->andWhere($this->condition);
            }

            $out['results'] = $query->all();
        }

        return $out;
    }
}