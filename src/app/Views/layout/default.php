<?php
// --------------------------------------------------------------------
// File: app/Views/layout/default.php (ไฟล์ที่แก้ไข)
// --------------------------------------------------------------------
// นี่คือไฟล์ Template หลักที่ถูกแก้ไขให้นำ Header และ Sidebar
// จากไฟล์อื่นเข้ามาแสดงผล และแก้ไขวิธีการเรียกไฟล์ CSS/JS
// --------------------------------------------------------------------
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="ระบบติดตามผู้ป่วยยาเสพติด" />
    <meta name="author" content="Your Name" />
    <title><?= $this->renderSection('title', 'SMIV CARE') ?></title>
    <link href="<?= base_url("vendor/fontawesome-free/css/all.min.css") ?> rel=" stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url('css/sb-admin-2.css') ?>" rel="stylesheet" />

    <script src="<?= base_url('vendor/jquery/jquery.min.js') ?>"></script>
    <script src="<?= base_url('vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?= base_url('vendor/jquery-easing/jquery.easing.min.js') ?>"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?= base_url('js/sb-admin-2.min.js') ?>"></script>

    <!-- Page level plugins -->
    <script src="<?= base_url('vendor/chart.js/Chart.min.js') ?>"></script>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <script src="<?= base_url('js/sb-admin-2.js') ?>" crossorigin="anonymous"></script>

    <!-- สามารถเพิ่ม CSS เฉพาะหน้าได้จากตรงนี้ -->
    <?= $this->renderSection('pageStyles') ?>
</head>

<body class="sb-nav-fixed">

    <!-- ===== Top Navbar (Header) ถูกดึงมาจากไฟล์อื่น ===== -->


    <!-- ============================================================== -->
    <!-- Layout Sidenav                                                 -->
    <!-- ============================================================== -->
    <div id="layoutSidenav">

        <!-- ===== Sidebar ถูกดึงมาจากไฟล์อื่น ===== -->
        <?= $this->include('layout/partials/sidebar') ?>

        <!-- ============================================================== -->
        <!-- Page Content                                                   -->
        <!-- ============================================================== -->
        <div id="layoutSidenav_content">
            <main>
                <!-- ส่วนนี้คือที่ที่เนื้อหาของแต่ละหน้าจะถูกแสดงผล -->
                <?= $this->renderSection('content') ?>
            </main>

            <!-- ============================================================== -->
            <!-- Footer                                                         -->
            <!-- ============================================================== -->
            <footer class="py-4 bg-light mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; mkho.moph.go.th 2024</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

    <!-- JS หลักของ Template - แก้ไข path ให้ถูกต้อง -->
    <script src="/js/scripts.js"></script>

    <!-- สามารถเพิ่ม JS เฉพาะหน้าได้จากตรงนี้ -->
    <?= $this->renderSection('pageScripts') ?>


</body>

</html>