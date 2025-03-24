/**
 * Компонент страницы пользователя
 */
Vue.component('user-page', {
    /**
     * Пропсы компонента
     */
    props: ['currentUser', 'userData'],

    /**
     * Реактивные данные компонента
     */
    methods: {
        /**
         * Метод выхода из учетной записи
         * @returns {Promise<void>}
         */
        async logout() {
            await this.$root.postJson('/auth/logout')
            location.href = '/auth/'
        },
        change() {
            location.href = '/user/edit'
        },
    },
    /**
     * Шаблон компонента
     */
    template: `
<div class="user-page__center">
        <div class="user-page">
            <div class="user-card">
                <h1 class="user-title">User Information</h1>
                <div class="user-info">
                    <p><strong>ID:</strong> <span id="user-login">{{ userData['id'] }}</span></p>
                    <p><strong>Login:</strong> <span id="user-login">{{ userData['login'] }}</span></p>
                    <p><strong>Name:</strong> <span id="user-name">{{ userData['name'] }}</span></p>
                    <p v-if="userData['role'] === 1"><strong>Role:</strong> <span id="user-role">Admin</span></p>
                    <p v-if="userData['role'] === 0"><strong>Role:</strong> <span id="user-role">User</span></p>
                </div>
                <div class="user-actions" v-if="String(currentUser['id']) === String(userData['id'])">
                    <button id="change-data" class="btn" @click="change">Change Data</button>
                    <button id="logout" class="btn btn-logout" @click="logout">Log Out</button>
                </div>
            </div>
    </div>
</div>
`
})