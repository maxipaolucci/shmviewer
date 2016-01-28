angular.module('nav-panel', ['ngMessages']).directive('shmNavPanel', function(){
        return {
            restrict : 'E',
            templateUrl : './components/nav-panel/nav-panel.html',
            controller : ['$log','$window', '$scope', 'Post',
                function($log, $window, $scope, Post){
                
                    this.appConfig = $scope.appConfig;
                    this.appTitle = this.appConfig.appTitle;
                    this.showSearchForm = false;
                    this.showSeparatorSpan = true;
                    $scope.searchString = "";

                    /**
                     * Initialize the controller logic
                     */
                    this.initialize = function () {

                    };

                    this.getRandomPost = function () {
                        //lets use -1 to tell the server that we want a random post
                        Post.getPostById(-1).then(function(data) {
                            if (data.post) {
                                $window.open(data.post.post_url);
                            } else {
                                $log.log('Cant retrive the data');
                            }   
                        }, function (data) {
                            $log.log(data);
                        });
                    };
                    
                    this.clickHiddenSearchBtn = function () {
                        $('.searchForm .hiddenSearchBtn').click();
                    };
                    
                    this.closeSearchForm = function () {
                        this.showSearchForm = false;
                        this.showSeparatorSpan = true;
                        $('.searchForm .search-field').val('');
                    };
                    
                    this.searchAction = function () {
                        if (!this.showSearchForm) {
                            this.showSearchForm = true;
                            if ($(window).width() > this.appConfig.screenSMmax) {
                                this.showSeparatorSpan = false;
                            }
                            $('.searchForm .search-field-container').addClass('md-input-focused');
                            $('.searchForm .search-field').focus();
                        } else if($scope.searchString) {
                            this.clickHiddenSearchBtn();
                        }
                    };
                    
                    
                    this.initialize();
            }],
            controllerAs : 'navPanelCtrl'
        };
    });