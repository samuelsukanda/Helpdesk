$.fn.dataTable.ext.errMode = "none";
$(document).ready(function () {
    // Animasi progress bar
    setTimeout(() => {
        document.querySelectorAll(".progress-bar-fill").forEach((bar) => {
            bar.style.width = bar.dataset.width;
        });
    }, 300);

    // Inisialisasi DataTable
    const table = $("#reportsTable").DataTable({
        responsive: true,
        pageLength: 25,
        lengthMenu: [
            [10, 25, 50, 100, -1],
            [10, 25, 50, 100, "Semua"],
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
                targets: [0, 2, 3],
            },
            {
                responsivePriority: 2,
                targets: [1, 4],
            },
            {
                responsivePriority: 3,
                targets: [5, 6, 7, 8, 9],
            },
        ],
        order: [[8, "desc"]],
        dom: '<"top flex items-center justify-between gap-4 mb-4"lf>rt<"bottom flex items-center justify-between gap-4 mt-4"ip>',
    });

    // Tooltip sederhana untuk judul yang terpotong
    $(".ticket-row td:nth-child(2) p").each(function () {
        if (this.offsetWidth < this.scrollWidth) {
            $(this).attr("title", $(this).text());
        }
    });
});
