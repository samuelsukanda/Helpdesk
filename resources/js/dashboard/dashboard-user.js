$.fn.dataTable.ext.errMode = "none";
$(document).ready(function () {
    // Inisialisasi DataTable
    const table = $("#userTicketsTable").DataTable({
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
                responsivePriority: 1,
                targets: [0, 3],
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

    // Tooltip sederhana untuk judul yang terpotong
    $(".ticket-row td:nth-child(2) p").each(function () {
        if (this.offsetWidth < this.scrollWidth) {
            $(this).attr("title", $(this).text());
        }
    });
});
