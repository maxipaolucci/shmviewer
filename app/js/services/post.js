angular.module('shmviewer').provider('Post', function PostProvider() {
    
    this.$get = function ($http, $q, AppSettings) {
        return {
            /**
             * Get a list of posts
             * @param {string} postType . The type of post to load: article, video, all
             * @param {int} pageNum . The page number to load
             * @returns {$q@call;defer.promise}
             */
            getPosts : function(postType, pageNum) {
                var deferred = $q.defer();
                var url = AppSettings.urls.servicesServer + '/services/getPosts.json.php?type=' + 
                        postType + '&page_num=' + pageNum + '&page_size=' + AppSettings.listPageSizes.posts;
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
                $http.get(AppSettings.urls.servicesServer + '/services/getPostById.json.php?id=' + postId )
                    .success(function(data){
                        deferred.resolve(data);
                    }).error(function(){
                        deferred.reject('There was an error trying to retrieve post by ID: ' + postId);
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
                $http.get(AppSettings.urls.servicesServer + '/services/searchPosts.json.php?by=' + searchString +
                        '&page_num=' + pageNum + '&page_size=' + AppSettings.listPageSizes.posts)
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