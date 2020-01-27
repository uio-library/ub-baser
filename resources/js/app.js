/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import Vue from './bootstrap'
import Lang from 'laravel-vue-lang';

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

import DataTable from './components/DataTable'
import OpesDataTable from './components/bases/opes/OpesDataTable'
import SearchForm from './components/SearchForm'
import EditForm from './components/EditForm'
import PageEditor from './components/PageEditor'
import ImageViewer from './components/ImageViewer'
import NationalLibrarySearch from './components/NationalLibrarySearch'

Vue.config.devtools = true

Vue.component('data-table', DataTable)
Vue.component('opes-data-table', OpesDataTable)
Vue.component('search-form', SearchForm)
Vue.component('edit-form', EditForm)
Vue.component('page-editor', PageEditor)
Vue.component('image-viewer', ImageViewer)
Vue.component('national-library-search', NationalLibrarySearch)

Vue.use(Lang, {
  locale: window.default_locale,
  fallback: window.fallback_locale,
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

export default new Vue({
  el: '#app',
})
