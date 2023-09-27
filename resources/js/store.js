import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const store = new Vuex.Store({
  state: {
    loggedIn: false,
    user: null, // Puedes almacenar información del usuario aquí
    userRol:  null
  },
  mutations: {
    setLoggedIn(state, user) {
      state.loggedIn = true;
      state.user = user;
      state.userRol = user.rol;
      localStorage.setItem('session' , state.loggedIn);
      localStorage.setItem('userAdmin' , `${state.userRol}`);
    },
    setLoggedOut(state) {
      state.loggedIn = false;
      state.user = null;
    },
  },
  actions: {
    logins({ commit }, user) {
      
      // Aquí puedes realizar la autenticación, por ejemplo, mediante una llamada a la API
      // Una vez autenticado, puedes obtener la información del usuario y pasarla a la mutación
      const authenticatedUser = user; // Cambia esto con la información real del usuario
      commit('setLoggedIn', authenticatedUser);
    },
    logouts({ commit }) {
      // Lógica para cerrar sesión, si es necesario
      localStorage.setItem('session' , false);
      localStorage.clear();
      commit('setLoggedOut');
    },
  },
  getters: {
    isLoggedIn(state) {
      return state.loggedIn;
    },
    currentUser(state) {
      return state.user;
    },
  },
});

export default store;


