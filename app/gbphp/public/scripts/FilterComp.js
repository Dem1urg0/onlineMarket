Vue.component('filter-el', {
    data() {
        return {
            userSearch: '',
            products: {
                woman: [],
                man: []
            },
            filtered: {
                woman: [],
                man: []
            },
            show:false
        }
    },
    methods: {
        filtration(value) {
            this.filtered = {
                woman: this.filterProducts(this.products.woman, value),
                man: this.filterProducts(this.products.man, value)
            };

        },
        filterProducts(products, value) {
            let regexp = new RegExp(value, 'i');
            return products.filter(product => regexp.test(product));
        },
    },
    mounted() {
        this.$parent.getJson('/api/clothing-types')
            .then(data => {
                for (let el of data) {
                    if (el.gender === 'Woman') {
                        this.products.woman = el.clothes;
                        this.filtered.woman = el.clothes;
                    } else {
                        this.products.man = el.clothes;
                        this.filtered.man = el.clothes;
                    }
                }
            }).catch(error => {
            console.log(error);
        });
    },
    template: `
      <form action="#" class="form">
        <button class="form__browse" @click="show=!show">
          Browse
          <drop-filter ref="drop-filter" :filteredWoman="filtered.woman" :filteredMan="filtered.man"
                       v-if="this.show"></drop-filter>
        </button>
        <input type="text" v-model="userSearch">
        <button @click="filtration(userSearch)" class="form__search"><img
            src="/style/img/search.svg" alt="search" width="18px" height="18px"></button>
      </form>
    `
});