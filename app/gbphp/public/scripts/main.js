/**
 * Инициализация Vue
 * @type {Vue}
 */
const app = new Vue({
    /**
     * Элемент, к которому привязывается Vue
     */
    el: '#app',

    /**
     * Методы Vue
     */
    methods: {
        /**
         * Получение данных от сервера в формате JSON (метод GET)
         * @param url
         * @returns {Promise<any>}
         */
        getJson(url) {
            return fetch(url)
                .then(result => result.json())
                .catch(error => {
                    console.log(error)
                })
        },

        /**
         * Отправка данных на сервер в формате JSON (метод POST)
         * @param url
         * @param data
         * @returns {Promise<any>}
         */
        postJson(url, data) {
            return fetch(url, {
                method: 'POST',
                headers: {
                    "Content-Type": "application/json",
                    "Cache-Control": "no-cache",
                    "Pragma": "no-cache"
                },
                credentials: 'same-origin',
                body: JSON.stringify(data)
            }).then(result => result.json())
                .catch(error => {
                    console.log(error);
                    return null;
                });
        },

        /**
         * Отправка данных на сервер в формате JSON (метод PUT)
         * @param url
         * @param data
         * @returns {Promise<any>}
         */
        putJson(url, data) {
            return fetch(url, {
                method: 'PUT',
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(data)
            }).then(result => result.json())
                .catch(error => {
                    console.log(error)
                });
        },

        /**
         * Удаление данных на сервере в формате JSON (метод DELETE)
         * @param url
         * @returns {Promise<any>}
         */
        deleteJson(url) {
            return fetch(url, {
                method: 'DELETE',
                headers: {
                    "Content-Type": "application/json"
                },
            }).then(result => result.json())
                .catch(error => {
                    console.log(error)
                });
        },
    },
});
