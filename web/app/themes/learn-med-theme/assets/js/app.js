// app.js
// create our angular app and inject ngAnimate and ui-router 
// =============================================================================

var templateDir = "<?php echo get_template_directory_uri(); ?>";

angular.module('formApp', ['ngAnimate', 'ui.router'])

// configuring our routes 
// =============================================================================
.config(function($stateProvider, $urlRouterProvider) {
	
	$stateProvider
	
		// route to show our basic form (/form)
		.state('form', {
			url: templateDir + '/forms',
			templateUrl: 'form.php',
			controller: 'formController'
		})
		
		// nested states 
		// each of these sections will have their own view
		// url will be nested (/form/profile)
		.state('form.profile', {
			url: templateDir + '/forms/profile',
			templateUrl: 'form-profile.php'
		})
		
		// url will be /form/interests
		.state('form.interests', {
			url: templateDir + '/forms/interests',
			templateUrl: 'form-interests.php'
		})
		
		// url will be /form/payment
		.state('form.payment', {
			url: templateDir + '/forms/payment',
			templateUrl: 'form-payment.php'
		});
		
	// catch all route
	// send users to the form page 
	$urlRouterProvider.otherwise('/forms/profile');
})

// our controller for the form
// =============================================================================
.controller('formController', function($scope) {
	
	// we will store all of our form data in this object
	$scope.formData = {};
	
	// function to process the form
	$scope.processForm = function() {
		alert('awesome!');
	};
	
});