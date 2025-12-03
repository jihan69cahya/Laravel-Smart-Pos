@extends('layouts.main')
@section('title', 'Data Mutasi')
@section('title_page', 'Data Mutasi')

@section('content')
    <div class="row">
        <div class="col-sm-12">

            <div class="card mb-3">
                <div class="card-header">
                    <h5>Filter Data Mutasi</h5>
                </div>
                <div class="card-body">
                    <form id="formFilter" class="row g-3">

                        <div class="col-md-3">
                            <input class="form-control digits" id="tanggal" name="tanggal" type="text">
                        </div>

                        <div class="col-md-4">
                            <select name="id_produk" id="id_produk" class="form-select select2 select2-not-modal">
                                <option value="ALL">-- Semua Produk --</option>
                                @foreach ($data['produk'] as $item)
                                    <option value="{{ $item->id }}">{{ $item->kode }} || {{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 d-flex gap-2">
                            <button type="button" id="btnFilter" class="btn btn-primary btn-sm px-3" onclick="get_data()">
                                <i class="fa fa-search"></i> Filter
                            </button>
                            <button type="button" id="btnExport" class="btn btn-success btn-sm px-3"
                                onclick="export_excel()">
                                <i class="fa fa-file-excel-o"></i> Export
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Daftar Data Mutasi</h5>
                    <span>Dibawah ini adalah data log keluar/masuk produk</span>
                </div>

                <div class="card-body">
                    <table id="table" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Produk</th>
                                <th>Jumlah</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            var start = moment().startOf('month');
            var end = moment().endOf('month');

            function set_range(start, end) {
                $('#tanggal span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }

            $('#tanggal').daterangepicker({
                startDate: start,
                endDate: end,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,
                            'month')
                        .endOf(
                            'month')
                    ]
                }
            }, set_range);

            set_range(start, end);

            get_data();
        });

        function get_data() {
            var tanggal = $('#tanggal').val();
            if (tanggal) {
                var parts = tanggal.split(' - ');

                var start = moment(parts[0], 'MM/DD/YYYY').format('YYYY-MM-DD');
                var end = moment(parts[1], 'MM/DD/YYYY').format('YYYY-MM-DD');
            }
            var id_produk = $('#id_produk').val();

            let table = $("#table").DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{ url()->current() }}",
                    type: 'GET',
                    data: {
                        id_produk: id_produk,
                        start: start,
                        end: end
                    },
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
                        data: 'status',
                        className: 'text-center',
                        name: 'status',
                    },
                    {
                        data: 'keterangan',
                        className: 'text-center',
                        name: 'keterangan',
                    },
                ],
                createdRow: function(row, data, dataIndex) {
                    $(row).addClass('small-padding');
                }
            });
        }

        function export_excel() {
            var tanggal = $('#tanggal').val();
            if (tanggal) {
                var parts = tanggal.split(' - ');

                var start = moment(parts[0], 'MM/DD/YYYY').format('YYYY-MM-DD');
                var end = moment(parts[1], 'MM/DD/YYYY').format('YYYY-MM-DD');
            }
            var id_produk = $('#id_produk').val();
            var url = "{{ route('data.mutasi.export') }}";
            url += '?id_produk=' + encodeURIComponent(id_produk) +
                '&start=' + encodeURIComponent(start) +
                '&end=' + encodeURIComponent(end);

            window.open(url, '_blank');
        }
    </script>
@endsection
