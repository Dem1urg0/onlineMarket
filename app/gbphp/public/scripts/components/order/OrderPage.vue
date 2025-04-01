
<template>
  <div class="orders">
    <p v-if="Object.keys(renderOrders).length === 0">Orders not found</p>
    <order-page-order
        ref="order"
        @delete-order="emitDeleteOrder"
        v-else
        v-for="(orderData, key) in renderOrders"
        :key="key"
        :products="orderData.products"
        :order-info="orderData.info"
        :order-key="key"
        :current-user="currentUser">
    </order-page-order>
  </div>
</template>

<script>
/**
 * Импорт компонента заказа на странице заказов
 */
import OrderPageOrder from "./OrderPageOrder.vue";

/**
 * Компонент для страницы заказов
 */
export default {
  name: "order-page",
  components: {OrderPageOrder},
  /**
   * Пропсы компонента
   */
  props:{
    /**
     * Заказы
     */
    orders: {
      type: Object,
      required: true
    },
    /**
     * Текущий пользователь
     */
    currentUser: {
      type: Object,
      required: true
    }
  },

  /**
   * Реактивные данные компонента
   * @returns {{renderOrders: string}}
   */
  data() {
    return {
      renderOrders: '',
    }
  },
  /**
   * Код, который выполняется при монтировании компонента
   */
  mounted() {
    this.renderOrders = this.orders;
  },

  /**
   * Методы компонента
   */
  methods: {
    /**
     * Эмит события удаления заказа
     * @param orderKey
     */
    emitDeleteOrder(orderKey) {
      delete this.renderOrders[orderKey];
    }
  },
}
</script>

