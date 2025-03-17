function getCart() {
    return sessionStorage.getItem('cart') ? JSON.parse(sessionStorage.getItem('cart')) : [];
}

function findItem(product, cart) {
    return cart.find(el =>
        el.id === product.id &&
        el.color === product.color &&
        el.size === product.size
    );
}

function saveCart(cart) {
    sessionStorage.setItem('cart', JSON.stringify(cart));
}

function addProduct(product) {
    let cart = getCart();
    const findLink = findItem(product, cart);

    if (!product.count) {
        product.count = 1;
    }

    if (findLink) {
        findLink.count = Number(findLink.count) + 1;
    } else {
        cart.push({...product});
    }

    console.log('Товар добавлен в корзину');
    saveCart(cart);
}

function decProduct(product) {
    let cart = getCart();
    const findLink = findItem(product, cart);

    if (findLink) {
        if (findLink.count > 1) {
            findLink.count--;
        } else {
            removeProduct(product);
        }
    } else {
        console.error('Error: product not found');
        return;
    }
    console.log('Количество товара уменьшено');
    saveCart(cart);
}

function removeProduct(product) {
    let cart = getCart();

    const index = cart.findIndex(el =>
        el.id === product.id &&
        el.color === product.color &&
        el.size === product.size
    );

    if (index !== -1) {
        cart.splice(index, 1);
    }
    saveCart(cart);
}

function total(cart) {
    return cart.reduce((acc, item) => acc + item.price * item.count, 0);
}

function clearCart() {
    sessionStorage.removeItem('cart');
}
