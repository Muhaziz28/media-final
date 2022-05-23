@extends('admin.layout.admin_layout')
{{-- @extends('admin.layout.style') --}}
@section('title', 'Scor')
@section('content')

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">

            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                Import Nilai
            </button>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Import Nilai</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('admin.import') }}" method="post" id="import-excel" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="">Excel</label>
                                    <input type="file" class="form-control" name="file" required>
                                    <span class="text-danger error-text kd_error"></span>
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
                <table class="table table-bordered" id="nilai-table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>KD</th>
                            <th>Scor</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    @include('admin.layout.edit_nilai')
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

        // get
        $('#nilai-table').DataTable({
            processing: true,
            info: true,
            ajax: "{{ route('admin.get-nilai') }}",
            "pageLength": 5,
            "aLengthMenu": [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "All"]
            ],
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                },
                {
                    data: 'nis',
                    name: 'nis'
                },
                {
                    data: 'nama',
                    name: 'nama'
                },
                {
                    data: 'kelas',
                    name: 'kelas'
                },
                {
                    data: 'kd',
                    name: 'kd'
                },
                {
                    data: 'scor',
                    name: 'scor'
                },
                {
                    data: 'actions',
                    name: 'actions'
                }
            ]
        });

        // get by id
        $(document).on('click', '#editNilai', function() {
            var nilai_id = $(this).data('id');
            // alert(nilai_id);
            $('.editNilai').find("form")[0].reset();
            $('.editNilai').find('span.error-text').text('');
            $.post('<?= route('admin.detail-nilai') ?>', {
                nilai_id: nilai_id
            }, function(data) {
                // alert(data.details.judul_kd);
                $('.editNilai').find('input[name="id"]').val(data.details.id);
                $('.editNilai').find('input[name="nis"]').val(data.details.nis);
                $('.editNilai').find('input[name="nama"]').val(data.details.nama);
                $('.editNilai').find('input[name="kelas"]').val(data.details.kelas);
                $('.editNilai').find('input[name="kd"]').val(data.details.kd);
                $('.editNilai').find('input[name="scor"]').val(data.details.scor); 
                $(
                    '.editNilai').modal('show');
            }, 'json');
        });
        // update
        $('#update-nilai-form').on('submit', function(e) {
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
                        $('#nilai-table').DataTable().ajax.reload(null, false);
                        $('.editNilai').modal('hide');
                        $('.editNilai').find('form')[0].reset();
                        toastr.success(data.msg);
                    }
                }
            });
        });

        // delete kd
        $(document).on('click', '#deleteNilai', function() {
            var nilai_id = $(this).data('id');
            // alert(nilai_id)
            var url = '<?= route('admin.delete-nilai') ?>';

            swal.fire({
                title: 'Data akan dihapus?',
                html: 'data akan nilai <b>dihapus</b>',
                showCancelButton: true,
                showCloseButton: true,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
                cancelButtonColor: '#d33',
                confirmButtonColor: '#3085d6',
                width: '300px',
                allowOutsideClick: false,
            }).then(function(result) {
                if (result.value) {
                    $.post(url, {
                        nilai_id: nilai_id
                    }, function(data) {
                        if (data.code == 1) {
                            $('#nilai-table').DataTable().ajax.reload(null, false);
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