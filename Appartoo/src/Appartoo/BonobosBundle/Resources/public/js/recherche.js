var app = angular.module('recherche', []);
app.controller('rechercheCtrl', function($scope, $http) {
    $http.get("http://localhost/Appartoo/web/app_dev.php/users")
    .then(function(response) {
        $scope.liste = response.data;
    });
});
