<template>
  <section class="arrivals__info container">
    <div class="order_info address">
      <div @click="blocksShow.address = !blocksShow.address" class="a01"><h2>01. Shipping Address</h2></div>
      <div class="address_content" v-if="blocksShow.address">
        <div class="lastCheck">
          <h4>COUNTRY</h4>
          <select class="pass" style='width: 392px; height: 47px;' v-model="userData.address.country.value">
            <option selected disabled>Select country</option>
            <option v-for="country in countries" :value="country.country">{{ country.country }}</option>
          </select>
          <p class="required" v-if="blocksError.address.country">* Required Fileds</p>
          <h4>CITY</h4>
          <input type="text" class="pass" v-model="userData.address.city.value">
          <p class="required" v-if="blocksError.address.city">* Required Fileds</p>
          <h4>ADDRESS</h4>
          <input type="text" class="pass" v-model="userData.address.address.value">
          <p class="required" v-if="blocksError.address.address">* Required Fileds</p>
          <h4>ZIP</h4>
          <input type="text" class="pass" v-model="userData.address.zip.value">
          <p class="required" v-if="blocksError.address.zip">* Required Fileds</p>
          <br>
          <button class="lastCheck__button" @click="ValidTest('address')">NEXT</button>
        </div>
      </div>
      <div class="line"></div>
    </div>
    <div class="order_info">
      <div @click="blocksShow.billing = !blocksShow.billing" class="a01"><h2>02. BILLING INFORMATION</h2></div>
      <div class="address_content" v-if="blocksShow.billing">
        <div class="lastCheck">
          <h4>FIRST NAME</h4>
          <input type="text" class="email country" v-model="userData.billing.first.value">
          <p class="required" v-if="blocksError.billing.first">* Required Fileds</p>
          <h4>SECOND NAME</h4>
          <input type="text" class="pass" v-model="userData.billing.second.value">
          <p class="required" v-if="blocksError.billing.second">* Required Fileds</p>
          <h4>SURNAME</h4>
          <input type="text" class="pass" v-model="userData.billing.sur.value">
          <p class="required" v-if="blocksError.billing.sur">* Required Fileds</p>
          <br>
          <button class="lastCheck__button" @click="ValidTest('billing')">NEXT</button>
        </div>
      </div>
      <div class="line"></div>
    </div>
    <div class="order_info">
      <div class="a01" @click="blocksShow.shipping = !blocksShow.shipping"><h2>03. SHIPPING METHOD</h2></div>
      <div class="address_content" v-if="blocksShow.shipping">
        <div class="lastCheck">
          <h4>SHIPPING METHOD</h4>
          <select
              style='width: 370px;height: 45px;border: solid 1px #eaeaea; background-color: #fff; padding-left: 20px;'
              name="Method" v-model="userData.shipping.method.value">
            <option value="airplane">airplane</option>
            <option value="car">car</option>
            <option value="ship">ship</option>
          </select>
          <br>
          <p class="required" v-if="blocksError.shipping.method">* Required Fileds</p>
          <br>
          <button class="lastCheck__button" @click="ValidTest('shipping')">NEXT</button>
        </div>
      </div>
      <div class="line"></div>
    </div>
    <div class="order_info">
      <div class="a01" @click="blocksShow.review = !blocksShow.review "><h2>04. ORDER REVIEW</h2></div>
      <div class="address_content" v-if="blocksShow.review">
        <div class="lastCheck">
          <div class="lastCheck">
            <div class="lastCheck__data">
              <div class="lastCheck__data__address">
                <h4>ADDRESS</h4>
                <p>COUNTRY: {{ this.userData.address.country.value }}</p>
                <p>CITY: {{ this.userData.address.city.value }}</p>
                <p>ADDRESS: {{ this.userData.address.address.value }}</p>
                <p>ZIP: {{ this.userData.address.zip.value }}</p>
              </div>
              <div class="lastCheck__data__billing">
                <h4>BILLING</h4>
                <p>FIRST NAME: {{ this.userData.billing.first.value }}</p>
                <p>SECOND NAME: {{ this.userData.billing.second.value }}</p>
                <p>SURNAME: {{ this.userData.billing.sur.value }}</p>
                <p></p>
              </div>
              <div class="lastCheck__data__shipping">
                <h4>SHIPPING</h4>
                <p>METHOD: {{ this.userData.shipping.method.value }}</p>
              </div>
            </div>
            <h2>YOUR ORDER</h2>
            <div class="line" style="margin-bottom: 20px;"></div>
            <cart-page-items @total-changed="totalChanged" ref="cart-page-items"></cart-page-items>
            <br>
            <div class="lastCheck__order">
              <div class="info__discount">
                <h2>COUPON DISCOUNT</h2>
                <p>Enter your coupon code if you have one</p>
                <input type="text" v-model="code.value" placeholder="Code">
                <div class="info__discount__button-block">
                  <button @click="checkCode()">APPLY COUPON</button>
                  <p v-if="code.codeRes === 404" style="color: red;">Invalid code</p>
                  <p v-if="code.codeRes === 400" style="color: red;">Fill all data</p>
                  <p v-if="code.codeRes === 200" style="color: green;">Correct code</p>
                </div>
              </div>
              <div class="info__total-price">
                <div class="info__total-price__prices">
                  <p style="margin-bottom: 0; font-size: 16px; color:black" class='info__total-price__prices__sale'>
                    Discount: {{ code.sale }}%</p>
                  <div class="info__total-price__block">
                    <div class="info__total-price__block__sub">
                      <p style="margin-bottom: 0;">SUB TOTAL</p>
                      <p style="margin-bottom: 0;">{{ total.totalPrice }}</p>
                    </div>
                    <div class="info__total-price__block__grand">
                      <h2>GRAND TOTAL</h2>
                      <p style="margin-bottom: 0;">{{ total.totalSale }}</p>
                    </div>
                  </div>
                </div>
                <div class="info__total-price__line"></div>
                <button @click="SendOrder()">Create order</button>
              </div>
            </div>
          </div>
        </div>
        <div class="line" style="margin-top: 20px;"></div>
      </div>
    </div>
  </section>
</template>
<script>
/**
 * Импортирует компонент товаров в корзине
 */
import CartPageItems from "./cart/page/CartPageItems.vue";

/**
 * Компонент страницы оформления заказа
 */
export default {
  name: 'billing-page',
  components: {CartPageItems},
  /**
   * Ожидает получить данные
   */
  props: {
    /**
     * Текущий пользователь
     */
    currentUser: {
      type: Object,
      required: true
    },
    /**
     * Страны
     */
    countries: {
      type: Object,
      required: true
    }
  },

  /**
   * Реактивные данные компонента
   * @returns {{blocksError: {address: {zip: boolean, country: boolean, address: boolean, city: boolean}, shipping: {method: boolean}, billing: {sur: boolean, first: boolean, second: boolean}}, total: {totalPrice: number, totalSale: number}, userData: {address: {zip: {valid: string, value: string}, country: {valid: string, value: string}, address: {valid: string, value: string}, city: {valid: string, value: string}}, shipping: {method: {valid: string, value: string}}, billing: {sur: {valid: string, value: string}, first: {valid: string, value: string}, second: {valid: string, value: string}}}, code: {codeRes: boolean, sale: number, value: string}, blocksShow: {address: boolean, shipping: boolean, review: boolean, billing: boolean}}}
   */
  data() {
    return {
      userData: {
        address: {
          country: {value: '', valid: 'letters'},
          city: {value: '', valid: 'letters'},
          address: {value: '', valid: 'letters'},
          zip: {value: '', valid: 'numbers'},
        },
        billing: {
          first: {value: '', valid: 'letters'},
          second: {value: '', valid: 'letters'},
          sur: {value: '', valid: 'letters'},
        },
        shipping: {
          method: {value: '', valid: 'letters'},
        },
      },
      blocksError: {
        address: {
          country: false,
          city: false,
          address: false,
          zip: false,
        },
        billing: {
          first: false,
          second: false,
          sur: false,
        },
        shipping: {
          method: false,
        }
      },
      blocksShow: {
        address: true,
        billing: false,
        shipping: false,
        review: false,
      },
      code: {
        value: '',
        codeRes: false,
        sale: 0,
      },
      total: {
        totalPrice: 0,
        totalSale: 0,
      },
    }
  },
  /**
   * Код выполняется после монтирования компонента.
   * Подгружает данные о доставке из сессии
   */
  mounted() {
    const sessionDeliveryInfo = JSON.parse(sessionStorage.getItem('deliveryInfo'));
    if (!sessionDeliveryInfo) {
      return;
    }
    this.code.value = sessionDeliveryInfo.code;
    const deliveryInfo = sessionDeliveryInfo.deliveryInfo;
    this.userData.address.country.value = deliveryInfo.country.value;
    this.userData.address.city.value = deliveryInfo.city.value;
    this.userData.address.zip.value = deliveryInfo.zip.value;
  },

  /**
   * Методы компонента
   */
  methods: {
    /**
     * Проверка валидности данных
     * @param name - имя блока данных для проверки
     * @constructor
     */
    ValidTest(name) {
      if (!name) {
        return;
      }

      Object.entries(this.userData[name]).forEach(([key, {value, valid}]) => {
        const validationPatterns = {
          letters: /^[A-Za-z]+$/,
          numbers: /^[0-9]+$/
        };

        const pattern = validationPatterns[valid];
        const isValid = pattern && pattern.test(value) && value !== '';

        this.blocksError[name][key] = !isValid;
      })
      this.CheckBlock(name);
    },

    /**
     * Проверка блока данных на наличие ошибки. Если ошибка есть, то блок ошибки для блока отображается
     * @param name - имя блока данных
     * @constructor
     */
    CheckBlock(name) {
      const hasError = Object.values(this.blocksError[name]).some(value => value);

      if (hasError) {
        this.blocksShow[name] = true;
      } else {
        this.blocksShow[name] = false;

        let nextBlock = Object.keys(this.blocksShow)[Object.keys(this.blocksShow).indexOf(name) + 1];
        if (nextBlock) {
          this.blocksShow[nextBlock] = true;
        }
      }
    },

    /**
     * Проверка всех блоков на наличие ошибок
     * @returns {boolean} - true, если ошибок нет, иначе false
     * @constructor
     */
    CheckAllBlocks() {
      return !Object.entries(this.blocksError).some(([_, value]) => {
        return Object.values(value).some(bool => bool);
      });
    },

    /**
     * Формирование объекта заказа
     * @returns {{address: {zip: string, country: string, address: string, city: string}, code: {value}, shipping: {method: string}, billing: {sur: string, first: string, second: string}}}
     * @constructor
     */
    Order() {
      return {
        address: {
          country: this.userData.address.country.value,
          city: this.userData.address.city.value,
          address: this.userData.address.address.value,
          zip: this.userData.address.zip.value,
        },
        billing: {
          first: this.userData.billing.first.value,
          second: this.userData.billing.second.value,
          sur: this.userData.billing.sur.value,
        },
        shipping: {
          method: this.userData.shipping.method.value,
        },
        code: {
          value: this.code.value,
        }
      }
    },

    /**
     * Отправка заказа на сервер
     * @returns {Promise<void>}
     * @constructor
     */
    async SendOrder() {
      if (this.currentUser.id === 0) {
        location.href = '/auth/';
        return;
      }
      if (this.code.value !== '') {
        await this.checkCode();
        if (this.code.codeRes !== 200) {
          return;
        }
      }
      Object.keys(this.userData).forEach((key) => {
        this.ValidTest(key)
      })
      if (sessionStorage.getItem('cart') === null || JSON.parse(sessionStorage.getItem('cart')).length === 0) {
        alert('Cart is empty');
        return;
      }

      if (this.CheckAllBlocks()) {
        this.$root.postJson('/api/order/set', {
          order: this.Order(),
          cart: JSON.parse(sessionStorage.getItem('cart'))
        })
            .then(response => {
              switch (response.code) {
                case 200:
                  sessionStorage.setItem('cart', JSON.stringify([]));
                  location.href = '/order/';
                  break;
                case 401:
                  location.href = '/auth/';
                  break;
                default:
                  alert(`Error: ${response.msg}`);
                  break;
              }
            }).catch(error => {
          console.log('Error:', error);
        })
      } else {
        alert('Заполните все поля')
      }
    },

    /**
     * Обработчик события изменения общей стоимости товаров в корзине
     * @param value - новая общая стоимость товаров в корзине
     */
    totalChanged(value) {
      this.total.totalPrice = value;
      this.fillTotal()
    },

    /**
     * Заполнение общей стоимости заказа
     */
    fillTotal() {
      this.total.totalSale = this.total.totalPrice - this.total.totalPrice * this.code.sale / 100;
    },

    /**
     * Проверка кода на скидку на сервере
     * @returns {Promise<void>}
     */
    async checkCode() {
      if (this.userData.address.country.value === '' || this.code.value === '') {
        this.codeRes = 400;
        return;
      }

      this.code.sale = 0;
      this.code.response = false;

      try {
        const response = await this.$root.postJson('/api/code/checkCode', {
          code: this.code.value,
          country: this.userData.address.country.value
        });

        if (!response) {
          console.error('Некорректный ответ от API:', response);
          this.codeRes = 400;
          return;
        }

        switch (response.code) {
          case 404:
            this.code.codeRes = 404;
            break;
          case 400:
            this.code.codeRes = 400;
            break;
          case 200:
            this.code.sale = response.sale;
            this.fillTotal();
            this.code.codeRes = 200;
            break;
        }
      } catch (error) {
        console.error('Error in checkCode:', error);
      }
    }
  }
}
</script>