@extends('admin.layout.admin_layout')
{{-- @extends('admin.layout.style') --}}
@section('title', 'Materi')
@section('content')

<!-- DataTales Example -->
<div class="card shadow mb-4">
    <div class="card-header py-3">

        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#materiModal">
            Tambah Materi
        </button>

        <!-- Modal -->
        <div class="modal fade" id="materiModal" tabindex="-1" aria-labelledby="materiModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="materiModalLabel">Tambah Materi</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('admin.add-materi') }}" method="post" id="add-materi-form" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="">KD</label>
                                <select name="kd_id" id="kd" class="form-control" required>
                                    <option value="">Pilih KD</option>
                                    @foreach ($kd as $item)
                                    <option value="{{ $item->id }}">{{ $item->kd }}</option>
                                    @endforeach
                                </select>
                                <span class="text-danger error-text kd_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="">Judul</label>
                                <input type="text" class="form-control" name="judul" placeholder="Judul" required>
                                <span class="text-danger error-text judul_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="">Image</label>
                                <input type="file" class="form-control" name="image" required>
                                <span class="text-danger error-text image_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="">Soal Url</label>
                                <input type="text" class="form-control" name="soal" placeholder="Paste soal url here" required>
                                <span class="text-danger error-text soal_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="">Video</label>
                                <input type="text" class="form-control" name="video" placeholder="Paste video url here" required>
                                <span class="text-danger error-text video_error"></span>
                            </div>
                            <div class="form-group">
                                <label for="">File</label>
                                <input type="file" class="form-control" name="file" required>
                                <span class="text-danger error-text file_error"></span>
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
            <table class="table table-bordered" id="materi-table" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>KD</th>
                        <th>Judul </th>
                        <th>Image</th>
                        <th>Soal</th>
                        <th>Video</th>
                        <th>File</th>
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

        // tambah materi
        $('#add-materi-form').on('submit', function(e) {
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
                        $('#materiModal').modal('hide');
                        $('#materi-table').DataTable().ajax.reload(null, false);
                        toastr.success(data.msg);
                    }
                }
            });
        });

        // get materi
        $('#materi-table').DataTable({
            processing: true,
            info: true,
            ajax: "{{ route('admin.get-materi') }}",
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
                    data: 'kd.kd',
                    name: 'kd.kd'
                },
                {
                    data: 'judul',
                    name: 'judul'
                },
                {
                    data: 'image',
                    name: 'image',
                    render: function(data, type, row) {
                        return '<img src="{{ asset("storage/") }}/' + data + '" width="50" height="50" />';
                    }
                },
                {
                    data: 'soal',
                    name: 'soal'
                },
                {
                    data: 'video',
                    name: 'video'
                },
                {
                    data: 'file',
                    name: 'file',
                    // jika materi != null, tampilkan tombol download
                    render: function(data, type, row) {
                        if (data != null) {
                            return '<a href="{{ asset("storage/") }}/' + data + '" class="btn btn-primary btn-sm" target="_blank">Download</a>';
                        } else {
                            return '<span class="text-danger">Tidak ada file</span>';
                        }
                    }
                },
                {
                    data: 'actions',
                    name: 'actions'
                }

            ]
        });

        // delete materi
        $(document).on('click', '#deleteMateri', function() {
            var materi_id = $(this).data('id');

            var url = '<?= route("admin.delete-materi") ?>';

            swal.fire({
                title: 'Data akan dihapus?',
                html: 'data materi anda akan <b>dihapus</b>',
                showCancelButton: true,
                showCloseButton: true,
                cancelButtonText: 'Cancel',
                confirmButtonText: 'Delete',
                cancelButtonColor: '#3085d6',
                confirmButtonColor: '#d22d3d',
                // width: '300px',
                allowOutsideClick: false,
            }).then(function(result) {
                if (result.value) {
                    $.post(url, {
                        materi_id: materi_id
                    }, function(data) {
                        if (data.code == 1) {
                            $('#materi-table').DataTable().ajax.reload(null, false);
                            toastr.success(data.msg);
                        } else {
                            toastr.error(data.msg)
                        }
                    }, 'json');
                }
            })


        });
    });
</script>

@endsection