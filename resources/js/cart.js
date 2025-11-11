// Получить CSRF
function getCsrfToken() {
    const meta = document.querySelector('meta[name="csrf-token"]');
    return meta ? meta.getAttribute('content') : null;
}

// Уведомления
function showNotification(message, type = 'success') {
    document.querySelectorAll('.cart-notification').forEach(n => n.remove());

    const box = document.createElement('div');
    box.className = `cart-notification fixed top-20 right-4 z-[9999] px-6 py-4 rounded-lg shadow-xl text-white
        ${type === 'success' ? 'bg-green-600' : 'bg-red-600'} animate-slide-in`;

    box.innerHTML = `
        <div class="flex items-center justify-between">
            <span class="font-medium text-lg">${message}</span>
            <button class="ml-4 font-bold text-xl" onclick="this.closest('.cart-notification').remove()">×</button>
        </div>`;

    document.body.appendChild(box);

    setTimeout(() => {
        box.style.opacity = 0;
        setTimeout(() => box.remove(), 300);
    }, 3500);
}

// Счетчик
function updateCartCount(count) {
    const el = document.querySelector('[data-cart-count]');
    if (el) {
        el.textContent = count;
        el.classList.add('animate-pulse');
        setTimeout(() => el.classList.remove('animate-pulse'), 400);
    }
}

// ✅ Не менять кнопки внутри корзины!
function updateButtonState(button, inCart) {
    if (button.closest('[data-cart-item]')) return;

    if (inCart) {
        button.textContent = 'В корзине';
        button.classList.remove('bg-blue-600','hover:bg-blue-700');
        button.classList.add('bg-green-600','hover:bg-green-700');
        button.dataset.cartRemove = '';
        delete button.dataset.cartAdd;
    } else {
        button.textContent = 'В корзину';
        button.classList.remove('bg-green-600','hover:bg-green-700');
        button.classList.add('bg-blue-600','hover:bg-blue-700');
        button.dataset.cartAdd = '';
        delete button.dataset.cartRemove;
    }
}

// Лоадер — только визуальный
function setButtonLoading(button, loading) {
    if (!button) return;

    if (loading) {
        button.disabled = true;
        button.classList.add('opacity-75','cursor-not-allowed');
    } else {
        button.disabled = false;
        button.classList.remove('opacity-75','cursor-not-allowed');
    }
}

function cartRequest(url, method, body) {
    return fetch(url, {
        method,
        headers: {
            'Content-Type':'application/json',
            'X-CSRF-TOKEN': getCsrfToken(),
            'Accept':'application/json',
            'X-Requested-With':'XMLHttpRequest'
        },
        credentials:'same-origin',
        body: JSON.stringify(body)
    }).then(async r => {
        const data = await r.json();
        if (!r.ok) throw new Error(data.message || 'Ошибка');
        return data;
    });
}

// ✅ Обновление цены строки
function updateItemTotal(productId, quantity) {
    const row = document.querySelector(`[data-cart-item="${productId}"]`);
    if (!row) return;

    const priceCell = row.querySelector('[data-item-price]');
    const totalCell = row.querySelector('[data-item-total]');

    if (!priceCell || !totalCell) return;

    const price = parseFloat(priceCell.getAttribute('data-price'));
    if (isNaN(price)) return;

    const total = price * quantity;

    totalCell.textContent = total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' ₽';
}

// ✅ Обновление общего итога
function updateCartTotal(total) {
    const el = document.querySelector('[data-cart-total]');
    if (!el) return;

    el.textContent = total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ' ') + ' ₽';
}

// ✅ Исправленный handleQuantityChange
function handleQuantityChange(input) {
    const productId = input.dataset.productId;
    let quantity = parseInt(input.value);

    // ✅ Не даем уйти в 0 / NaN
    if (!quantity || quantity < 1) {
        quantity = 1;
        input.value = 1;
    }

    updateQuantity(productId, quantity)
        .then(data => {
            updateCartCount(data.cart_count);
            updateItemTotal(productId, quantity);

            if (data.total !== undefined) {
                updateCartTotal(data.total);
            }

            showNotification('Количество обновлено');
        })
        .catch(() => {
            showNotification('Ошибка при обновлении количества', 'error');
        });
}

function updateQuantity(productId, quantity) {
    return cartRequest('/api/cart/update', 'PUT', {
        product_id: productId,
        quantity: quantity
    });
}

// === ИНИЦИАЛИЗАЦИЯ ===
document.addEventListener('DOMContentLoaded', () => {

    // Загрузка состояния и установка кнопок
    if (getCsrfToken()) {
        fetch('/api/cart/state', {
            method: 'GET',
            headers: {'Accept':'application/json'}
        })
            .then(r => r.json())
            .then(data => {
                if (!data.success) return;

                updateCartCount(data.cart_count);

                document.querySelectorAll('[data-product-id]').forEach(btn => {
                    const id = String(btn.dataset.productId);
                    const inCart = data.items.some(i => String(i.product_id) === id);
                    updateButtonState(btn, inCart);
                });
            })
            .catch(() => {});
    }

    // === Клики ===
    document.addEventListener('click', e => {
        const addBtn = e.target.closest('[data-cart-add]');
        const removeBtn = e.target.closest('[data-cart-remove]');

        // Добавить
        if (addBtn) {
            e.preventDefault();
            const id = addBtn.dataset.productId;
            const qty = parseInt(addBtn.dataset.quantity) || 1;

            setButtonLoading(addBtn, true);

            cartRequest('/api/cart/add', 'POST', {product_id:id, quantity:qty})
                .then(data => {
                    updateButtonState(addBtn, true);
                    updateCartCount(data.cart_count);
                    showNotification(data.message || 'Добавлено в корзину');
                })
                .catch(err => showNotification(err.message, 'error'))
                .finally(() => setButtonLoading(addBtn, false));
        }

        // Удалить
        if (removeBtn) {
            e.preventDefault();
            const id = removeBtn.dataset.productId;
            const fromCartPage = !!removeBtn.closest('[data-cart-item]');

            setButtonLoading(removeBtn, true);

            cartRequest('/api/cart/remove', 'DELETE', {product_id:id})
                .then(data => {
                    updateCartCount(data.cart_count);
                    showNotification(data.message || 'Удалено');

                    if (fromCartPage) {
                        const row = removeBtn.closest('[data-cart-item]');
                        if (row) {
                            row.style.opacity = 0;
                            setTimeout(() => {
                                row.remove();
                                if (data.cart_count === 0) location.reload();
                            }, 250);
                        }
                    } else {
                        updateButtonState(removeBtn, false);
                    }
                })
                .catch(err => showNotification(err.message, 'error'))
                .finally(() => setButtonLoading(removeBtn, false));
        }
    });

    // === Изменение количества ===
    document.addEventListener('input', e => {
        const qtyInput = e.target.closest('[data-cart-quantity]');
        if (!qtyInput) return;

        clearTimeout(qtyInput._timer);
        qtyInput._timer = setTimeout(() => handleQuantityChange(qtyInput), 400);
    });
});
