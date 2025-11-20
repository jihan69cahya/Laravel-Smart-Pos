@extends('layouts.main')
@section('title', 'Master Kategori')
@section('title_page', 'Master Kategori')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Daftar Data Kategori</h5><span>Dibawah ini adalah data kategori yang bisa dipakai di master
                        produk</span>
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
                                <th>Nama</th>
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

    @include('master.kategori.form')

@endsection

@section('js')
    <script>
        let url_tambah = "{{ route('master.kategori.store') }}";
        let url_edit = "{{ route('master.kategori.update', ['kategori' => ':id']) }}";
        let url_hapus = "{{ route('master.kategori.destroy', '') }}";

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
                        data: 'nama',
                        className: 'text-center',
                        name: 'nama',
                        render: (data) => {
                            return `<b>${data}</b>`;
                        }
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
                $("#btn_tambah").show();
                $("#btn_edit").hide();
                $("#title_modal").text("Tambah data kategori");
                $("#formData").attr("onsubmit", "return tambah_data()");
            } else {
                $("#btn_tambah").hide();
                $("#btn_edit").show();
                $("#title_modal").text("Edit data kategori");
                $("#formData").attr("onsubmit", "return edit_data()");
                $.ajax({
                    type: "GET",
                    url: "{{ route('master.kategori.edit', ['kategori' => ':id']) }}".replace(':id', id),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    success: function(hasil) {
                        $("#id").val(id);
                        $("#nama").val(hasil.nama);
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
