/**
 * AeroStock — Mesin Kasir (POS)
 * Manajemen state keranjang belanja untuk antarmuka Point of Sale.
 */

const POS_READ_ONLY = Boolean(window.POS_READ_ONLY);
let receiptNeedsRefresh = false;

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
        return 'Rp' + Math.round(Number(n) || 0).toLocaleString('id-ID');
    },

    render() {
        const container = document.getElementById('cartItems');
        const empty = document.getElementById('cartEmpty');
        const countEl = document.getElementById('cartCount');
        const subEl = document.getElementById('subtotalDisplay');
        const taxEl = document.getElementById('taxDisplay');
        const totEl = document.getElementById('totalDisplay');
        const btn = document.getElementById('checkoutBtn');
        const mobileCount = document.getElementById('mobileCartCount');
        const mobileTotal = document.getElementById('mobileCartTotal');

        if (!container || !empty || !countEl || !subEl || !taxEl || !totEl || !btn) return;

        countEl.textContent = this.count;
        subEl.textContent = this.fmt(this.subtotal);
        taxEl.textContent = this.fmt(this.tax);
        totEl.textContent = this.fmt(this.total);
        btn.disabled = this.items.length === 0;
        if (mobileCount) mobileCount.textContent = this.count;
        if (mobileTotal) mobileTotal.textContent = this.fmt(this.total);

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
        const card = document.querySelector(`.product-card[data-id="${id}"]`);
        if (card) {
            card.style.borderColor = 'var(--accent)';
            card.style.transform = 'scale(0.97)';
            setTimeout(() => { card.style.borderColor = ''; card.style.transform = ''; }, 180);
        }
    }
};

// ── Fungsi global ────────────────────────────────────────────────────────────

function addToCart(id, name, price, image, stock) {
    if (POS_READ_ONLY) return;
    Cart.add(id, name, price, image, stock);
}
function clearCart() { if (!POS_READ_ONLY) Cart.clear(); }

function isMobilePos() {
    return window.matchMedia('(max-width: 760px)').matches;
}

function toggleMobileCart() {
    document.body.classList.toggle('mobile-cart-open');
}

function openMobileCart() {
    if (isMobilePos()) document.body.classList.add('mobile-cart-open');
}

function closeMobileCart() {
    document.body.classList.remove('mobile-cart-open');
}

function bindProductCards() {
    if (POS_READ_ONLY) return;

    document.querySelectorAll('.product-card').forEach(card => {
        card.addEventListener('click', () => {
            addToCart(
                Number(card.dataset.id),
                card.dataset.name,
                Number(card.dataset.price),
                card.dataset.image,
                Number(card.dataset.stock)
            );
        });
    });
}

function selectPayment(el, method) {
    if (POS_READ_ONLY) return;

    Cart.paymentMethod = method;
    document.querySelectorAll('.pay-method-btn').forEach(b => b.classList.remove('active'));
    el.classList.add('active');
}

function receiptSnapshot() {
    return {
        items: Cart.items.map(item => ({ ...item })),
        subtotal: Cart.subtotal,
        tax: Cart.tax,
        total: Cart.total,
        paymentMethod: Cart.paymentMethod,
        paidAt: new Date(),
    };
}

function renderReceipt(data, snapshot) {
    const payLabels = { cash: 'Tunai', card: 'Kartu', ewallet: 'QRIS' };
    const receiptItems = document.getElementById('receiptItems');
    const paidAt = data.created_at ? new Date(data.created_at) : snapshot.paidAt;
    const items = Array.isArray(data.items) && data.items.length
        ? data.items.map(item => ({
            name: item.name,
            qty: Number(item.quantity),
            price: Number(item.unit_price),
            subtotal: Number(item.subtotal),
        }))
        : snapshot.items.map(item => ({
            name: item.name,
            qty: Number(item.qty),
            price: Number(item.price),
            subtotal: Number(item.price) * Number(item.qty),
        }));
    const subtotal = data.subtotal ?? snapshot.subtotal;
    const tax = data.tax ?? snapshot.tax;
    const total = data.total ?? snapshot.total;
    const paymentMethod = data.payment_method || snapshot.paymentMethod;

    receiptItems.innerHTML = '';

    document.getElementById('receiptRef').textContent = data.reference;
    document.getElementById('receiptDate').textContent = paidAt.toLocaleString('id-ID', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
    document.getElementById('receiptMethod').textContent = payLabels[paymentMethod] || paymentMethod || '-';
    document.getElementById('receiptSubtotal').textContent = Cart.fmt(subtotal);
    document.getElementById('receiptTax').textContent = Cart.fmt(tax);
    document.getElementById('receiptTotal').textContent = Cart.fmt(total);

    items.forEach(item => {
        const row = document.createElement('div');
        row.className = 'receipt-item';

        const info = document.createElement('div');
        const name = document.createElement('strong');
        const qty = document.createElement('span');
        name.textContent = item.name;
        qty.textContent = `${item.qty} x ${Cart.fmt(item.price)}`;
        info.append(name, qty);

        const itemTotal = document.createElement('strong');
        itemTotal.textContent = Cart.fmt(item.subtotal);

        row.append(info, itemTotal);
        receiptItems.appendChild(row);
    });
}

function filterCategory(el, catId) {
    document.querySelectorAll('.cat-tab').forEach(t => t.classList.remove('active'));
    el.classList.add('active');
    document.querySelectorAll('.product-card').forEach(card => {
        card.style.display = (catId === 'all' || card.dataset.category === catId) ? '' : 'none';
    });
}

async function processCheckout() {
    if (POS_READ_ONLY) return;
    if (Cart.items.length === 0) return;

    const btn = document.getElementById('checkoutBtn');
    const snapshot = receiptSnapshot();
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
            renderReceipt(data, snapshot);
            receiptNeedsRefresh = true;
            document.getElementById('receiptModal').classList.add('active');
            Cart.clear();
            closeMobileCart();
        } else {
            alert(data.message || 'Transaksi gagal. Silakan coba kembali.');
        }
    } catch (e) {
        alert('Terjadi kesalahan jaringan. Silakan coba kembali.');
    } finally {
        btn.disabled = Cart.items.length === 0;
        btn.textContent = 'Proses Pembayaran';
    }
}

function closeReceipt() {
    if (receiptNeedsRefresh) {
        window.location.reload();
        return;
    }

    document.getElementById('receiptModal').classList.remove('active');
}

function printReceipt() {
    window.print();
}

document.addEventListener('keydown', e => {
    if (e.key === 'Escape') {
        if (document.getElementById('receiptModal')?.classList.contains('active')) {
            closeReceipt();
            return;
        }

        document.querySelectorAll('.modal-overlay.active').forEach(m => m.classList.remove('active'));
        closeMobileCart();
    }
});

bindProductCards();
if (!POS_READ_ONLY) Cart.render();
