<?php
/* @var $this SiteController */
?>
<?php
$this->pageTitle = Yii::app()->name;
echo  Yii::app()->name;
?>

<h1 id="page-title"><?php echo CHtml::encode(Yii::app()->name); ?>: User Page <i>(<?php echo Yii::app()->params->ip; ?>)</i></h1>
<div>
    <h3 id="statusUpdate">This shop is now</h3>
</div>
<div class="center" id="buttonControl">
    <input type="button" id="statusToggle" value="closed"/>
    <div>
        <dfn>click to change state</dfn>
    </div>
</div>

