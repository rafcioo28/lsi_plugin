<?php

function display_licence() {
    ?>
    <script>
    jQuery(document).ready(function($) {
	$.soap({
	url: 'https://172.25.1.2:8062/NLicenseService.asmx',
	method: 'GetDealerLicence',

	data: {
		Auth: 	'0A7E3AC8-CA2C-4766-B35A-C3F98A998DEA',
		Nip: 	'5211785118</Nip>',

	},

	success: function (soapResponse) {
		console.log(soapResponse);
		// do stuff with soapResponse
		// if you want to have the response as JSON use soapResponse.toJSON();
		// or soapResponse.toString() to get XML string
		// or soapResponse.toXML() to get XML DOM
	},
	error: function (SOAPResponse) {
		// show error
	}
	});
});

    </script>
    <?php
}
