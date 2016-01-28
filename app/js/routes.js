angular.module('shmviewer').config(function($provide, $stateProvider, $urlRouterProvider) {
    
    $urlRouterProvider.otherwise('/home');

    $stateProvider
        // HOME STATES AND NESTED VIEWS ========================================
        .state('home', {
            url: '/home',
            templateUrl: 'views/home.html'
        })

        // ABOUT PAGE AND MULTIPLE NAMED VIEWS =================================
        .state('searchResults', {
            url: '/search',
            templateUrl: 'views/search-results.html'
        });
});