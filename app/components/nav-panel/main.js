angular.module('nav-panel', ['ngMessages']).directive('shmNavPanel', function(){
    var componentName = 'nav-panel component - shmNavPanel directive: ';
    
    return {
        restrict : 'E',
        scope: true,
        templateUrl : './components/nav-panel/nav-panel.html',
        controller : function($log, $window, $scope, $state, AppSettings, Post, SearchPost){
            $scope.appTitle = AppSettings.title;
            $scope.showSearchForm = false;
            $scope.showSeparatorSpan = true;
            $scope.searchString = "";
            
            /**
             * Closes the search input form in the navbar
             */
            $scope.closeSearchForm = function () {
                $scope.showSearchForm = false;
                $scope.showSeparatorSpan = true;
                $('.searchForm .search-field').val('');
            };
            
            /**
             * Get a random post from the server.
             */
            $scope.randomPostAction = function () {
                //lets use -1 to tell the server that we want a random post
                Post.getPostById(-1).then(function(data) {
                    if (data.post) {
                        $window.open(data.post.post_url);
                    } else {
                        $log.log(componentName + '(getRandomPost()) Cannot retrive the post data');
                    }   
                }, function (data) {
                    $log.log(data);
                });
            };
            
            /**
             * Executes the search action
             * @returns {undefined}
             */
            $scope.searchAction = function () {
                if (!$scope.showSearchForm) {
                    $scope.showSearchForm = true;
                    if ($(window).width() > AppSettings.cssBreakpoints.screenSMmax) {
                        $scope.showSeparatorSpan = false;
                    }
                    $('.searchForm .search-field-container').addClass('md-input-focused');
                    $('.searchForm .search-field').focus();
                } else if($scope.searchString) {
                    if ($state.is('search') && SearchPost.getSearchString() === $scope.searchString) {
                        //do nothing, the user is performing the same search again
                    } else {
                        //the user performed a different search so go ahead
                        SearchPost.setSearchString($scope.searchString);
                        $state.go('search', {}, {reload: true}); //second parameter is for $stateParams
                    }
                }
            };

            /**
             * Initialize the controller logic
             */
            var initialize = function () {};
            initialize();
        },
        controllerAs : 'navPanelCtrl'
    };
});