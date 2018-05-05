<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

$cakeDescription = 'CakePHP: the rapid development php framework';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?=
    $this->Html->css([
        '/js/bootstrap/dist/css/bootstrap.min',
        '/js/font-awesome/css/font-awesome.min',
        '/js/Ionicons/css/ionicons.min',
        'dist/AdminLTE.min',
        'dist/skins/_all-skins.min',
        '/js/morris.js/morris',
        '/js/jvectormap/jquery-jvectormap',
        '/js/bootstrap-datepicker/dist/css/bootstrap-datepicker.min',
        '/js/bootstrap-daterangepicker/daterangepicker',
        '/js/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min',
        '/js/datatables.net-bs/css/dataTables.bootstrap.min',
        'dist/app',
    ])
    ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue layout-top-nav">
<div class="wrapper" id="auth-wrapper">
    <!-- Begin Header -->
    <header class="main-header">
        <nav class="navbar navbar-static-top">
            <div class="container">
                <div class="row">
                    <div class="col-sm-8 col-sm-offset-2">
                        <div class="navbar-header">
                            <h1>ゴルフシステム</h1>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.container-fluid -->
        </nav>
    </header>
    <!-- End Sidebar -->

    <!-- Begin Content -->
    <div class="content-wrapper">
        <section class="content">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </section>
    </div>
    <!-- End Content -->

    <!-- Begin Footer -->
    <footer class="main-footer">
        <div class="pull-right hidden-xs">
            <b>Version</b> 2.4.0
        </div>
        <strong>Copyright &copy; 2014-2017 <?= $this->Html->link(__('GMO-Z.com RUNSYSTEM JSC'), 'https://runsystem.net') ?>.</strong> All rights reserved.
    </footer>
    <!-- End Footer -->
</div>

<?=
$this->Html->script([
    'jquery/dist/jquery.min',
    'jquery-ui/jquery-ui.min',
    'bootstrap/dist/js/bootstrap.min',
    'raphael/raphael.min',
    'morris.js/morris.min',
    'jquery-sparkline/dist/jquery.sparkline.min',
    'plugins/jvectormap/jquery-jvectormap-1.2.2.min',
    'plugins/jvectormap/jquery-jvectormap-world-mill-en',
    'jquery-knob/dist/jquery.knob.min',
    'moment/min/moment.min',
    'bootstrap-daterangepicker/daterangepicker',
    'bootstrap-datepicker/dist/js/bootstrap-datepicker.min',
    'plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min',
    'jquery-slimscroll/jquery.slimscroll.min',
    'fastclick/lib/fastclick',
    'dist/adminlte.min',
    'dist/pages/dashboard',
    'dist/demo',
])
?>

<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
</body>
</html>
