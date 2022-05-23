@extends('admin.layout.admin_layout')
@section('title', 'Dashboard')

@section('content')
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1> 
    </div>

    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Siswa</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $siswa }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-address-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                KD</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $kd; }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div> 

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Materi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $materi; }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-book-open fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>

@endsection

@section('script')
<script>
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": true,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(function() {
        // tambah kd
        $('#add-kd-form').on('submit', function(e) {
            e.preventDefault();
            // alert('hello form');
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'JSON',
                contentType: false,
                beforeSend: function() {
                    $(form).find('span.error-text').text('');
                },
                success: function(data) {
                    if (data.code == 0) {
                        $.each(data.error, function(prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                        });
                    } else {
                        $(form)[0].reset();
                        // alert(data.msg);
                        $('#exampleModal').modal('hide');
                        $('#kd-table').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                    }
                }
            });
        })

        // get
        $('#kd-table').DataTable({
            processing: true,
            info: true,
            ajax: "{{ route('admin.get-kd') }}",
            "pageLength": 5,
            "aLengthMenu": [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "All"]
            ],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                },
                {
                    data: 'kd',
                    name: 'kd'
                },
                {
                    data: 'judul_kd',
                    name: 'judul_kd'
                },
                {
                    data: 'keterangan',
                    name: 'keterangan'
                },
                {
                    data: 'actions',
                    name: 'actions'
                }
            ]
        });

        // get by id
        $(document).on('click', '#editKd', function() {
            var kd_id = $(this).data('id');
            // alert(kd_id);
            $('.editKd').find("form")[0].reset();
            $('.editKd').find('span.error-text').text('');
            $.post('<?= route('admin.detail-kd') ?>', {
                kd_id: kd_id
            }, function(data) {
                // alert(data.details.judul_kd);
                $('.editKd').find('input[name="id"]').val(data.details.id);
                $('.editKd').find('input[name="kd"]').val(data.details.kd);
                $('.editKd').find('input[name="judul_kd"]').val(data.details.judul_kd);
                $('.editKd').find('input[name="keterangan"]').val(data.details.keterangan);
                $(
                    '.editKd').modal('show');
            }, 'json');
        });
        // update
        $('#update-kd-form').on('submit', function(e) {
            e.preventDefault();
            var form = this;
            $.ajax({
                url: $(form).attr('action'),
                method: $(form).attr('method'),
                data: new FormData(form),
                processData: false,
                dataType: 'json',
                contentType: false,
                beforeSend: function() {
                    $(form).find('span.error-text').text('');
                },
                success: function(data) {
                    if (data.code == 0) {
                        $.each(data.error, function(prefix, val) {
                            $(form).find('span.' + prefix + '_error').text(val[0]);
                        });
                    } else {
                        $('#kd-table').DataTable().ajax.reload(null, false);
                        $('.editKd').modal('hide');
                        $('.editKd').find('form')[0].reset();
                        toastr.success(data.msg);
                    }
                }
            });
        });

        // delete kd
        $(document).on('click', '#deleteKd', function() {
            var kd_id = $(this).data('id');
            // alert(kd_id)
            var url = '<?= route('admin.delete-kd') ?>';

            swal.fire({
                title: 'Data akan dihapus?',
                html: 'data akan kd <b>dihapus</b>',
                showCancelButton: true,
                showCloseButton: true,
                cancelButtonText: 'Cancel',
                confirmButtonText: 'Delete',
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                width: '300px',
                allowOutsideClick: false,
            }).then(function(result) {
                if (result.value) {
                    $.post(url, {
                        kd_id: kd_id
                    }, function(data) {
                        if (data.code == 1) {
                            $('#kd-table').DataTable().ajax.reload(null, false);
                            toastr.success(data.msg);
                        } else {
                            toastr.error(data.msg);
                        }
                    }, 'json');
                }
            });
        })
    });
</script>

@endsection
