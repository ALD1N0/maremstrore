let cart = {};
let total = 0;
function tambahBarang(el, id, nama, harga, stok, cardEl = null) {
    let card = cardEl ? cardEl : el.closest(".card");
    let stokEl = card.querySelector(".stok");
    let btn = card.querySelector(".btn-add");
    let currentStok = parseInt(stokEl.getAttribute("data-stok"));
    if (currentStok <= 0) {
        Swal.fire({
            toast: true,
            position: 'center',
            icon: 'warning',
            title: 'Stok habis',
            showConfirmButton: false,
            timer: 2000
        });
        return;
    }
    currentStok--;
    stokEl.setAttribute("data-stok", currentStok);
    stokEl.innerText = "Stok: " + currentStok;
    if (currentStok === 0) {
        btn.disabled = true;
        btn.innerText = "Habis";
        card.classList.add("habis");
    }
    if (cart[id]) {
        cart[id].qty += 1;
    } else {
        cart[id] = {
            id_barang: id,
            nama: nama,
            harga: harga,
            qty: 1,
            stokEl: stokEl,
            btn: btn,
            card: card
        };
    }
    renderCart();
}
function tambahQty(id) {
    let item = cart[id];
    let currentStok = parseInt(item.stokEl.getAttribute("data-stok"));
    if (currentStok <= 0) {
        Swal.fire({
            toast: true,
            position: 'center',
            icon: 'warning',
            title: 'Stok habis',
            showConfirmButton: false,
            timer: 2000
        });
        return;
    }
    currentStok--;
    item.stokEl.setAttribute("data-stok", currentStok);
    item.stokEl.innerText = "Stok: " + currentStok;
    item.qty += 1;
    if (currentStok === 0) {
        item.btn.disabled = true;
        item.btn.innerText = "Habis";
        item.card.classList.add("habis");
    }
    renderCart();
}
function kurangQty(id) {
    let item = cart[id];
    let currentStok = parseInt(item.stokEl.getAttribute("data-stok"));
    currentStok++;
    item.stokEl.setAttribute("data-stok", currentStok);
    item.stokEl.innerText = "Stok: " + currentStok;
    item.btn.disabled = false;
    item.btn.innerText = "Tambah";
    if (currentStok > 0) {
        item.card.classList.remove("habis");
    }
    item.qty--;
    if (item.qty <= 0) {
        delete cart[id];
    }
    renderCart();
}
function renderCart() {
    const list = document.getElementById("listBarang");
    list.innerHTML = "";
    total = 0;
    let jumlahItem = 0;
    for (let id in cart) {
        let item = cart[id];
        let subtotal = item.harga * item.qty;
        total += subtotal;
        jumlahItem += item.qty;
        list.innerHTML += `
            <tr class="cart-item-row">
                <td>
                    ${item.nama}
                </td>
                <td>
                    <div class="qty-control">
                        <button onclick="kurangQty(${id})">−</button>
                        <span>${item.qty}</span>
                        <button onclick="tambahQty(${id})">+</button>
                    </div>
                </td>
                <td>
                    Rp ${subtotal.toLocaleString("id-ID")}
                </td>
            </tr>
        `;
    }
    document.getElementById("total").innerText =
        total.toLocaleString("id-ID");
}
function cariProdukTabel() {
    let input = document.getElementById("searchProduk").value.toLowerCase();
    // ambil semua baris tabel
    let rows = document.querySelectorAll("#tabelProduk tr");
    rows.forEach((row, index) => {
        if (index === 0) return;
        let namaEl = row.querySelector(".nama-produk");
        if (!namaEl) return;
        let nama = namaEl.innerText.toLowerCase();
        if (nama.includes(input)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
}
function cariProduk() {
    let input = document.getElementById("searchInput").value.toLowerCase();
    let cards = document.querySelectorAll(".card");
    cards.forEach(card => {
        let nama = card.querySelector(".nama").innerText.toLowerCase();
        if (nama.includes(input)) {
            card.style.display = "block";
        } else { card.style.display = "none"; }
    });
}
async function checkout() {
    if (Object.keys(cart).length === 0) {
        showToast("🛒 Keranjang masih kosong");
        return;
    }
    let items = "";
    let dataItems = "";
    for (let id in cart) {
        let item = cart[id];
        let subtotal = item.harga * item.qty;
        items += `
        <tr>
            <td>${item.nama} x${item.qty}</td>
            <td style="text-align:right;">Rp ${subtotal}</td>
        </tr>
        `;
        dataItems += `${item.nama} x${item.qty} = Rp ${subtotal}\n`;
    }
    let strukHTML = `
    <div class="receipt-paper">
        <!-- HEADER -->
        <div class="receipt-header">
            <h2>MAREM STORE</h2>
            <p>
                ${new Date().toLocaleString()}
            </p>
            <p>
                ========================
            </p>
        </div>
        <!-- ITEMS -->
        <table class="receipt-table">
            ${items}
        </table>
        <!-- TOTAL -->
        <div class="receipt-total">
            <span>TOTAL</span>
            <strong>
                Rp ${Number(total).toLocaleString()}
            </strong>
        </div>
        <div class="receipt-divider">
            ========================
        </div>
        <!-- FOOTER -->
        <div class="receipt-footer">
            <p>Terima Kasih 🙏</p>
            <small>
                Barang yang sudah dibeli
                tidak dapat dikembalikan
            </small>
        </div>
        <!-- ACTION -->
        <div class="receipt-actions">
            <button onclick="printStruk()" class="btn-print">
                🖨 Print
            </button>
            <button onclick="kembaliKeCart()" class="btn-back">
                ← Kembali
            </button>
        </div>
    </div>
`;
    document.getElementById("cartView").style.display = "none";
    document.getElementById("strukView").style.display = "block";
    document.getElementById("strukView").innerHTML = strukHTML;
    // SIMPAN KE SERVER
    let itemsData = [];
    for (let id in cart) {
        let item = cart[id];
        itemsData.push({
            id_barang: item.id_barang,
            jumlah: item.qty,
            subtotal: item.harga * item.qty
        });
    }
    await fetch('/transaksi', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            items: itemsData,
            total: total
        })
    });
    // reset cart
    cart = {};
    renderCart();
}
function formatRupiah(angka) {
    return angka.toLocaleString('id-ID');
}
function printStruk() {
    const receipt =
        document.querySelector(".receipt-paper");
    if (!receipt) return;
    const oldFrame =
        document.getElementById("printFrame");
    if (oldFrame) {
        oldFrame.remove();
    }
    const iframe =
        document.createElement("iframe");
    iframe.id = "printFrame";
    iframe.style.position = "fixed";
    iframe.style.right = "0";
    iframe.style.bottom = "0";
    iframe.style.width = "0";
    iframe.style.height = "0";
    iframe.style.border = "0";
    document.body.appendChild(iframe);
    const frameDoc =
        iframe.contentWindow.document;
    frameDoc.open();
    frameDoc.write(`
        <html>
        <head>
            <title>Print Struk</title>
            <style>
                @page{
                    size: 80mm auto;
                    margin: 0;
                }
                *{
                    box-sizing: border-box;
                }
                body{
                    margin: 0;
                    display: flex;
                    justify-content: center;
                    background: white;
                    font-family: monospace;
                    color: black;
                    font-size: 12px;
                }
                .receipt-paper{
                    width: 80mm;
                    padding: 8px;
                }
                .receipt-header{
                    text-align: center;
                    margin-bottom: 10px;
                }
                .receipt-header h2{
                    margin: 0;
                    font-size: 18px;
                }
                .receipt-header p{
                    margin: 3px 0;
                    font-size: 11px;
                }
                .receipt-table{
                    width: 100%;
                    border-collapse: collapse;
                }
                .receipt-table td{
                    padding: 4px 0;
                }
                .receipt-table td:last-child{
                    text-align: right;
                }
                .receipt-total{
                    display: flex;
                    justify-content: space-between;
                    margin-top: 10px;
                    font-weight: bold;
                }
                .receipt-footer{
                    text-align: center;
                    margin-top: 12px;
                }
                .receipt-actions{
                    display: none !important;
                }
            </style>
        </head>
        <body>
            ${receipt.outerHTML}
        </body>
        </html>
    `);
    frameDoc.close();
    setTimeout(() => {
        iframe.contentWindow.focus();
        iframe.contentWindow.print();
    }, 500);
}
function kembaliKeCart() {
    document.getElementById("strukView").style.display = "none";
    const cart = document.getElementById("cartView");
    cart.style.display = "flex";
    cart.style.height = "100%";
}
function printTransaksi(id) {
    const content =
        document.getElementById(
            "transaksi-" + id
        );
    if (!content) return;
    createPrintFrame(
        "MAREM STORE",
        content.outerHTML
    );
}
function printSemua() {
    const content =
        document.querySelector(
            ".content-print"
        );
    if (!content) return;
    createPrintFrame(
        "LAPORAN TRANSAKSI",
        content.innerHTML
    );
}
function createPrintFrame(title, htmlContent) {
    const oldFrame =
        document.getElementById(
            "printFrame"
        );
    if (oldFrame) {
        oldFrame.remove();
    }
    const iframe =
        document.createElement(
            "iframe"
        );
    iframe.id = "printFrame";
    iframe.style.position = "fixed";
    iframe.style.width = "0";
    iframe.style.height = "0";
    iframe.style.border = "0";
    document.body.appendChild(
        iframe
    );
    const frameDoc =
        iframe.contentWindow.document;
    frameDoc.open();
    frameDoc.write(`
<html>
<head>
<style>
    @page{
        size: A4 portrait;
        margin: 20mm;
    }
    *{
        box-sizing: border-box;
    }
    body{
        margin: 0;
        padding: 30px;
        background: white;
        color: #111;
        font-family: Arial, sans-serif;
        font-size: 16px;
        line-height: 1.5;
    }
    .print-wrapper{
        width: 100%;
        max-width: 1000px;
        margin: 0 auto;
    }
    /* JUDUL */
    h3{
        text-align: center;
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 30px;
        color: #000;
    }
    /* TANGGAL */
    .report-date{
        font-size: 18px;
        font-weight: bold;
        margin: 25px 0 15px;
    }
    /* TABLE */
    table{
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 25px;
    }
    th{
        background: #f3f4f6;
        font-size: 18px;
        font-weight: bold;
        padding: 12px;
        border: 2px solid #000;
        text-align: left;
    }
    td{
        font-size: 17px;
        padding: 12px;
        border: 1px solid #000;
    }
    th:nth-child(2),
    td:nth-child(2){
        text-align: center;
        width: 120px;
    }
    th:last-child,
    td:last-child{
        text-align: right;
        width: 180px;
    }
    /* TOTAL */
    .total-box{
        margin-top: 10px;
        padding: 14px;
        border: 2px solid #000;
        display: flex;
        justify-content: space-between;
        font-size: 20px;
        font-weight: bold;
        background: #f9fafb;
    }
    /* HIDE BUTTON */
    button,
    .btn-print-single,
    .btn-print-all{
        display: none !important;
    }
</style>
</head>
<body>
    <div class="print-wrapper">
        <h3>${title}</h3>
        ${htmlContent}
    </div>
</body>
</html>
`);
    frameDoc.close();
    setTimeout(() => {
        iframe.contentWindow.focus();
        iframe.contentWindow.print();
    }, 500);
}
function filterProduk() {
    let filter = document.getElementById("filterProduk").value;
    let grid = document.getElementById("produkGrid");
    // ambil semua card
    let cards = Array.from(grid.querySelectorAll(".card"));
    // sorting
    if (filter === "nama") {
        cards.sort(function (a, b) {
            let namaA = a.querySelector(".nama").textContent.toLowerCase();
            let namaB = b.querySelector(".nama").textContent.toLowerCase();
            return namaA.localeCompare(namaB);
        });
    }
    else if (filter === "stok") {
        cards.sort(function (a, b) {
            let stokA = parseInt(a.querySelector(".stok").getAttribute("data-stok"));
            let stokB = parseInt(b.querySelector(".stok").getAttribute("data-stok"));
            return stokA - stokB;
        });
    }
    grid.innerHTML = "";
    cards.forEach(function (card) {
        grid.appendChild(card);
    });
}
function printStokHabis() {
    const table =
        document.getElementById(
            "tabelProduk"
        );
    const rows =
        Array.from(
            table.rows
        ).slice(1);
    let data = "";
    rows.forEach((row) => {
        const stok =
            parseInt(
                row.cells[3].innerText
            );
        if (stok <= 3) {
            data += `
                <tr>
                    <td>
                        ${row.cells[0].innerText}
                    </td>
                    <td>
                        ${row.cells[2].innerText}
                    </td>
                    <td>
                        ${row.cells[3].innerText}
                    </td>
                </tr>
            `;
        }
    });
    const oldFrame =
        document.getElementById(
            "printFrame"
        );
    if (oldFrame) {
        oldFrame.remove();
    }
    const iframe =
        document.createElement(
            "iframe"
        );
    iframe.id = "printFrame";
    iframe.style.position = "fixed";
    iframe.style.width = "0";
    iframe.style.height = "0";
    iframe.style.border = "0";
    document.body.appendChild(
        iframe
    );
    const frameDoc =
        iframe.contentWindow.document;
    frameDoc.open();
    frameDoc.write(`
        <html>
        <head>
            <title>
                Cetak Stok Habis
            </title>
            <style>
                *{
                    box-sizing: border-box;
                }
                body{
                    margin: 0;
                    padding: 20px;
                    min-height: 100vh;
                    display: flex;
                    justify-content: center;
                    align-items: flex-start;
                    font-family: Arial, sans-serif;
                    background: white;
                }
                .print-wrapper{
                    width: 100%;
                    max-width: 700px;
                }
                h2{
                    text-align: center;
                    margin-bottom: 20px;
                }
                table{
                    width: 100%;
                    border-collapse: collapse;
                }
                th,
                td{
                    border:
                        1px solid black;
                    padding: 10px;
                    text-align: left;
                }
                th{
                    background: #f3f4f6;
                }
            </style>
        </head>
        <body>
            <div class="print-wrapper">
                <h2>
                    Daftar Produk Stok Menipis
                </h2>
                <table>
                    <tr>
                        <th>
                            Nama
                        </th>
                        <th>
                            Harga
                        </th>
                        <th>
                            Stok
                        </th>
                    </tr>
                    ${data ||
        `
                        <tr>
                            <td colspan="3">
                                Tidak ada stok habis
                            </td>
                        </tr>
                        `
        }
                </table>
            </div>
        </body>
        </html>
    `);
    frameDoc.close();
    setTimeout(() => {
        iframe.contentWindow.focus();
        iframe.contentWindow.print();
    }, 500);
}
function confirmDelete(button) {
    const form = button.closest("form");
    Swal.fire({
        title: "Hapus produk?",
        text: "Data yang dihapus tidak dapat dikembalikan.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Iya, Hapus",
        cancelButtonText: "Tidak",
        reverseButtons: true,
        allowOutsideClick: false,
        confirmButtonColor: "#ef4444",
        cancelButtonColor: "#9ca3af"
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
    return false;
}
function confirmLogout(button) {
    const form = button.closest("form");
    Swal.fire({
        title: "Logout?",
        text: "Apakah kamu yakin ingin keluar dari akun?",
        icon: "question",
        showCancelButton: true,
        showConfirmButton: true,
        confirmButtonText: "Iya",
        cancelButtonText: "Tidak",
        reverseButtons: true,
        allowOutsideClick: false,
        confirmButtonColor: "#2563eb",
        cancelButtonColor: "#9ca3af",
        background: "#ffffff",
        color: "#111827"
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
    return false;
}
function showToast(message) {
    const oldToast = document.querySelector(".custom-toast");
    if (oldToast) {
        oldToast.remove();
    }
    const toast = document.createElement("div");
    toast.className = "custom-toast";
    toast.innerHTML = message;
    document.body.appendChild(toast);
    setTimeout(() => {
        toast.classList.add("show");
    }, 50);
    setTimeout(() => {
        toast.classList.remove("show");
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 2500);
}
