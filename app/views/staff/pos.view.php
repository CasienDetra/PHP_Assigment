<?php require('views/partials/head.php'); ?>

<div class="pos-container">
    <div class="menu-section">
        <div class="category-tabs">
            <button class="cat-btn active" onclick="filterCategory('all')">All</button>
            <?php foreach(array_keys($menu) as $cat): ?>
                <button class="cat-btn" onclick="filterCategory('<?= $cat ?>')"><?= $cat ?></button>
            <?php endforeach; ?>
        </div>

        <div class="menu-grid">
            <?php foreach ($menu as $category => $items): ?>
                <?php foreach ($items as $item): ?>
                    <div class="menu-item-card" data-category="<?= $category ?>" onclick='addToCart(<?= json_encode($item) ?>)'>
                        <div class="item-img" style="background-image: url('<?= $item['image_path'] ?? '' ?>');">
                            <?php if(!$item['image_path']) echo '<span>No Img</span>'; ?>
                        </div>
                        <div class="item-details">
                            <h4><?= $item['name'] ?></h4>
                            <div class="prices">
                                <span class="usd">$<?= number_format($item['price_usd'], 2) ?></span>
                                <span class="khr">៛<?= number_format($item['price_khr'], 0) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="cart-section">
        <h3>Current Order</h3>
        <div class="order-type-selector">
            <label><input type="radio" name="orderType" value="Dine-in" checked> Dine-in</label>
            <label><input type="radio" name="orderType" value="Takeaway"> Takeaway</label>
        </div>
        
        <div id="cart-items" class="cart-items">
            <!-- JS will populate -->
            <p class="empty-cart-msg">No items yet</p>
        </div>

        <div class="cart-summary">
            <div class="row">
                <span>Total ($):</span>
                <span id="total-usd">$0.00</span>
            </div>
            <div class="row">
                <span>Total (៛):</span>
                <span id="total-khr">៛0</span>
            </div>
            <button class="btn btn-primary btn-block pay-btn" onclick="placeOrder()">Place Order</button>
        </div>
    </div>
</div>

<!-- Receipt Modal -->
<div id="receipt-modal" class="modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closeReceipt()">&times;</span>
        <div id="receipt-print-area" class="receipt-body">
            <div class="receipt-header">
                <h2>Coffee POS</h2>
                <p>Date: <span id="r-date"></span></p>
                <p>Order ID: #<span id="r-id"></span></p>
            </div>
            <hr>
            <div id="r-items"></div>
            <hr>
            <div class="receipt-total">
                <p>Total USD: <span id="r-total-usd"></span></p>
                <p>Total KHR: <span id="r-total-khr"></span></p>
            </div>
            <div class="receipt-footer">
                <p>Thank you!</p>
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn btn-primary" onclick="printReceipt()">Print / Download</button>
            <button class="btn btn-danger" onclick="closeReceipt()">Close</button>
        </div>
    </div>
</div>

<!-- Include html2canvas for Download as Image feature -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<script>
let cart = [];

function addToCart(item) {
    const existing = cart.find(i => i.id === item.id);
    if (existing) {
        existing.qty++;
    } else {
        cart.push({...item, qty: 1});
    }
    updateCartUI();
}

function removeFromCart(id) {
    cart = cart.filter(i => i.id !== id);
    updateCartUI();
}

function updateQty(id, change) {
    const item = cart.find(i => i.id === id);
    if (item) {
        item.qty += change;
        if (item.qty <= 0) removeFromCart(id);
        else updateCartUI();
    }
}

function updateCartUI() {
    const container = document.getElementById('cart-items');
    container.innerHTML = '';
    
    if (cart.length === 0) {
        container.innerHTML = '<p class="empty-cart-msg">No items yet</p>';
        document.getElementById('total-usd').innerText = '$0.00';
        document.getElementById('total-khr').innerText = '៛0';
        return;
    }

    let totalUsd = 0;
    let totalKhr = 0;

    cart.forEach(item => {
        totalUsd += item.price_usd * item.qty;
        totalKhr += item.price_khr * item.qty;

        const el = document.createElement('div');
        el.className = 'cart-item';
        el.innerHTML = `
            <div class="item-info">
                <strong>${item.name}</strong>
                <small>$${(item.price_usd * item.qty).toFixed(2)}</small>
            </div>
            <div class="item-controls">
                <button onclick="updateQty(${item.id}, -1)">-</button>
                <span>${item.qty}</span>
                <button onclick="updateQty(${item.id}, 1)">+</button>
            </div>
        `;
        container.appendChild(el);
    });

    document.getElementById('total-usd').innerText = '$' + totalUsd.toFixed(2);
    document.getElementById('total-khr').innerText = '៛' + totalKhr.toLocaleString();
}

function filterCategory(cat) {
    document.querySelectorAll('.cat-btn').forEach(b => b.classList.remove('active'));
    event.target.classList.add('active');
    
    document.querySelectorAll('.menu-item-card').forEach(card => {
        if (cat === 'all' || card.dataset.category === cat) {
            card.style.display = 'flex';
        } else {
            card.style.display = 'none';
        }
    });
}

function placeOrder() {
    if (cart.length === 0) return alert('Cart is empty!');
    
    const payBtn = document.querySelector('.pay-btn');
    payBtn.disabled = true;
    payBtn.innerText = 'Processing...';

    const orderType = document.querySelector('input[name="orderType"]:checked').value;
    const totalUsd = cart.reduce((sum, i) => sum + (i.price_usd * i.qty), 0);
    const totalKhr = cart.reduce((sum, i) => sum + (i.price_khr * i.qty), 0);

    const payload = {
        order_type: orderType,
        total_usd: totalUsd,
        total_khr: totalKhr,
        items: cart
    };

    fetch('/staff/order', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(payload)
    })
    .then(res => {
        if (!res.ok) {
            return res.text().then(text => { throw new Error(text || 'Server error') });
        }
        return res.json();
    })
    .then(data => {
        if (data.success) {
            showReceipt(data);
            cart = [];
            updateCartUI();
        } else {
            alert('Error: ' + (data.message || 'Unknown error occurred'));
        }
    })
    .catch(err => {
        console.error('Order failed:', err);
        alert('Failed to place order. Check console for details.');
    })
    .finally(() => {
        payBtn.disabled = false;
        payBtn.innerText = 'Place Order';
    });
}

function showReceipt(data) {
    document.getElementById('r-date').innerText = data.date;
    document.getElementById('r-id').innerText = data.order_id;
    document.getElementById('r-total-usd').innerText = '$' + parseFloat(data.total_usd).toFixed(2);
    document.getElementById('r-total-khr').innerText = '៛' + parseInt(data.total_khr).toLocaleString();
    
    const itemsContainer = document.getElementById('r-items');
    itemsContainer.innerHTML = '';
    
    data.items.forEach(item => {
        const div = document.createElement('div');
        div.style.display = 'flex';
        div.style.justifyContent = 'space-between';
        div.style.marginBottom = '5px';
        div.innerHTML = `
            <span>${item.qty}x ${item.name}</span>
            <span>$${(item.price_usd * item.qty).toFixed(2)}</span>
        `;
        itemsContainer.appendChild(div);
    });

    document.getElementById('receipt-modal').style.display = 'block';
}

function closeReceipt() {
    document.getElementById('receipt-modal').style.display = 'none';
}

function printReceipt() {
    const area = document.getElementById('receipt-print-area');
    
    html2canvas(area, {
        backgroundColor: '#ffffff',
        scale: 2 // Higher resolution
    }).then(canvas => {
        const link = document.createElement('a');
        link.download = 'receipt-' + document.getElementById('r-id').innerText + '.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
    });
}
</script>

<?php require('views/partials/footer.php'); ?>
