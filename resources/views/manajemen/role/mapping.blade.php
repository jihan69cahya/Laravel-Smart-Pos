@extends('layouts.main')
@section('title', 'Mapping Menu')
@section('title_page', 'Mapping Menu')

@section('css')
    <style>
        .table-hover tbody tr:hover {
            background-color: rgba(var(--theme-default), 0.05);
        }

        .form-check-input {
            cursor: pointer;
            width: 1.2em;
            height: 1.2em;
            border: 2px solid #198754;
        }

        .form-check-input:checked {
            background-color: #198754 !important;
            border-color: #198754 !important;
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 0.2rem rgba(25, 135, 84, 0.25);
        }

        .form-check-label {
            cursor: pointer;
        }

        .badge {
            font-size: 0.875rem;
            padding: 0.35em 0.65em;
        }

        .btn:disabled {
            cursor: not-allowed;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Daftar Menu</h5>
                    <span>Anda dapat memapping menu yang sesuai dengan role <b>{{ $role }}</b>.</span>
                </div>
                <div class="card-body">
                    <form id="formMapping">
                        @csrf

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead class="table-primary">
                                    <tr>
                                        <th width="5%" class="text-center">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="checkAll">
                                                <label class="form-check-label" for="checkAll"></label>
                                            </div>
                                        </th>
                                        <th width="5%">No</th>
                                        <th width="30%">Nama Menu</th>
                                        <th width="35%">Route</th>
                                        <th width="15%">Icon</th>
                                        <th width="10%">Parent</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($menu as $index => $item)
                                        <tr>
                                            <td class="text-center">
                                                <div class="form-check">
                                                    <input class="form-check-input menu-checkbox" type="checkbox"
                                                        name="menu_ids[]" value="{{ $item->id }}"
                                                        id="menu{{ $item->id }}"
                                                        {{ in_array($item->id, $mapping) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="menu{{ $item->id }}"></label>
                                                </div>
                                            </td>
                                            <td>{{ $index + 1 }}</td>
                                            <td>
                                                <strong>{{ $item->nama }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge badge-light-info">
                                                    {{ $item->route ?? '-' }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($item->icon)
                                                    <i class="{{ $item->icon }} me-2"></i>
                                                    <span class="text-muted">{{ $item->icon }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($item->id_parent)
                                                    {{ $item->relParent->nama }}
                                                @else
                                                    <b>Parent</b>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                <div class="py-4">
                                                    <i class="fa fa-inbox fa-3x text-muted mb-3"></i>
                                                    <p class="text-muted">Tidak ada data menu</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if ($menu->count() > 0)
                            <div class="mt-4 d-flex justify-content-between align-items-center">
                                <a href="javascript:history.back()" class="btn btn-light">
                                    <i class="fa fa-arrow-left me-2"></i>Kembali
                                </a>
                                <button type="submit" class="btn btn-primary" id="btnSimpan">
                                    <i class="fa fa-save me-2"></i>Simpan Mapping
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('#checkAll').on('change', function() {
                $('.menu-checkbox').prop('checked', $(this).is(':checked'));
            });

            $('.menu-checkbox').on('change', function() {
                const totalCheckboxes = $('.menu-checkbox').length;
                const checkedCheckboxes = $('.menu-checkbox:checked').length;
                $('#checkAll').prop('checked', totalCheckboxes === checkedCheckboxes);
            });

            const totalCheckboxes = $('.menu-checkbox').length;
            const checkedCheckboxes = $('.menu-checkbox:checked').length;
            $('#checkAll').prop('checked', totalCheckboxes === checkedCheckboxes);

            $('#formMapping').on('submit', function(e) {
                e.preventDefault();

                const btn = $('#btnSimpan');
                const btnText = btn.html();

                btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i>Menyimpan...');

                const formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('manajemen.role.mapping.save', encrypt($id)) }}",
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        swal("Berhasil!", response.success, "success")
                            .then(() => {
                                window.location.href =
                                    "{{ route('manajemen.role.index') }}";
                            });
                    },
                    error: function(xhr) {
                        btn.prop('disabled', false).html(btnText);

                        let errorMessage = 'Terjadi kesalahan saat menyimpan data!';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }

                        swal("Gagal!", errorMessage, "error");
                    }
                });
            });
        });
    </script>
@endsection
