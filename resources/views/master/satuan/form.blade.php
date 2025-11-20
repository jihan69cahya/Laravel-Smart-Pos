<div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="title_modal">Form Master Satuan</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formData" onsubmit="return false;">
                    <div class="form-group mb-2">
                        <label for="nama">Nama Satuan *</label>
                        <input type="hidden" name="id" id="id">
                        <input type="text" id="nama" name="nama" class="form-control"
                            placeholder="Masukkan Nama Satuan ...">
                        <small class="text-danger pl-1" id="error-nama"></small>
                    </div>
                    <div class="form-group mb-2">
                        <label for="keterangan">Keterangan (opsional)</label>
                        <textarea id="keterangan" name="keterangan" class="form-control" placeholder="Masukkan keterangan ..." rows="3"></textarea>
                        <small class="text-danger pl-1" id="error-keterangan"></small>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn_tambah" onclick="tambah_data()">Simpan</button>
                <button type="button" class="btn btn-primary" id="btn_edit" onclick="edit_data()">Perbarui</button>
            </div>
        </div>
    </div>
</div>
