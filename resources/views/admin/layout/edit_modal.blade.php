<div class="modal fade editKd" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit KD</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= route('admin.update-kd') ?>" method="post" id="update-kd-form">
                    @csrf
                    <div class="form-group">
                        <input type="hidden" name="id">
                        <label for="">KD</label>
                        <input type="text" class="form-control" name="kd" placeholder="KD">
                        <span class="text-danger error-text kd_error"></span>
                    </div>
                    <div class="form-group">
                        <label for="">Judul KD</label>
                        <input type="text" class="form-control" name="judul_kd" placeholder="judul_kd">
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
