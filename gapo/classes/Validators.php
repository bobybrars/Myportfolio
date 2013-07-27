<?php

/**
 * Validator interface
 */
interface Validator_Interface
{
    public function isValid($value);
    public function getMessages();
}

/**
 * Validator abstract class that all Validators should extend
 */
abstract class Validator_Abstract implements Validator_Interface
{
    protected $_messages = array();
    
    public function getMessages()
    {
        return $this->_messages;
    }
    
    public function addMessage($message)
    {
        $this->_messages[] = $message;
    }
}

/**
 * Email Validator Class
 * 
 * Validates an email address
 */
class Email_Validator extends Validator_Abstract
{
    public function isValid($value)
    {
        $isValid = true;
        $atIndex = strrpos($value, "@");
        if (is_bool($atIndex) && !$atIndex) {
          $isValid = false;
        } else {
          $domain = substr($value, $atIndex+1);
          $local = substr($value, 0, $atIndex);
          $localLen = strlen($local);
          $domainLen = strlen($domain);
          if ($localLen < 1 || $localLen > 64)
          {
             // local part length exceeded
             $isValid = false;
          }
          else if ($domainLen < 1 || $domainLen > 255)
          {
             // domain part length exceeded
             $isValid = false;
          }
          else if ($local[0] == '.' || $local[$localLen-1] == '.')
          {
             // local part starts or ends with '.'
             $isValid = false;
          }
          else if (preg_match('/\\.\\./', $local))
          {
             // local part has two consecutive dots
             $isValid = false;
          }
          else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
          {
             // character not valid in domain part
             $isValid = false;
          }
          else if (preg_match('/\\.\\./', $domain))
          {
             // domain part has two consecutive dots
             $isValid = false;
          }
          else if
        (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                     str_replace("\\\\","",$local)))
          {
             // character not valid in local part unless 
             // local part is quoted
             if (!preg_match('/^"(\\\\"|[^"])+"$/',
                 str_replace("\\\\","",$local)))
             {
                $isValid = false;
             }
          }
          if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
          {
             // domain not found in DNS
             $isValid = false;
          }
        }
        
        if ($isValid === false) {
            $this->addMessage('Invalid email address');
        }
        
        return $isValid;
    }
}

/**
 * Required Validator Class
 * 
 * Validates a required field
 */
class Required_Validator extends Validator_Abstract
{
    public function isValid($value)
    {
        if (!empty($value)) {
            return true;
        }
        
        $this->addMessage('This field is required');
        return false;
    }
}

/**
 * Empty Validator Class
 * 
 * Used by the "Reverse captcha" field which must be
 * left empty.
 */
class Empty_Validator extends Validator_Abstract
{
    public function isValid($value)
    {
        if (empty($value)) {
            return true;
        }
        
        $this->addMessage('This field should be empty');
        return false;
    }
}