<?php

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */


$urlParams = $generator->generateUrlParams();
$nameAttribute = $generator->getNameAttribute();

echo "<?php\n"; ?>


use floor12\editmodal\EditModalHelper;
use floor12\editmodal\IconHelper;
use floor12\editmodal\EditModalColumn;
use kartik\form\ActiveForm;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;


$this->title = 'Список';
$this->params['breadcrumbs'][] = $this->title;

echo Html::button(IconHelper::PLUS . ' Добавить объект', [
'onclick' => EditModalHelper::showForm(['form'], 0),
'class' => 'btn btn-primary btn-sm pull-right'
])

?>

<h1><?= '<?= $this->title ?>' ?></h1>

<?= "<?php \$form = ActiveForm::begin([
    'method' => 'GET',
    'options' => ['class' => 'autosubmit', 'data-container' => '#items'],
    'enableClientValidation' => false,
]); ?>" ?>

    <div class="filter-block">
        <div class="row">
            <div class="col-md-9">
                <?="<?= \$form->field(\$model,'filter')->label(false)->textInput(['placeholder'=>'Поиск','autofocus' => true])?>"?>
            </div>
            <div class="col-md-3">
                <?="<?= \$form->field(\$model,'status')->label(false)->dropDownList([],['prompt'=>'Все статусы'])?>"?>
            </div>
        </div>
    </div>

<?= "<?php\n";?>

ActiveForm::end();


Pjax::begin([
    'id' => 'items',
    'scrollTo' => true,
]);

echo GridView::widget([
    'dataProvider' => $model->dataProvider(),
    'layout' => '{items}{pager}{summary}',
    'tableOptions' => ['class' => 'table table-striped'],
    'columns' => [
<?php
$count = 0;
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        if (++$count < 6) {
            echo "            '" . $name . "',\n";
        } else {
            echo "            //'" . $name . "',\n";
        }
    }
} else {
    foreach ($tableSchema->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        if (++$count < 6) {
            echo "            '" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
        } else {
            echo "            //'" . $column->name . ($format === 'text' ? "" : ":" . $format) . "',\n";
        }
    }
}
?>
        [
        'class' => EditModalColumn::class,
        'contentOptions' => ['style' => 'min-width:100px;', 'class' => 'text-right'],
        ],
    ],
]);

Pjax::end();