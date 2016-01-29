/**
 * This factory contains configuration values for the application
 */
angular.module('shmviewer').provider('AppSettings', function AppSettingsProvider() {
    //APP Settings
    var settings = {
        title: 'SHM Viewer',
        cssBreakpoints : {
            screenSMmax : 599,
            screenMDmin : 600,
            screenMDmax : 959,
            screenLGmin : 960
        },
        urls : {
            servicesServer : 'http://shmviewer.localhost'
        },
        listPageSizes : {
            posts: 50
        }
    };
    
    //Configuration methods
    this.setServicesServer = function(serverUrl) {
        settings.urls.servicesServer = serverUrl;
    };
    
    this.setPostListPageSize = function(pageSize) {
        settings.listPageSizes.posts = pageSize;
    };
    
    //this is what the service provides
    this.$get = function () {
        return settings;
    };
});