(function(){
    var app = angular.module('post-list', ['ngFx', 'ngAnimate','posts-service']);

    app.directive('shmPostList', function($log){
        return {
            restrict : 'E',
            templateUrl : './components/post-list/post-list.html',
            scope: { posttype: '@' },
            controller : ['$http', '$scope', 'postsService', function($http, $scope, postsService){
                var main = this;
                var pageSize = 30;
                
                this.selectedPostId = null;
                this.lastPostsPageLoaded = 0;
                this.posts = [];
                this.appConfig = $scope.$parent.appConfig;
                
                
                /**
                 * Answer whether the post as param is selected or not
                 * @param {int} postId - The id of the post that wanna ask for
                 * @return {boolean} - True if the post as param is selected or not in the view
                 * */
                this.isSelected = function(postId) {
                    return this.selectedPostId === postId;
                };
                
                /**
                 * Set the post as selected
                 * @param {int} postId - the post that the user want to select
                 */
                this.selectPost = function(postId) {
                    this.selectedPostId = postId;
                };
                
                /**
                 * Get more posts from the server and append it to the array of posts
                 * @param {int} pageNum . Number of page to get posts
                 */
                this.loadMorePosts = function(pageNum) {
                    pageNum = !pageNum ? 0 : pageNum;
                    
                    if ($scope.posttype === 'search') {
                        this.searchPosts(pageNum);
                    } else {
                        postsService.getPosts($scope.posttype, pageNum, pageSize).then(function(data) {
                            if (data.posts) {
                                main.posts = main.posts.concat(data.posts.slice(0, pageSize));
                            } else {
                                $log.log('Cant retrive the data');
                            }
                        }, function (data) {
                            $log.log(data);
                        });
                    }
                };
                
                this.searchPosts = function (pageNum) {
                    pageNum = !pageNum ? 0 : pageNum;
                    //lets use -1 to tell the server that we want a random post
                    postsService.searchPosts($scope.$parent.searchString, pageNum, pageSize).then(function(data) {
                        if (data) {
                            main.posts = main.posts.concat(data.posts.slice(0, pageSize));
                        } else {
                            $log.log('Cant retrive the data');
                        }
                    }, function (data) {
                        $log.log(data);
                    });
                };
                
                /**
                 * Manage the click event of the user in the "Load more posts" button at the end
                 * of the posts list
                 */
                this.loadMoreBtnHandler = function () {
                    this.lastPostsPageLoaded += 1;
                    this.loadMorePosts(this.lastPostsPageLoaded);
                };
                
                /**
                 * Initialize the controller logic
                 */
                this.initialize = function () {
                    this.loadMorePosts(this.lastPostsPageLoaded);
                };
                
                this.initialize();
            }],
            controllerAs : 'postlistCtrl'
        };
    });
})();