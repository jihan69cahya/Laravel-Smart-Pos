@extends('layouts.main')
@section('title', 'Harga Produk')
@section('title_page', 'Harga Produk')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-start">
                    <div>
                        <h5 class="mb-3">Daftar Harga</h5>

                        <div class="d-flex align-items-center bg-white border rounded p-3 shadow-sm">
                            <div class="me-4">
                                <small class="text-muted d-block">Kode</small>
                                <span class="fw-semibold">{{ $produk->kode ?? '-' }}</span>
                            </div>
                            <div class="vr mx-3"></div>
                            <div class="me-4">
                                <small class="text-muted d-block">Nama Produk</small>
                                <span class="fw-semibold">{{ $produk->nama ?? '-' }}</span>
                            </div>
                            <div class="vr mx-3"></div>
                            <div class="me-4">
                                <small class="text-muted d-block">Satuan</small>
                                <span class="badge bg-primary px-3 py-2">{{ $produk->relSatuan->nama ?? '-' }}</span>
                            </div>
                            <div class="vr mx-3"></div>
                            <div class="me-2">
                                <small class="text-muted d-block">Kategori</small>
                                <span class="badge bg-success px-3 py-2">{{ $produk->relKategori->nama ?? '-' }}</span>
                            </div>
                        </div>

                        <p class="mt-3">Anda dapat mengupdate harga produk sesuai dengan harga terkini.</p>

                        <button type="button" class="btn btn-primary mt-2" data-bs-toggle="modal" data-bs-target="#modal"
                            onclick="submit('tambah')">
                            <i class="fa fa-plus-circle me-1"></i> Tambah data
                        </button>
                    </div>

                    <div>
                        <a href="javascript:history.back()" class="btn btn-light">
                            <i class="fa fa-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <table id="table" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Harga</th>
                                <th>Harga Diskon</th>
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

    @include('master.produk.form_harga')
@endsection


@section('js')
    <script>
        let url_tambah = "{{ route('master.harga.store') }}";
        let url_edit = "{{ route('master.harga.update', ['harga' => ':id']) }}";
        let url_hapus = "{{ route('master.harga.destroy', '') }}";
        let id_produk = "{{ $id }}";

        $(document).ready(function() {
            get_data();
        });

        function get_data() {
            delete_error();
            delete_form();

            let table = $("#table").DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{ route('master.harga.show', $id) }}",
                    type: 'GET',
                },
                columns: [{
                        data: 'tanggal',
                        name: 'tanggal',
                        className: 'text-center',
                    },
                    {
                        data: 'harga',
                        className: 'text-center',
                        name: 'harga'
                    },
                    {
                        data: 'harga_diskon',
                        className: 'text-center',
                        name: 'harga_diskon'
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
                $("#title_modal").text("Tambah data harga");
                $("#formData").attr("onsubmit", "return tambah_data()");
            } else {
                $("#btn_tambah").hide();
                $("#btn_edit").show();
                $("#info_edit").show();
                $("#title_modal").text("Edit data harga");
                $("#formData").attr("onsubmit", "return edit_data()");
                $.ajax({
                    type: "GET",
                    url: "{{ route('master.harga.edit', ['harga' => ':id']) }}".replace(':id', id),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    success: function(hasil) {
                        $("#id").val(id);
                        $("#tanggal").val(hasil.tanggal);
                        formatMaskMoney("#harga", hasil.harga);
                        formatMaskMoney("#harga_diskon", hasil.harga_diskon);
                    },
                });
            }
            delete_error();
            delete_form();
            $("#id_produk").val(id_produk);
            $("#tanggal").val("{{ date('Y-m-d') }}");
        }
    </script>

    @include('js.crud')
@endsection
