(function() {

	/**
	 * Woocommerce user Registration
	 */

	var formElem = document.querySelector(".register");
	if(formElem.addEventListener){

		formElem.addEventListener("submit", submitHandler, false);  //Modern browsers

	}else if(formElem.attachEvent){

		formElem.attachEvent('onsubmit', submitHandler);            //Old IE

	}

	function submitHandler() {

		var device_id = _sensfrx("getRequestString");
		var deviceidelement = document.createElement('input');
		deviceidelement.setAttribute('type', 'hidden');
		deviceidelement.setAttribute('name', 'device_id_register');
		deviceidelement.setAttribute('value', device_id);
		formElem.appendChild(deviceidelement);

	}

})();