/**
 * AeroStock — Mesin Kasir (POS)
 * Manajemen state keranjang belanja untuk antarmuka Point of Sale.
 */

const Cart = {
    items: [],
    paymentMethod: 'cash',

    add(id, name, price, image, stock) {
        const existing = this.items.find(i => i.id === id);
        if (existing) {
            if (existing.qty < stock) existing.qty++;
        } else {
            this.items.push({ id, name, price, image, stock, qty: 1 });
        }
        this.render();
        this.showAddFeedback(id);
    },

    remove(id) {
        this.items = this.items.filter(i => i.id !== id);
        this.render();
    },

    updateQty(id, delta) {
        const item = this.items.find(i => i.id === id);
        if (!item) return;
        item.qty += delta;
        if (item.qty <= 0) { this.remove(id); return; }
        if (item.qty > item.stock) item.qty = item.stock;
        this.render();
    },

    clear() {
        this.items = [];
        this.render();
    },

    get subtotal() { return this.items.reduce((s, i) => s + i.price * i.qty, 0); },
    get tax() { return Math.round(this.subtotal * 0.10); },
    get total() { return this.subtotal + this.tax; },
    get count() { return this.items.reduce((c, i) => c + i.qty, 0); },

    fmt(n) {
        return 'Rp' + Math.round(n).toLocaleString('id-ID');
    },

    render() {
        const container = document.getElementById('cartItems');
        const empty = document.getElementById('cartEmpty');
        const countEl = document.getElementById('cartCount');
        const subEl = document.getElementById('subtotalDisplay');
        const taxEl = document.getElementById('taxDisplay');
        const totEl = document.getElementById('totalDisplay');
        const btn = document.getElementById('checkoutBtn');

        countEl.textContent = this.count;
        subEl.textContent = this.fmt(this.subtotal);
        taxEl.textContent = this.fmt(this.tax);
        totEl.textContent = this.fmt(this.total);
        btn.disabled = this.items.length === 0;

        Array.from(container.querySelectorAll('.cart-item')).forEach(el => el.remove());

        if (this.items.length === 0) {
            empty.style.display = 'flex';
            return;
        }
        empty.style.display = 'none';

        this.items.forEach(item => {
            const el = document.createElement('div');
            el.className = 'cart-item';
            el.dataset.id = item.id;
            el.innerHTML = `
        <img class="cart-item-img"
             src="${item.image || 'https://placehold.co/80/EFF6FF/2563EB?text=IMG'}"
             alt="${item.name}"
             onerror="this.src='https://placehold.co/80/EFF6FF/2563EB?text=IMG'">
        <div class="cart-item-info">
          <div class="cart-item-name">${item.name}</div>
          <div class="cart-item-price">${this.fmt(item.price)} / item</div>
        </div>
        <div class="qty-ctrl">
          <button class="qty-btn" onclick="Cart.updateQty(${item.id}, -1)">−</button>
          <span class="qty-num">${item.qty}</span>
          <button class="qty-btn" onclick="Cart.updateQty(${item.id}, 1)">+</button>
        </div>
        <button class="cart-item-remove" onclick="Cart.remove(${item.id})" title="Hapus">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round">
            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
          </svg>
        </button>
      `;
            container.appendChild(el);
        });
    },

    showAddFeedback(id) {
        const card = document.querySelector(`[onclick*="addToCart(${id},"]`);
        if (card) {
            card.style.borderColor = 'var(--accent)';
            card.style.transform = 'scale(0.97)';
            setTimeout(() => { card.style.borderColor = ''; card.style.transform = ''; }, 180);
        }
    }
};

// ── Fungsi global ────────────────────────────────────────────────────────────

function addToCart(id, name, price, image, stock) { Cart.add(id, name, price, image, stock); }
function clearCart() { Cart.clear(); }

function selectPayment(el, method) {
    Cart.paymentMethod = method;
    document.querySelectorAll('.pay-method-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
}

function filterCategory(el, catId) {
    document.querySelectorAll('.cat-tab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    document.querySelectorAll('.product-card').forEach(card => {
        card.style.display = (catId === 'all' || card.dataset.category === catId) ? '' : 'none';
    });
}

async function processCheckout() {
    if (Cart.items.length === 0) return;

    const btn = document.getElementById('checkoutBtn');
    btn.disabled = true;
    btn.textContent = 'Memproses...';

    try {
        const res = await fetch(window.POS_STORE, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': window.CSRF,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                items: Cart.items.map(i => ({ id: i.id, qty: i.qty })),
                payment_method: Cart.paymentMethod,
            }),
        });

        const data = await res.json();

        if (data.success) {
            document.getElementById('receiptRef').textContent = data.reference;
            document.getElementById('receiptTotal').textContent = Cart.fmt(data.total);
            document.getElementById('receiptModal').classList.add('active');
            Cart.clear();
        } else {
            alert('Transaksi gagal. Silakan coba kembali.');
        }
    } catch (e) {
        alert('Terjadi kesalahan jaringan. Silakan coba kembali.');
    } finally {
        btn.disabled = false;
        btn.textContent = 'Proses Pembayaran';
    }
}

function closeReceipt() {
    document.getElementById('receiptModal').classList.remove('active');
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.active').forEach(m => m.classList.remove('active'));
    }
});

Cart.render();
