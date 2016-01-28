angular.module('posts-service', [])
    .service('postsService', ['$http', '$q', 'appConfig', 
            function($http, $q, appConfig) {
        
        this.getPosts = function(postType, pageNum, pageSize) {
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
        };
        
        this.getPostById = function(postId) {
            var deferred = $q.defer();
            $http.get(appConfig.servicesServer + '/services/getPostById.json.php?id=' + postId )
                .success(function(data){
                    deferred.resolve(data);
                }).error(function(){
                    deferred.reject('There was an error trying to retrieve post by ID: ' + postid);
                });
            return deferred.promise;
        };
        
        this.searchPosts = function(searchString, pageNum, pageSize) {
            var deferred = $q.defer();
            $http.get(appConfig.servicesServer + '/services/searchPosts.json.php?by=' + searchString +
                    '&page_num=' + pageNum + '&page_size=' + pageSize)
                .success(function(data){
                    deferred.resolve(data);
                }).error(function(){
                    deferred.reject('There was an error trying to retrieve post by: ' + searchString);
                });
            return deferred.promise;
        };
    }]);