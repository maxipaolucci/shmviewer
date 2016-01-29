angular.module('shmviewer').config(function($stateProvider, $urlRouterProvider) {
    $urlRouterProvider.otherwise('/home');

    $stateProvider.state('home', {
        url: '/home',
        templateUrl: 'views/home.html'
    })
    .state('search', {
        url: '/search',
        templateUrl: 'views/search-results.html'
    });
});