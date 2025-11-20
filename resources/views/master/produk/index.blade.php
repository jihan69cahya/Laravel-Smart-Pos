@extends('layouts.main')
@section('title', 'Master Produk')
@section('title_page', 'Master Produk')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Daftar Data Produk</h5><span>Dibawah ini adalah data produk yang ada digunakan di sistem.</span>
                    <div class="d-flex mt-3">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal"
                            onclick="submit('tambah')">
                            <span class="fa fa-plus-circle"></span> Tambah data
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="table" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Foto</th>
                                <th>Kategori</th>
                                <th>Nama</th>
                                <th>Satuan</th>
                                <th>Deskripsi</th>
                                <th>Stok Minimal</th>
                                <th>Harga Terbaru</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    @include('master.produk.form')

@endsection

@section('js')
    <script>
        let url_tambah = "{{ route('master.produk.store') }}";
        let url_edit = "{{ route('master.produk.update', ['produk' => ':id']) }}";
        let url_hapus = "{{ route('master.produk.destroy', '') }}";

        $(document).ready(function() {
            get_data();

            $('#foto').on('change', function() {
                let file = this.files[0];

                if (file) {
                    let reader = new FileReader();

                    reader.onload = function(e) {
                        $('#preview-foto').attr('src', e.target.result).show();
                    }

                    reader.readAsDataURL(file);
                } else {
                    $('#preview-foto').hide().attr('src', '');
                }
            });
        });

        function get_data() {
            delete_error();
            delete_form();

            let table = $("#table").DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{ url()->current() }}",
                    type: 'GET',
                },
                columns: [{
                        data: 'kode',
                        name: 'kode',
                        className: 'text-center',
                    },
                    {
                        data: 'foto',
                        className: 'text-center',
                        name: 'foto'
                    },
                    {
                        data: 'kategori',
                        className: 'text-center',
                        name: 'kategori'
                    },
                    {
                        data: 'nama',
                        className: 'text-center',
                        name: 'nama',
                        render: (data) => {
                            return `<b>${data}</b>`;
                        }
                    },
                    {
                        data: 'satuan',
                        className: 'text-center',
                        name: 'satuan'
                    },
                    {
                        data: 'deskripsi',
                        className: 'text-center',
                        name: 'deskripsi',
                    },
                    {
                        data: 'stok_minimal',
                        className: 'text-center',
                        name: 'stok_minimal',
                    },
                    {
                        data: 'harga_terbaru',
                        className: 'text-center',
                        name: 'harga_terbaru',
                    },
                    {
                        data: 'aksi',
                        className: 'text-center',
                        name: 'aksi'
                    },
                ],
                createdRow: function(row, data, dataIndex) {
                    $(row).addClass('small-padding');
                }
            });
        }

        function submit(id) {
            $('#preview-foto').hide().attr('src', '');
            if (id == "tambah") {
                $("#btn_tambah").show();
                $("#btn_edit").hide();
                $("#info_edit").hide();
                $("#title_modal").text("Tambah data produk");
                $("#formData").attr("onsubmit", "return tambah_data()");
            } else {
                $("#btn_tambah").hide();
                $("#btn_edit").show();
                $("#info_edit").show();
                $("#title_modal").text("Edit data produk");
                $("#formData").attr("onsubmit", "return edit_data()");
                $.ajax({
                    type: "GET",
                    url: "{{ route('master.produk.edit', ['produk' => ':id']) }}".replace(':id', id),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    success: function(hasil) {
                        $("#id").val(id);
                        $("#kode").val(hasil.kode);
                        $("#nama").val(hasil.nama);
                        $("#kategori").val(hasil.id_kategori).change();
                        $("#satuan").val(hasil.id_satuan).change();
                        $("#stok_minimal").val(hasil.stok_minimal);
                        $("#deskripsi").val(hasil.deskripsi);
                    },
                });
            }
            delete_error();
            delete_form();
        }

        function produk_harga(url) {
            window.location.href = url;
        }
    </script>

    @include('js.crud')
@endsection
