/**
 * Инициализация Vue
 */

import mitt from 'mitt'
const emitter = mitt()
export { emitter }

import DropCart from './components/cart/drop/DropCart.vue';
import DropCartItem from './components/cart/drop/DropCartItem.vue';
import CartPage from './components/cart/page/CartPage.vue';
import CartPageItems from './components/cart/page/CartPageItems.vue';

import DropAccount from './components/drop/DropAccount.vue';
import DropDef from './components/drop/DropDef.vue';

import OrderPage from './components/order/OrderPage.vue';
import OrderPageOrder from './components/order/OrderPageOrder.vue';

import ProdItem from './components/product/ProdItem.vue';
import ProdPage from './components/product/ProdPage.vue';
import ProdOnHomePage from './components/product/ProdOnHomePage.vue';
import ProdPageRecommend from './components/product/ProdPageRecommend.vue';
import ProdsPage from './components/product/ProdsPage.vue';

import EditPage from './components/user/EditPage.vue';
import UserPage from './components/user/UserPage.vue';
import UsersPage from './components/user/UsersPage.vue';

import AuthForm from './components/AuthForm.vue';
import BillingPage from './components/BillingPage.vue';

export default {
    components: {
        DropCart, DropCartItem, CartPage, CartPageItems,
        DropAccount, DropDef, OrderPage, OrderPageOrder,
        ProdItem, ProdPage, ProdOnHomePage, ProdPageRecommend,
        ProdsPage, EditPage, UserPage, AuthForm, BillingPage, UsersPage
    },

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
}
