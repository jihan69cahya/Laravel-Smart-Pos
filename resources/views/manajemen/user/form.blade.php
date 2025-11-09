<div id="modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="title_modal">Form Manajemen User</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formData" onsubmit="return false;">
                    <div class="form-group mb-2">
                        <label for="nama">Nama User *</label>
                        <input type="hidden" name="id" id="id">
                        <input type="text" id="nama" name="nama" class="form-control"
                            placeholder="Masukkan Nama User ...">
                        <small class="text-danger pl-1" id="error-nama"></small>
                    </div>
                    <div class="form-group mb-2">
                        <label for="username">Username *</label>
                        <input type="text" id="username" name="username" class="form-control"
                            placeholder="Masukkan username (untuk login)">
                        <small class="text-danger pl-1" id="error-username"></small>
                    </div>
                    <div class="form-group mb-2">
                        <label for="email">Email *</label>
                        <input type="text" id="email" name="email" class="form-control"
                            placeholder="Masukkan email aktif ...">
                        <small class="text-danger pl-1" id="error-email"></small>
                    </div>
                    <div class="form-group mb-2">
                        <label for="password" id="label_password">Password *</label>
                        <input type="password" id="password" name="password" class="form-control"
                            placeholder="Masukkan password (minimal 6 karakter)">
                        <small class="form-text text-muted pl-1" id="info_edit">
                            Isi password hanya jika ingin mengganti.
                        </small>
                        <small class="text-danger pl-1" id="error-password"></small>
                    </div>
                    <div class="form-group mb-2">
                        <label for="role">Role *</label>
                        <select class="js-example-basic-single col-sm-12 select2" id="role" name="role">
                            @foreach ($role as $item)
                                <option value="{{ $item->id }}">{{ $item->nama }}</option>
                            @endforeach
                        </select>
                        <small class="text-danger pl-1" id="error-role"></small>
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
