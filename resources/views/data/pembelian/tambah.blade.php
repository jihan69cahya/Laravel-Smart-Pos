@extends('layouts.main')
@section('title', 'Tambah Pembelian')
@section('title_page', 'Tambah Pembelian')
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Tambah Pembelian</h5>
                    <span>Masukkan data-data di bawah ini dengan benar.</span>
                </div>
                <div class="card-body">
                    <form id="formPembelian" action="javascript:void(0)" method="POST" onsubmit="return false;">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="tanggal" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" class="form-control" id="tanggal" name="tanggal"
                                    value="{{ date('Y-m-d') }}" required>
                                <small class="text-danger pl-1" id="error-tanggal"></small>
                            </div>
                            <div class="col-md-6">
                                <label for="no_faktur" class="form-label">Nomor Faktur <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="no_faktur" name="no_faktur"
                                    placeholder="Masukkan nomor faktur" required>
                                <small class="text-danger pl-1" id="error-no_faktur"></small>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="supplier" class="form-label">Supplier <span class="text-danger">*</span></label>
                                <select class="form-select select2 select2-not-modal" id="supplier" name="supplier"
                                    required>
                                    <option value="">-- Pilih Supplier --</option>
                                    @foreach ($data['supplier'] as $supplier)
                                        <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
                                    @endforeach
                                </select>
                                <small class="text-danger pl-1" id="error-supplier"></small>
                            </div>
                            <div class="col-md-6">
                                <label for="keterangan" class="form-label">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3"
                                    placeholder="Masukkan keterangan (opsional)"></textarea>
                                <small class="text-danger pl-1" id="error-keterangan"></small>
                            </div>
                        </div>

                        <hr class="my-4">

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#modal" onclick="aksi('tambah')">
                                    <i class="fa fa-plus"></i> Tambah Produk
                                </button>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="cariKodeBarang"
                                    placeholder="Cari kode barang...">
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
                                        <th width="15%">Total</th>
                                        <th width="10%">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="bodyTabelProduk">

                                </tbody>
                            </table>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="card border-success">
                                    <div class="card-header bg-primary text-white">
                                        <h6 class="mb-0">Ringkasan Pembayaran</h6>
                                    </div>
                                    <div class="card-body bg-light">
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <strong class="text-dark">Sub Total:</strong>
                                            </div>
                                            <div class="col-6 text-end">
                                                <span class="badge bg-secondary" id="sub_total">Rp. 0</span>
                                                <input type="hidden" name="sub_total" id="sub_total">
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <label for="pajak" class="form-label mb-0">Pajak (%):</label>
                                            </div>
                                            <div class="col-6">
                                                <input type="text"
                                                    class="form-control form-control-sm text-end number-only"
                                                    id="pajak" name="pajak" value="0" min="0"
                                                    step="0.01">
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <strong class="text-dark">Nilai Pajak:</strong>
                                            </div>
                                            <div class="col-6 text-end">
                                                <span class="badge bg-info" id="nilai_pajak">Rp. 0</span>
                                                <input type="hidden" name="nilai_pajak" id="nilai_pajak">
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <label for="potongan" class="form-label mb-0">Potongan:</label>
                                            </div>
                                            <div class="col-6">
                                                <input type="text"
                                                    class="form-control form-control-sm text-end maskRupiah"
                                                    id="potongan" name="potongan" value="0" min="0">
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6">
                                                <label for="biaya_tambahan" class="form-label mb-0">Biaya
                                                    Tambahan:</label>
                                            </div>
                                            <div class="col-6">
                                                <input type="text"
                                                    class="form-control form-control-sm text-end maskRupiah"
                                                    id="biaya_tambahan" name="biaya_tambahan" value="0"
                                                    min="0">
                                            </div>
                                        </div>
                                        <hr class="my-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <strong class="fs-5 text-success">Total Tagihan:</strong>
                                            </div>
                                            <div class="col-6 text-end">
                                                <span class="badge bg-success fs-6" id="total_tagihan">Rp. 0</span>
                                                <input type="hidden" name="total_tagihan" id="total_tagihan">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="button" id="btn_simpan_pembelian" class="btn btn-success"
                                    onclick="simpan_pembelian()">
                                    <i class="fa fa-save"></i> Simpan Pembelian
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

    @include('data.pembelian.form')
@endsection

@section('js')
    <script>
        let dataProduk = [];
        let editIndex = -1;
        let produk_ids = [];

        $(document).ready(function() {
            delete_error();

            $('#tabelProduk').DataTable({
                info: false,
                paging: false,
            });

            $('#pajak, #potongan, #biaya_tambahan').on('keyup', function(e) {
                hitungTotalTagihan();
            });

            $('#cariKodeBarang').on('keyup', function(e) {
                cariProdukByKode($(this).val());
            });
        });

        function aksi(id) {
            if (id == "tambah") {
                resetFormModal();
                get_produk_select(produk_ids);
                $("#btn_tambah").show();
                $("#btn_edit").hide();
                $("#title_modal").text("Tambah Produk");
                editIndex = -1;
            } else {
                $("#btn_tambah").hide();
                $("#btn_edit").show();
                $("#title_modal").text("Edit Produk");
            }
        }

        function resetFormModal() {
            $('#produk').val('').trigger('change');
            $('#satuan').val('');
            $('#harga').val('0');
            $('#jumlah').val('1');
            $('#total').val('0');
        }

        function get_produk_select(ids = [], id_produk = null, id_produk_edit = null) {
            $.ajax({
                url: "{{ route('get_produk_pembelian') }}",
                type: 'GET',
                dataType: 'json',
                data: {
                    ids: ids,
                    id_produk_edit: id_produk_edit
                },
                success: function(data) {
                    $('#produk').empty().append('<option></option>');

                    $.each(data, function(index, produk) {
                        $('#produk').append(
                            $('<option>', {
                                value: produk.id,
                                text: produk.kode + ' || ' + produk.nama,
                                'data-satuan': produk.rel_satuan.nama,
                                'data-nama': produk.nama,
                                'data-kode': produk.kode
                            })
                        );
                    });

                    if (id_produk) {
                        $("#produk").val(id_produk).change();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Terjadi kesalahan: ' + error);
                    swal("Error!", "Gagal memuat data produk", "error");
                }
            });
        }

        $('#produk').on('change', function() {
            let satuan = $(this).find('option:selected').data('satuan');
            $('#satuan').val(satuan);
        });

        $(document).on('keyup change blur', '#harga, #jumlah', function() {
            hitungTotalProduk();
        });

        function hitungTotalProduk() {
            let harga = parseFloat($('#harga').val().replace(/[^0-9]/g, '')) || 0;
            let jumlah = parseFloat($('#jumlah').val()) || 0;
            let total = harga * jumlah;
            $('#total').val(formatRupiah(total));
        }

        function tambahProduk() {
            let produk_id = $('#produk').val();
            let produk_nama = $('#produk option:selected').data('nama');
            let produk_kode = $('#produk option:selected').data('kode');
            let satuan = $('#satuan').val();
            let harga = parseFloat($('#harga').val().replace(/[^0-9]/g, '')) || 0;
            let jumlah = parseFloat($('#jumlah').val()) || 0;
            let total = harga * jumlah;

            if (!produk_id) {
                swal("Perhatian!", "Pilih produk terlebih dahulu", "warning");
                return;
            }
            if (jumlah <= 0) {
                swal("Perhatian!", "Jumlah harus lebih dari 0", "warning");
                return;
            }
            if (harga <= 0) {
                swal("Perhatian!", "Harga harus lebih dari 0", "warning");
                return;
            }

            let produkExists = dataProduk.some(item => item.produk_id == produk_id);
            if (produkExists) {
                swal("Perhatian!", "Produk sudah ada dalam daftar", "warning");
                return;
            }

            dataProduk.push({
                produk_id: produk_id,
                produk_nama: produk_nama,
                produk_kode: produk_kode,
                satuan: satuan,
                harga: harga,
                jumlah: jumlah,
                total: total
            });

            produk_ids.push(parseInt(produk_id));

            renderTabelProduk();

            hitungTotalTagihan();

            $('#modal').modal('hide');

            resetFormModal();

            swal("Berhasil!", "Produk berhasil ditambahkan", "success");
        }

        function editProdukFromTable(index) {
            editIndex = index;
            let item = dataProduk[index];

            aksi('edit');

            get_produk_select(produk_ids, item.produk_id, item.produk_id);

            setTimeout(() => {
                $('#produk').val(item.produk_id).trigger('change');
                $('#satuan').val(item.satuan);
                $('#harga').val(formatRupiah(item.harga));
                $('#jumlah').val(item.jumlah);
                $('#total').val(formatRupiah(item.total));
            }, 300);
        }

        function editProduk() {
            let produk_id = $('#produk').val();
            let produk_nama = $('#produk option:selected').data('nama');
            let produk_kode = $('#produk option:selected').data('kode');
            let satuan = $('#satuan').val();
            let harga = parseFloat($('#harga').val().replace(/[^0-9]/g, '')) || 0;
            let jumlah = parseFloat($('#jumlah').val()) || 0;
            let total = harga * jumlah;

            if (!produk_id) {
                swal("Perhatian!", "Pilih produk terlebih dahulu", "warning");
                return;
            }
            if (jumlah <= 0) {
                swal("Perhatian!", "Jumlah harus lebih dari 0", "warning");
                return;
            }
            if (harga <= 0) {
                swal("Perhatian!", "Harga harus lebih dari 0", "warning");
                return;
            }

            if (editIndex >= 0) {

                let old_produk_id = dataProduk[editIndex].produk_id;

                produk_ids = produk_ids.filter(id => id != old_produk_id);

                produk_ids.push(parseInt(produk_id));

                dataProduk[editIndex] = {
                    produk_id: produk_id,
                    produk_nama: produk_nama,
                    produk_kode: produk_kode,
                    satuan: satuan,
                    harga: harga,
                    jumlah: jumlah,
                    total: total
                };

                renderTabelProduk();
                hitungTotalTagihan();

                $('#modal').modal('hide');

                editIndex = -1;
                resetFormModal();

                swal("Berhasil!", "Produk berhasil diupdate", "success");
            }
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
                tbody.append(`
            <tr>
                <td class="text-center">${index + 1}</td>
                <td>${item.produk_nama}</td>
                <td>${item.satuan}</td>
                <td class="text-end">${formatRupiah(item.harga)}</td>
                <td class="text-center">${item.jumlah}</td>
                <td class="text-end">${formatRupiah(item.total)}</td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-warning" 
                            onclick="editProdukFromTable(${index})" 
                            data-bs-toggle="modal" data-bs-target="#modal">
                        <i class="fa fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-danger" 
                            onclick="hapusProduk(${index})">
                        <i class="fa fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);
            });
        }

        function editProdukFromTable(index) {
            editIndex = index;
            let item = dataProduk[index];

            aksi('edit');

            get_produk_select(produk_ids, item.produk_id, item.produk_id);

            setTimeout(() => {
                $('#produk').val(item.produk_id).trigger('change');
                $('#satuan').val(item.satuan);
                $('#harga').val(formatRupiah(item.harga));
                $('#jumlah').val(item.jumlah);
                $('#total').val(formatRupiah(item.total));
            }, 300);
        }

        function hapusProduk(index) {
            swal({
                title: "Apakah Anda yakin?",
                text: "Produk akan dihapus dari daftar",
                icon: "warning",
                buttons: ["Batal", "Ya, Hapus"],
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    let produk_id = dataProduk[index].produk_id;
                    produk_ids = produk_ids.filter(id => id != produk_id);

                    dataProduk.splice(index, 1);

                    renderTabelProduk();

                    hitungTotalTagihan();

                    swal("Berhasil!", "Produk berhasil dihapus", "success");
                }
            });
        }

        function hitungTotalTagihan() {
            let subTotal = dataProduk.reduce((sum, item) => sum + item.total, 0);

            let pajak = parseFloat($('#pajak').val()) || 0;
            let potongan = parseFloat($('#potongan').val().replace(/[^0-9]/g, '')) || 0;
            let biayaTambahan = parseFloat($('#biaya_tambahan').val().replace(/[^0-9]/g, '')) || 0;

            let nilaiPajak = (subTotal * pajak) / 100;

            let totalTagihan = subTotal + nilaiPajak - potongan + biayaTambahan;

            $('#sub_total').text(formatRupiah(subTotal));
            $('input[name="sub_total"]').val(subTotal);

            $('#nilai_pajak').text(formatRupiah(nilaiPajak));
            $('input[name="nilai_pajak"]').val(nilaiPajak);

            $('#total_tagihan').text(formatRupiah(totalTagihan));
            $('input[name="total_tagihan"]').val(totalTagihan);
        }

        function cariProdukByKode(kode) {
            $.ajax({
                url: "{{ route('get_produk_pembelian_kode') }}",
                type: 'GET',
                data: {
                    searching: kode
                },
                dataType: 'json',
                success: function(produk) {
                    if (produk && produk.id) {
                        let exists = produk_ids.includes(parseInt(produk.id));

                        if (exists) {
                            swal("Perhatian!", "Produk sudah ada dalam daftar", "warning");
                            $('#cariKodeBarang').val('').focus();
                            return;
                        }

                        $('#modal').modal('show');
                        aksi('tambah');

                        get_produk_select(produk_ids, produk.id);

                        setTimeout(() => {
                            $('#produk').val(produk.id).trigger('change');
                            $('#satuan').val(produk.rel_satuan.nama);
                            $('#harga').val(formatRupiah(produk.harga_beli || 0));
                            $('#jumlah').val(1).focus().select();
                            hitungTotalProduk();
                        }, 500);

                        $('#cariKodeBarang').val('');

                    }
                },
                error: function(xhr) {
                    if (xhr.status === 404) {
                        swal("Tidak Ditemukan!", "Kode barang tidak ditemukan", "warning");
                    } else {
                        swal("Error!", "Gagal mencari produk", "error");
                    }
                }
            });
        }

        function simpan_pembelian() {
            if (dataProduk.length === 0) {
                swal("Perhatian!", "Tambahkan minimal 1 produk", "warning");
                return;
            }

            var formData = new FormData(document.getElementById('formPembelian'));
            formData.append('produk_data', JSON.stringify(dataProduk));
            $.ajax({
                type: "POST",
                url: "{{ route('data.pembelian.store') }}",
                data: formData,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                dataType: "json",
                processData: false,
                contentType: false,
                beforeSend: function() {
                    $("#btn_simpan_pembelian").prop("disabled", true).html(
                        "<div class='spinner-border spinner-border-sm text-dark' role='status'></div>");
                },
                success: function(response) {
                    delete_error();
                    if (response.errors) {
                        Object.keys(response.errors).forEach(function(fieldName) {
                            $("#error-" + fieldName).show();
                            $("#error-" + fieldName).html(
                                response.errors[fieldName][0]
                            );
                        });
                    } else if (response.success) {
                        swal({
                            title: "Berhasil!",
                            text: response.success,
                            icon: "success",
                            button: false,
                            timer: 1000
                        });
                        window.location.href = "{{ route('data.pembelian.index') }}";
                    } else if (response.error) {
                        swal("Gagal!", response.error, "error");
                    }
                    $("#btn_simpan_pembelian").prop("disabled", false).html(
                        '<i class="fa fa-save"></i> Simpan Pembelian');
                },
                error: function(xhr, status, error) {
                    $("#btn_simpan_pembelian").prop("disabled", false).html(
                        '<i class="fa fa-save"></i> Simpan Pembelian');
                    swal("Gagal!", "Terjadi kesalahan, coba lagi nanti", "error");
                },
            });
        }
    </script>
@endsection
