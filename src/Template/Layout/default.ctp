<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
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
            'dist/app'
        ])
    ?>
    <?=
        $this->Html->script([
        'jquery/dist/jquery.min',
        'dist/constant'
        ])
    ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <!-- Begin Header -->
        <?= $this->element('header') ?>
        <!-- End Sidebar -->

        <!-- Begin Sidebar -->
        <?= $this->element('sidebar') ?>
        <!-- End Sidebar -->

        <!-- Begin Content -->
        <div class="content-wrapper">
            <?php if($this->fetch('content_header')) { ?>
                <?= $this->fetch('content_header') ?>
            <?php } ?>
            <section class="content">
                <?= $this->Flash->render() ?>
                <?= $this->fetch('content') ?>
            </section>
        </div>
        <!-- End Content -->

        <!-- Begin Footer -->
        <?= $this->element('footer') ?>
        <!-- End Footer -->
    </div>

    <?=
        $this->Html->script([
            'jquery-ui/jquery-ui.min',
            'bootstrap/dist/js/bootstrap.min',
            'raphael/raphael.min',
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
            'dist/demo',
            'dist/app'
        ])
    ?>

    <script>
        $.widget.bridge('uibutton', $.ui.button);
    </script>
</body>
</html>
