@extends('layouts.main')
@section('title', 'Manajemen User')
@section('title_page', 'Manajemen User')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Daftar Data User</h5><span>Dibawah ini adalah data user yang mengoperasikan sistem ini.</span>
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
                                <th>Username</th>
                                <th>Email</th>
                                <th>Role</th>
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

    @include('manajemen.user.form')

@endsection

@section('js')
    <script>
        let url_tambah = "{{ route('manajemen.user.store') }}";
        let url_edit = "{{ route('manajemen.user.update', ['user' => ':id']) }}";
        let url_hapus = "{{ route('manajemen.user.destroy', '') }}";

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
                        data: 'username',
                        className: 'text-center',
                        name: 'username'
                    },
                    {
                        data: 'email',
                        className: 'text-center',
                        name: 'email'
                    },
                    {
                        data: 'role',
                        className: 'text-center',
                        name: 'role'
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
                $("#info_edit").hide();
                $("#title_modal").text("Tambah data user");
                $("#label_password").text("Password *");
            } else {
                $("#btn_tambah").hide();
                $("#btn_edit").show();
                $("#info_edit").show();
                $("#title_modal").text("Edit data user");
                $("#label_password").text("Password");
                $.ajax({
                    type: "GET",
                    url: "{{ route('manajemen.user.edit', ['user' => ':id']) }}".replace(':id', id),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    success: function(hasil) {
                        $("#id").val(id);
                        $("#nama").val(hasil.nama);
                        $("#username").val(hasil.username);
                        $("#email").val(hasil.email);
                        $("#role").val(hasil.id_role).change();
                    },
                });
            }
            delete_error();
            delete_form();
        }
    </script>

    @include('js.crud')
@endsection
