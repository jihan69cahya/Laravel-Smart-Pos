@extends('layouts.main')
@section('title', 'Master Supplier')
@section('title_page', 'Master Supplier')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Daftar Data Supplier</h5><span>Dibawah ini adalah data supplier tempat dimana anda melakukan
                        pembelian barang.</span>
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
                                <th>Alamat</th>
                                <th>Email</th>
                                <th>Telepon</th>
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

    @include('master.supplier.form')

@endsection

@section('js')
    <script>
        let url_tambah = "{{ route('master.supplier.store') }}";
        let url_edit = "{{ route('master.supplier.update', ['supplier' => ':id']) }}";
        let url_hapus = "{{ route('master.supplier.destroy', '') }}";

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
                        data: 'alamat',
                        className: 'text-center',
                        name: 'alamat'
                    },
                    {
                        data: 'email',
                        className: 'text-center',
                        name: 'email'
                    },
                    {
                        data: 'telp',
                        className: 'text-center',
                        name: 'telp'
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
                $("#title_modal").text("Tambah data supplier");
            } else {
                $("#btn_tambah").hide();
                $("#btn_edit").show();
                $("#info_edit").show();
                $("#title_modal").text("Edit data supplier");
                $.ajax({
                    type: "GET",
                    url: "{{ route('master.supplier.edit', ['supplier' => ':id']) }}".replace(':id', id),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    dataType: "json",
                    success: function(hasil) {
                        $("#id").val(id);
                        $("#nama").val(hasil.nama);
                        $("#alamat").val(hasil.alamat);
                        $("#email").val(hasil.email);
                        $("#telepon").val(hasil.telp);
                    },
                });
            }
            delete_error();
            delete_form();
        }
    </script>

    @include('js.crud')
@endsection
