
// Vue.component('drop-filter', {
//     data: {
//         show: false,
//     },
//     methods: {
//         getHrefGood,
//     },
//     props: ['filteredWoman', 'filteredMan'],
//     template: `
//       <div class="drop drop__browse">
//         <div class="drop__browse__flex" v-if="filteredWoman.length != 0">
//           <h3 class="drop__h3 drop__browse__h3">Women</h3>
//           <ul class="drop__menu">
//             <li v-for="item of filteredWoman" :key="filteredWoman.indexOf('item')">
//               <a :href="getHrefGood(item.id)" class="drop__link">{{ item }}</a>
//             </li>
//           </ul>
//         </div>
//         <div class="drop__browse__flex" v-if="filteredMan.length != 0">
//           <h3 class="drop__h3 drop__browse__h3">Men</h3>
//           <ul class="drop__menu">
//             <li v-for="item of filteredMan" :key="filteredMan.indexOf('item')">
//               <a :href="getHrefGood(item.id)" class="drop__link">{{ item }}</a></li>
//           </ul>
//         </div>
//         <img style="width: 236px" v-if="filteredWoman.length === 0" src="html/img/no_product_found.png" alt="no_product_img">
//       </div>
//     `
// })

/**
 * Компонент для отображения выпадающего меню с фильтрами для товаров
 */
Vue.component('drop-def', {
    /**
     * Пропсы компонента
     */
    props: ['categories', 'brands', 'designers'],

    /**
     * Реактивные данные компонента
     * @returns {{designersNames: *[], show: boolean, brandsNames: *[], categoriesNames: *[], genders: string[], titles: string[]}}
     */
    data() {
        return {
            show: false,
            titles: [
                'Man',
                'Woman',
                'Unisex',
                'Categories',
                'Brands',
                'Designers'
            ],
            genders: [
                'Man',
                'Woman',
                'Unisex',
            ],
            brandsNames: [],
            categoriesNames: [],
            designersNames: [],
        }
    },

    /**
     * Код, который выполняется при монтировании компонента
     * Инициализация данных
     */
    mounted() {
        this.initData();
    },

    /**
     * Методы компонента
     */
    methods: {

        /**
         * Инициализация данных из пропсов
         */
        initData() {
            this.brandsNames = this.brands;
            this.categoriesNames = this.categories;
            this.designersNames = this.designers;
        },

        /**
         * Показать/скрыть выпадающее меню
         */
        showDrop() {
            this.show = !this.show;
        },

        /**
         * Получить ссылку для заголовка
         * @param title - название заголовка
         * @returns {string} - ссылка
         */
        getLinkForTitle(title) {
            let link = '';
            if (!title || !this.titles.includes(title)) {
                return link;
            }

            if (this.genders.includes(title)) {
                return link = '/good/all?gender=' + title.toLowerCase();
            }

        },

        /**
         * Получить ссылку для фильтра
         * @param title - название заголовка
         * @param name - название фильтра
         * @returns {string} - ссылка
         */
        getLinkForFilter(title, name) {
            let link = '';
            if (!title || !name || !this.titles.includes(title)) {
                return link;
            }

            if (title === 'Categories') {
                const category = this.categoriesNames.find(item => item.name === name);
                return link = '/good/all?category=' + category.name;
            }
            if (title === 'Brands') {
                const brand = this.brandsNames.find(item => item.name === name);
                return link = '/good/all?brands=' + brand.name;
            }
            if (title === 'Designers') {
                const designer = this.designersNames.find(item => item.name === name);
                return link = '/good/all?designers=' + designer.name;
            }
        }
    },
    /**
     * Шаблон компонента
     */
    template: `
      <ul class="menu">
        <li class="menu__list"><a href="/home" class="menu__link">Home</a></li>
        <div v-for="item of titles">
          <li class="menu__list"><a :href="getLinkForTitle(item)" class="menu__link">{{ item }}</a>
            <div v-if="!genders.includes(item)" class="drop drop_middle"> 
             <div class="drop__flex">
                <h3 class="drop__h3 drop__browse__h3">{{ item }}</h3>
                <ul class="drop__menu">
                  <li v-if="item === 'Categories'" v-for="name in categoriesNames"><a :href="getLinkForFilter(item, name.name)" class="drop__link">{{ name.name }}</a></li>
                  <li v-if="item === 'Brands'" v-for="name in brandsNames"><a :href="getLinkForFilter(item, name.name)" class="drop__link" >{{ name.name }}</a></li>
                  <li v-if="item === 'Designers'" v-for="name in designersNames"><a :href="getLinkForFilter(item, name.name)" class="drop__link">{{ name.name }}</a></li>
                </ul>
              </div>
            </div>
          </li>
        </div>
      </ul>
    `
})
/**
 * Компонент для отображения выпадающего меню с аккаунтом пользователя
 */
Vue.component('account', {
    /**
     * Пропсы компонента
     */
    props: ['currentUser'],

    /**
     * Реактивные данные компонента
     * @returns {{show: boolean}}
     */
    data() {
        return {
            show: false,
        };
    },

    /**
     * Методы компонента
     */
    methods: {
        /**
         * Получить ссылку на страницу пользователя (pathUtils.js)
         */
        getHrefUser,
    },
    /**
     * Шаблон компонента
     */
    template: `
      <button @click="show = !show" class="button Account">
        My Account
        <div class="drop_acc" v-if="show && (currentUser.id !== 0)">
            <li><a :href="getHrefUser(currentUser.id)">MY ACCOUNT</a></li>
            <li><a href="/order/all">MY ORDERS</a></li>
            <li><a href="/auth/logout">LOG-OUT</a></li>
        </div>
        <div class="drop_acc" v-if="show && (currentUser.id === 0)">
            <li><a href="/auth/">LOG-IN</a></li>
            <li><a href="/user/add/">REGISTER</a></li>
        </div>
      </button>
    `
});
