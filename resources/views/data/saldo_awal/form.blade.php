<div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="title_modal">Form Data Saldo Awal</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formData" onsubmit="return false;">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="id_log_stok" id="id_log_stok">
                    <div class="form-group mb-2">
                        <label for="tanggal">Tanggal *</label>
                        <input type="date" id="tanggal" name="tanggal" class="form-control">
                        <small class="text-danger pl-1" id="error-tanggal"></small>
                    </div>
                    <div class="form-group mb-2">
                        <label for="produk">Produk *</label>
                        <select class="js-example-basic-single col-sm-12 select2 select2-modal" id="produk"
                            name="produk">
                        </select>
                        <small class="text-danger pl-1" id="error-produk"></small>
                    </div>
                    <div class="form-group mb-2">
                        <label for="jumlah">Jumlah *</label>
                        <input type="text" id="jumlah" name="jumlah" class="form-control number-only"
                            placeholder="Masukkan jumlah ...">
                        <small class="text-danger pl-1" id="error-jumlah"></small>
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
