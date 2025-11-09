<div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="title_modal">Form Manajemen Menu</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formData" onsubmit="return false;">
                    <div class="form-group mb-2">
                        <label for="nama">Nama Menu *</label>
                        <input type="hidden" name="id" id="id">
                        <input type="text" id="nama" name="nama" class="form-control"
                            placeholder="Masukkan Nama Menu ...">
                        <small class="text-danger pl-1" id="error-nama"></small>
                    </div>
                    <div class="form-group mb-2">
                        <label for="route">Route</label>
                        <input type="text" id="route" name="route" class="form-control"
                            placeholder="Contoh. manajemen.menu.index">
                        <small class="text-danger pl-1" id="error-route"></small>
                    </div>
                    <div class="form-group mb-2">
                        <label for="icon">Icon</label>
                        <input type="text" id="icon" name="icon" class="form-control"
                            placeholder="Contoh. fa fa-eye (menggunakan font awesome)">
                        <small class="text-danger pl-1" id="error-icon"></small>
                        <small class="text-muted d-block mt-1">
                            Lihat daftar ikon di
                            <a href="https://fontawesome.com/icons" target="_blank">
                                Font Awesome Icons Library
                            </a>
                        </small>
                    </div>
                    <div class="form-group mb-2">
                        <label for="id_parent">Parent *</label>
                        <select class="js-example-basic-single col-sm-12 select2" id="id_parent" name="id_parent">

                        </select>
                        <small class="text-danger pl-1" id="error-id_parent"></small>
                    </div>
                    <div class="form-group mb-2">
                        <label for="urutan">Urutan</label>
                        <input type="text" id="urutan" name="urutan" class="form-control number-only"
                            placeholder="Masukkan urutan menu">
                        <small class="text-danger pl-1" id="error-urutan"></small>
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
