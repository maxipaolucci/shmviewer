angular.module('shmviewer', ['ui.bootstrap', 'ui.router', 'nav-panel', 'post-list'])
    .controller('mainController', function(){
        /**
         * set the height of the main body container based on the window height - the toolbar height
         */
        var adjustAppBodyHeight = function() {
//            var bodyContainerHeight = $j(window).height() - 64;//64 height of the toolbar (fixed value)
//            $j('#app-body-container').height(bodyContainerHeight);
        };
        
        /**
         * Initialize the controller logic
         */
        var initialize = function () {
            adjustAppBodyHeight();
        };
        initialize();
    })
    .config(function(AppSettingsProvider) {
        AppSettingsProvider.setServicesServer('http://www.iprsportingclub.com.ar/shmviewer');
        AppSettingsProvider.setServicesServer('http://shmviewer.localhost');
        AppSettingsProvider.setPostListPageSize(30);
    });