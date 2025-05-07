<template>
  <div>
    <section class="products container">
      <div class="products__detail">
        <p>product details</p>
        <p>unite Price</p>
        <p>Quantity</p>
        <p>shipping</p>
        <p>Subtotal</p>
        <p>ACTION</p>
      </div>
      <div>
        <div class="line products__item__line"></div>
        <cart-page-items ref="cart-page-items" @total-changed="totalChanged"></cart-page-items>
      </div>
    </section>
    <section class="info container">
      <div class="info__shipping-address">
        <h2 style="margin-bottom: 22px;">SHIPPING ADDRESS</h2>
        <div class="info__shipping-address__block">
          <select v-model="deliveryInfo.country.value">
            <option value="" disabled>Choose a country.</option>
            <option v-for="country of countries" :value="country.country">{{ country.country }}</option>
          </select>
          <p v-if="deliveryInfo.country.error">Choose country</p>
        </div>
        <div class="info__shipping-address__block">
          <input type="text" v-model="deliveryInfo.city.value" placeholder="City">
          <p v-if="deliveryInfo.city.error">Invalid city</p>
        </div>
        <div class="info__shipping-address__block">
          <input type="text" v-model="deliveryInfo.zip.value" placeholder="Postcode / Zip">
          <p v-if="deliveryInfo.zip.error">Invalid zip</p>
        </div>
      </div>
      <div class="info__discount">
        <h2>COUPON DISCOUNT</h2>
        <p>Enter your coupon code if you have one</p>
        <input type="text" v-model="code" placeholder="Code">
        <div class="info__discount__button-block">
          <button @click="checkCode()">APPLY COUPON</button>
          <p v-if="codeRes === 404" style="color: red;">Invalid code</p>
          <p v-if="codeRes === 400" style="color: red;">Fill all data</p>
          <p v-if="codeRes === 200" style="color: green;">Correct code</p>
        </div>
      </div>
      <div class="info__total-price">
        <div class="info__total-price__prices">
          <p style="margin-bottom: 0;" class='info__total-price__prices__sale'>Discount: {{ sale }}%</p>
          <div class="info__total-price__block">
            <div class="info__total-price__block__sub">
              <p style="margin-bottom: 0;">SUB TOTAL</p>
              <p style="margin-bottom: 0;">{{ total }}</p>
            </div>
            <div class="info__total-price__block__grand">
              <h2>GRAND TOTAL</h2>
              <p style="margin-bottom: 0;">{{ totalWithSale }}</p>
            </div>
          </div>
        </div>
        <div class="info__total-price__line"></div>
        <button @click="checkOut">proceed to checkout</button>
      </div>
    </section>
  </div>
</template>

<script>
/**
 * Импорт компонента товаров страницы корзины
 */
import CartPageItems from '@/js/components/cart/page/CartPageItems.vue';

/**
 * Компонент страницы корзины
 */
export default {
  name: "cart-page",
  /**
   * Импорт компонента
   */
  components: {CartPageItems},
  /**
   * Пропсы компонента
   */
  props:{
    /**
     * Список стран
     */
    countriesServer: {
      type: Array,
      required: true
    }
  },
  /**
   * Реактивные данные компонента
   * @returns {{codeRes: boolean, totalWithSale: number, total: number, deliveryInfo: {zip: {valid: string, error: boolean, value: string}, country: {valid: string, error: boolean, value: string}, city: {valid: string, error: boolean, value: string}}, sale: number, code: string, countries: *[]}}
   */
  data() {
    return {
      totalWithSale: 0,
      total: 0,
      countries: [],
      deliveryInfo: {
        country: {
          'value': '',
          'valid': 'letters',
          'error': false,
        },
        city: {
          'value': '',
          'valid': 'letters',
          'error': false,
        },
        zip: {
          'value': '',
          'valid': 'numbers',
          'error': false,
        },
      },
      code: '',
      codeRes: false,
      sale: 0,
    }
  },
  /**
   * Код выполняемый после монтирования компонента.
   * Получает список стран
   */
  mounted() {
    this.countries = this.countriesServer
  },

  /**
   * Методы компонента
   */
  methods: {
    /**
     * Обработчик изменения общей стоимости корзины
     * @param value - новая стоимость
     */
    totalChanged(value) {
      this.total = value;
      this.fillTotal()
    },

    /**
     * Подсчет общей стоимости с учетом скидки
     */
    fillTotal() {
      this.totalWithSale = this.total - this.total * this.sale / 100;
    },

    /**
     * Проверка валидности данных доставки
     */
    validTest() {
      const validators = {
        letters: value => /^[A-Za-z]+$/.test(value) && value !== '',
        numbers: value => /^[0-9]+$/.test(value) && value !== ''
      };

      Object.values(this.deliveryInfo).forEach(item => {
        item.error = !validators[item.valid](item.value);
      });
    },

    /**
     * Переход к оформлению заказа
     * @returns {Promise<void>}
     */
    async checkOut() {
      this.validTest();
      if (this.code !== '') {
        await this.checkCode();
      } else {
        this.codeRes = false;
      }

      const cartItems = JSON.parse(sessionStorage.getItem('cart') || '[]');
      if (cartItems.length === 0) {
        console.warn('Корзина пуста');
        return;
      }

      if (Object.values(this.deliveryInfo).some(item => item.error)) {
        console.warn('Есть ошибки в данных доставки');
        return;
      }

      if (this.total === 0 || this.totalWithSale === 0) {
        console.warn('Сумма заказа не может быть 0');
        return;
      }

      if (this.codeRes === 200 || this.codeRes === false) {
        const orderInfo = {
          deliveryInfo: this.deliveryInfo,
          code: this.code,
        };
        sessionStorage.setItem('deliveryInfo', JSON.stringify(orderInfo));
        window.location.href = '/order/checkout';
      }
    },

    /**
     * Проверка промокода на сервере
     * @returns {Promise<void>}
     */
    async checkCode() {
      if (!this.deliveryInfo.country.value || !this.code) {
        this.codeRes = 400;
        console.warn('Недостаточно данных для проверки промокода');
        return;
      }

      this.sale = 0;
      this.codeRes = false;

      try {
        const response = await this.$root.postJson('/api/code/checkCode', {
          code: this.code,
          country: this.deliveryInfo.country.value
        });

        if (!response) {
          console.error('Некорректный ответ от API:', response);
          this.codeRes = 400;
          return;
        }

        switch (response.code) {
          case 404:
            this.codeRes = 404;
            break;
          case 400:
            this.codeRes = 400;
            break;
          case 200:
            this.sale = response.sale || 0;
            this.fillTotal();
            this.codeRes = 200;
            break;
          default:
            console.warn('Неизвестный код ошибки:', response.code);
            this.codeRes = 400;
        }
      } catch (error) {
        console.error('Ошибка при проверке промокода:', error);
        this.codeRes = 400;
      }
    },
  },
}
</script>


