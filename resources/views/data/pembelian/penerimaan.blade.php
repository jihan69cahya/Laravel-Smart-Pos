@extends('layouts.main')
@section('title', 'Penerimaan Barang Pembelian')
@section('title_page', 'Penerimaan Barang Pembelian')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Penerimaan Pembelian #{{ $data['pembelian']['no_faktur'] }}</h5>
                    <span>Masukkan data-data di bawah ini dengan benar.</span>
                </div>

                <div class="card-body">
                    <form id="formPembelian" action="javascript:void(0)" method="POST" onsubmit="return false;">
                        <input type="hidden" name="id_pembelian" id="id_pembelian" value="{{ $data['id'] }}">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal"
                                    value="{{ $data['pembelian']['tanggal'] }}" readonly>
                            </div>
                            <div class="col-md-6">
                                <label for="no_faktur" class="form-label">Nomor Faktur <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="no_faktur" name="no_faktur"
                                    placeholder="Masukkan nomor faktur" value="{{ $data['pembelian']['no_faktur'] }}"
                                    readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="supplier" class="form-label">Supplier <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="supplier" name="supplier"
                                    placeholder="Masukkan nomor faktur"
                                    value="{{ $data['pembelian']['relSupplier']['nama'] }}" readonly>
                                <small class="text-danger pl-1" id="error-supplier"></small>
                            </div>
                            <div class="col-md-6">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3" readonly>{{ $data['pembelian']['keterangan'] ?? '-' }}</textarea>
                                <small class="text-danger pl-1" id="error-keterangan"></small>
                            </div>
                        </div>

                        <hr class="my-4">
                        <h4>Detail Produk</h4>
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered" id="tabelProduk">
                                <thead class="table-light">
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="30%">Nama Produk</th>
                                        <th width="15%">Satuan</th>
                                        <th width="15%">Harga</th>
                                        <th width="10%">Jumlah</th>
                                        <th width="15%">Jumlah Terima</th>
                                        <th width="10%">Sisa</th>
                                    </tr>
                                </thead>
                                <tbody id="bodyTabelProduk">

                                </tbody>
                            </table>
                        </div>
                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="button" id="btn_simpan_penerimaan" class="btn btn-success"
                                    onclick="simpan_penerimaan()">
                                    <i class="fa fa-save"></i> Simpan Penerimaan
                                </button>
                                <a href="{{ route('data.pembelian.index') }}" class="btn btn-secondary">
                                    <i class="fa fa-arrow-left"></i> Batal
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        let dataProduk = [];
        let id_pembelian = "{{ $data['id'] }}";

        $(document).ready(function() {
            $('#tabelProduk').DataTable({
                info: false,
                paging: false,
            });

            get_data_edit();
        });

        function get_data_edit() {
            $.ajax({
                url: "{{ route('get_detail_pembelian') }}",
                type: 'GET',
                dataType: 'json',
                data: {
                    id_pembelian: id_pembelian,
                },
                success: function(data) {
                    data.forEach(item => {
                        let produk = item.rel_produk;

                        dataProduk.push({
                            id: item.id,
                            produk_id: produk.id,
                            produk_nama: produk.nama,
                            produk_kode: produk.kode,
                            satuan: produk.rel_satuan ? produk.rel_satuan.nama : '',
                            harga: item.harga,
                            jumlah: item.jumlah,
                            jumlah_terima: item.jumlah_terima,
                            id_log_stok: item.id_log_stok ?? null,
                            total: item.total
                        });
                    });

                    renderTabelProduk();
                },
                error: function(xhr, status, error) {
                    console.error('Terjadi kesalahan: ' + error);
                    swal("Error!", "Gagal memuat data produk", "error");
                }
            });
        }

        function renderTabelProduk() {
            let tbody = $('#bodyTabelProduk');
            tbody.empty();

            if (dataProduk.length === 0) {
                tbody.append(`
                    <tr>
                        <td colspan="7" class="text-center text-muted">Belum ada produk ditambahkan</td>
                    </tr>
                `);
                return;
            }

            dataProduk.forEach((item, index) => {
                let sisa = item.jumlah - item.jumlah_terima;
                tbody.append(`
                    <tr>
                        <td class="text-center">${index + 1}</td>
                        <td>${item.produk_nama}</td>
                        <td>${item.satuan}</td>
                        <td class="text-end">${formatRupiah(item.harga)}</td>
                        <td class="text-center">${item.jumlah}</td>
                        <td class="text-end">
                        <input type="text"
                            id="jumlah_terima_${item.id}"
                            name="jumlah_terima[${item.id}]"
                            class="form-control form-control-sm number-only"
                            data-index="${index}"
                            data-id="${item.id}"
                            max="${sisa}"
                            min="0"
                            placeholder="Max. ${sisa}">
                        </td>
                        <td class="text-center">${formatNumber(sisa)}</td>
                    </tr>
                `);
            });
        }

        function simpan_penerimaan() {
            let formData = new FormData();

            formData.append('id_pembelian', id_pembelian);

            let isNull = true;
            let isValid = true;
            let detail = [];
            let errorNullProducts = [];
            let errorMaxProducts = [];

            dataProduk.forEach(item => {
                let input = $(`#jumlah_terima_${item.id}`);
                let val = parseInt(input.val()) || 0;
                let max = parseInt(input.attr("max"));

                input.removeClass("is-invalid");

                if (max > 0 && val < 1) {
                    isNull = false;
                    input.addClass("is-invalid");
                    errorNullProducts.push(item.produk_nama);
                }

                if (val > max) {
                    isValid = false;
                    input.addClass("is-invalid");
                    errorMaxProducts.push(`${item.produk_nama} (max ${max})`);
                }

                detail.push({
                    id: item.id,
                    id_log_stok: item.id_log_stok,
                    produk_id: item.produk_id,
                    jumlah_terima: val
                });
            });

            if (!isNull) {
                swal("Peringatan!",
                    "Jumlah terima harus diisi untuk produk:\n\n• " + errorNullProducts.join("\n• "),
                    "warning"
                );
                return;
            }

            if (!isValid) {
                swal("Peringatan!",
                    "Jumlah terima melebihi batas maksimal untuk:\n\n• " + errorMaxProducts.join("\n• "),
                    "warning"
                );
                return;
            }

            formData.append("detail", JSON.stringify(detail));

            $.ajax({
                url: "{{ route('data.pembelian.simpan_penerimaan') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                beforeSend: function() {
                    $('#btn_simpan_penerimaan').prop('disabled', true).html(
                        '<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
                },
                success: function(response) {
                    $('#btn_simpan_penerimaan').prop('disabled', false).html(
                        '<i class="fa fa-save"></i> Simpan Penerimaan');
                    if (response.success) {
                        swal({
                            title: "Berhasil!",
                            text: response.success,
                            icon: "success"
                        }).then(() => {
                            window.location.href = "{{ route('data.pembelian.index') }}";
                        });
                    } else if (response.error) {
                        swal("Gagal!", response.error, "error");
                    }
                },
                error: function(xhr) {
                    $('#btn_simpan_penerimaan').prop('disabled', false).html(
                        '<i class="fa fa-save"></i> Simpan Penerimaan');
                    swal("Gagal!", "Terjadi kesalahan, coba lagi nanti", "error");
                }
            });
        }
    </script>
@endsection
