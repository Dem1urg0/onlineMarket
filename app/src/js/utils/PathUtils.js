/**
 * Утилиты для работы с путями
 */

/**
 * Путь к странице товара
 * @returns {string} - путь к странице товара
 */
export function getHrefGood(id) {
    return `/good/one?id=${id}`
}

/**
 * Путь к странице пользователя
 * @param id - id пользователя
 * @returns {string} - путь к странице пользователя
 */
export function getHrefUser(id) {
    return `/user/one?id=${id}`
}