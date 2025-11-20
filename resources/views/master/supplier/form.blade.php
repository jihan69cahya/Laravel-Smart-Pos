<div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="title_modal">Form Master Supplier</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formData" onsubmit="return false;">
                    <div class="form-group mb-2">
                        <label for="nama">Nama Supplier *</label>
                        <input type="hidden" name="id" id="id">
                        <input type="text" id="nama" name="nama" class="form-control"
                            placeholder="Masukkan Nama Supplier ...">
                        <small class="text-danger pl-1" id="error-nama"></small>
                    </div>
                    <div class="form-group mb-2">
                        <label for="alamat">Alamat *</label>
                        <textarea id="alamat" name="alamat" rows="3" class="form-control" placeholder="Masukkan alamat ..."></textarea>
                        <small class="text-danger pl-1" id="error-alamat"></small>
                    </div>
                    <div class="form-group mb-2">
                        <label for="email">Email</label>
                        <input type="text" id="email" name="email" class="form-control"
                            placeholder="Masukkan email aktif ...">
                        <small class="text-danger pl-1" id="error-email"></small>
                    </div>
                    <div class="form-group mb-2">
                        <label for="telepon">Telepon</label>
                        <input type="text" id="telepon" name="telepon" class="form-control number-only"
                            placeholder="Masukkan telepon aktif ...">
                        <small class="text-danger pl-1" id="error-telepon"></small>
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
