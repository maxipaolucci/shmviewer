/**
 * This factory is made to share searching behavior between the navbar (that host the search form)
 * and others directives in app.
 * @param {type} param1
 * @param {type} param2
 */
angular.module('shmviewer').factory('SearchPost', function SearchPostFactory() {
    var searchString = '';
    
    return {
        getSearchString : function () {
            return searchString;
        },

        setSearchString : function (newSearchString) {
            searchString = newSearchString;
        }
    };
});