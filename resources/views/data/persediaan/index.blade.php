@extends('layouts.main')
@section('title', 'Data Persediaan')
@section('title_page', 'Data Persediaan')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Daftar Data Persediaan</h5><span>Dibawah ini adalah data persediaan produk per hari ini</span>
                </div>
                <div class="card-body">
                    <table id="table" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Produk</th>
                                <th>Nama Produk</th>
                                <th>Satuan</th>
                                <th>Stok</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            get_data();
        });

        function get_data() {
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
                        data: 'kode_produk',
                        className: 'text-center',
                        name: 'kode_produk',
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
                        data: 'satuan',
                        className: 'text-center',
                        name: 'satuan',
                    },
                    {
                        data: 'stok',
                        className: 'text-center',
                        name: 'stok',
                    },
                ],
                createdRow: function(row, data, dataIndex) {
                    $(row).addClass('small-padding');
                }
            });
        }
    </script>
@endsection
