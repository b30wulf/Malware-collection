<?php

/**
 * Point to your config file
 *
 */
define("OPEN_SSL_CONF_PATH", "/usr/share/ssl/openssl.cnf");
/**
 * Length of time certificate is valid (in days)
 *
 */
define("OPEN_SSL_CERT_DAYS_VALID", 365);
/**
 * Passphrase required with private key
 *
 */
define("OPEN_SSL_PASSPHRASE", "lkdfjbjeyrasdfvkajwdeblsolkdkdjfbvzslalsmdbfvksb");
/**
 * Enter description here...
 *
 */
define("OPEN_SSL_PUBKEY_PATH", "z:\home\z0.homeip.net\www\cache\key.pem"); // Public key path

/**
 * A wrapper class for a simple subset of the PHP OpenSSL functions. Use for public key encryption jobs.
 *
 * <code>
 *
 * // To configure
 * // 1. Set OPEN_SSL_CONF_PATH to the path of your openssl.cnf file.
 * // 2. Set OPEN_SSL_PASSPHRASE to any passphrase.
 * // 3. Use the OpenSSL::do_csr method to generate your private and public keys (see next section).
 * // 4. Save the private key somewhere offline and save your public key somewhere on this machine.
 * // 5. Set OPEN_SSL_PUBKEY_PATH to the public key's path.
 *
 * // To generate keys
 * $ossl = new OpenSSL;
 * $ossl->do_csr();
 * $privatekey = $ossl->privatekey;
 * $publickey = $ossl->publickey;
 * unset($ossl);
 *
 * // Encrypt
 * $text = "Secret text";
 * $ossl = new OpenSSL;
 * $ossl->encrypt($text);
 * $encrypted_text = $ossl->crypttext;
 * $ekey = $ossl->ekey;
 * unset($ossl);
 *
 * // Decrypt
 * $ossl = new OpenSSL;
 * $decrypted_text = $ossl->decrypt($encrypted_text, $privatekey, $ekey);
 * unset($ossl);
 *
 * @author Matt Alexander (mattalexx@gmail.com) [based on code by Alex Poole (php@wwwcrm.com)]
 * @copyright 2007
 *
 */
class OpenSSL {
  
   public $privatekey;
   public $publickey;
   public $csr;
   public $crypttext;
   public $ekey;
   
   public function encrypt($plain) {
     
      // Turn public key into resource
      $publickey = openssl_get_publickey(is_file(OPEN_SSL_PUBKEY_PATH)? file_get_contents(OPEN_SSL_PUBKEY_PATH) : OPEN_SSL_PUBKEY_PATH);
     
      // Encrypt
      openssl_seal($plain, $crypttext, $ekey, array($publickey));
      openssl_free_key($publickey);
     
      // Set values
      $this->crypttext = $crypttext;
      $this->ekey = $ekey[0];
   }
 
   public function decrypt($crypt, $privatekey, $ekey="") {
  
      // Turn private key into resource
      $privatekey = openssl_get_privatekey((is_file($privatekey)? file_get_contents($privatekey) : $privatekey), OPEN_SSL_PASSPHRASE);
     
      // Decrypt
      openssl_open($crypt, $plaintext, $ekey, $privatekey);
      openssl_free_key($privatekey);
     
      // Return value
      return $plaintext;
   }

   public function do_csr(
   $countryName = "UK",
   $stateOrProvinceName = "London",
   $localityName = "Blah",
   $organizationName = "Blah1",
   $organizationalUnitName = "Blah2",
   $commonName = "Joe Bloggs",
   $emailAddress = "openssl@domain.com"
   ) {        
      $dn = array(
         "countryName" => $countryName,
         "stateOrProvinceName" => $stateOrProvinceName,
         "localityName" => $localityName,
         "organizationName" => $organizationName,
         "organizationalUnitName" => $organizationalUnitName,
         "commonName" => $commonName,
         "emailAddress" => $emailAddress
         );
      /*
      $config = array(
         "config" => OPEN_SSL_CONF_PATH
         );
      */
      $privkey = openssl_pkey_new();
      
      /*
      $csr = openssl_csr_new($dn, $privkey, $config);
      $sscert = openssl_csr_sign($csr, null, $privkey, OPEN_SSL_CERT_DAYS_VALID, $config);
      openssl_x509_export($sscert, $this->publickey);
      openssl_pkey_export($privkey, $this->privatekey, OPEN_SSL_PASSPHRASE, $config);
      */
      
      $csr = openssl_csr_new($dn, $privkey);
      $sscert = openssl_csr_sign($csr, null, $privkey, OPEN_SSL_CERT_DAYS_VALID);
      openssl_x509_export($sscert, $this->publickey);
      openssl_pkey_export($privkey, $this->privatekey, OPEN_SSL_PASSPHRASE);
      openssl_csr_export($csr, $this->csr);
   }
  
}

?>