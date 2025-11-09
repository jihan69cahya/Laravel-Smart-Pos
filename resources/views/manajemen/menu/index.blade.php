@extends('layouts.main')
@section('title', 'Manajemen Menu')
@section('title_page', 'Manajemen Menu')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Daftar Data Menu</h5><span>Dibawah ini adalah data semua menu yang nantinya dapat diatur sesuai
                        dengan role user.</span>
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
                                <th>Route</th>
                                <th>Icon</th>
                                <th>Parent</th>
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

    @include('manajemen.menu.form')

@endsection


@section('js')
    <script>
        let url_tambah = "{{ route('manajemen.menu.store') }}";
        let url_edit = "{{ route('manajemen.menu.update', ['menu' => ':id']) }}";
        let url_hapus = "{{ route('manajemen.menu.destroy', '') }}";

        $(document).ready(function() {
            get_data();
        });

        function get_data() {
            delete_error();
            delete_form();
            getParentMenu();

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
                        data: 'route',
                        className: 'text-center',
                        name: 'route'
                    },
                    {
                        data: 'icon',
                        className: 'text-center',
                        name: 'icon'
                    },
                    {
                        data: 'parent',
                        className: 'text-center',
                        name: 'parent'
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
                $("#title_modal").text("Tambah data Menu");
            } else {
                $("#btn_tambah").hide();
                $("#btn_edit").show();
                $("#title_modal").text("Edit data Menu");
                $.ajax({
                    type: "GET",
                    url: "{{ route('manajemen.menu.edit', ['menu' => ':id']) }}".replace(':id', id),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    success: function(hasil) {
                        $("#id").val(id);
                        $("#nama").val(hasil.nama);
                        $("#route").val(hasil.route);
                        $("#icon").val(hasil.icon);
                        $("#id_parent").val(hasil.id_parent).change();
                    },
                });
            }
            delete_error();
            delete_form();
        }

        function getParentMenu() {
            $.ajax({
                type: "GET",
                url: "{{ route('get_parent_menu') }}",
                dataType: "json",
                success: function(data) {
                    let select = $("#id_parent");
                    select.empty();
                    select.append('<option value="0">Parent</option>');

                    if (Array.isArray(data)) {
                        data.forEach(item => {
                            select.append(`<option value="${item.id}">${item.nama}</option>`);
                        });
                    } else if (data.id) {
                        select.append(`<option value="${data.id}">${data.nama}</option>`);
                    }

                    select.trigger('change');
                },
                error: function(xhr) {
                    console.error("Gagal memuat data parent menu:", xhr);
                }
            });
        }
    </script>

    @include('js.crud')
@endsection
