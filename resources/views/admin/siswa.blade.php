@extends('admin.layout.admin_layout')
{{-- @extends('admin.layout.style') --}}
@section('title', 'Siswa')
@section('content')

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#siswaModal">
            Tambah Siswa
        </button>

        <!-- Modal -->
        <div class="modal fade" id="siswaModal" tabindex="-1" aria-labelledby="siswaModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="siswaModalLabel">Tambah Siswa</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.add-siswa') }}" method="post" id="add-siswa-form">
                            @csrf
                            <div class="form-group">
                                <label for="">Nama Siswa</label>
                                <input type="text" class="form-control" name="name" placeholder="Nama Siswa" required>
                                <span class="text-danger error-text name_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="">NIS</label>
                                <input type="text" class="form-control" name="nis" placeholder="NIS" required>
                                <span class="text-danger error-text nis_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="">Kelas</label>
                                <input type="text" class="form-control" name="kelas" placeholder="Kelas" value="XI SMK " required>
                                <span class="text-danger error-text kelas_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="">Password</label>
                                <input type="text" class="form-control" name="password" placeholder="Password minimal 8 karakter" required>
                                <span class="text-danger error-text password_error"></span>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-success">SAVE</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="siswa-table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama </th>
                        <th>NIS</th>
                        <th>Kelas</th>
                        <th>Password</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

@include('admin.layout.edit_siswa_modal')
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

        // tambah siswa
        $('#add-siswa-form').on('submit', function(e) {
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
                        $('#siswaModal').modal('hide');
                        $('#siswa-table').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                    }
                }
            });
        });

        // get siswa
        $('#siswa-table').DataTable({
            processing: true,
            info: true,
            ajax: "{{ route('admin.get-siswa') }}",
            "pageLength": 5,
            "aLengthMenu": [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "All"]
            ],
            columns: [{
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'nis',
                    name: 'nis'
                },
                {
                    data: 'kelas',
                    name: 'kelas'
                },
                {
                    data: 'password_view',
                    name: 'password_view'
                },
                {
                    data: 'actions',
                    name: 'actions'
                }

            ]
        });

        // get by id
        $(document).on('click', '#editSiswa', function() {
            var siswa_id = $(this).data('id');
            // alert(siswa_id);
            $('.editSiswa').find("form")[0].reset();
            $('.editSiswa').find('span.error-text').text('');
            $.post('<?= route('admin.detail-siswa') ?>', {
                siswa_id: siswa_id
            }, function(data) {
                $('.editSiswa').find('input[name="id"]').val(data.details.id);
                $('.editSiswa').find('input[name="name"]').val(data.details.name);
                $('.editSiswa').find('input[name="nis"]').val(data.details.nis);
                $('.editSiswa').find('input[name="kelas"]').val(data.details.kelas);
                $('.editSiswa').find('input[name="password_view"]').val(data.details.password_view);
                $('.editSiswa').modal('show');
            }, 'json')
        });

        // update siswa
        $('#update-siswa-form').on('submit', function(e) {
            e.preventDefault();
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
                        $('#siswa-table').DataTable().ajax.reload(null, false);
                        $('.editSiswa').modal('hide');
                        $('.editSiswa').find(form)[0].reset();
                        toastr.success(data.msg);
                    }
                }
            });
        });

        // ! DELETE SISWA
        $(document).on('click', '#deleteSiswa', function() {
            var siswa_id = $(this).data('id');
            var url = '<?= route('admin.delete-siswa') ?>';

            swal.fire({
                title: 'Data akan dihapus?',
                html: '<p>Data yang dihapus tidak dapat dikembalikan!</p>',
                type: 'warning',
                showCloseButton: true,
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                allowOutsideClick: false,
            }).then(function(result) {
                if (result.value) {
                    $.post(url, {
                        siswa_id: siswa_id
                    }, function(data) {
                        if (data.code == 1) {
                            $('#siswa-table').DataTable().ajax.reload(null, false);
                            toastr.success(data.msg);
                        } else {
                            toastr.error(data.msg);
                        }
                    }, 'json');
                }
            })
        });
    });
</script>

@endsection