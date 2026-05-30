document.addEventListener("DOMContentLoaded", function () {

    let input = document.getElementById("inputGambar");
    let preview = document.getElementById("previewGambar");

    if (!input || !preview) return;

    input.addEventListener("change", function (e) {
        let file = e.target.files[0];

        if (file) {
            let reader = new FileReader();

            reader.onload = function (e) {
                preview.src = e.target.result;
            }

            reader.readAsDataURL(file);
        }
    });

});

document.addEventListener("DOMContentLoaded", function () {

    let input = document.getElementById("inputGambar");
    let preview = document.getElementById("previewGambar");

    input.addEventListener("change", function (e) {
        let file = e.target.files[0];

        if (file) {
            let reader = new FileReader();

            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = "block";
            }

            reader.readAsDataURL(file);
        }
    });

});

function filterbarangproduk() {
    let filter = document.getElementById("filterbarangproduk").value;
    let table = document.getElementById("tabelProduk");
    let rows = Array.from(table.rows).slice(1); // skip header

    if (filter === "nama") {
        rows.sort((a, b) => {
            let namaA = a.querySelector(".nama-produk").innerText.toLowerCase();
            let namaB = b.querySelector(".nama-produk").innerText.toLowerCase();
            return namaA.localeCompare(namaB);
        });
    }

    if (filter === "stok") {
        rows.sort((a, b) => {
            let stokA = parseInt(a.cells[3].innerText);
            let stokB = parseInt(b.cells[3].innerText);
            return stokA - stokB;
        });
    }

    // reset isi tabel
    let tbody = table;
    rows.forEach(row => tbody.appendChild(row));
}
