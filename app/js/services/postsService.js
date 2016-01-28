angular.module('shmviewer').provider('Post', function PostProvider() {
    
    var pageSize = 30;
    
    this.setPageSize = function (newPageSize) {
        pageSize = newPageSize;
    };
    
    this.$get = function ($http, $q, appConfig) {
        return {
            getPageSize : function () {
                return pageSize;
            },
            
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