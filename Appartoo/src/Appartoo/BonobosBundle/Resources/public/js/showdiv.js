var app = angular.module('show', []);
app.controller('showCtrl', function($scope) {
    $scope.show = false;
    $scope.modif = function() {
        $scope.show = !$scope.show;
    };
});
