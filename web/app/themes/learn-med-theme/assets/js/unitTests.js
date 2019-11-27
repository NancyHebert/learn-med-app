var myTests = new jqUnit.TestCase("My Application Tests");  

myTests.test("Test Scenario 1", function () {  
   
	jqUnit.isVisible("My component is visible", "#my-component-id");  
	jqUnit.notVisible("My hidden component is not visible", "#my-hidden-component-id");  
	jqUnit.assertNotNull("My function 1 doesn't return null", myApplicationInstance.myFunction1());  
	jqUnit.assertNotUndefined("My function 1 doesn't return undefined value", myApplicationInstance.myFunction1());
	jqUnit.assertFalse("My function 2 returns false", myApplicationInstance.myFunction2());  
	jqUnit.assertTrue("My variable 1 is set", myApplicationInstance.myVar1);
	jqUnit.assertEquals("My function 3 returns correct value", "expectedValue", myApplicationInstance.myFunction3());

});

// Mark up to display unit tests
// <h1 id="qunit-header">My Application Basic Tests</h1>
// <h2 id="qunit-banner"></h2>
// <div id="qunit-testrunner-toolbar"></div>
// <h2 id="qunit-userAgent"></h2>
// <ol id="qunit-tests"></ol>