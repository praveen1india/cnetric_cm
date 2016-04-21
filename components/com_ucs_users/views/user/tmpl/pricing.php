<?php
/*
 * Created on Feb 26, 2016
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
?>
<script src="http://code.angularjs.org/1.2.0-rc.2/angular.js"></script>
<script type="text/javascript">
  function MyCtrl($scope) {

  $scope.myvalue = false;
  $scope.showAlert = function(){
    $scope.myvalue = true;
  };
}
</script>


<div ng-app>
<div ng-controller="MyCtrl">
<div class="PricingMain BagWhite">
		<div class="PricingInner">

		<h2>Leading ecommerce solutions that grow with your business</h2>
		<p class="TIT2">Try Bigcommerce free for 15 days and see what plan is right for you</p>
			<div class="pricingComparison-planMain">
				<!-- Pln-1 -->
				<div class="pricingComparison-plan">
				<h3 class="block--pricingComparison-plan-name">Standard</h3>
					<div class="Block-plan-price">
					<p>Everything you need to sell more online</p>
					<div class="Comparison-plan-price">
					<br>
					<p class="price"><span class="price-currency">$</span><span class="price-amount">29</span><span class="price-decimals">.95</span></p>
					</div>
					<p>Billed monthly up to $50k in online sales per year </p>
					</div>
				<a href="#">View features</a>
    			<div><button id="mybutton_ViewMore" ng-click="myvalue = myvalue == true ? false : true;">View More Details</button> <span class="glyphicon glyphicon-chevron-down" ng-click="myvalue = myvalue == true ? false : true;"></span></div>
				<div ng-show="myvalue" class="hideByDefault">
				<h5 class="OPT1">Front-end</h5>
				<p>Microsites</p>
				<p>Business Models (software Sales, Insurance, Telecom)</p>
				<p>Inventory</p>
				<p>Social Integrations</p>
				<p>Payment capabilities</p>
				<p>Rewards / Loyalty Schemes</p>
				<p>B2B Capabilities</p>
				<p>Multi-Language</p>
				<p>Search</p>
				<p>Product Bundles</p>
				<p>eSpots</p>
				<p>Checkout options</p>
				<p>Wishlist</p>
				<p>My Account </p>
				<p>Product attributes (e.g. colour, size)</p>
				<p>Navigation (breadcrumbs)</p>
				<p>B2B Capabilities</p>

				<h5 class="OPT1">Back-end</h5>
				<p>Market place</p>
				<p>Analytics</p>
				<p>Multi Site Capabilities</p>
				<p>Returns process</p>
				<p>Taxation</p>
				<p>Product Upload</p>
				<p>Shipping rules</p>

				<h5 class="OPT1">Non-Functional</h5>
				<p>Resilience</p>
				<p>Security</p>
				<p>Scalability & Performance</p>
				<p>Diagnostics</p>
				<p>Testing environments</p>
				<p>updates & patching</p>
				<p>CDN Integration</p>
				<p>RBAC</p>
				<p>localisation</p>
				<p>Browser / Device Support</p>
				<p>Caching</p>
				<p>localisation</p>

				<h5 class="OPT1">Support</h5>
				<p>Proactive Monitoring</p>
				<p>Support capabilities</p>
				<p>Error message customisation</p>
				<p>Proactive Monitoring</p>

				<h5 class="OPT1">Toolling</h5>
				<p>BU Tooling</p>
				<p>Static content management</p>
				</div>

				</div>
				<!-- Pln-1 -->

				<!-- Pln-2 -->

				<div class="pricingComparison-plan">
				<h3 class="block--pricingComparison-plan-name">Plus</h3>
				<div class="Block-plan-price">
					<p>Powerful marketing and conversion tools to grow sales</p>
					<div class="Comparison-plan-price">
					<br>
					<p class="price"><span class="price-currency">$</span><span class="price-amount">79</span><span class="price-decimals">.95</span></p>
					</div>
					<p>Billed monthly up to $125k in online sales per year</p>
					</div>
				<a href="#">View features</a>

				<div ng-show="myvalue" class="hideByDefault">
				<br/></br><br/></br>
				<h5 class="OPT1">Front-end</h5>
				<p>Microsites</p>
				<p>Business Models (software Sales, Insurance, Telecom)</p>
				<p>Inventory</p>
				<p>Social Integrations</p>
				<p>Payment capabilities</p>
				<p>Rewards / Loyalty Schemes</p>
				<p>B2B Capabilities</p>
				<p>Multi-Language</p>
				<p>Search</p>
				<p>Product Bundles</p>
				<p>eSpots</p>
				<p>Checkout options</p>
				<p>Wishlist</p>
				<p>My Account </p>
				<p>Product attributes (e.g. colour, size)</p>
				<p>Navigation (breadcrumbs)</p>
				<p>B2B Capabilities</p>

				<h5 class="OPT1">Back-end</h5>
				<p>Market place</p>
				<p>Analytics</p>
				<p>Multi Site Capabilities</p>
				<p>Returns process</p>
				<p>Taxation</p>
				<p>Product Upload</p>
				<p>Shipping rules</p>

				<h5 class="OPT1">Non-Functional</h5>
				<p>Resilience</p>
				<p>Security</p>
				<p>Scalability & Performance</p>
				<p>Diagnostics</p>
				<p>Testing environments</p>
				<p>updates & patching</p>
				<p>CDN Integration</p>
				<p>RBAC</p>
				<p>localisation</p>
				<p>Browser / Device Support</p>
				<p>Caching</p>
				<p>localisation</p>

				<h5 class="OPT1">Support</h5>
				<p>Proactive Monitoring</p>
				<p>Support capabilities</p>
				<p>Error message customisation</p>
				<p>Proactive Monitoring</p>

				<h5 class="OPT1">Toolling</h5>
				<p>BU Tooling</p>
				<p>Static content management</p>
				</div>

				</div>
				<!-- Pln-2 -->

				<!-- Pln-3 -->
				<div class="pricingComparison-plan">
				<h3 class="block--pricingComparison-plan-name">Pro</h3>
					<div class="Block-plan-price">
						<p>Enterprise-grade features for fast-growing brands</p>
						<div class="Comparison-plan-price">
						<br>
						<p class="price"><span class="price-currency">$</span><span class="price-amount">199</span><span class="price-decimals">.95</span></p>
						</div>
						<p>Up to 3k orders per year</p>
					</div>
				<a href="#"> View features</a>

				<div ng-show="myvalue" class="hideByDefault">
				<br/></br><br/></br>
				<h5 class="OPT1">Front-end</h5>
				<p>Microsites</p>
				<p>Business Models (software Sales, Insurance, Telecom)</p>
				<p>Inventory</p>
				<p>Social Integrations</p>
				<p>Payment capabilities</p>
				<p>Rewards / Loyalty Schemes</p>
				<p>B2B Capabilities</p>
				<p>Multi-Language</p>
				<p>Search</p>
				<p>Product Bundles</p>
				<p>eSpots</p>
				<p>Checkout options</p>
				<p>Wishlist</p>
				<p>My Account </p>
				<p>Product attributes (e.g. colour, size)</p>
				<p>Navigation (breadcrumbs)</p>
				<p>B2B Capabilities</p>

				<h5 class="OPT1">Back-end</h5>
				<p>Market place</p>
				<p>Analytics</p>
				<p>Multi Site Capabilities</p>
				<p>Returns process</p>
				<p>Taxation</p>
				<p>Product Upload</p>
				<p>Shipping rules</p>

				<h5 class="OPT1">Non-Functional</h5>
				<p>Resilience</p>
				<p>Security</p>
				<p>Scalability & Performance</p>
				<p>Diagnostics</p>
				<p>Testing environments</p>
				<p>updates & patching</p>
				<p>CDN Integration</p>
				<p>RBAC</p>
				<p>localisation</p>
				<p>Browser / Device Support</p>
				<p>Caching</p>
				<p>localisation</p>

				<h5 class="OPT1">Support</h5>
				<p>Proactive Monitoring</p>
				<p>Support capabilities</p>
				<p>Error message customisation</p>
				<p>Proactive Monitoring</p>

				<h5 class="OPT1">Toolling</h5>
				<p>BU Tooling</p>
				<p>Static content management</p>
				</div>

				</div>
				<!-- Pln-3 -->

				<!-- Pln-4 -->
				<div class="pricingComparison-plan">
				<h3 class="block--pricingComparison-plan-name">Enterprise</h3>
						<div class="Block-plan-price">
							<p>Premium features and services for high-volume brands</p>
							<div class="Comparison-plan-price">
							<br>
							<p class="price">Custom order-based pricing tailored to your business</p>
							</div>
							<p>For stores making $1M+ in online sales per year  </p>
						</div>
				<a href="#">View features</a>

				<div ng-show="myvalue" class="hideByDefault">
				<br/></br><br/></br>
				<h5 class="OPT1">Front-end</h5>
				<p>Microsites</p>
				<p>Business Models (software Sales, Insurance, Telecom)</p>
				<p>Inventory</p>
				<p>Social Integrations</p>
				<p>Payment capabilities</p>
				<p>Rewards / Loyalty Schemes</p>
				<p>B2B Capabilities</p>
				<p>Multi-Language</p>
				<p>Search</p>
				<p>Product Bundles</p>
				<p>eSpots</p>
				<p>Checkout options</p>
				<p>Wishlist</p>
				<p>My Account </p>
				<p>Product attributes (e.g. colour, size)</p>
				<p>Navigation (breadcrumbs)</p>
				<p>B2B Capabilities</p>

				<h5 class="OPT1">Back-end</h5>
				<p>Market place</p>
				<p>Analytics</p>
				<p>Multi Site Capabilities</p>
				<p>Returns process</p>
				<p>Taxation</p>
				<p>Product Upload</p>
				<p>Shipping rules</p>

				<h5 class="OPT1">Non-Functional</h5>
				<p>Resilience</p>
				<p>Security</p>
				<p>Scalability & Performance</p>
				<p>Diagnostics</p>
				<p>Testing environments</p>
				<p>updates & patching</p>
				<p>CDN Integration</p>
				<p>RBAC</p>
				<p>localisation</p>
				<p>Browser / Device Support</p>
				<p>Caching</p>
				<p>localisation</p>

				<h5 class="OPT1">Support</h5>
				<p>Proactive Monitoring</p>
				<p>Support capabilities</p>
				<p>Error message customisation</p>
				<p>Proactive Monitoring</p>

				<h5 class="OPT1">Toolling</h5>
				<p>BU Tooling</p>
				<p>Static content management</p>
				</div>

				</div>
				<!-- Pln-4 -->
			</div>
		</div>
</div>
</div></div></div>