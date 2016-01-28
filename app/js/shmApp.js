angular.module('shmviewer', ['ui.router', 'ngMaterial', 'nav-panel', 'post-list'])
    .controller('mainController', ['$scope', 'appConfig', function($scope, $appConfig){
        $scope.appConfig = $appConfig;
        
        /**
         * set the height of the main body container based on the window height - the toolbar height
         */
        this.adjustAppBodyHeight = function() {
            var bodyContainerHeight = $(window).height() - 64;//64 height of the toolbar (fixed value)
            $('#app-body-container').height(bodyContainerHeight);
        };
        
        /**
         * Initialize the controller logic
         */
        this.initialize = function () {
            this.adjustAppBodyHeight();
        };

       this.initialize();
    }])
    .constant('appConfig', {
        appTitle: 'SHM Viewer',
        screenSMmax : 599,
        screenMDmin : 600,
        screenMDmax : 959,
        screenLGmin : 960,
        //servicesServer : 'http://www.iprsportingclub.com.ar/shmviewer'
        servicesServer : 'http://shmviewer.localhost'
    })
    .config(function($provide, $stateProvider, $urlRouterProvider) {

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