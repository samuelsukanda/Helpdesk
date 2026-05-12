$(document).ready(function () {
    // Row click handler untuk navigasi ke edit
    $(".user-row").on("click", function (e) {
        if (!$(e.target).closest("a, button, form").length) {
            window.location.href = $(this).data("href");
        }
    });

    // SweetAlert2 Delete Handler
    $(".btn-delete").on("click", function (e) {
        e.preventDefault();
        e.stopPropagation();

        const btn = $(this);
        const userName = btn.data("user-name");
        const userEmail = btn.data("user-email");
        const deleteUrl = btn.data("delete-url");

        Swal.fire({
            title: "Hapus Pengguna?",
            html: `
                <div class="text-center">
                    <p class="text-gray-600 mb-2">Anda akan menghapus pengguna:</p>
                    <p class="font-bold text-gray-800 text-lg mb-1">${userName}</p>
                    <p class="text-gray-400 text-sm font-mono">${userEmail}</p>
                    <p class="text-red-500 text-xs mt-3"><i class="fas fa-exclamation-triangle mr-1"></i>Tindakan ini tidak dapat dibatalkan!</p>
                </div>
            `,
            icon: "warning",
            iconColor: "#f59e0b",
            showCancelButton: true,
            confirmButtonText: "Ya, Hapus",
            cancelButtonText: "Batal",
            confirmButtonColor: "#dc2626",
            cancelButtonColor: "#6b7280",
            reverseButtons: true,
            focusCancel: true,
            showCloseButton: true,
            closeButtonHtml: '<i class="fas fa-times"></i>',
            customClass: {
                confirmButton: "swal2-confirm",
                cancelButton: "swal2-cancel",
                popup: "rounded-xl",
            },
            showClass: {
                popup: "animate__animated animate__fadeInUp animate__faster",
            },
            hideClass: {
                popup: "animate__animated animate__fadeOutDown animate__faster",
            },
            preConfirm: () => {
                Swal.showLoading();
                return new Promise((resolve) => {
                    const form = document.createElement("form");
                    form.method = "POST";
                    form.action = deleteUrl;
                    form.style.display = "none";

                    const csrfToken = document
                        .querySelector('meta[name="csrf-token"]')
                        ?.getAttribute("content");
                    if (csrfToken) {
                        const csrfInput = document.createElement("input");
                        csrfInput.type = "hidden";
                        csrfInput.name = "_token";
                        csrfInput.value = csrfToken;
                        form.appendChild(csrfInput);
                    }

                    const methodInput = document.createElement("input");
                    methodInput.type = "hidden";
                    methodInput.name = "_method";
                    methodInput.value = "DELETE";
                    form.appendChild(methodInput);

                    document.body.appendChild(form);
                    form.submit();
                    resolve();
                });
            },
        });
    });
});
