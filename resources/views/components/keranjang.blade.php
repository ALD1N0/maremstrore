<section id="keranjang" class="keranjang">
    <div id="cartView" class="cart-container">
        <div class="cart-handle"></div>
        <div class="cart-header">
            <div class="header-left">
                <h3 class="cart-title">
                    🛒 Keranjang
                </h3>
                <p class="cart-subtitle">
                    Produk yang dipilih
                </p>
            </div>
        </div>

        <div class="cart-body">
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Jumah</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody id="listBarang"></tbody>
            </table>
        </div>

        <div class="cart-footer">
            <div class="cart-summary">
                <div class="summary-left">
                    <span class="total-label">
                        Total Belanja
                    </span>
                    <h4 class="total-price">
                        Rp <span id="total">0</span>
                    </h4>
                </div>
            </div>
            <button onclick="checkout()" class="btn-checkout">
                Checkout Sekarang
            </button>
        </div>
    </div>
    <div id="strukView" style="display:none;"></div>
</section>

<script>
    const keranjang = document.getElementById("keranjang");
    const handle = document.querySelector(".cart-handle");
    handle.addEventListener("click", () => {
        keranjang.classList.toggle("expand");
    });
</script>
