angular.module('post-list', ['ngFx', 'ngAnimate']).directive('shmPostList', function(){
        var componentName = 'post-list component - shmPostList directive: ';
        
        return {
            restrict : 'E',
            templateUrl : './components/post-list/post-list.html',
            scope: { posttype: '@' },
            controller : function($scope, $log, Post){
                var selectedPostId = null;
                var lastPostsPageLoaded = 0;
                
                $scope.posts = [];
                
                /**
                 * Answer whether the post as param is selected or not
                 * @param {int} postId - The id of the post that wanna ask for
                 * @return {boolean} - True if the post as param is selected or not in the view
                 * */
                $scope.isSelected = function(postId) {
                    return selectedPostId === postId;
                };
                
                /**
                 * Set the post as selected
                 * @param {int} postId - the post that the user want to select
                 */
                $scope.selectPost = function(postId) {
                    selectedPostId = postId;
                };
                
                /**
                 * Get more posts from the server and append it to the array of posts
                 * @param {int} pageNum . Number of page to get posts
                 */
                var loadMorePosts = function(pageNum) {
                    pageNum = !pageNum ? 0 : pageNum;
                    
                    if ($scope.posttype === 'search') {
                        searchPosts(pageNum);
                    } else {
                        Post.getPosts($scope.posttype, pageNum).then(function(data) {
                            if (data.posts) {
                                $scope.posts = $scope.posts.concat(data.posts.slice(0, Post.getPageSize()));
                            } else {
                                $log.log(componentName + '(loadMorePosts()) Cannot retrive the posts data');
                            }
                        }, function (data) {
                            $log.log(data);
                        });
                    }
                };
                
                var searchPosts = function (pageNum) {
                    pageNum = !pageNum ? 0 : pageNum;
                    //lets use -1 to tell the server that we want a random post
                    Post.searchPosts($scope.$parent.searchString, pageNum).then(function(data) {
                        if (data) {
                            $scope.posts = $scope.posts.concat(data.posts.slice(0, Post.getPageSize()));
                        } else {
                            $log.log(componentName + '(searchPosts()) Cannot retrive the data');
                        }
                    }, function (data) {
                        $log.log(data);
                    });
                };
                
                /**
                 * Manage the click event of the user in the "Load more posts" button at the end
                 * of the posts list
                 */
                $scope.loadMoreBtnHandler = function () {
                    lastPostsPageLoaded += 1;
                    loadMorePosts(lastPostsPageLoaded);
                };
                
                /**
                 * Initialize the controller logic
                 */
                var initialize = function () {
                    loadMorePosts(lastPostsPageLoaded);
                };
                
                initialize();
            },
            controllerAs : 'postlistCtrl'
        };
    });