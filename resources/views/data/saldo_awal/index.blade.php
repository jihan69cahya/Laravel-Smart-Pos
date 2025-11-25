@extends('layouts.main')
@section('title', 'Data Saldo Awal')
@section('title_page', 'Data Saldo Awal')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Daftar Data Saldo Awal</h5><span>Dibawah ini adalah data saldo awal tiap produk per tanggal</span>
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
                                <th>Jumlah</th>
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

    @include('data.saldo_awal.form')

@endsection

@section('js')
    <script>
        let url_tambah = "{{ route('data.saldo-awal.store') }}";
        let url_edit = "{{ route('data.saldo-awal.update', ['saldo_awal' => ':id']) }}";
        let url_hapus = "{{ route('data.saldo-awal.destroy', '') }}";

        $(document).ready(function() {
            get_data();
        });

        function get_produk_select(id_produk = null) {
            $.ajax({
                url: "{{ route('get_produk_saldo_awal') }}",
                type: 'GET',
                dataType: 'json',
                data: {
                    id: id_produk
                },
                success: function(data) {
                    $('#produk').empty().append('<option></option>');

                    $.each(data, function(index, produk) {
                        $('#produk').append(
                            $('<option>', {
                                value: produk.id,
                                text: produk.kode + ' || ' + produk.nama + ' || ' + produk
                                    .rel_satuan.nama
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
                        data: 'jumlah',
                        className: 'text-center',
                        name: 'jumlah',
                    },
                    {
                        data: 'keterangan',
                        className: 'text-center',
                        name: 'keterangan'
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
                $("#title_modal").text("Tambah data saldo awal");
                $("#formData").attr("onsubmit", "return tambah_data()");
            } else {
                $("#btn_tambah").hide();
                $("#btn_edit").show();
                $("#title_modal").text("Edit data saldo awal");
                $("#formData").attr("onsubmit", "return edit_data()");
                $.ajax({
                    type: "GET",
                    url: "{{ route('data.saldo-awal.edit', ['saldo_awal' => ':id']) }}".replace(':id', id),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    success: function(hasil) {
                        get_produk_select(hasil.id_produk);
                        $("#id").val(id);
                        $("#id_log_stok").val(hasil.id_log_stok);
                        $("#produk").val(hasil.id_produk);
                        $("#tanggal").val(hasil.tanggal);
                        $("#jumlah").val(hasil.jumlah);
                        $("#keterangan").val(hasil.keterangan);
                    },
                });
            }
            delete_error();
            delete_form();
        }
    </script>

    @include('js.crud')
@endsection
