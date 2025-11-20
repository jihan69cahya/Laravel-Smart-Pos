@extends('layouts.main')
@section('title', 'Master Pelanggan')
@section('title_page', 'Master Pelanggan')

@section('css')
    <style>
        .form-check-input:checked {
            background-color: #24695c;
            border-color: #24695c;
        }

        .form-check-input:checked+.form-check-label {
            color: #24695c;
            font-weight: 600;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Daftar Data Pelanggan</h5><span>Dibawah ini adalah data pelanggan yang terdaftar sebagai member di
                        toko anda.</span>
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
                                <th>No Member</th>
                                <th>Nama</th>
                                <th>Telepon</th>
                                <th>Alamat</th>
                                <th>Jenis Kelamin</th>
                                <th>Total Poin</th>
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

    @include('master.pelanggan.form')

@endsection

@section('js')
    <script>
        let url_tambah = "{{ route('master.pelanggan.store') }}";
        let url_edit = "{{ route('master.pelanggan.update', ['pelanggan' => ':id']) }}";
        let url_hapus = "{{ route('master.pelanggan.destroy', '') }}";

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
                        data: 'no_member',
                        name: 'no_member',
                        className: 'text-center',
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
                        data: 'telp',
                        className: 'text-center',
                        name: 'telp'
                    },
                    {
                        data: 'alamat',
                        className: 'text-center',
                        name: 'alamat'
                    },
                    {
                        data: 'jenis_kelamin',
                        className: 'text-center',
                        name: 'jenis_kelamin'
                    },
                    {
                        data: 'total_poin',
                        className: 'text-center',
                        name: 'total_poin'
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
                $("#title_modal").text("Tambah data pelanggan");
                $("#formData").attr("onsubmit", "return tambah_data()");
            } else {
                $("#btn_tambah").hide();
                $("#btn_edit").show();
                $("#title_modal").text("Edit data pelanggan");
                $("#formData").attr("onsubmit", "return edit_data()");
                $.ajax({
                    type: "GET",
                    url: "{{ route('master.pelanggan.edit', ['pelanggan' => ':id']) }}".replace(':id', id),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    success: function(hasil) {
                        $("#id").val(id);
                        $("#nama").val(hasil.nama);
                        $("#telepon").val(hasil.telp);
                        $("#alamat").val(hasil.alamat);
                        $("#jenis_kelamin").val(hasil.jenis_kelamin).change();
                    },
                });
            }
            delete_error();
            delete_form();
        }
    </script>

    @include('js.crud')
@endsection
