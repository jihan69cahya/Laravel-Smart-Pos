@extends('layouts.main')
@section('title', 'Data Log Akticitas')
@section('title_page', 'Data Log Akticitas')

@section('content')
    <div class="row">
        <div class="col-sm-12">

            <div class="card mb-3">
                <div class="card-header">
                    <h5>Filter Data Log Aktivitas</h5>
                </div>
                <div class="card-body">
                    <form id="formFilter" class="row g-3">

                        <div class="col-md-3">
                            <input class="form-control digits daterange" id="tanggal" name="tanggal" type="text">
                        </div>

                        <div class="col-md-4">
                            <select name="id_user" id="id_user" class="form-select select2 select2-not-modal">
                                <option value="ALL">-- Semua Pengguna --</option>
                                @foreach ($data['user'] as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }} || {{ $item->role_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3 d-flex gap-2">
                            <button type="button" id="btnFilter" class="btn btn-primary btn-sm px-3" onclick="get_data()">
                                <i class="fa fa-search"></i> Filter
                            </button>
                        </div>

                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5>Daftar Data Log Akticitas</h5><span>Dibawah ini adalah data riwayat aktivitas yang dilakukan oleh
                        pengguna</span>
                </div>
                <div class="card-body">
                    <table id="table" class="table table-striped dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                                <th>Keterangan</th>
                                <th>Pengguna yang melakukan</th>
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
            var tanggal = $('#tanggal').val();
            if (tanggal) {
                var parts = tanggal.split(' - ');

                var start = moment(parts[0], 'MM/DD/YYYY').format('YYYY-MM-DD');
                var end = moment(parts[1], 'MM/DD/YYYY').format('YYYY-MM-DD');
            }
            var id_user = $('#id_user').val();

            let table = $("#table").DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ajax: {
                    url: "{{ url()->current() }}",
                    type: 'GET',
                    data: {
                        id_user: id_user,
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
                        data: 'status',
                        className: 'text-center',
                        name: 'status',
                    },
                    {
                        data: 'keterangan',
                        className: 'text-center',
                        name: 'keterangan',
                    },
                    {
                        data: 'nama_user',
                        className: 'text-center',
                        name: 'nama_user',
                        render: (data) => {
                            return `<b>${data}</b>`;
                        }
                    },
                ],
                createdRow: function(row, data, dataIndex) {
                    $(row).addClass('small-padding');
                }
            });
        }
    </script>
@endsection
