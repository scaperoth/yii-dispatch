<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />

        <?php
        $cs = Yii::app()->clientScript;
        $themePath = Yii::app()->theme->baseUrl;

        /**
         * StyleSHeets
         */
        $cs->registerCssFile($themePath . '/assets/css/bootstrap.min.css');
        $cs->registerCssFile($themePath . '/assets/css/bootstrap-theme.min.css');
        $cs->registerCssFile($themePath . '/assets/fractionslider/fractionslider.css');
        $cs->registerCssFile($themePath . '/assets/css/style.css');
        $cs->registerCssFile('//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css');
        $cs->registerCssFile('//fonts.googleapis.com/css?family=Kreon:400|Oswald:400');

        /**
         * JavaScripts
         */
        $cs->registerScriptFile($themePath . '/assets/js/jquery-migrate-1.2.1.min.js', CClientScript::POS_END);
        $cs->registerCoreScript('jquery', CClientScript::POS_BEGIN);
        $cs->registerCoreScript('jquery.ui', CClientScript::POS_BEGIN);

        $cs->registerScriptFile($themePath . '/assets/js/bootstrap.min.js', CClientScript::POS_END);
        $cs->registerScriptFile($themePath . '/assets/js/stellar/jquery.stellar.min.js', CClientScript::POS_BEGIN);
        $cs->registerScriptFile($themePath . '/assets/fractionslider/jquery.fractionslider.js', CClientScript::POS_END);
        $cs->registerScriptFile($themePath . '/assets/nicescroll/jquery.nicescroll.min.js', CClientScript::POS_END);
        $cs->registerScriptFile($themePath . '/assets/js/script.js', CClientScript::POS_END);
        $cs->registerScript('tooltip', "$('[data-toggle=\"tooltip\"]').tooltip();$('[data-toggle=\"popover\"]').tooltip()", CClientScript::POS_READY);
        ?>
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
            <script src="<?php
        echo $themePath . '/assets/js/html5shiv.js';
        ?>"></script>
            <script src="<?php
        echo $themePath . '/assets/js/respond.min.js';
        ?>"></script>
        
        <![endif]-->

    </head>

    <body >

        <?php
        $this->widget('bootstrap.widgets.BsNavbar', array(
            'collapse' => true,
            'brandLabel' => '<img src="' . $themePath . '/assets/img/at_logo.png" alt="Academic Technologies Logo"/>',
            'brandUrl' => Yii::app()->homeUrl,
            'items' => array(
                array(
                    'class' => 'bootstrap.widgets.BsNav',
                    'type' => 'navbar',
                    'activateParents' => true,
                    'htmlOptions' => array(
                        'pull' => BSHtml::PULL_RIGHT,
                    ),
                    'items' => array(
                        array(
                            'label' => 'Home',
                            'url' => array(
                                '/'
                            ),
                            'visible' => !Yii::app()->user->isGuest,
                            'icon' => 'home',
                        ),
                        array(
                            'label' => 'Admin',
                            'visible' => (Yii::app()->user->checkAccess('admin') ? true : false),
                            'url' => array(
                                '/admin/'
                            ),
                            'icon' => 'dashboard',
                            'items' => array(
                                array(
                                    'label' => 'Tasks',
                                    'url' => array(
                                        '/admin/tasks'
                                    )
                                ),
                                array(
                                    'label' => 'Control Panel',
                                    'url' => array(
                                        '/admin/control'
                                    )
                                ),
                                array(
                                    'label' => 'Archive',
                                    'url' => array(
                                        '/admin/archive'
                                    )
                                ),
                            ),
                        ),
                        array(
                            'label' => 'Login',
                            'url' => array(
                                '/login/'
                            ),
                            'visible' => Yii::app()->user->isGuest,
                            'icon' => 'power-off',
                        ),
                        array(
                            'label' => 'Logout (' . Yii::app()->user->name . ')',
                            'url' => array(
                                '/login/logout'
                            ),
                            'visible' => !Yii::app()->user->isGuest,
                            'icon' => 'power-off',
                        )
                    ),
                )
            )
        ));
        ?>
        <div class="container-fluid bs-docs-container"  id="page">
            <div class="col-lg-offset-2 col-lg-8">
                <?php if (isset($this->breadcrumbs)): ?>
                    <?php
                    $this->widget('bootstrap.widgets.BsBreadcrumb', array(
                        'links' => $this->breadcrumbs,
                    ));
                    ?><!-- breadcrumbs -->
                <?php endif ?>


                <?php
                echo BSHtml::buttonGroup(array(
                    array(
                        'label' => 'On Assignment'
                    ),
                    array(
                        'label' => 'Do Not Disturb'
                    ),
                    array(
                        'own' => BSHtml::ajaxButton('Available', Yii::app()->createAbsoluteUrl('site/ajaxTest'), array(
                            'cache' => true,
                            'data' => array(
                                'message' => 'clicked the Ajaxbutton from the buttongroup'
                            ),
                            'type' => 'POST',
                            'success' => 'js:function(data){
                        console.log(data);
                        $(".modal-body").html(data);
                        $("#demo_modal").modal("show");
                    }'
                                ), array(
                            'icon' => 'bell'
                        ))
                    )
                        ), array(
                    'color' => BSHtml::BUTTON_COLOR_WARNING,
                    'type' => BSHtml::BUTTON_TYPE_LINK
                ));
                ?>
            </div>
            <?php echo $content; ?>

            <div class="clearfix"></div>

            <div id="footer">

            </div> <!--footer -->

        </div><!-- page -->

    </body>
    <script>
        $(document).ready(
                function() {
                    $("html").niceScroll({
                        cursorwidth: '8px',
                        cursorborder: 'none',
                        overflow: 'hidden',
                        cursoropacitymin: 1,
                        scrollspeed: 70,
                    });
                    $('.slider').fractionSlider({
                        'fullWidth': true,
                        'controls': true,
                        'responsive': true,
                        'dimensions': '1700, 300',
                        'slideTransitionSpeed': 0,
                        'increase': true,
                    });
                    $.stellar({
                        horizontalScrolling: false,
                        verticalScrolling: true,
                        responsive: true,
                        positionProperty: 'position',
                    });


                }

        );
    </script>
</html>
