<?php
/**
 * @var TransactionsController $this
 * @var ActiveForm $form
 * @var TransactionsSearch $model
 * @var CActiveDataProvider $dataProvider
 * @var Transactions[] $data
 * @var array $aggregatorsList
 */

use app\modules\backend\models\search\TransactionsSearch;

$title_ = 'Пополнения баланса';
$this->pageTitle = $title_;
$this->breadcrumbs = [$title_];
?>


<?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

<?php $form = $this->beginWidget('ActiveForm', [
    'id' => $this->getId() . '-form',
    'method' => 'GET',
    'action' => ['index'],
]) ?>

<table class="table">
    <colgroup>
        <col style="width: 5%;">
        <col>
        <col style="width: 15%;">
        <col style="width: 10%;">
        <col style="width: 12%;">
        <col style="width: 13%;">
        <col style="width: 15%;">
        <col style="width: 10%;">
    </colgroup>
    <thead>
    <tr>
        <th>ID</th>
        <th>Платежная система</th>
        <th>Юзер</th>
        <th>Сумма</th>
        <th>Стутус</th>
        <th>IP</th>
        <th>Дата создания</th>
        <th></th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td><?php echo $form->textField($model, 'id', ['class' => 'form-control input-sm']) ?></td>
        <td><?php echo $form->dropDownList($model, 'payment_system', $model->getAggregatorsList(), ['class' => 'form-control input-sm', 'empty' => '-- выбрать --']) ?></td>
        <td></td>
        <td><?php echo $form->textField($model, 'sum', ['class' => 'form-control input-sm']) ?></td>
        <td><?php echo $form->dropDownList($model, 'status', Transactions::getPayStatusList(), ['class' => 'form-control input-sm', 'empty' => '-- выбрать --']) ?></td>
        <td><?php echo $form->textField($model, 'user_ip', ['class' => 'form-control input-sm']) ?></td>
        <td></td>
        <td>
            <button type="submit" class="btn btn-primary glyphicon glyphicon-search" title="Искать"></button>
            <?php echo CHtml::link('', ['index'], ['class' => 'btn btn-default glyphicon glyphicon-ban-circle', 'title' => 'Сбросить']) ?>
        </td>
    </tr>
    <?php if ($data = $dataProvider->getData()) { ?>
        <?php foreach ($data as $row) { ?>
            <tr>
                <td><?php echo $row->id ?></td>
                <td><?php echo $row->getAggregatorType() ?></td>
                <td><?php echo(isset($row->user->login) ? CHtml::link($row->user->login, ['/backend/users/view', 'user_id' => $row->user->user_id]) : '*Unknown*') ?></td>
                <td><?php echo formatCurrency($row->sum, false) ?></td>
                <td>
                    <span class="label <?php echo($row->status == Transactions::STATUS_SUCCESS ? 'label-success' : 'label-default') ?>"><?php echo $row->getStatus() ?></span>
                </td>
                <td>
                    <?php if ($row->user_ip !== '127.0.0.1') {
                        echo CHtml::link($row->user_ip, getLocationLinkByIp($row->user_ip), ['title' => 'Информация о IP', 'rel' => 'tooltip', 'target' => '_blank']);
                    } else {
                        echo $row->user_ip;
                    } ?>
                <td><?php echo $row->getCreatedAt('Y-m-d H:i') ?></td>
                <td></td>
            </tr>
        <?php } ?>
    <?php } else { ?>
        <tr>
            <td colspan="8">Нет данных.</td>
        </tr>
    <?php } ?>
    </tbody>
</table>

<?php $this->endWidget() ?>

<?php $this->widget('CLinkPager', [
    'pages' => $dataProvider->getPagination(),
]) ?>
