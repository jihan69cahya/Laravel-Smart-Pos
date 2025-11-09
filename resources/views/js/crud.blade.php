<script>
    function tambah_data() {
        var formData = new FormData(document.getElementById('formData'));
        $.ajax({
            type: "POST",
            url: url_tambah,
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: "json",
            processData: false,
            contentType: false,
            beforeSend: function() {
                $("#btn_tambah").prop("disabled", true).html(
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
                    $("#modal").modal("hide");
                    swal("Berhasil!", response.success, "success");
                    get_data();
                } else if (response.error) {
                    toastr.error(response.error);
                    swal("Gagal!", response.error, "error");
                }
                $("#btn_tambah").prop("disabled", false).text("Simpan");
            },
            error: function(xhr, status, error) {
                $("#btn_tambah").prop("disabled", false).text("Simpan");
                swal("Gagal!", "Terjadi kesalahan, coba lagi nanti", "error");
            },
        });
    }

    function edit_data() {
        var formData = new FormData(document.getElementById('formData'));
        var id = $('#id').val();
        $.ajax({
            type: "POST",
            url: url_edit.replace(':id', id),
            data: formData,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            dataType: "json",
            processData: false,
            contentType: false,
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-HTTP-Method-Override', 'PUT');
                $("#btn_edit").prop("disabled", true).html(
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
                    $("#modal").modal("hide");
                    swal("Berhasil!", response.success, "success");
                    get_data();
                } else if (response.error) {
                    swal("Gagal!", response.error, "error");
                }
                $("#btn_edit").prop("disabled", false).text("Perbarui");
            },
            error: function(xhr, status, error) {
                $("#btn_edit").prop("disabled", false).text("Perbarui");
                swal("Gagal!", "Terjadi kesalahan, coba lagi nanti", "error");
            },
        });
    }

    function hapus_data(id) {
        swal({
            title: "Apakah Anda yakin?",
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: "warning",
            buttons: ["Batal", "Ya, hapus!"],
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    type: "DELETE",
                    url: url_hapus + "/" + id,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.success) {
                            swal("Berhasil!", response.success, "success");
                            get_data();
                        } else if (response.error) {
                            swal("Gagal!", response.error, "error");
                        }
                    },
                    error: function() {
                        swal("Gagal!", "Terjadi kesalahan, coba lagi nanti", "error");
                    },
                });
            }
        });
    }
</script>
