$(document).ready(function () {
    $("body").on("click", "#delete-student", function (event) {
        event.preventDefault();
        const value = $(this).attr("data-id");
        const name = $(this).attr("data-name");
        let token = $("meta[name='csrf-token']").attr("content");
        Swal.fire({
            title: "Are you sure want to inactive " + name + "?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, inactive!",
        }).then((result) => {
            if (result.isConfirmed) {
                console.log(value);
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                        "Access-Control-Allow-Origin": "*",
                    },
                    accepts: {
                        mycustomtype: "application/x-some-custom-type",
                        "Access-Control-Allow-Origin": "*",
                    },
                    url: `/admin/student/${value}`,
                    type: "PATCH",
                    cache: false,
                    data: {
                        id: value,
                        _token: token,
                    },
                })
                    .then((res) => {
                        console.log(res);
                        Swal.fire(
                            "Inactive!",
                            `${name} has been inactive.`,
                            "success"
                        );

                        //   setTimeout(() => {
                        //       window.location.href = '/admin/list'
                        //   }, 1500)

                        $(`#index_student_${value}`).remove();
                    })
                    .catch((err) => {
                        console.log(err);
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Something went wrong!",
                            // text: err,
                            footer: '<a href="">Why do I have this issue?</a>',
                        });
                    });
            }
        });
    });

    $("body").on("click", "#delete-user", function (event) {
        event.preventDefault();
        const value = $(this).attr("data-id");
        const name = $(this).attr("data-name");
        let token = $("meta[name='csrf-token']").attr("content");
        Swal.fire({
            title: "Are you sure want to delete " + name + "?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                console.log("masuk");
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    accepts: {
                        mycustomtype: "application/x-some-custom-type",
                    },
                    url: `/admin/user/${value}`,
                    type: "delete",
                    cache: false,
                    data: {
                        id: value,
                        _token: token,
                    },
                })
                    .then((res) => {
                        console.log(res);
                        Swal.fire(
                            "Deleted!",
                            `${name} has been deleted.`,
                            "success"
                        );

                        //   setTimeout(() => {
                        //       window.location.href = '/admin/user'
                        //   }, 1500)

                        $(`#index_user_${value}`).remove();
                    })
                    .catch((err) => {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Something went wrong!",
                            footer: '<a href="">Why do I have this issue?</a>',
                        });
                    });
            }
        });
    });

    $("body").on("click", "#delete-teacher", function (event) {
        event.preventDefault();
        const value = $(this).attr("data-id");
        const name = $(this).attr("data-name");
        let token = $("meta[name='csrf-token']").attr("content");
        Swal.fire({
            title: "Are you sure want to inactive " + name + "?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, inactive!",
        }).then((result) => {
            if (result.isConfirmed) {
                console.log("masuk");
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    accepts: {
                        mycustomtype: "application/x-some-custom-type",
                    },
                    url: `/admin/teachers/deactivated/${value}`,
                    type: "put",
                    cache: false,
                    data: {
                        id: value,
                        _token: token,
                    },
                })
                    .then((res) => {
                        console.log(res);
                        Swal.fire(
                            "Inactive!",
                            `${name} has been inactive.`,
                            "success"
                        );

                        //   setTimeout(() => {
                        //       window.location.href = '/admin/user'
                        //   }, 1500)

                        $(`#index_teacher_${value}`).remove();
                    })
                    .catch((err) => {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Something went wrong!",
                            footer: '<a href="">Why do I have this issue?</a>',
                        });
                    });
            }
        });
    });


    $("body").on("click", "#active-teacher", function (event) {
        event.preventDefault();
        const value = $(this).attr("data-id");
        const name = $(this).attr("data-name");
        let token = $("meta[name='csrf-token']").attr("content");
        Swal.fire({
            title: "Are you sure want to activate " + name + "?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, inactive!",
        }).then((result) => {
            if (result.isConfirmed) {
                console.log("masuk");
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    accepts: {
                        mycustomtype: "application/x-some-custom-type",
                    },
                    url: `/admin/teachers/activated/${value}`,
                    type: "put",
                    cache: false,
                    data: {
                        id: value,
                        _token: token,
                    },
                })
                    .then((res) => {
                        console.log(res);
                        Swal.fire(
                            "Activate!",
                            `${name} has been activated.`,
                            "success"
                        );

                        //   setTimeout(() => {
                        //       window.location.href = '/admin/user'
                        //   }, 1500)

                        $(`#index_teacher_${value}`).remove();
                    })
                    .catch((err) => {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Something went wrong!",
                            footer: '<a href="">Why do I have this issue?</a>',
                        });
                    });
            }
        });
    });

    $("body").on("click", "#delete-payment", function (event) {
        event.preventDefault();
        const value = $(this).attr("data-id");
        const type = $(this).attr("data-type");
        let token = $("meta[name='csrf-token']").attr("content");
        Swal.fire({
            title: "Are you sure want to delete payment " + type + "?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
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
                    url: `/admin/payment-grades/${value}`,
                    type: "delete",
                    cache: false,
                    data: {
                        id: value,
                        _token: token,
                    },
                })
                    .then((res) => {
                        console.log(res);
                        Swal.fire(
                            "Deleted!",
                            `${type} has been deleted.`,
                            "success"
                        );

                        //   setTimeout(() => {
                        //       window.location.href = '/admin/user'
                        //   }, 1500)

                        $(`#index_payment_${value}`).remove();
                    })
                    .catch((err) => {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Something went wrong!",
                            footer: '<a href="">Why do I have this issue?</a>',
                        });
                    });
            }
        });
    });

    $("body").on("click", "#delete-book", function (event) {
        event.preventDefault();
        const value = $(this).attr("data-id");
        const name = $(this).attr("data-name");
        let token = $("meta[name='csrf-token']").attr("content");
        Swal.fire({
            title: "Are you sure want to delete book " + name + "?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
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
                    url: `/admin/books/${value}`,
                    type: "delete",
                    cache: false,
                    data: {
                        id: value,
                        _token: token,
                    },
                })
                    .then((res) => {
                        console.log(res);
                        Swal.fire(
                            "Deleted!",
                            `Books ${name} has been deleted.`,
                            "success"
                        );

                        setTimeout(() => {
                            window.location.href = "/admin/books";
                        }, 2500);

                        // $(`#index_payment_${value}`).remove();
                    })
                    .catch((err) => {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Something went wrong!",
                            footer: '<a href="">Why do I have this issue?</a>',
                        });
                    });
            }
        });
    });

    $("body").on("click", "#update-status", function (event) {
        event.preventDefault();
        const value = $(this).attr("data-id");
        const name = $(this).attr("data-name");
        const subject = $(this).attr("data-subject");
        let token = $("meta[name='csrf-token']").attr("content");
        Swal.fire({
            title:
                "Are you sure want to label paid " +
                subject +
                " from " +
                name +
                "?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, update it!",
        }).then((result) => {
            if (result.isConfirmed) {
                
                $(`#changes-paket`).remove();
                $(`#paket-installment`).remove();
                $(`#update-status`).remove();
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
                    url: `/admin/bills/update-paid/${value}`,
                    type: "PATCH",
                    cache: false,
                    data: {
                        id: value,
                        _token: token,
                    },
                })
                    .then((res) => {

                        console.log(res);

                        if(!res.success)
                        {
                            
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: res.text,
                                footer: '<a href="">Why do I have this issue?</a>',
                            });
                        } else {
                            
                            Swal.fire(
                                "Updated!",
                                `Payment 2${subject} from ${name} has been successfully.`,
                                "success"
                            );
    
    
                            setTimeout(() => {
                                window.location.href = `/admin/bills/detail-payment/${value}`;
                            }, 2500);
                        }
                    })
                    .catch(err => {
                        console.log(err);
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Something went wrong!",
                            footer: '<a href="">Why do I have this issue?</a>',
                        });
                    });
            }
        });
    });

    $("body").on("click", "#update-status-book", function (event) {
        event.preventDefault();
        const value = $(this).attr("data-id");
        const name = $(this).attr("data-name");
        const studentId = $(this).attr("data-student-id");
        let token = $("meta[name='csrf-token']").attr("content");
        Swal.fire({
            title:
                "Are you sure want to label paid for invoice #" +
                value +
                " from " +
                name +
                "?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, update it!",
        }).then((result) => {
            if (result.isConfirmed) {
                $(`#update-status-book`).remove();
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    accepts: {
                        mycustomtype: "application/x-some-custom-type",
                    },
                    url: `/admin/bills/update-paid/${value}/${studentId}`,
                    type: "PATCH",
                    cache: false,
                    data: {
                        id: value,
                        _token: token,
                    },
                })
                    .then((res) => {

                        if(res.success) {
                            Swal.fire(
                                "Updated!",
                                `Paid invoice #${value} from ${name} has been successfully.`,
                                "success"
                            );
    
                            setTimeout(() => {
                                window.location.href = `/admin/bills/detail-payment/${value}`;
                            }, 2500);
    
                        } else {
                            
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: "Something went wrong!",
                                footer: '<a href="">Why do I have this issue?</a>',
                            });
                        }

                        console.log(res)
                        
                    })
                    .catch((err) => {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Something went wrong!",
                            footer: '<a href="">Why do I have this issue?</a>',
                        });
                    });
            }
        });
    });

    $("body").on("click", "#active-student", function (event) {
        event.preventDefault();
        const value = $(this).attr("data-id");
        const name = $(this).attr("data-name");
        let token = $("meta[name='csrf-token']").attr("content");
        Swal.fire({
            title: "Are you sure want to activate " + name + "?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, activate it !",
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    accepts: {
                        mycustomtype: "application/x-some-custom-type",
                    },
                    url: `/admin/student/activate/${value}`,
                    type: "PATCH",
                    cache: false,
                    data: {
                        id: value,
                        _token: token,
                    },
                })
                    .then((res) => {
                        console.log(res);

                        if(!res.success)
                        {
                            Swal.fire({
                                icon: "error",
                                title: "Oops...",
                                text: res.text,
                                footer: '<a href="">Why do I have this issue?</a>',
                            });
                        } else {
                            
                            Swal.fire(
                                "Activate!",
                                `${name} has been activate.`,
                                "success"
                            );
    
                            $(`#index_student_${value}`).remove();
                        }

                    })
                    .catch((err) => {
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Something went wrong!",
                            footer: '<a href="">Why do I have this issue?</a>',
                        });
                    });
            }
        });
    });
});
