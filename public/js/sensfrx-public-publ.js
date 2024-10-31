(function() {

	/**
	 * SensFRX device initialization
	 */

	var anchors = document.querySelectorAll("a[href*='logout']");
	for(var i=0; i<anchors.length; i++) {

		if(anchors[i].addEventListener){

			anchors[i].addEventListener("click", logoutClickHandler, false);  //Modern browsers

		}else if(anchors[i].attachEvent){

			anchors[i].attachEvent('onclick', logoutClickHandler);            //Old IE

		}

	}

	// ------------------ Start Pixel For Bot ----------------------------------------------------------------------

	var d_id_for_bot = _sensfrx("getRequestString");
	function sliceString(str, length) {

		length = length/2+1
		if (length >= str.length) {
			return [str, ''];
		} else {
			return [str.slice(0, length), str.slice(length)];
		}

	}

	function setCookie(cookieName, cookieValue, daysToExpire) {                         // Length issue sometime get which prevent the cookie to generate
		let expires = '';
		if (daysToExpire) {
			const date = new Date();
			date.setTime(date.getTime() + (daysToExpire * 24 * 60 * 60 * 1000));
			expires = '; expires=' + date.toUTCString();
		}
		
		document.cookie = cookieName + '=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/';
		document.cookie = cookieName + '=' + cookieValue + expires + '; path=/';
	}

	var d_id_for_bot_slice = sliceString(d_id_for_bot, d_id_for_bot.length);

	var device_id_0 = d_id_for_bot_slice[0];
	var device_id_0_0 = sliceString(device_id_0, device_id_0.length);
	var device_id_0_1 = device_id_0_0[0];
	var device_id_0_2 = device_id_0_0[1];

	var device_id_1 = d_id_for_bot_slice[1];
	var device_id_1_0 = sliceString(device_id_1, device_id_1.length);
	var device_id_1_1 = device_id_1_0[0];
	var device_id_1_2 = device_id_1_0[1];

	// console.log(d_id_for_bot);
	setCookie('device0', device_id_0_1 , 7);
	setCookie('device1', device_id_0_2 , 7);
	setCookie('device2', device_id_1_1 , 7);
	setCookie('device3', device_id_1_2 , 7);

	//----------------------End Pixel for Bot --------------------------------------------------------------------------------

	function logoutClickHandler(e) {

		e.preventDefault();
		var device_id = _sensfrx("getRequestString");
		var href = this.href;
		href+= '&did='+device_id;
		window.location = href;

	}

	var formElem = document.querySelector(".lost_reset_password,form.login");
	if(formElem) {

		if(formElem.addEventListener){

			formElem.addEventListener("click", submitHandler, false);  // Modern browsers

		}else if(formElem.attachEvent){

			formElem.attachEvent('onclick', submitHandler);            // Old IE

		}

	}

	function submitHandler() {

		var device_id = _sensfrx("getRequestString");
		var deviceidelement = document.createElement('input');
		deviceidelement.setAttribute('type', 'hidden');
		deviceidelement.setAttribute('name', 'device_id');
		deviceidelement.setAttribute('value', device_id);
		formElem.appendChild(deviceidelement);

	}

})();

