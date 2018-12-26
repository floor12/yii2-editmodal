<?php

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

/* @var $model \yii\db\ActiveRecord */
$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

echo '<?php' ?>

    /* @var $this yii\web\View */
    /* @var $model <?= ltrim($generator->modelClass, '\\') ?> */
    /* @var $form yii\widgets\ActiveForm */

    use yii\helpers\Html;
    use yii\widgets\ActiveForm;

    $form = ActiveForm::begin([
    'id' => 'modal-form',
    'options' => ['class' => 'modaledit-form'],
    'enableClientValidation' => true
    ]);
    ?>

    <div class='modal-header'>
        <h2><?= "<?= \$model->isNewRecord ? 'Создание' : 'Редактирование' ?> " ?> объекта</h2>
    </div>

    <div class='modal-body'>

        <?php foreach ($generator->getColumnNames() as $attribute) {
            if (in_array($attribute, $safeAttributes)) {
                echo "    <?= " . $generator->generateActiveField($attribute) . " ?>\n\n";
            }
        } ?>
    </div>

    <div class='modal-footer'>
        <?= "<?= Html::a('Отмена', '', ['class' => 'btn btn-default modaledit-disable']) ?> " ?>

        <?= "<?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?> " ?>

    </div>

<?= "<?php ActiveForm::end(); ?>" ?>