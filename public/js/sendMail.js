$(document).ready(() => {
    $("body").on("click", "#re-send-mail", function (event) {
        event.preventDefault();

        Swal.fire({
            title: "Are you sure want to resent email?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, resent it!"
        }).then((result) => {
            if (result.isConfirmed) {
                const value = $(this).attr("status-id");
                let token = $("meta[name='csrf-token']").attr("content");
                Swal.fire({
                    title: "Sending emails",
                    text: "This will close if the email has been sent.",
                    allowEscapeKey: false,
                    allowOutsideClick: false,
                    timerProgressBar: true,
                    didOpen: () => {
                        $('#re-send-mail').hide();
                        Swal.showLoading();
                        console.log(value);
                        $.ajax({
                                headers: {
                                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                                        "content"
                                    ),
                                },
                                accepts: {
                                    mycustomtype: "application/x-some-custom-type",
                                },
                                url: `/admin/bills/status/${value}`,
                                type: "PATCH",
                                cache: false,
                                data: {
                                    id: value,
                                    _token: token,
                                },
                            })
                            .then((res) => {

                                console.log(res);

                                if (res.code == 200) {

                                    $("#status-invoice").removeClass("badge-danger").addClass("badge-success").html('success');
                                    Swal.fire({
                                        position: "top-end",
                                        icon: "success",
                                        title: res.msg,
                                        showConfirmButton: false,
                                        timer: 1500
                                    });

                                } else if (res.code == 408) {
                                    Swal.fire({
                                        title: "The Internet?",
                                        text: "That thing is still around?",
                                        icon: "question"
                                    });

                                    $('#re-send-mail').show();

                                } else {
                                    Swal.fire({
                                        icon: "error",
                                        title: res.code,
                                        text: res.msg,
                                    });

                                    $('#re-send-mail').show();
                                }

                            })
                            .catch((err) => {
                                console.log(err);
                                Swal.fire({
                                    icon: "error",
                                    title: "Oops...",
                                    text: err.msg,
                                    text: 'Make sure your internet remains stable!',
                                    // footer: '<a href="">Why do I have this issue?</a>',
                                });

                                $('#re-send-mail').show();

                            });
                    },
                })
            }
        })
    })
})
