(function() {

	/**
	 * Sensfrx device initialization
	 */

	var anchors = document.querySelectorAll("a[href*='logout']");

	for(var i=0; i<anchors.length; i++) {

		if(anchors[i].addEventListener){

			anchors[i].addEventListener("click", logoutClickHandler, false);  //Modern browsers

		}else if(anchors[i].attachEvent){

			anchors[i].attachEvent('onclick', logoutClickHandler);            //Old IE

		}

	}

	function logoutClickHandler(e) {

		e.preventDefault();

		var device_id = _sensfrx("getRequestString");

		var href = this.href;

		href+= '&did='+device_id;

		window.location = href;

	}



})();

// Woo Sensfrx Tab and subtab
function showTab(tabId) {
	var tabs = document.getElementsByClassName('sensfrx_sub_subtab-content');
	for (var i = 0; i < tabs.length; i++) {
		tabs[i].classList.remove('active');
	}
	var selectedTab = document.getElementById(tabId);
	selectedTab.classList.add('active');
	var navTabs = document.getElementsByClassName('sensfrx_tab_nav-tab1');
	for (var i = 0; i < navTabs.length; i++) {
		navTabs[i].classList.remove('active');
	}
	event.target.classList.add('active');
	window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Open the specified tab if a tab parameter is present in the URL hash
document.addEventListener('DOMContentLoaded', function () {
	document.body.classList.add('loaded');
	var hash = window.location.hash;
	var tabParam = hash.substring(1); // Remove the '#' from the hash
	if (tabParam) {
		var tabLink = document.querySelector('.sensfrx_tab_nav-tab1[href="#' + tabParam + '"]');
		if (tabLink) {
			tabLink.click();
		}
	} else {
		var defaultTab = document.querySelector('.sensfrx_tab_nav-tab1');
		if (defaultTab) {
			defaultTab.click();
		}
	}
	// Woocommerce Save Changes Button Hide 
	var saveButton = document.querySelector('.woocommerce-save-button.button-primary');
	if (saveButton) {
		saveButton.style.display = 'none';
	}
	var button = document.evaluate('/html/body/div[1]/div[2]/div[3]/div[1]/div[6]/p/button', document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
	if (button) {
		button.style.display = 'none';
	}
	var button = document.evaluate('/html/body/div[1]/div[2]/div[3]/div/div[6]/b/p/button', document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
	if (button) {
		button.style.display = 'none';
	}
	var button = document.evaluate('/html/body/div[1]/div[2]/div[3]/div/div[6]/form/b/b/b/b/b/p/button', document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
	if (button) {
		button.style.display = 'none';
	}
});

window.onload = function() {
	document.body.scrollTop = 0;
	document.documentElement.scrollTop = 0;
};

// Sensfrx Properties not mention notification 

jQuery(document).ready(function($) {
	$(document).on('click', '.notice.is-dismissible .notice-dismiss', function() {
		const notification = $(this).closest('.notice');
		const notificationValue = notification.data('notification-value');
		function formatDateToPHPFormat(date) {
			const year = date.getFullYear();
			const month = (date.getMonth() + 1).toString().padStart(2, '0');
			const day = date.getDate().toString().padStart(2, '0');
			const hours = date.getHours().toString().padStart(2, '0');
			const minutes = date.getMinutes().toString().padStart(2, '0');
			const seconds = date.getSeconds().toString().padStart(2, '0');
			return `${year}-${month}-${day} ${hours}:${minutes}:${seconds}`;
		}

		const now = new Date();
		const formattedDate = formatDateToPHPFormat(now);
		const cookieName = 'notification_closed';
		const cookieValue = formattedDate;
		const expiration = new Date();
		expiration.setTime(expiration.getTime() + 24 * 60 * 60 * 1000);
		document.cookie = cookieName + '=' + cookieValue + '; expires=' + expiration.toUTCString() +'; path=/';
	});
});

// Validation Rules 

function validation_rules(event) {
	var button = document.evaluate('/html/body/div[1]/div[2]/div[3]/div[1]/div[6]/p/button', document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
	if (button) {
		button.click();
	} else {
		console.error('Save Change Button not found');
	}
	var scoreInputs = document.getElementsByName("score[]");
	var activeCheckboxes = document.getElementsByName("active[]");
	var modulesValues = [];
	var check_the_input = true;
	for (var i = 0; i < scoreInputs.length; i++) {                   // Check score[] inputs for above 0 or below 100
		var scoreValue = parseFloat(scoreInputs[i].value);
		if (isNaN(scoreValue) || scoreValue < 1 || scoreValue > 100) {
			alert("Please fill in all Score Value fields with values between 1 and 100.");
			check_the_input = false;
			event.preventDefault();
			break;
		}
	}
	if (check_the_input === false) {
		return false; 

	} else {
		// Initialize arrays to store values
		var scoreValues = [];
		var activeValues = [];
		// Collect values from score[] inputs
		for (var i = 0; i < scoreInputs.length; i++) {
			scoreValues.push(scoreInputs[i].value);
		}
		// Collect values from active[] checkboxes
		for (var i = 0; i < activeCheckboxes.length; i++) {
			if (activeCheckboxes[i].checked) {
				activeValues.push("1");
			} else {
				activeValues.push("0"); // Store "off" for unchecked checkboxes
			}
		}
		// Set the collected values into the other form's input fields
		document.getElementById("scoreValues").value = scoreValues.join(', ');
		document.getElementById("activeValues").value = activeValues.join(', ');
		setTimeout(function() {
			document.getElementById("validator").click();
		}, 100); 
	}
}
function hideAlert() {
	var alert = document.getElementById('myAlert');
	if (alert) {
		alert.style.display = 'none';
	}
}

setTimeout(hideAlert, 9000);

// Account and Privacy

function getClickedValuePrivacy(submitButton) {
	var button = document.evaluate('/html/body/div[1]/div[2]/div[3]/div[1]/div[6]/p/button', document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
	if (button) {
		button.click();
	} else {
		console.error('Save Change Button not found');
	}
	const privacy_email = document.getElementById('privacy_email').value;
	const privacy_checkbox = document.getElementById('declaration');
	var checkbox = document.getElementById("declaration");
	var errorMessage = document.querySelector(".sensfrx_privacy_error-message");
	if (privacy_checkbox.checked) {
		var checkboxValue1 = '1';
	} else {
		var checkboxValue1 = '0';
	}
	if (privacy_email === "") {
		alert("Please fill the Email.");
		return false; // Prevent form submission
	} else if (!checkbox.checked) {
		errorMessage.style.display = "block";
		return false; // Prevent form submission
	} else {
		const privacy_ema = document.getElementById('emailprivacy');
		privacy_ema.value = privacy_email;
		const privacy_check = document.getElementById('checkboxprivacy');
		privacy_check.value = checkboxValue1;
		setTimeout(function() {
			document.getElementById("Privacy").click();
		}, 100); 
	}
}

// Integration Page

function clicksavechange(){
	var button = document.evaluate('/html/body/div[1]/div[2]/div[3]/div[1]/div[6]/p/button', document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
	if (button) {
		button.click();
	} else {
		console.error('Save Change Button not found');
	}

}

// Notification & Alert 

function getClickedValueNotification(submitButton) {
	var button = document.evaluate('/html/body/div[1]/div[2]/div[3]/div[1]/div[6]/p/button', document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
	if (button) {
		button.click();
	} else {
		console.error('Save Change Button not found');
	}
	const emai = document.getElementById('emailInput22').value;
	if (emai === "") {
		alert("Please fill the Email.");
		return false; // Prevent form submission
	} else {
		const emaill = document.getElementById('emailInput22').value;
		const checkboxs = document.getElementById('emailCheckboxx');
		const dropdownn = document.getElementById('threshold').value;
		if (checkboxs.checked) {
			var checkboxValue = '1';
		} else {
			var checkboxValue = '0';
		}
		setTimeout(function() {
			const ema = document.getElementById('hiddeneamil');
			ema.value = emaill;
			const check = document.getElementById('hiddencheckbox');
			check.value = checkboxValue;
			const dropd = document.getElementById('hiddendropdown');
			dropd.value = dropdownn;
			document.getElementById("Notification").click();
		}, 100); 
	}
}

// Policy 

function getClickedValue(submitButton) {
	var button = document.evaluate('/html/body/div[1]/div[2]/div[3]/div[1]/div[6]/p/button', document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
	if (button) {
		button.click();
	} else {
		console.error('Save Change Button not found');
	}
	var checkbox = document.getElementById("sensfrx_policy_options[sensfrx_shadow_mode]");
	var value1 = checkbox.checked ? 1 : 0;
	var shadow = document.getElementById("shadow");
	shadow.value = value1;
	setTimeout(function() {
		document.getElementById("Policy").click();
	}, 1000); 
}
function webhook_rules() {
	var button = document.evaluate('/html/body/div[1]/div[2]/div[3]/div[1]/div[6]/p/button', document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
	if (button) {
		button.click();
	} else {
		console.error('Save Change Button not found');
	}

	setTimeout(function() {
		document.getElementById("webhook_update").click();
	}, 1000); 
}
var button1 = document.getElementById("button1");
var button2 = document.getElementById("button2");
var button3 = document.getElementById("button3");
var button4 = document.getElementById("button4");
var div1 = document.getElementById("div1");
var div2 = document.getElementById("div2");
var div3 = document.getElementById("div3");
var div4 = document.getElementById("div4");
var shadow_mode_hide_webhook = document.getElementById("webhookupdatehide");
document.addEventListener('DOMContentLoaded', function() {
	if (button1) {
		button1.style.backgroundColor = "#003153"; 
		button1.addEventListener("click", function() {
			div1.style.display = "block";
			div2.style.display = "none";
			div3.style.display = "none";
			div4.style.display = "none";

			button1.classList.add("active");
			button2.classList.remove("active");
			button3.classList.remove("active");
			button4.classList.remove("active");

			button1.style.backgroundColor = "#003153"; 
			button2.style.backgroundColor = ""; 
			button3.style.backgroundColor = ""; 
			button4.style.backgroundColor = ""; 
		});
	}
	if (button2) {
		button2.addEventListener("click", function() {
			div1.style.display = "none";
			div2.style.display = "block";
			div3.style.display = "none";
			div4.style.display = "none";
	
			button1.classList.remove("active");
			button2.classList.add("active");
			button3.classList.remove("active");
			button4.classList.remove("active");
	
			button1.style.backgroundColor = ""; 
			button2.style.backgroundColor = "#003153"; 
			button3.style.backgroundColor = ""; 
			button4.style.backgroundColor = ""; 
		});
	}
	if (button3) {
		button3.addEventListener("click", function() {
			div1.style.display = "none";
			div2.style.display = "none";
			div3.style.display = "block";
			div4.style.display = "none";
			
			button1.classList.remove("active");
			button2.classList.remove("active");
			button3.classList.add("active");
			button4.classList.remove("active");
	
			button1.style.backgroundColor = ""; 
			button2.style.backgroundColor = ""; 
			button3.style.backgroundColor = "#003153"; 
			button4.style.backgroundColor = ""; 
		});
	}
	if (button4) {
		button4.addEventListener("click", function() {
			div1.style.display = "none";
			div2.style.display = "none";
			div3.style.display = "none";
			div4.style.display = "block";
			
			button1.classList.remove("active");
			button2.classList.remove("active");
			button3.classList.remove("active");
			button4.classList.add("active");
	
			button1.style.backgroundColor = ""; 
			button2.style.backgroundColor = ""; 
			button3.style.backgroundColor = ""; 
			button4.style.backgroundColor = "#003153"; 
		});
	}
	
});
if (window.location.href.includes('&webhook=update')) {
	// Remove &webhook=update from the URL
	var newURL = window.location.href.replace('&webhook=update', '');
	// Replace the URL without &webhook=update
	history.replaceState(null, null, newURL);
	// Perform the action you want
	// ...
}

// Profile 

function openEditForm() {
	document.getElementById('editButton').style.display = 'none';
	document.querySelector('.edit-sensfrx_form-container').style.display = 'block';
}
function closeForm() {
	document.getElementById('editButton').style.display = 'block';
	document.querySelector('.edit-sensfrx_form-container').style.display = 'none';
}
function Profile() {
	console.log('inside of profile funciton which');
	var button = document.evaluate('/html/body/div[1]/div[2]/div[3]/div[1]/div[6]/p/button', document, null, XPathResult.FIRST_ORDERED_NODE_TYPE, null).singleNodeValue;
	if (button) {
		button.click();
	} else {
		console.error('Save Change Button not found');
	}
	const name = document.getElementById('name').value;
	var selectedSex = document.querySelector('input[name="sex"]:checked').value;
	const email = document.getElementById('emaile').value;
	const phone = document.getElementById('phone').value;
	const brand_name = document.getElementById('brand_name').value;
	const brand_url = document.getElementById('brand_url').value;
	const org_name = document.getElementById('org_name').value;
	const timezone1 = document.getElementById('timezone').value;
	if (name === "" || email === "" || selectedSex === "" || phone === "" || brand_name === "" || brand_url === "" || org_name === "" || timezone1 === "") {
		document.getElementById("errorMessage").style.display = "block";
		return false; // Prevent form submission
	} else {
		var inputName = document.getElementById('myInputName');
		inputName.value = name;
		var inputEmail = document.getElementById('myInputEmail');
		inputEmail.value = email;
		var inputGender = document.getElementById('myInputSex');
		inputGender.value = selectedSex;
		var inputBrandName = document.getElementById('myInputBrandName');
		inputBrandName.value = brand_name;
		var inputOrgName = document.getElementById('myInputOrgName');
		inputOrgName.value = org_name;
		var inputBrandUrl = document.getElementById('myInputBrandUrl');
		inputBrandUrl.value = brand_url;
		var inputPhone = document.getElementById('myInputPhone');
		inputPhone.value = phone;
		var timezone2 = document.getElementById('timezone1');
		timezone2.value = timezone1;
		setTimeout(function() {
			document.getElementById("Profile_same_tab").click();
		}, 100); 
	}
}

// General Page 

var apiResponse; // Declare a variable to store the response
var fromDate = new Date();
var toDate = new Date();
fromDate.setDate(fromDate.getDate() - 7);
// Format the dates as required by your API
var fromDateStr = formatDate(fromDate);
var toDateStr = formatDate(toDate);
callATOapi(fromDateStr, toDateStr);
calltransapi(fromDateStr, toDateStr);
callregapi(fromDateStr, toDateStr);

document.addEventListener("DOMContentLoaded", function() {
	
	var selectElement = document.getElementById("sensfrx_date_dashboard");
	if (selectElement !== null) {
		// Your code here
		selectElement.addEventListener("change", function() {
			var selectedOption = selectElement.options[selectElement.selectedIndex];
			// Execute your function based on the selected option's value
			var selectedValue = selectedOption.value;
			switch (selectedValue) {
				case "filter-7":
					// Execute the function for Last 7 Day's
					yourFunctionForLast7Days();
					break;
				case "filter-30":
					// Execute the function for Last 30 Day's
					yourFunctionForLast30Days();
					break;
				case "filter-365":
					// Execute the function for Last Year
					yourFunctionForLastYear();
					break;
				default:
					// Handle other cases or do nothing
					break;
			}
		});
	} 
});
// Define your functions here
function yourFunctionForLast7Days() {
	// Implement the function for Last 7 Day's
	var fromDate = new Date();
	var toDate = new Date();
	fromDate.setDate(fromDate.getDate() - 7);
	// Format the dates as required by your API
	var fromDateStr7 = formatDate(fromDate);
	var toDateStr7 = formatDate(toDate);
	callATOapi(fromDateStr7, toDateStr7);
	calltransapi(fromDateStr7, toDateStr7);
	callregapi(fromDateStr7, toDateStr7);
}
function yourFunctionForLast30Days() {
	// Implement the function for Last 30 Day's
	var fromDate = new Date();
	var toDate = new Date();
	fromDate.setDate(fromDate.getDate() - 30);
	// Format the dates as required by your API
	var fromDateStr30 = formatDate(fromDate);
	var toDateStr30 = formatDate(toDate);
	callATOapi(fromDateStr30, toDateStr30);
	calltransapi(fromDateStr30, toDateStr30);
	callregapi(fromDateStr30, toDateStr30);
}
function yourFunctionForLastYear() {
	// Implement the function for Last Year
	var fromDate = new Date();
	var toDate = new Date();
	fromDate.setDate(fromDate.getDate() - 365);
	// Format the dates as required by your API
	var fromDateStr365 = formatDate(fromDate);
	var toDateStr365 = formatDate(toDate);
	callATOapi(fromDateStr365, toDateStr365);
	calltransapi(fromDateStr365, toDateStr365);
	callregapi(fromDateStr365, toDateStr365);
}
function callregapi(date1, date2) {
	var apiUrl_sensfrx = 'https://a.sensfrx.ai/v1/reg-stats?from_date=' + date1 + '&to_date=' + date2;
	var apiKey = document.getElementById("sensfrx_apiKey");
	if (apiKey) {
		apiKey = apiKey.value;
	}
	if ( apiKey ) {
		// console.log('TRANSE');
		jQuery.ajax({
			url: apiUrl_sensfrx,
			method: 'GET', // Use POST method as well
			dataType: 'json',
			contentType: 'application/json',
			// data: requestData, // Send requestData as JSON in the request body
			// Set the headers for Basic Authentication and Content-Type
			headers: {
				'Authorization': 'Basic ' + apiKey,
				'Content-Type': 'application/json'
			},
			success: function(response) {
				// Handle the API response here
				// console.log('API Response:', response);
				apiResponse = response;
				// Call a function or perform actions that depend on apiResponse here
				processApiResponseNewAccount(apiResponse);
			},
			error: function(xhr, status, error) {
				// Handle errors here
				// console.error('AJAX Error:', error);
			}
		});
	} 
}
function calltransapi(date1, date2) {
	var apiUrl_sensfrx = 'https://a.sensfrx.ai/v1/trans-stats?from_date=' + date1 + '&to_date=' + date2;
	var apiKey = document.getElementById("sensfrx_apiKey");
	if (apiKey) {
		apiKey = apiKey.value;
	}
	if ( apiKey ) {
		// console.log('TRANSE');
		jQuery.ajax({
			url: apiUrl_sensfrx,
			method: 'GET', // Use POST method as well
			dataType: 'json',
			contentType: 'application/json',
			// data: requestData, // Send requestData as JSON in the request body
			// Set the headers for Basic Authentication and Content-Type
			headers: {
				'Authorization': 'Basic ' + apiKey,
				'Content-Type': 'application/json'
			},
			success: function(response) {
				// Handle the API response here
				// console.log('API Response:', response);
				apiResponse = response;
				// Call a function or perform actions that depend on apiResponse here
				processApiResponseTransaction(apiResponse);
			},
			error: function(xhr, status, error) {
				// Handle errors here
				// console.error('AJAX Error:', error);
			}
		});
	} 
}
function callATOapi(date1, date2) {
	// Construct the URL with the parameters
	var apiUrl_sensfrx = 'https://a.sensfrx.ai/v1/ato-stats?from_date=' + date1 + '&to_date=' + date2;
	var apiKey = document.getElementById("sensfrx_apiKey");
	if (apiKey) {
		apiKey = apiKey.value;
	}
	if ( apiKey ) {
		// console.log('ATO');
		jQuery.ajax({
			url: apiUrl_sensfrx,
			method: 'GET', // Use POST method as well
			dataType: 'json',
			contentType: 'application/json',
			// data: requestData, // Send requestData as JSON in the request body
			// Set the headers for Basic Authentication and Content-Type
			headers: {
				'Authorization': 'Basic ' + apiKey,
				'Content-Type': 'application/json'
			},
			success: function(response) {
				// Handle the API response here
				// console.log('API Response:', response);
				apiResponse = response;
				// Call a function or perform actions that depend on apiResponse here
				processApiResponseATO(apiResponse);
			},
			error: function(xhr, status, error) {
				// Handle errors here
				// console.error('AJAX Error:', error);
			}
		});
	} 
}
// Function to add '+' symbol and apply red color if '-' is present at the first position
function addPlusAndColorIfNecessary(text) {
	if (text.charAt(0) === '-') {
		return '<span id="addPlusAndColorIfNecessary">' + text + '%</span>';
	} else if (text.charAt(0) !== '+') {
		return '+' + text + '%';
	} else {
		return text + '%';
	}
}
// Function to process the API response or use apiResponse as needed
function processApiResponseNewAccount(apiResponse) {
	document.getElementById("Total_c_reg").innerText = apiResponse.data.t_count;
	document.getElementById("Total_d_reg").innerText = apiResponse.data.d_count;
	document.getElementById("Total_chall_reg").innerText = apiResponse.data.c_count;
	document.getElementById("Total_a_reg").innerText = apiResponse.data.a_count;
	document.getElementById("Total_c_change_reg").innerHTML = addPlusAndColorIfNecessary(apiResponse.data.t_change);
	document.getElementById("Total_d_change_reg").innerHTML = addPlusAndColorIfNecessary(apiResponse.data.d_change);
	document.getElementById("Total_chall_change_reg").innerHTML = addPlusAndColorIfNecessary(apiResponse.data.c_change);
	document.getElementById("Total_a_change_reg").innerHTML = addPlusAndColorIfNecessary(apiResponse.data.a_change);
}
function processApiResponseTransaction(apiResponse) {
	document.getElementById("Total_c").innerText = apiResponse.data.t_count;
	document.getElementById("Total_d").innerText = apiResponse.data.d_count;
	document.getElementById("Total_chall").innerText = apiResponse.data.c_count;
	document.getElementById("Total_a").innerText = apiResponse.data.a_count;
	document.getElementById("Total_c_change").innerHTML = addPlusAndColorIfNecessary(apiResponse.data.t_change);
	document.getElementById("Total_d_change").innerHTML = addPlusAndColorIfNecessary(apiResponse.data.d_change);
	document.getElementById("Total_chall_change").innerHTML = addPlusAndColorIfNecessary(apiResponse.data.c_change);
	document.getElementById("Total_a_change").innerHTML = addPlusAndColorIfNecessary(apiResponse.data.a_change);
}
function processApiResponseATO(apiResponse) {
	document.getElementById("Total_ATO_c").innerText = apiResponse.data.t_count;
	document.getElementById("Total_ATO_d").innerText = apiResponse.data.d_count;
	document.getElementById("Total_ATO_chall").innerText = apiResponse.data.c_count;
	document.getElementById("Total_ATO_a").innerText = apiResponse.data.a_count;
	document.getElementById("ATO_total_change").innerHTML = addPlusAndColorIfNecessary(apiResponse.data.t_change);
	document.getElementById("ATO_total_d_change").innerHTML = addPlusAndColorIfNecessary(apiResponse.data.d_change);
	document.getElementById("Total_ATO_chall_change").innerHTML = addPlusAndColorIfNecessary(apiResponse.data.c_change);
	document.getElementById("Total_ATO_a_change").innerHTML = addPlusAndColorIfNecessary(apiResponse.data.a_change); 
}
// Function to format a date as 'YYYY-MM-DD'
function formatDate(date) {
	var year = date.getFullYear();
	var month = padZeroes(date.getMonth() + 1); // Month is 0-based, so add 1
	var day = padZeroes(date.getDate());
	return day + '-' + month + '-' + year;
}
// Function to pad single-digit numbers with leading zeros
function padZeroes(value) {
	return value.toString().padStart(2, '0');
}

// Function to update the comparison result text
function updateComparisonResult() {
	const selectedElement = document.getElementById('sensfrx_date_dashboard');
	if (selectedElement) {
		const selectedOption = selectedElement.value;
		let resultText = '';
		// Determine the comparison result based on the selected option
		if (selectedOption === 'filter-7') {
			resultText = 'Comparison result till 7 Days';
		} else if (selectedOption === 'filter-30') {
			resultText = 'Comparison result till 30 Days';
		} else if (selectedOption === 'filter-365') {
			resultText = 'Comparison result till 1 Year';
		}
		document.getElementById('comparisonResult').textContent = resultText;
	} 
	
}

updateComparisonResult();

const sensfrx_general_dateDashboard = document.getElementById('sensfrx_date_dashboard');
if (sensfrx_general_dateDashboard) { 
    sensfrx_general_dateDashboard.addEventListener('change', updateComparisonResult);
} 

document.querySelectorAll('.sensfrx-css-123-tab').forEach(tab => {
	tab.addEventListener('click', function() {
		document.querySelectorAll('.sensfrx-css-123-tab').forEach(t => t.classList.remove('active'));
		document.querySelectorAll('.sensfrx-css-123-tabtab-content').forEach(content => content.classList.remove('active'));

		const tabId = this.getAttribute('sensfrx-general-data-tab');
		this.classList.add('active');
		document.getElementById(`sensfrx-whitelist-tab-content-${tabId}`).classList.add('active');
	});
});


function setCookie(name, value, days) {
    let expires = "";
    if (days) {
        let date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000)); // Set expiration time
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function getCookie(name) {
    let nameEQ = name + "=";
    let ca = document.cookie.split(';');
    for(let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}

function eraseCookie(name) {   
    document.cookie = name + "=; Max-Age=-99999999; path=/";  
}

document.addEventListener('DOMContentLoaded', function() {
     // Get stored tab ID from cookie
	 let openTab = getCookie('sensfrx_openTab');
    
	 if (openTab) {
		 console.log('Stored tab (from cookie):', openTab);
		 
		 // Remove 'active' class from all tabs and content
		 document.querySelectorAll('.sensfrx-css-123-tab').forEach(function (tab) {
			 tab.classList.remove('active');
		 });
		 document.querySelectorAll('.sensfrx-css-123-tabtab-content').forEach(function (tabContent) {
			 tabContent.classList.remove('active');
		 });
 
		 // Add 'active' class to the saved tab and content
		 document.querySelector(`.sensfrx-css-123-tab[sensfrx-general-data-tab="${openTab}"]`).classList.add('active');
		 document.getElementById(`sensfrx-whitelist-tab-content-${openTab}`).classList.add('active');
 
		 // Remove the cookie after it is used
		 eraseCookie('sensfrx_openTab');
	 }
});

// Function to save the tab state in a cookie
function sensfrx_tab_open_which_save() {
    setCookie('sensfrx_openTab', '3', 1); // Store for 1 day
    console.log('Tab saved to cookie');
}

function applyBulkAction(event) {
	const action = document.getElementById('trans_review_bulk_action_123').value;
	const checkboxes = document.querySelectorAll('.transaction_checkbox:checked');
	const orderIds = Array.from(checkboxes).map(checkbox => checkbox.getAttribute('data-order-id'));
	if (action == null || action == '') {
		alert("Please select the action.");
		event.preventDefault();
		return;
	}
	if (orderIds.length === 0) {
		alert("Please select at least one order.");
		event.preventDefault();
		return;
	}
	console.log('Applying action:', action, 'to orders:', orderIds, action);
	const privacy_ema = document.getElementById('trans_review_order_id');
	privacy_ema.value = orderIds.join(',');
	const privacy_check = document.getElementById('trans_review_bulk_action');
	privacy_check.value = action;
	setTimeout(function() {
		document.getElementById("trans_review_bulk_submit").click();
	}, 100); 
	
}

// Function to toggle select all checkboxes
function toggleSelectAll(selectAllCheckbox) {
	const checkboxes = document.querySelectorAll('.transaction_checkbox');
	checkboxes.forEach(checkbox => {
		checkbox.checked = selectAllCheckbox.checked;
	});
}

// Function to filter rows based on the search input
function filterTransactions() {
	const filterValue = document.getElementById('trans_review_search_filter').value.toLowerCase();
	const rows = document.querySelectorAll('#transaction_rows tr');
	let found = false; // Flag to check if any matching rows are found

	rows.forEach(row => {
		const orderId = row.getAttribute('data-order-id').toLowerCase();
		const email = row.getAttribute('data-email').toLowerCase();
		const date = row.getAttribute('data-date').toLowerCase();

		// Check if any field matches the filter value
		if (orderId.includes(filterValue) || email.includes(filterValue) || date.includes(filterValue)) {
			row.style.display = ''; // Show row
			found = true; // A match was found
		} else {
			row.style.display = 'none'; // Hide row
		}
	});

	// Display the message if no matches found
	document.getElementById('no_results_message').style.display = found ? 'none' : 'block';
}
// Function to save the tab state in a cookie
function sensfrx_tab_open_which_save_transaction_review(event) {
	let confirmationMessage = "";
	confirmationMessage = "Are you sure you want to approve this transaction?";

	if (confirm(confirmationMessage)) {
		setCookie('sensfrx_openTab', '4', 1); // Store for 1 day
	} else {
		event.preventDefault();
	}
}

function sensfrx_tab_open_which_save_transaction_review_bulk(event) {
	setCookie('sensfrx_openTab', '4', 1); // Store for 1 day
}

function sensfrx_tab_open_which_save_transaction_review_reject(event) {
	let confirmationMessage = "";
	confirmationMessage = "Are you sure you want to reject this transaction?";

	if (confirm(confirmationMessage)) {
		setCookie('sensfrx_openTab', '4', 1); // Store for 1 day
	} else {
		event.preventDefault();
	}
}

