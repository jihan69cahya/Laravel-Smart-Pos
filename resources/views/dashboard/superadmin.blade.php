@extends('layouts.main')
@section('title', 'Dashboard')
@section('title_page', 'Dashboard')
@section('content')
    <div class="row">
        <!-- Total Produk -->
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-shrink-0">
                            <div class="bg-light-primary text-primary rounded p-3">
                                <i class="icofont icofont-box fs-3"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 text-muted">Total Produk</h6>
                            <h3 class="mb-0">245</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Member -->
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-shrink-0">
                            <div class="bg-light-success text-success rounded p-3">
                                <i class="icofont icofont-users-alt-5 fs-3"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 text-muted">Total Member</h6>
                            <h3 class="mb-0">1,234</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Pembelian Hari Ini -->
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-shrink-0">
                            <div class="bg-light-warning text-warning rounded p-3">
                                <i class="icofont icofont-cart fs-3"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 text-muted">Pembelian Hari Ini</h6>
                            <h3 class="mb-0">Rp 5.400.000</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Penjualan Hari Ini -->
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="flex-shrink-0">
                            <div class="bg-light-danger text-danger rounded p-3">
                                <i class="icofont icofont-money fs-3"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-1 text-muted">Penjualan Hari Ini</h6>
                            <h3 class="mb-0">Rp 8.750.000</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Tabel Barang Terlaris -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>Barang Terlaris</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>Kategori</th>
                                    <th class="text-end">Terjual</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Minuman Soda 330ml</td>
                                    <td><span class="badge badge-success">Minuman</span></td>
                                    <td class="text-end"><strong>320 unit</strong></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Roti Tawar</td>
                                    <td><span class="badge badge-warning">Makanan</span></td>
                                    <td class="text-end"><strong>210 unit</strong></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Snack Coklat</td>
                                    <td><span class="badge badge-info">Snack</span></td>
                                    <td class="text-end"><strong>180 unit</strong></td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Kopi Instan 20g</td>
                                    <td><span class="badge badge-dark">Minuman</span></td>
                                    <td class="text-end"><strong>150 unit</strong></td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>Air Mineral 600ml</td>
                                    <td><span class="badge badge-primary">Minuman</span></td>
                                    <td class="text-end"><strong>270 unit</strong></td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>Permen Karet</td>
                                    <td><span class="badge badge-secondary">Snack</span></td>
                                    <td class="text-end"><strong>200 unit</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Pelanggan Terbanyak -->
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header pb-0">
                    <h5>Pembelian Pelanggan Terbanyak</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pelanggan</th>
                                    <th class="text-end">Total Transaksi</th>
                                    <th class="text-end">Total Belanja</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-title rounded-circle bg-primary">JD</span>
                                            </div>
                                            John Doe
                                        </div>
                                    </td>
                                    <td class="text-end">24x</td>
                                    <td class="text-end"><strong>Rp 12.500.000</strong></td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-title rounded-circle bg-success">JS</span>
                                            </div>
                                            Jane Smith
                                        </div>
                                    </td>
                                    <td class="text-end">21x</td>
                                    <td class="text-end"><strong>Rp 10.800.000</strong></td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-title rounded-circle bg-warning">RJ</span>
                                            </div>
                                            Robert Johnson
                                        </div>
                                    </td>
                                    <td class="text-end">19x</td>
                                    <td class="text-end"><strong>Rp 9.750.000</strong></td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-title rounded-circle bg-danger">MW</span>
                                            </div>
                                            Maria Williams
                                        </div>
                                    </td>
                                    <td class="text-end">17x</td>
                                    <td class="text-end"><strong>Rp 8.900.000</strong></td>
                                </tr>
                                <tr>
                                    <td>5</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-title rounded-circle bg-info">MB</span>
                                            </div>
                                            Michael Brown
                                        </div>
                                    </td>
                                    <td class="text-end">15x</td>
                                    <td class="text-end"><strong>Rp 7.650.000</strong></td>
                                </tr>
                                <tr>
                                    <td>6</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-title rounded-circle bg-secondary">ED</span>
                                            </div>
                                            Emma Davis
                                        </div>
                                    </td>
                                    <td class="text-end">14x</td>
                                    <td class="text-end"><strong>Rp 7.200.000</strong></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
