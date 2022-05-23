<div class="modal fade editSiswa" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Siswa</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.update-siswa') }}" method="post" id="update-siswa-form">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" name="id">
                        <label for="">Nama</label>
                        <input type="text" class="form-control" name="name" placeholder="Nama">
                        <span class="text-danger error-text name_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="">NIS</label>
                        <input type="text" class="form-control" name="nis" placeholder="nis">
                        <span class="text-danger error-text nis_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="">Kelas</label>
                        <input type="text" class="form-control" name="kelas" placeholder="kelas">
                        <span class="text-danger error-text kelas_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="text" class="form-control" name="password_view" placeholder="Password">
                        <span class="text-danger error-text kelas_error"></span>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn-success">SAVE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
