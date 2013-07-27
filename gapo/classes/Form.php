<?php

require_once 'Filters.php';
require_once 'Validators.php';

class Form
{
    protected $_fields = array();
    protected $_errors = array();
    
    public function __construct(array $fields = array())
    {
        $this->_fields = $fields;
    }
    
    /**
     * Is the form valid?
     *
     * @param array $values The values to check against
     * @return boolean True if valid, false otherwise
     */
    public function isValid(array $values = array())
    {
        // Validation
        $valid = true;
        
        foreach ($this->_fields as $name => &$field) {
            // Do filtering
            $field['value'] = $values[$name];
            if (isset($field['filters']) && is_array($field['filters']) && (count($field['filters']) > 0)) {
                foreach ($field['filters'] as $filterName) {
                    $filterClassName = ucfirst($filterName) . '_Filter';
                    if (class_exists($filterClassName)) {
                        $filter = new $filterClassName();
                        if ($filter instanceof Filter_Interface) {
                            $field['value'] = $filter->filter($field['value']);
                        }
                    }
                }
            }
            
            // Do validation
            $this->_errors[$name] = array();
            $field['valid'] = true;
            if (isset($field['validators']) && is_array($field['validators']) && (count($field['validators']) > 0)) {
                foreach ($field['validators'] as $validatorName) {                    
                    $validatorClassName = ucfirst($validatorName) . '_Validator';                    
                    if (class_exists($validatorClassName)) {
                        $validator = new $validatorClassName();
                        if ($validator instanceof Validator_Interface && !$validator->isValid($field['value'])) {
                            $this->_errors[$name] = array_merge($this->_errors[$name], $validator->getMessages());
                            $field['valid'] = false;
                            $valid = false;
                            
                            break; // Just show one error message per field, although there could potentially be more
                        }
                    }
                }
            }
        }
        
        return $valid;
    }
    
    /**
     * Get the fields of this form
     * 
     * @return array The fields
     */
    public function getFields()
    {
        return $this->_fields;
    }
    
    /**
     * Get the errors from this form
     * 
     * @return array An array of errors for every field
     */
    public function getErrors()
    {
        return $this->_errors;
    }
    
    /**
     * Get the JSON formatted error response
     * 
     * @return string A JSON formatted response to return to the calling JavaScript
     */
    public function getErrorResponseJson()
    {             
        $data = array();
        
        foreach ($this->_fields as $name => $info) {
            $errors = array();
            if (isset($this->_errors[$name]) && is_array($this->_errors[$name]) && count($this->_errors[$name]) > 0) {                
                $errors = $this->_errors[$name];
            }
            $fieldData = ' "' . $name . '" : { "value" : "' . $info['value'] . '"';
            if (count($errors) > 0) {
                $fieldData .= ', "errors" : [ "' . join('", "', $errors) . '" ]';
            }
            $fieldData .= ' }';
            
            $data[] = $fieldData;
        }
                
        $response = '{ "type" : "error", "data" : { ' . join(', ', $data) . ' } }';
        return $response;
    }
    
    /**
     * Get the JSON formatted success message
     * 
     * @param string $successfulSubmissionMessage The message to display
     * @return string A JSON formatted success message to return to the calling JavaScript
     */
    public function getSuccessResponseJson($successfulSubmissionMessage)
    {
        $response = '{ "type" : "success", "data" : "' . $successfulSubmissionMessage . '" }';
        return $response;
    }
    
    /**
     * Get the values of all fields
     *
     * @return array The values of all fields
     */
    public function getValues()
    {
        $values = array();
        
        foreach ($this->_fields as $name => $info) {
            $values[$name] = (isset($info['value'])) ? $info['value'] : null;
        }
        
        return $values;
    }
    
    /**
     * Get the value of a single field
     *
     * @param string $field The name of the field
     * @return mixed The value of the given field or null
     */  
    public function getValue($field)
    {
        if (isset($this->_fields[$field]) && isset($this->_fields[$field]['value'])) {
            return $this->_fields[$field]['value'];
        }
        
        return null;
    }
}