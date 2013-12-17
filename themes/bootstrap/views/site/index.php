<?php
/* @var $this SiteController */
$themePath = Yii::app()->theme->baseUrl;
$this->pageTitle = Yii::app()->name;

echo BSHtml::jumbotron('AT Dispatch', 'new dispatch!<p class="text-muted">This section probably won\'t be necessary...</p>')
?>

</div>
</div>
<style>
</style>
<div class="container-fluid " id="welcome">
    <div class="content">
        <!-- and so on -->
    </div>
</div>

<div class="container" >
    <div class="col-lg-4">
        <div class="bubble-icon fade-bg "><i class="fa fa-camera-retro fa-lg fade-bg fade-red fade-white-font"></i> </div>
        <h3>We did well this time</h3>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec et turpis ac nulla aliquet accumsan vitae et neque.
        </p>
    </div>

    <div class="col-lg-4">
        <div class="bubble-icon fade-bg "><i class="fade-white-font fa fa-android fa-lg "></i> </div>
        <h3>Testing</h3>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec et turpis ac nulla aliquet accumsan vitae et neque. Donec sodales sem vulputate sodales pretium. Nulla porta sem eget faucibus lacinia. 
        </p>
    </div>

    <div class="col-lg-4">
        <div class="bubble-icon fade-bg"><i class="fade-white-font fa fa-edit fa-lg"></i> </div>
        <h3>Web Development</h3>
        <p>
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec et turpis ac nulla aliquet accumsan vitae et neque. Donec sodales sem vulputate sodales pretium. Nulla porta sem eget faucibus lacinia. Praesent scelerisque gravida risus eget mollis.
        </p>
        <?php
        /*
          $items = array(
          array('image' => 'http://placehold.it/300x300&text=First+thumbnail', 'label' => 'First Thumbnail label', 'caption' => 'Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.'),
          array('image' => 'http://placehold.it/300x300&text=Second+thumbnail', 'label' => 'Second Thumbnail label', 'caption' => 'Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.'),
          array('image' => 'http://placehold.it/300x300&text=Third+thumbnail', 'label' => 'Third Thumbnail label', 'caption' => 'Cras justo odio, dapibus ac facilisis in, egestas eget quam. Donec id elit non mi porta gravida at eget metus. Nullam id dolor id nibh ultricies vehicula ut id elit.'),
          );
          echo BSHtml::carousel(
          $items
          ); */
        ?>
       
