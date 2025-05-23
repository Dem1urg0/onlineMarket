<template>
  <div class="order-container">
    <div class="order-container__header">Order Details</div>
    <div class="order-container__details">
      <div style="display: flex; justify-content: center">
        <div>
          <p><span>order id:</span> {{ orderKey }}</p>
          <p><span>user id:</span> {{ orderInfo.user_id }}</p>
        </div>
      </div>
      <div class="order-container__line"></div>
      <div style="display: flex; justify-content: center; flex-wrap: wrap; margin: 10px 0">
        <h3 style="width: 100px;">Products</h3>
        <div v-for="item in products" class="order-container__products">
          <p><span>NAME:</span> {{ item.name }}</p><p>|</p>
          <p><span>PRICE:</span> {{ item.price }}</p><p>|</p>
          <p><span>SIZE:</span> {{ item.size }}</p><p>|</p>
          <p><span>COLOR:</span> {{ item.color }}</p><p>|</p>
          <p><span>COUNT:</span> {{ item.count }}</p>
        </div>
        <div class="order-container__price">
          <h3><span>Total price:</span> {{ totalPrice }}</h3>
          <h3 v-if="orderInfo.sale"><span>Total with sale:</span> {{ totalPriceWithSale }}</h3>
        </div>
      </div>
      <div class="order-container__line"></div>
      <div class="order-container__info">
        <div class="order-container__info__address">
          <h3>Address</h3>
          <p><span>country:</span> {{ orderInfo.country }}</p>
          <p><span>city:</span> {{ orderInfo.city }}</p>
          <p><span>address:</span> {{ orderInfo.address }}</p>
          <p><span>zip:</span> {{ orderInfo.zip }}</p>
        </div>
        <div class="order-container__info__billing">
          <h3>Billing</h3>
          <p><span>first name:</span> {{ orderInfo.first }}</p>
          <p><span>second name:</span> {{ orderInfo.second }}</p>
          <p v-if="orderInfo.sur"><span>sur name:</span> {{ orderInfo.sur }}</p>
        </div>
        <div class="order-container__info__info">
          <h3>Info</h3>
          <p><span>shipping:</span> {{ orderInfo.shipping }}</p>
          <p v-if="orderInfo.code"><span>code:</span> {{ orderInfo.code }}</p>
          <p><span>order date:</span> {{ orderInfo.date }}</p>
          <p><span>status:</span> {{ setStatus }}</p>
        </div>
      </div>
      <div class="order-container__line"></div>
    </div>
    <div class="order-container__actions">
      <div v-if="currentUser.role === 1">
        <select v-model="status">
          <option disabled>Выберите</option>
          <option v-if="setStatus !== 'created'" value="created">created</option>
          <option v-if="setStatus !== 'in delivery'" value="in delivery">in delivery</option>
          <option v-if="setStatus !== 'delivered'" value="delivered">delivered</option>
        </select>
        <button class="order-container__logout-btn" @click="changeOrderStatus(orderKey)">
          Сменить статус
        </button>
      </div>
      <button v-if="currentUser.role === 1 || setStatus === 'created'"
              class="order-container__logout-btn order-container__cancel-btn"
              @click="deleteOrder(orderKey)">
        Отменить заказ
      </button>
    </div>
  </div>
</template>

<script>
/**
 * Импорт утилиты для вычисления общей стоимости заказа
 */

import {total} from "@/js/utils/CartUtils.js";

/**
 * Компонент заказа на странице заказов
 */
export default {
  name: "order-page-order",

  /**
   * Пропсы компонента
   */
  props:{
    /**
     * Ключ заказа
     */
    orderKey: {
      type: String,
      required: true
    },
    /**
     * Текущий пользователь
     */
    currentUser: {
      type: Object,
      required: true
    },
    /**
     * Продукты заказа
     */
    products: {
      type: Array,
      required: true
    },
    /**
     * Информация о заказе
     */
    orderInfo: {
      type: Object,
      required: true
    }
  },
  /**
   * Реактивные данные компонента
   * @returns {{totalPrice: number, totalPriceWithSale: number, setStatus: string, status: string}}
   */
  data() {
    return {
      status: '',
      totalPrice: 0,
      totalPriceWithSale: 0,
      setStatus: '',
    }
  },

  /**
   * Код, который выполняется при монтировании компонента.
   * Вычисляет общую стоимость заказа и стоимость заказа со скидкой и устанавливает статус заказа.
   */
  mounted() {
    this.getTotalPrice();
    if (this.orderInfo.sale) {
      this.getTotalPriceWithSale();
    }
    this.setStatus = this.orderInfo.status;
  },

  /**
   * Методы компонента
   */
  methods: {
    /**
     * Вычисляет общую стоимость заказа
     */
    getTotalPrice() {
      this.totalPrice = total(this.products);
    },

    /**
     * Вычисляет общую стоимость заказа со скидкой
     */
    getTotalPriceWithSale() {
      this.totalPriceWithSale = this.totalPrice - (this.totalPrice * this.orderInfo.sale / 100);
    },

    /**
     * Меняет статус заказа
     * @param orderKey
     * @returns {Promise<void>}
     */
    async changeOrderStatus(orderKey) {

      if (this.currentUser.role !== 1) {
        return;
      }
      if (this.status === '') {
        alert('Выберите статус');
        return;
      }
      if (this.status === this.setStatus) {
        alert('Статус не изменен');
        return;
      }

      try {
        const response = await this.$root.postJson('/api/order/changeStatus', {
          order_id: orderKey,
          status: this.status
        });

        if (!response) {
          console.error('Некорректный ответ от API:', response);
          this.codeRes = 400;
          return;
        }
        switch (response.code) {
          case 200:
            this.setStatus = this.status;
            break;
          default:
            if (response.msg) {
              alert(response.msg);
            } else alert('Ошибка смены статуса');
            break;
        }
      } catch (error) {
        console.error('Ошибка:', error);
        this.codeRes = 400;
      }
    },

    /**
     * Удаляет заказ
     * @param orderKey
     * @returns {Promise<void>}
     */
    async deleteOrder(orderKey) {
      if (this.currentUser.role !== 1 && this.setStatus !== 'created') {
        return;
      }

      try {
        const response = await this.$root.postJson('/api/order/delete', {
          order_id: orderKey,
        });

        if (!response) {
          console.error('Некорректный ответ от API:', response);
          this.codeRes = 400;
          return;
        }
        switch (response.code) {
          case 200:
            this.$emit('delete-order', orderKey);
            break;
          default:
            if (response.msg) {
              alert(response.msg);
            } else alert('Ошибка удаления заказа');
            break;
        }
      } catch (error) {
        console.error('Ошибка:', error);
        this.codeRes = 400;
      }
    }
  },
}
</script>


