<div class="modal fade editNilai" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Nilai</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= route('admin.update-nilai') ?>" method="post" id="update-nilai-form">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" name="id">
                        <label for="">NIS</label>
                        <input type="text" class="form-control" name="nis" placeholder="NIS">
                        <span class="text-danger error-text nis_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="">Nama</label>
                        <input type="text" class="form-control" name="nama" placeholder="Nama">
                        <span class="text-danger error-text nama_error"></span>
                    </div> 
                    <div class="form-group">
                        <label for="">Kelas</label>
                        <input type="text" class="form-control" name="kelas" placeholder="Kelas">
                        <span class="text-danger error-text kelas_error"></span>
                    </div> 
                    <div class="form-group">
                        <label for="">KD</label>
                        <input type="text" class="form-control" name="kd" placeholder="KD">
                        <span class="text-danger error-text kd_error"></span>
                    </div> 
                    <div class="form-group">
                        <label for="">Scor</label>
                        <input type="text" class="form-control" name="scor" placeholder="Scor">
                        <span class="text-danger error-text scor_error"></span>
                    </div> 
                    <div class="form-group">
                        <button type="submit" class="btn btn-block btn-success">SAVE</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
