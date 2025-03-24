/**
 * Компонент для редактирования данных пользователя
 */
Vue.component('edit-page', {
    /**
     * Пропсы компонента
     */
    props: ['currentUser', 'userData'],

    /**
     * Реактивные данные компонента
     * @returns {{password: string, name: string, login: string, errors: {password: boolean, login: boolean}}}
     */
    data() {
        return {
            'login': '',
            'name': '',
            'password': '',
            errors: {
                'login': false,
                'password': false
            },

        }
    },

    /**
     * Код вызываемый при монтировании компонента
     */
    mounted() {
        this.login = this.userData['login']
        this.name = this.userData['name']
    },

    /**
     * Методы компонента
     */
    methods: {
        /**
         * Смена данных пользователя на сервере
         * @returns {Promise<void>}
         */
        async change() {

            if (this.login === this.userData['login'] && this.password === '' || this.password === '' && this.login === '') {
                return
            }

            if (this.login !== this.userData['login'] && this.login !== '') {
                this.validateLogin();
                if (this.errors.login) {
                    return;
                }
            } else {
                this.login = '';
            }

            if (this.password !== '') {
                this.validatePassword();
                if (this.errors.password) {
                    return;
                }
            }
            try {
                const response = await this.$root.postJson('/api/user/UserEdit', {
                    login: this.login,
                    password: this.password
                });

                if (!response) {
                    console.error('Некорректный ответ от API:', response);
                    this.codeRes = 400;
                    return;
                }

                switch (response.code) {
                    case 200:
                        this.back();
                        break;
                    default:
                        alert('Error: ' + response.msg);
                }
            } catch (error) {
                console.error('Ошибка при изменении:', error);
                this.codeRes = 400;
            }
        },

        /**
         * Валидация логина
         */
        validateLogin() {
            this.errors.login = false;
            const login = this.login;

            if (login === this.userData['login']) {
                return;
            }
            if (login.length < 3) {
                this.errors.login = 'Short';
                return;
            }

            if (login.length > 20) {
                this.errors.login = 'Long';
                return;
            }

            const loginRegex = /^[a-zA-Z0-9_.]+$/;
            if (!loginRegex.test(login)) {
                this.errors.login = 'Font';
            }
        },

        /**
         * Валидация пароля
         * @returns {string}
         */
        validatePassword() {
            this.errors.password = false;
            const password = this.password;

            if (password.length < 8 || password.length > 32) {
                return this.errors.password = 'Password must be between 8 and 32 characters.';
            }

            if (!/[A-Z]/.test(password)) {
                return this.errors.password = 'Password must contain at least one uppercase letter.';
            }

            if (!/[a-z]/.test(password)) {
                return this.errors.password = 'Password must contain at least one lowercase letter.';
            }

            if (!/[0-9]/.test(password)) {
                return this.errors.password = 'Password must contain at least one digit.';
            }

            if (!/[!@#$%^&*()]/.test(password)) {
                return this.errors.password = 'Password must contain at least one special character (!@#$%^&*()).';
            }

        },

        /**
         * Переход на страницу пользователя
         */
        back() {
            location.href = getHrefUser(this.currentUser['id']);
        },
        /**
         * Получить путь к странице пользователя (pathUtils.js)
         */
        getHrefUser
    },
    /**
     * Шаблон компонента
     */
    template: `
    template: \`
<div class="user-page__center">
    <div class="user-page">
        <div class="user-card">
            <h1 class="user-title">Change User Information</h1>
            <div class="user-info">
                <p><strong>Login:</strong> <input id="user-login" v-model="login"></p>
                <p v-if="errors.login === 'Short'">Коротковат &#128556;</p>
                <p v-if="errors.login === 'Long'">Слишком большой &#128563;</p>
                <p v-if="errors.login === 'Exists'">Заняли &#128555;</p>
                <p v-if="errors.login === 'Font'">Убери странные символы &#128122;</p>
                <p><strong>Pass:</strong> <input id="user-login" v-model="password"></p>
                <p v-if="errors.password">{{ errors.password }}</p>
            </div>
            <div class="user-actions">
                <button id="change-data" class="btn" @click="change()">Change Data</button>
                <button id="logout" class="btn btn-logout" @click='back()'>Back</button>
            </div>
        </div>
    </div>
</div>
\`
    `
})