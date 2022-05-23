@extends('admin.layout.admin_layout')
{{-- @extends('admin.layout.style') --}}
@section('title', 'KD')
@section('content')

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">

            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
                Tambah KD
            </button>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('admin.addKd') }}" method="post" id="add-kd-form">
                                @csrf
                                <div class="form-group">
                                    <label for="">KD</label>
                                    <input type="text" class="form-control" name="kd" placeholder="KD">
                                    <span class="text-danger error-text kd_error"></span>
                                </div>
                                <div class="form-group">
                                    <label for="">KD</label>
                                    <input type="text" class="form-control" name="judul_kd" placeholder="Judul KD">
                                    <span class="text-danger error-text judul_kd_error"></span>
                                </div>
                                <div class="form-group">
                                    <label for="">Keterangan</label>
                                    <textarea type="text" class="form-control" name="keterangan" placeholder="keterangan"></textarea>
                                    <span class="text-danger error-text keterangan_error"></span>
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
                <table class="table table-bordered" id="kd-table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>KD</th>
                            <th>Judul</th>
                            <th>Keterangan</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
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
            columns: [
                {
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
                $('.editKd').find('textarea[name="keterangan"]').val(data.details.keterangan);
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
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
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