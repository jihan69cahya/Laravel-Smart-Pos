<!-- Modal Tambah/Edit Produk -->
<div class="modal fade" id="modal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="title_modal">Tambah Produk</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formModal">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <label for="produk" class="form-label">Produk <span class="text-danger">*</span></label>
                            <select class="form-select select2 select2-modal" id="produk" name="produk" required>
                                <option value="">-- Pilih Produk --</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="satuan" class="form-label">Satuan</label>
                            <input type="text" class="form-control" id="satuan" name="satuan"
                                placeholder="otomatis terisi..." readonly>
                        </div>
                        <div class="col-md-6">
                            <label for="jumlah" class="form-label">Jumlah <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="jumlah" name="jumlah" min="1"
                                step="1" value="1" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="harga" class="form-label">Harga Satuan <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control maskRupiah" id="harga" name="harga"
                                placeholder="Rp 0" required>
                        </div>
                        <div class="col-md-6">
                            <label for="total" class="form-label">Total</label>
                            <input type="text" class="form-control" id="total" name="total" placeholder="Rp 0"
                                readonly>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i> Batal
                </button>
                <button type="button" class="btn btn-primary" id="btn_tambah" onclick="tambahProduk()">
                    <i class="fa fa-plus"></i> Tambah
                </button>
                <button type="button" class="btn btn-warning" id="btn_edit" onclick="editProduk()"
                    style="display: none;">
                    <i class="fa fa-save"></i> Edit
                </button>
            </div>
        </div>
    </div>
</div>
