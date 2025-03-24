/**
 * Утилиты для работы с путями
 * @param img
 * @returns {string}
 */

/**
 * Получить путь к картинке
 * @param img - название картинки
 * @returns {string} - путь к картинке
 */
function hrefImg(img) {
    return `/style/img/${img}`
}

/**
 * Путь к странице товара
 * @param id - id товара
 * @returns {string} - путь к странице товара
 */
function getHrefGood(id) {
    return `/good/one?id=${id}`
}

/**
 * Путь к странице пользователя
 * @param id - id пользователя
 * @returns {string} - путь к странице пользователя
 */
function getHrefUser(id) {
    return `/user/one?id=${id}`
}