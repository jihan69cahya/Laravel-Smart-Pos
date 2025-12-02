@extends('layouts.main')
@section('title', 'Detail Pembelian')
@section('title_page', 'Detail Pembelian')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Detail Pembelian #{{ $data['pembelian']['no_faktur'] }}</h5>
                </div>

                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <small class="text-muted">Tanggal Pembelian</small>
                            <p class="mb-0">{{ \App\Helpers\Date::format($data['pembelian']['tanggal'], 1) }}</p>
                        </div>

                        <div class="col-md-3">
                            <small class="text-muted">Nomor Faktur</small>
                            <p class="mb-0">{{ $data['pembelian']['no_faktur'] }}</p>
                        </div>

                        <div class="col-md-3">
                            <small class="text-muted">Supplier</small>
                            <p class="mb-0">{{ $data['pembelian']['relSupplier']['nama'] }}</p>
                        </div>

                        <div class="col-md-3">
                            <small class="text-muted">Keterangan</small>
                            <p class="mb-0">{{ $data['pembelian']['keterangan'] ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-3">
                            <small class="text-muted">Status Penerimaan</small><br>
                            {!! \App\Helpers\Helper::statusPenerimaanBadge($data['pembelian']['status_penerimaan']) !!}
                        </div>

                        <div class="col-md-3">
                            <small class="text-muted">Tanggal Penerimaan</small>
                            <p class="mb-0">
                                {{ $data['pembelian']['tanggal_penerimaan'] ? \App\Helpers\Date::format($data['pembelian']['tanggal_penerimaan'], 1) : '-' }}
                            </p>
                        </div>
                    </div>

                    <div class="table-responsive mb-4">
                        <table class="table table-bordered" id="tabelProduk">
                            <thead class="table-light">
                                <tr>
                                    <th width="5%">No</th>
                                    <th width="30%">Nama Produk</th>
                                    <th width="15%">Satuan</th>
                                    <th width="15%">Harga</th>
                                    <th width="10%">Jumlah</th>
                                    <th width="10%">Jumlah Diterima</th>
                                    <th width="15%">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data['pembelian']['relDetail'] as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item['relProduk']['nama'] }}</td>
                                        <td>{{ $item['relProduk']['relSatuan']['nama'] }}</td>
                                        <td>{{ \App\Helpers\Money::stringToRupiah($item['harga']) }}</td>
                                        <td>{{ \App\Helpers\Money::formatNumber($item['jumlah']) }}</td>
                                        <td>{{ \App\Helpers\Money::formatNumber($item['jumlah_terima']) }}</td>
                                        <td>{{ \App\Helpers\Money::stringToRupiah($item['total']) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="row">
                        <div class="col-md-6 ms-auto">
                            <table class="table table-sm">
                                <tr>
                                    <td>Sub Total</td>
                                    <td class="text-end">
                                        {{ \App\Helpers\Money::stringToRupiah($data['pembelian']['sub_total']) }}</td>
                                </tr>
                                <tr>
                                    <td>Pajak ({{ $data['pembelian']['pajak'] }}%)</td>
                                    <td class="text-end">
                                        {{ \App\Helpers\Money::stringToRupiah($data['pembelian']['nilai_pajak']) }}</td>
                                </tr>
                                <tr>
                                    <td>Potongan</td>
                                    <td class="text-end">
                                        {{ \App\Helpers\Money::stringToRupiah($data['pembelian']['potongan']) }}</td>
                                </tr>
                                <tr>
                                    <td>Biaya Tambahan</td>
                                    <td class="text-end">
                                        {{ \App\Helpers\Money::stringToRupiah($data['pembelian']['biaya_tambahan']) }}</td>
                                </tr>
                                <tr class="table-light">
                                    <td><strong>Total Tagihan</strong></td>
                                    <td class="text-end">
                                        <strong>{{ \App\Helpers\Money::stringToRupiah($data['pembelian']['total_tagihan']) }}</strong>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <a href="{{ route('data.pembelian.index') }}" class="btn btn-dark">
                                <i class="fa fa-arrow-left"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $('#tabelProduk').DataTable({
            paging: false,
            searching: false,
            info: false
        });
    </script>
@endsection
