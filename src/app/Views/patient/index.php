
<?= $this->section('title') ?>
รายชื่อผู้ป่วยทั้งหมด
<?= $this->endSection() ?>

<?= $this->section('pageStyles') ?>
<!-- DataTables CSS -->
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4">จัดการข้อมูลผู้ป่วย</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('dashboard') ?>">แดชบอร์ด</a></li>
        <li class="breadcrumb-item active">รายชื่อผู้ป่วยทั้งหมด</li>
    </ol>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            รายชื่อผู้ป่วย
            <a href="<?= site_url('patients/create') ?>" class="btn btn-primary btn-sm float-end">
                <i class="fas fa-plus"></i> เพิ่มผู้ป่วยใหม่
            </a>
        </div>
        <div class="card-body">
            <table id="patientsTable" class="table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th>เลขบัตรประชาชน</th>
                        <th>ชื่อ-สกุล</th>
                        <th>ระดับความเสี่ยง</th>
                        <th>ที่อยู่</th>
                        <th>วันที่ลงทะเบียนx</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- ข้อมูลจะถูกโหลดผ่าน AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('pageScripts') ?>
<!-- DataTables JS -->
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#patientsTable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "<?= site_url('patients/ajax-list') ?>",
                "type": "POST",
                "data": {
                    '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
                }
            },
            "columns": [
                { "data": "id_card" },
                { 
                    "data": null,
                    "render": function ( data, type, row ) {
                        return row.firstname + ' ' + row.lastname;
                    }
                },
                { 
                    "data": "risk_level_name",
                    "render": function ( data, type, row ) {
                        return '<span class="badge" style="background-color:'+ (row.color_hex || '#6c757d') +';">'+ data +'</span>';
                    }
                },
                { 
                    "data": null,
                    "render": function ( data, type, row ) {
                        return (row.ampurname || '') + ', ' + (row.changwatname || '');
                    }
                },
                { 
                    "data": "created_at",
                    "render": function ( data, type, row ) {
                        let date = new Date(data);
                        return date.toLocaleDateString('th-TH', {
                            year: 'numeric', month: 'long', day: 'numeric'
                        });
                    }
                },
                { 
                    "data": "id",
                    "render": function ( data, type, row ) {
                        var showUrl = "<?= site_url('patients/show/') ?>" + data;
                        var editUrl = "<?= site_url('patients/edit/') ?>" + data;
                        var deleteUrl = "<?= site_url('patients/delete/') ?>" + data;
                        return `
                            <a href="${showUrl}" class="btn btn-info btn-sm" title="ดูรายละเอียด"><i class="fas fa-eye"></i></a>
                            <a href="${editUrl}" class="btn btn-warning btn-sm" title="แก้ไข"><i class="fas fa-edit"></i></a>
                            <a href="${deleteUrl}" class="btn btn-danger btn-sm" onclick="return confirm('คุณต้องการลบข้อมูลผู้ป่วยรายนี้ใช่หรือไม่?')" title="ลบ"><i class="fas fa-trash"></i></a>
                        `;
                    },
                    "orderable": false,
                    "searchable": false
                }
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.13.6/i18n/th.json"
            }
        });
    });
</script>
<?= $this->endSection() ?>