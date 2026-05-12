$.fn.dataTable.ext.errMode = 'none';
$(document).ready(function () {
    // Inisialisasi DataTable
    const table = $("#ticketsTable").DataTable({
        responsive: true,
        pageLength: 10,
        lengthMenu: [
            [5, 10, 25, 50, -1],
            [5, 10, 25, 50, "Semua"],
        ],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ per halaman",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ tiket",
            infoEmpty: "Tidak ada tiket",
            infoFiltered: "(difilter dari _MAX_ total)",
            paginate: {
                first: '<i class="fas fa-angle-double-left"></i>',
                previous: '<i class="fas fa-angle-left"></i>',
                next: '<i class="fas fa-angle-right"></i>',
                last: '<i class="fas fa-angle-double-right"></i>',
            },
            zeroRecords:
                '<div class="empty-state"><div class="empty-state-icon"><i class="fas fa-search text-3xl text-gray-300"></i></div><h4 class="text-gray-600 font-medium mb-1">Tidak ditemukan</h4><p class="text-gray-400 text-sm">Coba kata kunci lain</p></div>',
        },
        columnDefs: [
            {
                orderable: false,
                targets: [7],
            },
            {
                responsivePriority: 1,
                targets: [0, 3, 7],
            },
            {
                responsivePriority: 2,
                targets: [1, 4],
            },
            {
                responsivePriority: 3,
                targets: [2, 5, 6],
            },
        ],
        order: [[6, "desc"]],
        dom: '<"top flex items-center justify-between gap-4 mb-4"lf>rt<"bottom flex items-center justify-between gap-4 mt-4"ip>',
        drawCallback: function () {
            bindRowClicks();
            bindDeleteButtons();
        },
    });

    // Row click handler untuk navigasi
    function bindRowClicks() {
        $(".ticket-row")
            .off("click")
            .on("click", function (e) {
                if (!$(e.target).closest("a, button").length) {
                    window.location.href = $(this).data("href");
                }
            });
    }
    bindRowClicks();

    // SweetAlert2 Delete Handler
    function bindDeleteButtons() {
        $(".btn-delete")
            .off("click")
            .on("click", function (e) {
                e.preventDefault();
                e.stopPropagation();

                const btn = $(this);
                const ticketNumber = btn.data("ticket-number");
                const ticketTitle = btn.data("ticket-title");
                const deleteUrl = btn.data("delete-url");

                Swal.fire({
                    title: "Hapus Tiket?",
                    html: `
                            <div class="text-center">
                                <p class="text-gray-600 mb-2">Anda akan menghapus tiket:</p>
                                <p class="font-mono font-bold text-blue-600 bg-blue-50 inline-block px-3 py-1 rounded-lg mb-2">${ticketNumber}</p>
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
                                const csrfInput =
                                    document.createElement("input");
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
    }
    bindDeleteButtons();

    // Animasi progress bar
    setTimeout(() => {
        document.querySelectorAll(".progress-bar-fill").forEach((bar) => {
            bar.style.width = bar.dataset.width;
        });
    }, 300);

    // Tooltip sederhana untuk judul yang terpotong
    $(".ticket-row td:nth-child(2) p").each(function () {
        if (this.offsetWidth < this.scrollWidth) {
            $(this).attr("title", $(this).text());
        }
    });
});
