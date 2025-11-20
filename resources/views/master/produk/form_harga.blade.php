<div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="title_modal">Form Harga Produk</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formData" onsubmit="return false;" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="id_produk" id="id_produk">

                    <div class="form-group mb-2">
                        <label for="tanggal">Tanggal *</label>
                        <input type="date" id="tanggal" name="tanggal" class="form-control">
                        <small class="text-danger" id="error-tanggal"></small>
                    </div>

                    <div class="form-group mb-2">
                        <label for="harga">Harga *</label>
                        <input type="text" id="harga" name="harga" class="form-control maskRupiah"
                            placeholder="Masukkan harga ...">
                        <small class="text-danger" id="error-harga"></small>
                    </div>

                    <div class="form-group mb-2">
                        <label for="harga_diskon">Harga Diskon</label>
                        <input type="text" id="harga_diskon" name="harga_diskon" class="form-control maskRupiah"
                            placeholder="Masukkan harga diskon (opsional)">
                        <small class="text-danger" id="error-harga_diskon"></small>
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
