/**
 * Сортировка хранилища товара
 * @param storage - хранилище товара
 * @returns result - отсортированное хранилище
 */
export function sortStorage(storage) {
    let result = {};
    for (let i = 0; i < storage.length; i++) {
        const item = storage[i];
        const size = item.size;
        const color = item.color;

        if (!result[size]) {
            result[size] = [];
        }
        result[size].push(color);
    }
    return result;
}

/**
 * Сортировка хранилища товара для всех товаров
 * @param storages
 * @returns result
 */
export function sortStorageForGoods(storages) {
    return Object.entries(storages).reduce((result, [key, value]) => {
        result[key] = sortStorage(value);
        return result;
    }, {});
}

/**
 * Добавление хранилища к товарам
 * @param goods - товары
 * @param storages - хранилища
 * @returns goods - товары с хранилищами
 */
export function addStoragesToGoods(goods, storages) {
    goods.forEach(good => {
        if (storages[good.id]) {
            good.storage = storages[good.id];
        }
    });
    return goods;
}