angular.module("myapp", []);

//connect to database
angular.module("myapp").controller('dbCtrl', ['$scope', '$http', function ($scope, $http) {
    $http.get("adminer-4.2.2-en.php")
        .success(function(data, result){

            $scope.data = data;
            $scope.result = result;
        })
        .error(function() {
            $scope.data = "error in fetching data";
        });
}]);