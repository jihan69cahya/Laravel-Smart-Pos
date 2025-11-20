<div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="title_modal">Form Master Produk</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formData" onsubmit="return false;" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="id">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="kode">Kode *</label>
                                <input type="text" id="kode" name="kode" class="form-control"
                                    placeholder="Masukkan kode produk ...">
                                <small class="text-danger" id="error-kode"></small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="nama">Nama Produk *</label>
                                <input type="text" id="nama" name="nama" class="form-control"
                                    placeholder="Masukkan nama produk ...">
                                <small class="text-danger" id="error-nama"></small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="kategori">Kategori *</label>
                                <select id="kategori" name="kategori" class="form-control select2">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($data['kategori'] as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                    @endforeach
                                </select>
                                <small class="text-danger" id="error-kategori"></small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="satuan">Satuan *</label>
                                <select id="satuan" name="satuan" class="form-control select2">
                                    <option value="">-- Pilih Satuan --</option>
                                    @foreach ($data['satuan'] as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                    @endforeach
                                </select>
                                <small class="text-danger" id="error-satuan"></small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="stok_minimal">Stok Minimal *</label>
                                <input type="text" id="stok_minimal" name="stok_minimal"
                                    class="form-control number-only" placeholder="Masukkan stok minimal ...">
                                <small class="text-danger" id="error-stok_minimal"></small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group mb-2">
                                <label for="foto">Foto Produk</label>
                                <input type="file" id="foto" name="foto" class="form-control">
                                <small class="text-danger" id="error-foto"></small>
                                <small class="form-text text-muted pl-1" id="info_edit">
                                    Upload foto hanya jika ingin mengganti
                                </small>
                                <img id="preview-foto" src="" class="img-thumbnail mt-2"
                                    style="display:none; max-height:100px;">
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-2">
                        <label for="deskripsi">Deskripsi (opsional)</label>
                        <textarea id="deskripsi" name="deskripsi" rows="5" class="form-control"
                            placeholder="Masukkan deskripsi produk ..."></textarea>
                        <small class="text-danger" id="error-deskripsi"></small>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="btn_tambah"
                    onclick="tambah_data()">Simpan</button>
                <button type="button" class="btn btn-primary" id="btn_edit"
                    onclick="edit_data()">Perbarui</button>
            </div>
        </div>
    </div>
</div>
