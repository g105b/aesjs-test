<!doctype html>
<meta charset="utf-8" />
<title>AES-JS Test</title>

<form method="post">
	<label>
		<span>Secret message: </span>
		<input name="secret-message" placeholder="This will be encrypted" />
	</label>

	<button id="encrypt-js">Encrypt with aes-js</button>
	<button id="decrypt-js">Encrypt with aes-js</button>
	<button id="decrypt">Decrypt with PHP</button>
</form>

<script src="/aes.js"></script>
<script>

// var key = [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16 ];
var key = [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ];

function encryptToHex(text) {
	var textBytes = aesjs.utils.utf8.toBytes(text);
	var aesCtr = new aesjs.ModeOfOperation.ctr(key/*, new aesjs.Counter(5)*/);
	var encryptedBytes = aesCtr.encrypt(textBytes);
	return aesjs.utils.hex.fromBytes(encryptedBytes);
}

function decryptFromHex(encryptedHex) {
	var encryptedBytes = aesjs.utils.hex.toBytes(encryptedHex);
	var aesCtr = new aesjs.ModeOfOperation.ctr(key/*, new aesjs.Counter(5)*/);
	var decryptedBytes = aesCtr.decrypt(encryptedBytes);
	return aesjs.utils.utf8.fromBytes(decryptedBytes);
}

var secretInput = document.querySelector("[name='secret-message']");

document.getElementById("encrypt-js").addEventListener("click", function clickEncrypt(e) {
	e.preventDefault();
	secretInput.value = encryptToHex(secretInput.value);
});

document.getElementById("decrypt-js").addEventListener("click", function clickEncrypt(e) {
	e.preventDefault();
	var outputMessage = document.createElement("span");
	outputMessage.innerText =
		"Decrypted message from AES-JS: "
	 	+ decryptFromHex(secretInput.value);
	document.body.appendChild(outputMessage);
});
</script>

<?php
if(empty($_POST["secret-message"])) {
	exit;
}

const KEY = [ 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0 ];

echo "Decrypted message from PHP: "
	. decryptFromHex($_POST["secret-message"]);

function decryptFromHex(string $encryptedHex):string {
	$encryptedBytes = hex2bin($encryptedHex);
	$decryptedBytes = openssl_decrypt(
		$encryptedBytes,
		"AES-128-CTR",
		getKeyString(),
		OPENSSL_RAW_DATA
	);

	return $decryptedBytes;
}

function getKeyString():string {
	$string = "";
	foreach(KEY as $i) {
		$string .= dechex($i);
	}

	return $string;
}