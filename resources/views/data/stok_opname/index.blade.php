@extends('layouts.main')
@section('title', 'Data Stok Opname')
@section('title_page', 'Data Stok Opname')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Daftar Data Stok Opname</h5><span>Dibawah ini adalah data stok opname per tanggal</span>
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
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Produk</th>
                                <th>Stok</th>
                                <th>Jumlah Fisik</th>
                                <th>Keterangan</th>
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

    @include('data.stok_opname.form')

@endsection

@section('js')
    <script>
        let url_tambah = "{{ route('data.stok-opname.store') }}";
        let url_edit = "{{ route('data.stok-opname.update', ['stok_opname' => ':id']) }}";
        let url_hapus = "{{ route('data.stok-opname.destroy', '') }}";
        let id_produk = null;

        $(document).ready(function() {
            get_data();
        });

        function get_produk_select(id_produk = null) {
            $('#produk').empty().append('<option></option>');
            var tanggal = $('#tanggal').val();
            $.ajax({
                url: "{{ route('get_produk_stok_opname') }}",
                type: 'GET',
                dataType: 'json',
                data: {
                    id: id_produk,
                    tanggal: tanggal
                },
                success: function(data) {
                    $('#produk').empty().append('<option></option>');

                    $.each(data, function(index, produk) {
                        $('#produk').append(
                            $('<option>', {
                                value: produk.id,
                                text: produk.kode + ' || ' + produk.nama + ' || ' + produk
                                    .satuan,
                                'data-stok': produk.stok
                            })
                        );
                    });

                    $("#produk").val(id_produk).change();
                },
                error: function(xhr, status, error) {
                    console.error('Terjadi kesalahan: ' + error);
                }
            });
        }

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
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        className: 'text-center',
                        searchable: false,
                        orderable: false,
                    },
                    {
                        data: 'tanggal',
                        className: 'text-center',
                        name: 'tanggal',
                    },
                    {
                        data: 'produk',
                        className: 'text-center',
                        name: 'produk',
                        render: (data) => {
                            return `<b>${data}</b>`;
                        }
                    },
                    {
                        data: 'stok',
                        className: 'text-center',
                        name: 'stok',
                    },
                    {
                        data: 'fisik',
                        className: 'text-center',
                        name: 'fisik',
                    },
                    {
                        data: 'keterangan',
                        className: 'text-center',
                        name: 'keterangan',
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
            if (id == "tambah") {
                get_produk_select();
                $("#btn_tambah").show();
                $("#btn_edit").hide();
                $("#title_modal").text("Tambah data stok opname");
                $("#formData").attr("onsubmit", "return tambah_data()");
                id_produk = null;
            } else {
                $("#btn_tambah").hide();
                $("#btn_edit").show();
                $("#title_modal").text("Edit data stok opname");
                $("#formData").attr("onsubmit", "return edit_data()");
                $.ajax({
                    type: "GET",
                    url: "{{ route('data.stok-opname.edit', ['stok_opname' => ':id']) }}".replace(':id', id),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    success: function(hasil) {
                        id_produk = hasil.id_produk;
                        get_produk_select(hasil.id_produk);
                        $("#id").val(id);
                        $("#id_log_stok").val(hasil.id_log_stok);
                        $("#tanggal").val(hasil.tanggal);
                        $("#produk").val(hasil.id_produk);
                        $("#stok").val(hasil.stok);
                        $("#fisik").val(hasil.fisik);
                        $("#keterangan").val(hasil.keterangan);
                    },
                });
            }
            delete_error();
            delete_form();
            $("#tanggal").val("{{ date('Y-m-d') }}");
        }

        $('#tanggal').on('change', function() {
            get_produk_select(id_produk);
        });

        $('#produk').on('change', function() {
            let stok = $(this).find('option:selected').data('stok');
            $('#stok').val(stok);
        });
    </script>

    @include('js.crud')
@endsection
