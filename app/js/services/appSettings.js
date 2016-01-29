/**
 * This factory contains configuration values for the application
 */
angular.module('shmviewer').factory('AppSettings', function AppSettingsFactory() {
    return {
        appTitle: 'SHM Viewer',
        screenSMmax : 599,
        screenMDmin : 600,
        screenMDmax : 959,
        screenLGmin : 960,
        //servicesServer : 'http://www.iprsportingclub.com.ar/shmviewer'
        servicesServer : 'http://shmviewer.localhost'
    };
});