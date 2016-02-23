angular.module('shmviewer').provider('User', function UserProvider() {
    
    this.$get = function ($http, $q, AppSettings) {
        return {
            /**
             * Get all the users
             * @param {int} pageNum . The page number to load
             * @returns {$q@call;defer.promise}
             */
            getAll : function(pageNum) {
                var deferred = $q.defer();
                var url = AppSettings.urls.servicesServer + '/services/users/all.json.php?page_num=' + pageNum + 
                        '&page_size=' + AppSettings.listPageSizes.posts;
                
                $http.get(url)
                    .success(function(data){
                        deferred.resolve(data);
                    }).error(function(){
                        deferred.reject('There was an error trying to retrieve users');
                    });
                return deferred.promise;
            },
            
            /**
             * Get a user by id from the server
             * @param {int} userId . The user id.
             * @returns {$q@call;defer.promise}
             */
            getById : function(userId) {
                var deferred = $q.defer();
                $http.get(AppSettings.urls.servicesServer + '/services/users/getById.json.php?id=' + userId )
                    .success(function(data){
                        deferred.resolve(data);
                    }).error(function(){
                        deferred.reject('There was an error trying to retrieve a user by ID: ' + userId);
                    });
                return deferred.promise;
            },
            
            create : function () {
                var userData = {
                    firstname : 'Pepe',
                    lastname : 'Pono',
                    username : 'peposs',
                    password : 'pepino',
                    email : 'pepe@gmail.com'
                };
                
                var deferred = $q.defer();
                $http.post(AppSettings.urls.servicesServer + '/services/users/new.json.php', 
                        userData)
                    .success(function(data){
                        deferred.resolve(data);
                    }).error(function(){
                        deferred.reject('There was an error trying to retrieve create a new user');
                    });
                return deferred.promise;
            },
            
            update : function () {
                var userData = {
                    firstname : 'Pepe',
                    lastname : 'Ponomooooo',
                    admin : false,
                    password : 'cucumber',
                    email : 'maxipaolucci@gmail.com',
                    id : 4 
                };
                
                var deferred = $q.defer();
                $http.post(AppSettings.urls.servicesServer + '/services/users/edit.json.php', 
                        userData)
                    .success(function(data){
                        deferred.resolve(data);
                    }).error(function(){
                        deferred.reject('There was an error trying to edit an existing user.');
                    });
                return deferred.promise;
            }
        };
    };
});