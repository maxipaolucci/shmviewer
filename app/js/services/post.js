angular.module('shmviewer').provider('Post', function PostProvider() {
    //configuration value to the the size of the pages when post are returned from the server
    var pageSize = 30;
    
    //this is a configuration method to set the page size for posts pagination
    this.setPageSize = function (newPageSize) {
        pageSize = newPageSize;
    };
    
    this.$get = function ($http, $q, appConfig) {
        return {
            getPageSize : function () {
                return pageSize;
            },
            
            /**
             * Get a list of posts
             * @param {string} postType . The type of post to load: article, video, all
             * @param {int} pageNum . The page number to load
             * @returns {$q@call;defer.promise}
             */
            getPosts : function(postType, pageNum) {
                var deferred = $q.defer();
                var url = appConfig.servicesServer + '/services/getPosts.json.php?type=' + 
                        postType + '&page_num=' + pageNum + '&page_size=' + pageSize;
                //url = '/js/services/mocks/videos.json';
                $http.get(url)
                    .success(function(data){
                        deferred.resolve(data);
                    }).error(function(){
                        deferred.reject('There was an error trying to retrieve posts of type: ' + postType);
                    });
                return deferred.promise;
            },
            
            /**
             * Get a post by id from the server
             * @param {int} postId . The post id.
             * @returns {$q@call;defer.promise}
             */
            getPostById : function(postId) {
                var deferred = $q.defer();
                $http.get(appConfig.servicesServer + '/services/getPostById.json.php?id=' + postId )
                    .success(function(data){
                        deferred.resolve(data);
                    }).error(function(){
                        deferred.reject('There was an error trying to retrieve post by ID: ' + postid);
                    });
                return deferred.promise;
            },
            
            /**
             * Calls the search posts API service
             * @param {string} searchString. The string to filter posts by
             * @param {int} pageNum . The page number to load
             * @returns {$q@call;defer.promise}
             */
            searchPosts : function(searchString, pageNum) {
                var deferred = $q.defer();
                $http.get(appConfig.servicesServer + '/services/searchPosts.json.php?by=' + searchString +
                        '&page_num=' + pageNum + '&page_size=' + pageSize)
                    .success(function(data){
                        deferred.resolve(data);
                    }).error(function(){
                        deferred.reject('There was an error trying to retrieve post by: ' + searchString);
                    });
                return deferred.promise;
            }
        };
    };
});