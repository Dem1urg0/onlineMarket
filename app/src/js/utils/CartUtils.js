/**
 * Утилиты для работы с корзиной
 */

/**
 * Получение корзины из сессии
 * @returns {any|*[]} - корзина
 */
export function getCart() {
    return sessionStorage.getItem('cart') ? JSON.parse(sessionStorage.getItem('cart')) : [];
}

/**
 * Поиск товара в корзине
 * @param product - товар для поиска
 * @param cart - корзина
 * @returns {*} - ссылка на товар в корзине или undefined
 */
function findItem(product, cart) {
    return cart.find(el =>
        el.id === product.id &&
        el.color === product.color &&
        el.size === product.size
    );
}

/**
 * Сохранение корзины в сессию
 * @param cart - корзина
 */
function saveCart(cart) {
    sessionStorage.setItem('cart', JSON.stringify(cart));
}

/**
 * Добавление товара в корзину
 * @param product - товар для добавления
 */
export function addProduct(product) {
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

/**
 * Уменьшение количества товара в корзине на 1
 * @param product - товар для уменьшения
 */
export function decProduct(product) {
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

/**
 * Удаление товара из корзины
 * @param product - товар для удаления
 */
export function removeProduct(product) {
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

/**
 * Подсчет общей цены корзины
 * @param cart - корзина
 * @returns {*} - общая цена
 */
export function total(cart) {
    return cart.reduce((acc, item) => acc + item.price * item.count, 0);
}

/**
 * Очистка корзины
 */
export function clearCart() {
    sessionStorage.removeItem('cart');
}
