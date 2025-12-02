@extends('layouts.main')
@section('title', 'Data Pembelian')
@section('title_page', 'Data Pembelian')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Daftar Data Pembelian</h5><span>Dibawah ini adalah data pembelian yang telah dilakukan</span>
                    <div class="d-flex mt-3">
                        <button type="button" class="btn btn-primary"
                            onclick="window.location.href='{{ route('data.pembelian.create') }}'">
                            <span class="fa fa-plus-circle"></span> Tambah data
                        </button>
                    </div>
                </div>
                <div class="card-body">

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Tanggal (Range)</label>
                            <div class="input-group">
                                <input type="text" id="date_range" class="form-control"
                                    placeholder="Pilih Rentang Tanggal" autocomplete="off">
                                <span class="input-group-text">
                                    <i class="fa fa-calendar"></i>
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Supplier</label>
                            <select id="filter_supplier" class="js-example-basic-single select2 select2-not-modal">
                                <option value="">-- Semua Supplier --</option>
                                @foreach ($data['supplier'] as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4 d-flex align-items-end">
                            <button class="btn btn-primary me-2" onclick="get_data()">
                                <i class="fa fa-search"></i> Filter
                            </button>

                            <button class="btn btn-warning" onclick="resetFilter()">
                                <i class="fa fa-undo"></i> Reset
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="table" class="table table-striped dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>No. Faktur</th>
                                    <th>Supplier</th>
                                    <th>Tagihan</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        let url_hapus = "{{ route('data.pembelian.destroy', '') }}";

        $(document).ready(function() {
            let start = moment().subtract(6, 'days');
            let end = moment();

            $('#date_range').daterangepicker({
                locale: {
                    format: 'YYYY-MM-DD'
                },
                startDate: start,
                endDate: end
            });

            $('#date_range').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));

            get_data();
        });

        function get_data() {
            let table = $("#table").DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                destroy: true,
                ajax: {
                    url: "{{ url()->current() }}",
                    type: 'GET',
                    data: function(d) {
                        d.date_range = $('#date_range').val();
                        d.id_supplier = $('#filter_supplier').val();
                    }
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
                        data: 'no_faktur',
                        className: 'text-center',
                        name: 'no_faktur',
                        render: (data) => {
                            return `<b>${data}</b>`;
                        }
                    },
                    {
                        data: 'supplier',
                        className: 'text-center',
                        name: 'supplier',
                    },
                    {
                        data: 'tagihan',
                        className: 'text-center',
                        name: 'tagihan'
                    },
                    {
                        data: 'status',
                        className: 'text-center',
                        name: 'status'
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

        function resetFilter() {
            let start = moment().subtract(6, 'days');
            let end = moment();

            $('#date_range').data('daterangepicker').setStartDate(start);
            $('#date_range').data('daterangepicker').setEndDate(end);

            $('#date_range').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));

            $('#filter_supplier').val('');
            get_data();
        }

        function edit(id) {
            window.location.href = "/data/pembelian/" + id + "/edit";
        }

        function terima(id) {
            window.location.href = "/data/pembelian/terima/" + id;
        }

        function detail(id) {
            window.location.href = "/data/pembelian/detail/" + id;
        }
    </script>

    @include('js.crud')
@endsection
