window.addEventListener('DOMContentLoaded', event => {
    // Simple-DataTables
    // https://github.com/fiduswriter/Simple-DataTables/wiki

    const datatablesSimple = document.getElementById('datatablesSimple');
    if (datatablesSimple) {
       new simpleDatatables.DataTable(datatablesSimple, {
        labels: {
            placeholder: "Cari...",
            perPage: "data per halaman",
            noRows: "Tidak ada data yang ditemukan",
            info: "Menampilkan {start} sampai {end} dari {rows} entri"
        }
    });
    }
});
